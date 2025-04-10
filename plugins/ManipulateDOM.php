id: 39
name: ManipulateDOM
description: 'Manipulate DOM elements with HtmlPageDom. Yes, that''s exactly what jQuery does... But now we can do it server side, before the page is rendered. Much faster and more reliable.'
category: c_content
plugincode: "/**\n * ManipulateDOM plugin\n *\n * This plugin utilizes HtmlPageDom, a page crawler that can manipulate DOM\n * elements for us. Yes, that is exactly what jQuery does... But now we can do\n * it server side, before the page is rendered. Much faster and more reliable.\n *\n * Update March 2025: generated HTML output is now cached under the regular\n * resource cache. This means it will be cleared also on every save action.\n * Other plugins utilizing the HtmlPageDOM crawler are relying on this cache\n * too, so keep an eye on the priority of this plugin to make sure all output\n * is generated before it is being cached here.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nif (!class_exists(\\Wa72\\HtmlPageDom\\HtmlPageCrawler::class)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');\n    return;\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n        $start = null;\n        if ($debug = $modx->getOption('debug', null, false)) {\n            $start = microtime(true);\n        }\n\n        // Look for cached HTML output first...\n        $cacheFlag = false;\n        $cacheManager = $modx->getCacheManager();\n        $cacheElementKey = '/' . hash('xxh3', $modx->resource->_output);\n        $cacheOptions = [\n            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n        ];\n        // Unless user is logged in, or a POST or search request is made.\n        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n        $searchQuery = $_REQUEST['search'] ?? false;\n        if (!$isLoggedIn && !$_POST && !$searchQuery) {\n            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n            if ($cachedOutput) {\n                if ($debug) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Page DOM loaded from cache in: ' . microtime(true) - $start);\n                }\n                $modx->resource->_output = $cachedOutput;\n                break;\n            } else {\n                $cacheFlag = true;\n            }\n        }\n\n        // Check if content type is text/html\n        if (!in_array($modx->resource->get('content_type'), [1,11])) {\n            break;\n        }\n\n        // Get processed output of resource\n        $output = &$modx->resource->_output;\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($output);\n\n        // Add non-white class to body if custom background is set\n        try {\n            if ($modx->getObject('cgSetting', array('key' => 'theme_page_background_color'))->get('value') !== 'ffffff') {\n                $dom->filter('body')->addClass('non-white');\n            }\n        }\n        catch (Error $e) {\n            $modx->log(modX::LOG_LEVEL_ERROR, $e);\n        }\n\n        // Read inverted parameter from URL (for testing purposes)\n        $invertLayouts = $_GET['inverted'] ?? 0;\n        if ($invertLayouts) {\n            $dom->filter('.ui.menu')\n                ->addClass('inverted')\n            ;\n            $dom->filter('.vertical.stripe.segment.white')\n                ->removeClass('white')\n                ->addClass('inverted')\n            ;\n            $dom->filter('.vertical.backyard.segment.secondary')\n                ->removeClass('secondary')\n                ->addClass('inverted black')\n            ;\n        }\n\n        // Add header classes to HTML headings\n        $dom->filter('h1:not(.header):not(.title)')->addClass('ui header');\n        $dom->filter('h2:not(.header):not(.title)')->addClass('ui header');\n        $dom->filter('h3:not(.header):not(.title)')->addClass('ui header');\n        $dom->filter('h4:not(.header):not(.title)')->addClass('ui header');\n        $dom->filter('h5:not(.header):not(.title)')->addClass('ui header');\n\n        // Inject inverted classes to elements inside inverted segments\n        $dom->filter('.inverted.segment')\n            ->each(function (HtmlPageCrawler $segment) {\n\n                // Define elements that need to receive the inverted class\n                $elements = array(\n                    '.header',\n                    '.grid',\n                    'a:not(.button)',\n                    '.button:not(.primary):not(.secondary)',\n                    '.subtitle',\n                    '.lead',\n                    '.list',\n                    '.quote',\n                    '.divider:not(.hidden)',\n                    '.accordion:not(.styled)',\n                    '.text.menu',\n                    '.secondary.menu',\n                    '.basic.form',\n                    '.basic.segment',\n                    '.table',\n                    '.steps',\n                );\n\n                // Revert inverted styling inside these nested elements\n                $exceptions = array(\n                    '.segment:not(.inverted):not(.transparent)',\n                    '.card',\n                    '.message',\n                    '.accordion:not(.inverted)',\n                    '.popup:not(.inverted)',\n                    '.tabbed.menu',\n                    '.form:not(.basic)',\n                    '.leaflet-container',\n                );\n\n                // Prevent elements from having the same color as their parent background\n                if ($segment->hasClass('primary-color') || $segment->hasClass('secondary-color') || $segment->hasClass('secondary')) {\n                    $segment\n                        ->filter('.primary.button')\n                        ->addClass('white inverted')\n                    ;\n                    $segment\n                        ->filter('.secondary.button')\n                        ->removeClass('secondary')\n                        ->addClass('inverted')\n                    ;\n                    $segment\n                        ->filter('.bottom.attached.primary.button')\n                        ->removeClass('primary')\n                        ->addClass('secondary')\n                    ;\n                }\n\n                // Elements\n                foreach ($elements as $element) {\n                    $segment\n                        ->filter($element)\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Exceptions\n                foreach ($exceptions as $exception) {\n                    $segment\n                        ->filter($exception)\n                        ->each(function(HtmlPageCrawler $node) {\n                            $node\n                                ->filter('.inverted')\n                                ->removeClass('inverted')\n                            ;\n                            $node\n                                ->filter('.ui.white.button')\n                                ->removeClass('white')\n                            ;\n                        })\n                    ;\n                }\n            })\n        ;\n\n        // Remove rows from grids that have a reversed column order on mobile\n        $dom->filter('.ui.reversed.grid > .row')->unwrapInner();\n\n        // If grids are stackable on tablet, also hide (or show) designated mobile elements\n        $dom->filter('.ui[class*=\"stackable on tablet\"].grid [class*=\"mobile hidden\"]')\n            ->removeClass('mobile')\n            ->removeClass('hidden')\n            ->addClass('tablet or lower hidden')\n        ;\n        $dom->filter('.ui[class*=\"stackable on tablet\"].grid [class*=\"mobile only\"]')\n            ->addClass('tablet only')\n        ;\n\n        // Responsive image sizes might be incorrect in responsive grids\n        $dom->filter('.ui.stackable.grid, .ui.doubling.grid, .ui.stackable.cards')\n            ->each(function(HtmlPageCrawler $grid) {\n                $targetImg = '.row > .column > .ui.image > img, .column > .ui.image > img';\n                if ($grid->matches('.cards')) {\n                    $targetImg = '.card > .image > img';\n                }\n\n                // Tag images in stackable on tablet and two column doubling grids\n                if ($grid->matches('[class*=\"stackable on tablet\"]') || $grid->matches('[class*=\"two column\"].doubling')) {\n                    $grid->children($targetImg)->addClass('tablet-expand-full');\n                }\n                // Do the same for doubling grids with more than two columns\n                else if ($grid->matches('.doubling:not([class*=\"two column\"]):not([class*=\"equal width\"])')) {\n                    $grid->children($targetImg)->addClass('tablet-expand-half');\n\n                    if ($grid->matches('.doubling:not(.stackable)')) {\n                        $grid->children($targetImg)->addClass('mobile-expand-half');\n                    }\n                }\n\n                // Only target direct descendants\n                $grid->children($targetImg)\n                    ->each(function(HtmlPageCrawler $img) {\n                        $dataSizes = $img->getAttribute('data-sizes');\n                        $sizes = $dataSizes ?? $img->getAttribute('sizes');\n\n                        if (!$sizes) return;\n\n                        // If lazy load is enabled, sizes are stored in data-sizes\n                        $attribute = 'sizes';\n                        if ($dataSizes) $attribute = 'data-sizes';\n\n                        // Set mobile breakpoints to 100vw, because stacked means full width\n                        $stackedSizes = preg_replace('/\\(min-width: 360px\\).+/','(min-width: 360px) 100vw,', $sizes);\n                        $stackedSizes = preg_replace('/\\(max-width: 359px\\).+/','(max-width: 359px) 100vw', $stackedSizes);\n\n                        // Set optional sizes, if indicated\n                        if ($img->matches('.tablet-expand-full')) {\n                            $stackedSizes = preg_replace('/\\(min-width: 768px\\).+/','(min-width: 768px) 100vw,', $stackedSizes);\n                        }\n                        if ($img->matches('.tablet-expand-half')) {\n                            $stackedSizes = preg_replace('/\\(min-width: 768px\\).+/','(min-width: 768px) 50vw,', $stackedSizes);\n                        }\n                        if ($img->matches('.mobile-expand-half')) {\n                            $stackedSizes = preg_replace('/\\(min-width: 360px\\).+/','(min-width: 360px) 50vw,', $stackedSizes);\n                            $stackedSizes = preg_replace('/\\(max-width: 359px\\).+/','(max-width: 359px) 50vw', $stackedSizes);\n                        }\n\n                        $img->setAttribute($attribute, $stackedSizes);\n                    })\n                ;\n            })\n        ;\n\n        // Add class to empty grid columns\n        $dom->filter('.ui.grid .column')\n            ->each(function(HtmlPageCrawler $column) {\n                if($column->getInnerHtml() === '') {\n                    $column->addClass('empty');\n                }\n            })\n        ;\n\n        // Add column class to nested grids\n        //\n        // If a nested grid contains multiple columns, these columns are\n        //  arranged according to their size, combined with the 'equal width'\n        //  classes on the grid container. This works well in most cases, but\n        //  some grids don't scale down nicely on tablet / computer breakpoints\n        //  because the parent doesn't know how many columns it should count on.\n        // This addition sets these classes on the parent by counting the number\n        //  of columns being parsed.\n        // Only applies to nested grids containing a single row, as different\n        //  column counts can be applied to multiple rows in the same grid.\n        $dom->filter('.ui.nested.equal.width.grid')\n            ->each(function(HtmlPageCrawler $grid) {\n                $firstRow = $grid->filter('.row')->first()->filter('.column');\n                $allRows = $grid->filter('.column');\n\n                // Only operate on grids with a single row\n                if ($firstRow->length == $allRows->length) {\n                    $columns = $firstRow->length;\n                    switch ($columns) {\n                        case 4:\n                            $grid->addClass('four column');\n                            break;\n                        case 3:\n                            $grid->addClass('three column');\n                            break;\n                        case 2:\n                            $grid->addClass('two column');\n                            break;\n                    }\n                }\n            })\n        ;\n\n        $dom->filter('.ui.grid')\n            ->each(function(HtmlPageCrawler $grid) {\n                if ($grid->matches('[class*=\"very relaxed\"]')) {\n                    $grid->filter('.ui.lightbox.image > figcaption.grid')->addClass('very');\n                }\n                if ($grid->matches('.relaxed')) {\n                    $grid->filter('.ui.lightbox.image > figcaption.grid')->addClass('relaxed');\n                }\n            })\n        ;\n\n        // Display bullets above list items in centered lists\n        $dom->filter('.ui.center.aligned')\n            ->each(function(HtmlPageCrawler $container) {\n                $container->filter('.ui.list')->addClass('vertical');\n                $container->filter('.aligned:not(.center) .ui.vertical.list')->removeClass('vertical');\n            })\n        ;\n\n        // Turn HR into divider\n        $dom->filter('hr')\n            ->addClass('ui divider')\n        ;\n\n        // Make regular divider headers smaller\n        $dom->filter('span.ui.divider.header')\n            ->addClass('tiny')\n        ;\n\n        // Place accordion icons right of title\n        $dom->filter('#content .ui.accordion .title > .icon')\n            ->addClass('right')\n        ;\n\n        // Transform regular tables into SUI tables\n        $dom->filter('table:not(.ui.table)')\n            ->addClass('ui table')\n        ;\n\n        // Apply Swiper classes to appropriate slide elements\n        $dom->filter('.swiper')\n            ->each(function (HtmlPageCrawler $slider) {\n                $slider\n                    ->children('.nested.overview')\n                    ->removeClass('stackable')\n                    ->removeClass('doubling')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->children('.gallery')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->children('.cards')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->children('.swiper-wrapper > *')\n                    ->each(function (HtmlPageCrawler $slide) {\n                        if ($slide->hasClass('card')) {\n                            $slide\n                                ->addClass('ui fluid')\n                                ->wrap('<div class=\"swiper-slide\"></div>')\n                            ;\n                        }\n                        elseif ($slide->hasClass('image')) {\n                            $slide\n                                ->removeClass('content')\n                                ->removeClass('rounded')\n                                ->addClass('swiper-slide')\n                            ;\n                        }\n                        else {\n                            $slide->addClass('swiper-slide');\n                        }\n                    })\n                ;\n            })\n        ;\n\n        // Fill lightbox with gallery images\n        $lightbox = array();\n        $lightbox =\n            $dom->filter('.gallery.with.lightbox')\n                ->each(function (HtmlPageCrawler $gallery) use ($modx) {\n                    // Grab images sources from data attributes\n                    $images =\n                        $gallery\n                            ->filter('.lightbox > img')\n                            ->each(function (HtmlPageCrawler $img) use ($modx) {\n                                return $modx->getChunk('galleryRowImageLightbox', array(\n                                    'src' => $img->attr('data-lightbox-img'),\n                                    'caption' => $img->attr('data-caption'),\n                                    'title' => $img->attr('alt'),\n                                    'classes' => 'swiper-slide',\n                                ));\n                            })\n                    ;\n\n                    // Create lightbox for each gallery\n                    return $modx->getChunk('lightboxOuter', array(\n                        'uid' => $gallery->attr('data-uid'),\n                        'output' => implode($images),\n                    ));\n                })\n        ;\n\n        // Add lightbox to HTML\n        $dom->filter('#footer')\n            ->after(implode($lightbox))\n        ;\n\n        // Manipulate images / SVGs\n        $dom->filter('.ui.image, .ui.svg.image > svg, .ui.svg.image > img')\n            ->each(function (HtmlPageCrawler $img) {\n                $width = $img->getAttribute('width');\n                $height = $img->getAttribute('height');\n\n                // Remove empty width & height\n                if (!$width) $img->removeAttr('width');\n                if (!$height) $img->removeAttr('height');\n            })\n        ;\n\n        // Fix inline form fields\n        $dom->filter('.ui.form .inline.fields > .field')\n            ->removeClass('horizontal')\n            ->removeClass('vertical')\n            ->filter('label')\n            ->each(function(HtmlPageCrawler $label) {\n                if($label->getInnerHtml() === '') {\n                    $label->addClass('hidden');\n                }\n            })\n        ;\n        $dom->filter('.ui.form .inline.fields > .wide.field > .dropdown')\n            ->addClass('fluid')\n        ;\n\n        // Format inline (equal width) forms\n        // Counter to what you'd expect, the fields in this form shouldn't have\n        //  class inline. So they need to be removed, and the fields need to be\n        //  wrapped in a .fields container.\n        // Special treatment for the submit button: it can be positioned inline\n        //  via CB settings, after which it's inserted after the last form field.\n        $dom->filter('form[id*=\"form-\"].equal.width')\n            ->each(function (HtmlPageCrawler $form) {\n                $form\n                    ->filter('fieldset:not(:last-child)')\n                    ->wrapInner('<div class=\"fields\"></div>')\n                    ->filter('.field')\n                    ->removeClass('inline')\n                ;\n                $form\n                    ->filter('fieldset.submission')\n                    ->removeClass('submission')\n                    ->filter('input[type=\"submit\"].inline')\n                    ->appendTo($form->filter('fieldset .fields')->last())\n                    ->wrap('<div class=\"compact submission field\">')\n                    ->before('<label>')\n                ;\n            })\n        ;\n\n        // Disable steps following an active step\n        $dom->filter('.ui.consecutive.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->nextAll()\n                    ->addClass('disabled')\n                ;\n            })\n        ;\n        // Mark previous steps as completed\n        $dom->filter('.ui.completable.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->previousAll()\n                    ->addClass('completed')\n                ;\n            })\n        ;\n        // Completed steps can't be disabled\n        $dom->filter('.ui.steps .completed.step')->removeClass('disabled');\n\n        // Make sure AjaxUpload scripts are run after jQuery is loaded\n        $dom->filter('script')\n            ->each(function (HtmlPageCrawler $script) {\n                $src = $script->getAttribute('src');\n                $code = $script->getInnerHtml();\n\n                // Defer loading of AjaxUpload JS file\n                if (strpos($src,'ajaxupload') !== false) {\n                    $script->setAttribute('defer','');\n                }\n\n                // Wait for DOMContentLoaded event instead of using document.ready\n                if (strpos($code,'$(document).ready') !== false) {\n                    $code = str_replace('/* <![CDATA[ */', '', $code);\n                    $code = str_replace('/* ]]> */', '', $code);\n\n                    $script->setInnerHtml(\n                        str_replace(\n                            '$(document).ready(function ()',\n                            'window.addEventListener(\\'DOMContentLoaded\\', function()',\n                            $code\n                        )\n                    );\n                }\n            })\n        ;\n\n        // Fix ID conflicts in project hub\n        $dom->filter('#hub .pattern.segment#content')->setAttribute('id','content-global');\n        $dom->filter('#hub .pattern.segment#css')->setAttribute('id','css-global');\n        $dom->filter('#hub .pattern.segment#footer')->setAttribute('id','footer-global');\n        $dom->filter('#hub .pattern.segment#head')->setAttribute('id','head-global');\n        $dom->filter('#hub .pattern.segment#script')->setAttribute('id','script-global');\n        $dom->filter('#hub .pattern.segment#sidebar')->setAttribute('id','sidebar-global');\n\n        // Change links to fixed IDs\n        $dom->filter('#hub .pattern.segment .list a.item')\n            ->each(function (HtmlPageCrawler $link) {\n                $href = $link->getAttribute('href');\n                switch ($href) {\n                    case ($href == 'patterns/organisms#content'):\n                    case ($href == 'patterns/organisms#css'):\n                    case ($href == 'patterns/organisms#footer'):\n                    case ($href == 'patterns/organisms#head'):\n                    case ($href == 'patterns/organisms#script'):\n                    case ($href == 'patterns/organisms#sidebar'):\n                        $link->setAttribute('href', $href . '-global');\n                        break;\n                }\n            })\n        ;\n\n        // Save manipulated DOM\n        $output = $dom->saveHTML();\n\n        // Cache HTML output\n        if ($cacheFlag) {\n            $modx->cacheManager->set($cacheElementKey, $output, 0, $cacheOptions);\n        }\n        if ($debug) {\n            $modx->log(modX::LOG_LEVEL_ERROR, 'Page DOM manipulated in: ' . microtime(true) - $start);\n        }\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.manipulatedom.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * ManipulateDOM plugin
 *
 * This plugin utilizes HtmlPageDom, a page crawler that can manipulate DOM
 * elements for us. Yes, that is exactly what jQuery does... But now we can do
 * it server side, before the page is rendered. Much faster and more reliable.
 *
 * Update March 2025: generated HTML output is now cached under the regular
 * resource cache. This means it will be cleared also on every save action.
 * Other plugins utilizing the HtmlPageDOM crawler are relying on this cache
 * too, so keep an eye on the priority of this plugin to make sure all output
 * is generated before it is being cached here.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

if (!class_exists(\Wa72\HtmlPageDom\HtmlPageCrawler::class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');
    return;
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        $start = null;
        if ($debug = $modx->getOption('debug', null, false)) {
            $start = microtime(true);
        }

        // Look for cached HTML output first...
        $cacheFlag = false;
        $cacheManager = $modx->getCacheManager();
        $cacheElementKey = '/' . hash('xxh3', $modx->resource->_output);
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
        ];
        // Unless user is logged in, or a POST or search request is made.
        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
        $searchQuery = $_REQUEST['search'] ?? false;
        if (!$isLoggedIn && !$_POST && !$searchQuery) {
            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
            if ($cachedOutput) {
                if ($debug) {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Page DOM loaded from cache in: ' . microtime(true) - $start);
                }
                $modx->resource->_output = $cachedOutput;
                break;
            } else {
                $cacheFlag = true;
            }
        }

        // Check if content type is text/html
        if (!in_array($modx->resource->get('content_type'), [1,11])) {
            break;
        }

        // Get processed output of resource
        $output = &$modx->resource->_output;

        // Feed output to HtmlPageDom
        $dom = new HtmlPageCrawler($output);

        // Add non-white class to body if custom background is set
        try {
            if ($modx->getObject('cgSetting', array('key' => 'theme_page_background_color'))->get('value') !== 'ffffff') {
                $dom->filter('body')->addClass('non-white');
            }
        }
        catch (Error $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, $e);
        }

        // Read inverted parameter from URL (for testing purposes)
        $invertLayouts = $_GET['inverted'] ?? 0;
        if ($invertLayouts) {
            $dom->filter('.ui.menu')
                ->addClass('inverted')
            ;
            $dom->filter('.vertical.stripe.segment.white')
                ->removeClass('white')
                ->addClass('inverted')
            ;
            $dom->filter('.vertical.backyard.segment.secondary')
                ->removeClass('secondary')
                ->addClass('inverted black')
            ;
        }

        // Add header classes to HTML headings
        $dom->filter('h1:not(.header):not(.title)')->addClass('ui header');
        $dom->filter('h2:not(.header):not(.title)')->addClass('ui header');
        $dom->filter('h3:not(.header):not(.title)')->addClass('ui header');
        $dom->filter('h4:not(.header):not(.title)')->addClass('ui header');
        $dom->filter('h5:not(.header):not(.title)')->addClass('ui header');

        // Inject inverted classes to elements inside inverted segments
        $dom->filter('.inverted.segment')
            ->each(function (HtmlPageCrawler $segment) {

                // Define elements that need to receive the inverted class
                $elements = array(
                    '.header',
                    '.grid',
                    'a:not(.button)',
                    '.button:not(.primary):not(.secondary)',
                    '.subtitle',
                    '.lead',
                    '.list',
                    '.quote',
                    '.divider:not(.hidden)',
                    '.accordion:not(.styled)',
                    '.text.menu',
                    '.secondary.menu',
                    '.basic.form',
                    '.basic.segment',
                    '.table',
                    '.steps',
                );

                // Revert inverted styling inside these nested elements
                $exceptions = array(
                    '.segment:not(.inverted):not(.transparent)',
                    '.card',
                    '.message',
                    '.accordion:not(.inverted)',
                    '.popup:not(.inverted)',
                    '.tabbed.menu',
                    '.form:not(.basic)',
                    '.leaflet-container',
                );

                // Prevent elements from having the same color as their parent background
                if ($segment->hasClass('primary-color') || $segment->hasClass('secondary-color') || $segment->hasClass('secondary')) {
                    $segment
                        ->filter('.primary.button')
                        ->addClass('white inverted')
                    ;
                    $segment
                        ->filter('.secondary.button')
                        ->removeClass('secondary')
                        ->addClass('inverted')
                    ;
                    $segment
                        ->filter('.bottom.attached.primary.button')
                        ->removeClass('primary')
                        ->addClass('secondary')
                    ;
                }

                // Elements
                foreach ($elements as $element) {
                    $segment
                        ->filter($element)
                        ->addClass('inverted')
                    ;
                }

                // Exceptions
                foreach ($exceptions as $exception) {
                    $segment
                        ->filter($exception)
                        ->each(function(HtmlPageCrawler $node) {
                            $node
                                ->filter('.inverted')
                                ->removeClass('inverted')
                            ;
                            $node
                                ->filter('.ui.white.button')
                                ->removeClass('white')
                            ;
                        })
                    ;
                }
            })
        ;

        // Remove rows from grids that have a reversed column order on mobile
        $dom->filter('.ui.reversed.grid > .row')->unwrapInner();

        // If grids are stackable on tablet, also hide (or show) designated mobile elements
        $dom->filter('.ui[class*="stackable on tablet"].grid [class*="mobile hidden"]')
            ->removeClass('mobile')
            ->removeClass('hidden')
            ->addClass('tablet or lower hidden')
        ;
        $dom->filter('.ui[class*="stackable on tablet"].grid [class*="mobile only"]')
            ->addClass('tablet only')
        ;

        // Responsive image sizes might be incorrect in responsive grids
        $dom->filter('.ui.stackable.grid, .ui.doubling.grid, .ui.stackable.cards')
            ->each(function(HtmlPageCrawler $grid) {
                $targetImg = '.row > .column > .ui.image > img, .column > .ui.image > img';
                if ($grid->matches('.cards')) {
                    $targetImg = '.card > .image > img';
                }

                // Tag images in stackable on tablet and two column doubling grids
                if ($grid->matches('[class*="stackable on tablet"]') || $grid->matches('[class*="two column"].doubling')) {
                    $grid->children($targetImg)->addClass('tablet-expand-full');
                }
                // Do the same for doubling grids with more than two columns
                else if ($grid->matches('.doubling:not([class*="two column"]):not([class*="equal width"])')) {
                    $grid->children($targetImg)->addClass('tablet-expand-half');

                    if ($grid->matches('.doubling:not(.stackable)')) {
                        $grid->children($targetImg)->addClass('mobile-expand-half');
                    }
                }

                // Only target direct descendants
                $grid->children($targetImg)
                    ->each(function(HtmlPageCrawler $img) {
                        $dataSizes = $img->getAttribute('data-sizes');
                        $sizes = $dataSizes ?? $img->getAttribute('sizes');

                        if (!$sizes) return;

                        // If lazy load is enabled, sizes are stored in data-sizes
                        $attribute = 'sizes';
                        if ($dataSizes) $attribute = 'data-sizes';

                        // Set mobile breakpoints to 100vw, because stacked means full width
                        $stackedSizes = preg_replace('/\(min-width: 360px\).+/','(min-width: 360px) 100vw,', $sizes);
                        $stackedSizes = preg_replace('/\(max-width: 359px\).+/','(max-width: 359px) 100vw', $stackedSizes);

                        // Set optional sizes, if indicated
                        if ($img->matches('.tablet-expand-full')) {
                            $stackedSizes = preg_replace('/\(min-width: 768px\).+/','(min-width: 768px) 100vw,', $stackedSizes);
                        }
                        if ($img->matches('.tablet-expand-half')) {
                            $stackedSizes = preg_replace('/\(min-width: 768px\).+/','(min-width: 768px) 50vw,', $stackedSizes);
                        }
                        if ($img->matches('.mobile-expand-half')) {
                            $stackedSizes = preg_replace('/\(min-width: 360px\).+/','(min-width: 360px) 50vw,', $stackedSizes);
                            $stackedSizes = preg_replace('/\(max-width: 359px\).+/','(max-width: 359px) 50vw', $stackedSizes);
                        }

                        $img->setAttribute($attribute, $stackedSizes);
                    })
                ;
            })
        ;

        // Add class to empty grid columns
        $dom->filter('.ui.grid .column')
            ->each(function(HtmlPageCrawler $column) {
                if($column->getInnerHtml() === '') {
                    $column->addClass('empty');
                }
            })
        ;

        // Add column class to nested grids
        //
        // If a nested grid contains multiple columns, these columns are
        //  arranged according to their size, combined with the 'equal width'
        //  classes on the grid container. This works well in most cases, but
        //  some grids don't scale down nicely on tablet / computer breakpoints
        //  because the parent doesn't know how many columns it should count on.
        // This addition sets these classes on the parent by counting the number
        //  of columns being parsed.
        // Only applies to nested grids containing a single row, as different
        //  column counts can be applied to multiple rows in the same grid.
        $dom->filter('.ui.nested.equal.width.grid')
            ->each(function(HtmlPageCrawler $grid) {
                $firstRow = $grid->filter('.row')->first()->filter('.column');
                $allRows = $grid->filter('.column');

                // Only operate on grids with a single row
                if ($firstRow->length == $allRows->length) {
                    $columns = $firstRow->length;
                    switch ($columns) {
                        case 4:
                            $grid->addClass('four column');
                            break;
                        case 3:
                            $grid->addClass('three column');
                            break;
                        case 2:
                            $grid->addClass('two column');
                            break;
                    }
                }
            })
        ;

        $dom->filter('.ui.grid')
            ->each(function(HtmlPageCrawler $grid) {
                if ($grid->matches('[class*="very relaxed"]')) {
                    $grid->filter('.ui.lightbox.image > figcaption.grid')->addClass('very');
                }
                if ($grid->matches('.relaxed')) {
                    $grid->filter('.ui.lightbox.image > figcaption.grid')->addClass('relaxed');
                }
            })
        ;

        // Display bullets above list items in centered lists
        $dom->filter('.ui.center.aligned')
            ->each(function(HtmlPageCrawler $container) {
                $container->filter('.ui.list')->addClass('vertical');
                $container->filter('.aligned:not(.center) .ui.vertical.list')->removeClass('vertical');
            })
        ;

        // Turn HR into divider
        $dom->filter('hr')
            ->addClass('ui divider')
        ;

        // Make regular divider headers smaller
        $dom->filter('span.ui.divider.header')
            ->addClass('tiny')
        ;

        // Place accordion icons right of title
        $dom->filter('#content .ui.accordion .title > .icon')
            ->addClass('right')
        ;

        // Transform regular tables into SUI tables
        $dom->filter('table:not(.ui.table)')
            ->addClass('ui table')
        ;

        // Apply Swiper classes to appropriate slide elements
        $dom->filter('.swiper')
            ->each(function (HtmlPageCrawler $slider) {
                $slider
                    ->children('.nested.overview')
                    ->removeClass('stackable')
                    ->removeClass('doubling')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->children('.gallery')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->children('.cards')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->children('.swiper-wrapper > *')
                    ->each(function (HtmlPageCrawler $slide) {
                        if ($slide->hasClass('card')) {
                            $slide
                                ->addClass('ui fluid')
                                ->wrap('<div class="swiper-slide"></div>')
                            ;
                        }
                        elseif ($slide->hasClass('image')) {
                            $slide
                                ->removeClass('content')
                                ->removeClass('rounded')
                                ->addClass('swiper-slide')
                            ;
                        }
                        else {
                            $slide->addClass('swiper-slide');
                        }
                    })
                ;
            })
        ;

        // Fill lightbox with gallery images
        $lightbox = array();
        $lightbox =
            $dom->filter('.gallery.with.lightbox')
                ->each(function (HtmlPageCrawler $gallery) use ($modx) {
                    // Grab images sources from data attributes
                    $images =
                        $gallery
                            ->filter('.lightbox > img')
                            ->each(function (HtmlPageCrawler $img) use ($modx) {
                                return $modx->getChunk('galleryRowImageLightbox', array(
                                    'src' => $img->attr('data-lightbox-img'),
                                    'caption' => $img->attr('data-caption'),
                                    'title' => $img->attr('alt'),
                                    'classes' => 'swiper-slide',
                                ));
                            })
                    ;

                    // Create lightbox for each gallery
                    return $modx->getChunk('lightboxOuter', array(
                        'uid' => $gallery->attr('data-uid'),
                        'output' => implode($images),
                    ));
                })
        ;

        // Add lightbox to HTML
        $dom->filter('#footer')
            ->after(implode($lightbox))
        ;

        // Manipulate images / SVGs
        $dom->filter('.ui.image, .ui.svg.image > svg, .ui.svg.image > img')
            ->each(function (HtmlPageCrawler $img) {
                $width = $img->getAttribute('width');
                $height = $img->getAttribute('height');

                // Remove empty width & height
                if (!$width) $img->removeAttr('width');
                if (!$height) $img->removeAttr('height');
            })
        ;

        // Fix inline form fields
        $dom->filter('.ui.form .inline.fields > .field')
            ->removeClass('horizontal')
            ->removeClass('vertical')
            ->filter('label')
            ->each(function(HtmlPageCrawler $label) {
                if($label->getInnerHtml() === '') {
                    $label->addClass('hidden');
                }
            })
        ;
        $dom->filter('.ui.form .inline.fields > .wide.field > .dropdown')
            ->addClass('fluid')
        ;

        // Format inline (equal width) forms
        // Counter to what you'd expect, the fields in this form shouldn't have
        //  class inline. So they need to be removed, and the fields need to be
        //  wrapped in a .fields container.
        // Special treatment for the submit button: it can be positioned inline
        //  via CB settings, after which it's inserted after the last form field.
        $dom->filter('form[id*="form-"].equal.width')
            ->each(function (HtmlPageCrawler $form) {
                $form
                    ->filter('fieldset:not(:last-child)')
                    ->wrapInner('<div class="fields"></div>')
                    ->filter('.field')
                    ->removeClass('inline')
                ;
                $form
                    ->filter('fieldset.submission')
                    ->removeClass('submission')
                    ->filter('input[type="submit"].inline')
                    ->appendTo($form->filter('fieldset .fields')->last())
                    ->wrap('<div class="compact submission field">')
                    ->before('<label>')
                ;
            })
        ;

        // Disable steps following an active step
        $dom->filter('.ui.consecutive.steps .active.step')
            ->each(function (HtmlPageCrawler $step) {
                $step
                    ->nextAll()
                    ->addClass('disabled')
                ;
            })
        ;
        // Mark previous steps as completed
        $dom->filter('.ui.completable.steps .active.step')
            ->each(function (HtmlPageCrawler $step) {
                $step
                    ->previousAll()
                    ->addClass('completed')
                ;
            })
        ;
        // Completed steps can't be disabled
        $dom->filter('.ui.steps .completed.step')->removeClass('disabled');

        // Make sure AjaxUpload scripts are run after jQuery is loaded
        $dom->filter('script')
            ->each(function (HtmlPageCrawler $script) {
                $src = $script->getAttribute('src');
                $code = $script->getInnerHtml();

                // Defer loading of AjaxUpload JS file
                if (strpos($src,'ajaxupload') !== false) {
                    $script->setAttribute('defer','');
                }

                // Wait for DOMContentLoaded event instead of using document.ready
                if (strpos($code,'$(document).ready') !== false) {
                    $code = str_replace('/* <![CDATA[ */', '', $code);
                    $code = str_replace('/* ]]> */', '', $code);

                    $script->setInnerHtml(
                        str_replace(
                            '$(document).ready(function ()',
                            'window.addEventListener(\'DOMContentLoaded\', function()',
                            $code
                        )
                    );
                }
            })
        ;

        // Fix ID conflicts in project hub
        $dom->filter('#hub .pattern.segment#content')->setAttribute('id','content-global');
        $dom->filter('#hub .pattern.segment#css')->setAttribute('id','css-global');
        $dom->filter('#hub .pattern.segment#footer')->setAttribute('id','footer-global');
        $dom->filter('#hub .pattern.segment#head')->setAttribute('id','head-global');
        $dom->filter('#hub .pattern.segment#script')->setAttribute('id','script-global');
        $dom->filter('#hub .pattern.segment#sidebar')->setAttribute('id','sidebar-global');

        // Change links to fixed IDs
        $dom->filter('#hub .pattern.segment .list a.item')
            ->each(function (HtmlPageCrawler $link) {
                $href = $link->getAttribute('href');
                switch ($href) {
                    case ($href == 'patterns/organisms#content'):
                    case ($href == 'patterns/organisms#css'):
                    case ($href == 'patterns/organisms#footer'):
                    case ($href == 'patterns/organisms#head'):
                    case ($href == 'patterns/organisms#script'):
                    case ($href == 'patterns/organisms#sidebar'):
                        $link->setAttribute('href', $href . '-global');
                        break;
                }
            })
        ;

        // Save manipulated DOM
        $output = $dom->saveHTML();

        // Cache HTML output
        if ($cacheFlag) {
            $modx->cacheManager->set($cacheElementKey, $output, 0, $cacheOptions);
        }
        if ($debug) {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Page DOM manipulated in: ' . microtime(true) - $start);
        }

        break;
}