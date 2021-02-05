id: 39
name: ManipulateDOM
description: 'Manipulate DOM elements with HtmlPageDom. Yes, that''s exactly what jQuery does... But now we can do it server side, before the page is rendered. Much faster and more reliable.'
category: c_content
plugincode: "/**\n * ManipulateDOM plugin\n *\n * This plugin utilizes HtmlPageDom, a page crawler that can manipulate DOM\n * elements for us. Yes, that is exactly what jQuery does... But now we can do\n * it server side, before the page is rendered. Much faster and more reliable.\n *\n * @var modX $modx\n * @package romanesco\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Check if content type is text/html\n        if ($modx->resource->get('content_type') !== 1) {\n            break;\n        }\n\n        // Get processed output of resource\n        $output = &$modx->resource->_output;\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($output);\n\n        // Add non-white class to body if custom background is set\n        if ($modx->getObject('cgSetting', array('key' => 'theme_page_background_color'))->get('value') !== 'ffffff') {\n            $dom->filter('body')->addClass('non-white');\n        }\n\n        // Add header class to content headers without class name\n        $dom->filter('h1:not(.header)')->addClass('ui header');\n        $dom->filter('h2:not(.header)')->addClass('ui header');\n        $dom->filter('h3:not(.header)')->addClass('ui header');\n        $dom->filter('h4:not(.header)')->addClass('ui header');\n        $dom->filter('h5:not(.header)')->addClass('ui header');\n\n        // Inject inverted classes to elements inside inverted segments\n        $dom->filter('.inverted.segment')\n            ->each(function (HtmlPageCrawler $segment) {\n\n                // Define elements that need to receive the inverted class\n                $elements = array(\n                    '.header',\n                    '.grid',\n                    'a:not(.button)',\n                    '.button:not(.primary):not(.secondary)',\n                    '.lead',\n                    '.list',\n                    '.quote',\n                    '.divider:not(.hidden)',\n                    '.basic.form',\n                    '.accordion:not(.styled)',\n                );\n\n                // Revert inverted styling inside these nested elements\n                $exceptions = array(\n                    '.segment:not(.inverted):not(.transparent)',\n                    '.card',\n                    '.tabbed.menu',\n                    '.accordion:not(.inverted)',\n                    '.popup:not(.inverted)',\n                    '.message',\n                    '.leaflet-container',\n                );\n\n                // Prevent elements from having the same color as their parent background\n                if ($segment->hasClass('primary-color')) {\n                    $segment\n                        ->filter('.primary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                    $segment\n                        ->filter('.bottom.attached.primary.button')\n                        ->removeClass('primary')\n                        ->addClass('secondary')\n                    ;\n                }\n                if ($segment->hasClass('secondary-color')) {\n                    $segment\n                        ->filter('.secondary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Elements\n                foreach ($elements as $element) {\n                    $segment\n                        ->filter($element)\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Exceptions\n                foreach ($exceptions as $exception) {\n                    $segment\n                        ->filter($exception)\n                        ->each(function(HtmlPageCrawler $node) {\n                            $node\n                                ->filter('.inverted')\n                                ->removeClass('inverted')\n                            ;\n                        })\n                    ;\n                }\n            })\n        ;\n\n        // Remove rows from grids that have a reversed column order on mobile\n        $dom->filter('.reversed.grid > .row')->unwrapInner();\n\n        // Apply Swiper classes to appropriate slide elements\n        $dom->filter('.swiper-container')\n            ->each(function (HtmlPageCrawler $slider) {\n                $slider\n                    ->filter('.nested.overview')\n                    ->removeClass('stackable')\n                    ->removeClass('doubling')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.gallery')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.cards')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.swiper-wrapper > *')\n                    ->each(function (HtmlPageCrawler $slide) {\n                        if ($slide->hasClass('card')) {\n                            $slide\n                                ->addClass('ui fluid')\n                                ->wrap('<div class=\"swiper-slide\"></div>')\n                            ;\n                        }\n                        elseif ($slide->hasClass('image')) {\n                            $slide\n                                ->removeClass('content')\n                                ->removeClass('rounded')\n                                ->addClass('swiper-slide')\n                            ;\n                        }\n                        else {\n                            $slide->addClass('swiper-slide');\n                        }\n                    })\n                ;\n                // Move prev/next buttons out of container\n                // No longer used, but kept here as reference for how to find parent elements\n                //$slider->parents()->each(function (HtmlPageCrawler $parent) {\n                //    if ($parent->hasClass('nested','slider')) {\n                //        $parent->filter('.swiper-button-prev')->appendTo($parent);\n                //        $parent->filter('.swiper-button-next')->appendTo($parent);\n                //    }\n                //});\n            })\n        ;\n\n        // Fill lightbox with gallery images\n        $lightbox = array();\n        $lightbox =\n            $dom->filter('.gallery.with.lightbox')\n                ->each(function (HtmlPageCrawler $gallery) {\n                    global $modx;\n\n                    // Grab images sources from data attributes\n                    $images =\n                        $gallery\n                            ->filter('.lightbox > img')\n                            ->each(function (HtmlPageCrawler $img) {\n                                global $modx;\n                                return $modx->getChunk('galleryRowImageLightbox', array(\n                                    'src' => $img->attr('data-lightbox-img'),\n                                    'caption' => $img->attr('data-caption'),\n                                    'title' => $img->attr('alt'),\n                                    'classes' => 'swiper-slide',\n                                ));\n                            })\n                    ;\n\n                    // Create lightbox for each gallery\n                    return $modx->getChunk('lightboxOuter', array(\n                        'uid' => $gallery->attr('data-uid'),\n                        'output' => implode($images),\n                    ));\n                })\n        ;\n\n        // Add lightbox to HTML\n        $dom->filter('#footer')\n            ->append(implode($lightbox))\n        ;\n\n        // Disable steps following an active step\n        $dom->filter('.ui.consecutive.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->nextAll()\n                    ->addClass('disabled')\n                ;\n            })\n        ;\n        // Mark previous steps as completed\n        $dom->filter('.ui.completable.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->previousAll()\n                    ->addClass('completed')\n                ;\n            })\n        ;\n\n        // Save manipulated DOM\n        $output = $dom->saveHTML();\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.manipulatedom.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * ManipulateDOM plugin\n *\n * This plugin utilizes HtmlPageDom, a page crawler that can manipulate DOM\n * elements for us. Yes, that is exactly what jQuery does... But now we can do\n * it server side, before the page is rendered. Much faster and more reliable.\n *\n * @var modX $modx\n * @package romanesco\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Check if content type is text/html\n        if ($modx->resource->get('content_type') !== 1) {\n            break;\n        }\n\n        // Get processed output of resource\n        $output = &$modx->resource->_output;\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($output);\n\n        // Add non-white class to body if custom background is set\n        if ($modx->getObject('cgSetting', array('key' => 'theme_page_background_color'))->get('value') !== 'ffffff') {\n            $dom->filter('body')->addClass('non-white');\n        }\n\n        // Add header class to content headers without class name\n        $dom->filter('h1:not(.header)')->addClass('ui header');\n        $dom->filter('h2:not(.header)')->addClass('ui header');\n        $dom->filter('h3:not(.header)')->addClass('ui header');\n        $dom->filter('h4:not(.header)')->addClass('ui header');\n        $dom->filter('h5:not(.header)')->addClass('ui header');\n\n        // Inject inverted classes to elements inside inverted segments\n        $dom->filter('.inverted.segment')\n            ->each(function (HtmlPageCrawler $segment) {\n\n                // Define elements that need to receive the inverted class\n                $elements = array(\n                    '.header',\n                    '.grid',\n                    'a:not(.button)',\n                    '.button:not(.primary):not(.secondary)',\n                    '.lead',\n                    '.list',\n                    '.quote',\n                    '.divider:not(.hidden)',\n                    '.basic.form',\n                    '.accordion:not(.styled)',\n                );\n\n                // Revert inverted styling inside these nested elements\n                $exceptions = array(\n                    '.segment:not(.inverted):not(.transparent)',\n                    '.card',\n                    '.tabbed.menu',\n                    '.accordion:not(.inverted)',\n                    '.popup:not(.inverted)',\n                    '.message',\n                    '.leaflet-container',\n                );\n\n                // Prevent elements from having the same color as their parent background\n                if ($segment->hasClass('primary-color')) {\n                    $segment\n                        ->filter('.primary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                    $segment\n                        ->filter('.bottom.attached.primary.button')\n                        ->removeClass('primary')\n                        ->addClass('secondary')\n                    ;\n                }\n                if ($segment->hasClass('secondary-color')) {\n                    $segment\n                        ->filter('.secondary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Elements\n                foreach ($elements as $element) {\n                    $segment\n                        ->filter($element)\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Exceptions\n                foreach ($exceptions as $exception) {\n                    $segment\n                        ->filter($exception)\n                        ->each(function(HtmlPageCrawler $node) {\n                            $node\n                                ->filter('.inverted')\n                                ->removeClass('inverted')\n                            ;\n                        })\n                    ;\n                }\n            })\n        ;\n\n        // Remove rows from grids that have a reversed column order on mobile\n        $dom->filter('.reversed.grid > .row')->unwrapInner();\n\n        // Apply Swiper classes to appropriate slide elements\n        $dom->filter('.swiper-container')\n            ->each(function (HtmlPageCrawler $slider) {\n                $slider\n                    ->filter('.nested.overview')\n                    ->removeClass('stackable')\n                    ->removeClass('doubling')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.gallery')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.cards')\n                    ->addClass('swiper-wrapper')\n                ;\n                $slider\n                    ->filter('.swiper-wrapper > *')\n                    ->each(function (HtmlPageCrawler $slide) {\n                        if ($slide->hasClass('card')) {\n                            $slide\n                                ->addClass('ui fluid')\n                                ->wrap('<div class=\"swiper-slide\"></div>')\n                            ;\n                        }\n                        elseif ($slide->hasClass('image')) {\n                            $slide\n                                ->removeClass('content')\n                                ->removeClass('rounded')\n                                ->addClass('swiper-slide')\n                            ;\n                        }\n                        else {\n                            $slide->addClass('swiper-slide');\n                        }\n                    })\n                ;\n                // Move prev/next buttons out of container\n                // No longer used, but kept here as reference for how to find parent elements\n                //$slider->parents()->each(function (HtmlPageCrawler $parent) {\n                //    if ($parent->hasClass('nested','slider')) {\n                //        $parent->filter('.swiper-button-prev')->appendTo($parent);\n                //        $parent->filter('.swiper-button-next')->appendTo($parent);\n                //    }\n                //});\n            })\n        ;\n\n        // Fill lightbox with gallery images\n        $lightbox = array();\n        $lightbox =\n            $dom->filter('.gallery.with.lightbox')\n                ->each(function (HtmlPageCrawler $gallery) {\n                    global $modx;\n\n                    // Grab images sources from data attributes\n                    $images =\n                        $gallery\n                            ->filter('.lightbox > img')\n                            ->each(function (HtmlPageCrawler $img) {\n                                global $modx;\n                                return $modx->getChunk('galleryRowImageLightbox', array(\n                                    'src' => $img->attr('data-lightbox-img'),\n                                    'caption' => $img->attr('data-caption'),\n                                    'title' => $img->attr('alt'),\n                                    'classes' => 'swiper-slide',\n                                ));\n                            })\n                    ;\n\n                    // Create lightbox for each gallery\n                    return $modx->getChunk('lightboxOuter', array(\n                        'uid' => $gallery->attr('data-uid'),\n                        'output' => implode($images),\n                    ));\n                })\n        ;\n\n        // Add lightbox to HTML\n        $dom->filter('#footer')\n            ->append(implode($lightbox))\n        ;\n\n        // Disable steps following an active step\n        $dom->filter('.ui.consecutive.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->nextAll()\n                    ->addClass('disabled')\n                ;\n            })\n        ;\n        // Mark previous steps as completed\n        $dom->filter('.ui.completable.steps .active.step')\n            ->each(function (HtmlPageCrawler $step) {\n                $step\n                    ->previousAll()\n                    ->addClass('completed')\n                ;\n            })\n        ;\n\n        // Save manipulated DOM\n        $output = $dom->saveHTML();\n\n        break;\n}"

-----


/**
 * ManipulateDOM plugin
 *
 * This plugin utilizes HtmlPageDom, a page crawler that can manipulate DOM
 * elements for us. Yes, that is exactly what jQuery does... But now we can do
 * it server side, before the page is rendered. Much faster and more reliable.
 *
 * @var modX $modx
 * @package romanesco
 */

$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');

if (!class_exists('\Wa72\HtmlPageDom\HtmlPageCrawler')) {
    require $corePath . 'vendor/autoload.php';
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        // Check if content type is text/html
        if ($modx->resource->get('content_type') !== 1) {
            break;
        }

        // Get processed output of resource
        $output = &$modx->resource->_output;

        // Feed output to HtmlPageDom
        $dom = new HtmlPageCrawler($output);

        // Add non-white class to body if custom background is set
        if ($modx->getObject('cgSetting', array('key' => 'theme_page_background_color'))->get('value') !== 'ffffff') {
            $dom->filter('body')->addClass('non-white');
        }

        // Add header class to content headers without class name
        $dom->filter('h1:not(.header)')->addClass('ui header');
        $dom->filter('h2:not(.header)')->addClass('ui header');
        $dom->filter('h3:not(.header)')->addClass('ui header');
        $dom->filter('h4:not(.header)')->addClass('ui header');
        $dom->filter('h5:not(.header)')->addClass('ui header');

        // Inject inverted classes to elements inside inverted segments
        $dom->filter('.inverted.segment')
            ->each(function (HtmlPageCrawler $segment) {

                // Define elements that need to receive the inverted class
                $elements = array(
                    '.header',
                    '.grid',
                    'a:not(.button)',
                    '.button:not(.primary):not(.secondary)',
                    '.lead',
                    '.list',
                    '.quote',
                    '.divider:not(.hidden)',
                    '.basic.form',
                    '.accordion:not(.styled)',
                );

                // Revert inverted styling inside these nested elements
                $exceptions = array(
                    '.segment:not(.inverted):not(.transparent)',
                    '.card',
                    '.tabbed.menu',
                    '.accordion:not(.inverted)',
                    '.popup:not(.inverted)',
                    '.message',
                    '.leaflet-container',
                );

                // Prevent elements from having the same color as their parent background
                if ($segment->hasClass('primary-color')) {
                    $segment
                        ->filter('.primary.button')
                        ->removeClass('basic')
                        ->addClass('inverted')
                    ;
                    $segment
                        ->filter('.bottom.attached.primary.button')
                        ->removeClass('primary')
                        ->addClass('secondary')
                    ;
                }
                if ($segment->hasClass('secondary-color')) {
                    $segment
                        ->filter('.secondary.button')
                        ->removeClass('basic')
                        ->addClass('inverted')
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
                        })
                    ;
                }
            })
        ;

        // Remove rows from grids that have a reversed column order on mobile
        $dom->filter('.reversed.grid > .row')->unwrapInner();

        // Apply Swiper classes to appropriate slide elements
        $dom->filter('.swiper-container')
            ->each(function (HtmlPageCrawler $slider) {
                $slider
                    ->filter('.nested.overview')
                    ->removeClass('stackable')
                    ->removeClass('doubling')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->filter('.gallery')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->filter('.cards')
                    ->addClass('swiper-wrapper')
                ;
                $slider
                    ->filter('.swiper-wrapper > *')
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
                // Move prev/next buttons out of container
                // No longer used, but kept here as reference for how to find parent elements
                //$slider->parents()->each(function (HtmlPageCrawler $parent) {
                //    if ($parent->hasClass('nested','slider')) {
                //        $parent->filter('.swiper-button-prev')->appendTo($parent);
                //        $parent->filter('.swiper-button-next')->appendTo($parent);
                //    }
                //});
            })
        ;

        // Fill lightbox with gallery images
        $lightbox = array();
        $lightbox =
            $dom->filter('.gallery.with.lightbox')
                ->each(function (HtmlPageCrawler $gallery) {
                    global $modx;

                    // Grab images sources from data attributes
                    $images =
                        $gallery
                            ->filter('.lightbox > img')
                            ->each(function (HtmlPageCrawler $img) {
                                global $modx;
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
            ->append(implode($lightbox))
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

        // Save manipulated DOM
        $output = $dom->saveHTML();

        break;
}