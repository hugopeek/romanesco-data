id: 35
name: InjectInvertedClasses
category: c_content
plugincode: "/**\n *\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Check if content type is text/html\n        if ($modx->resource->get('content_type') !== 1) {\n            break;\n        }\n\n        // Get processed output of resource\n        $output = &$modx->resource->_output;\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($output);\n\n        // Do your thing\n        $dom->filter('.inverted.segment')\n            ->each(function (HtmlPageCrawler $segment) {\n\n                // Define elements that need to receive the inverted class\n                $elements = array(\n                    '.header',\n                    '.grid',\n                    'a:not(.button)',\n                    '.button:not(.primary):not(.secondary)',\n                    '.lead',\n                    '.list',\n                    '.quote',\n                    '.divider:not(.hidden)',\n                    '.basic.form',\n                    '.accordion:not(.styled)',\n                );\n\n                // Revert inverted styling inside these nested elements\n                $exceptions = array(\n                    '.segment:not(.inverted):not(.transparent)',\n                    '.card',\n                    '.tabbed.menu',\n                    '.accordion:not(.inverted)',\n                    '.message',\n                    '.leaflet-container',\n                );\n\n                // Prevent elements from having the same color as their parent background\n                if ($segment->hasClass('primary-color')) {\n                    $segment\n                        ->filter('.primary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                    $segment\n                        ->filter('.bottom.attached.primary.button')\n                        ->removeClass('primary')\n                        ->addClass('secondary')\n                    ;\n                }\n                if ($segment->hasClass('secondary-color')) {\n                    $segment\n                        ->filter('.secondary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Elements\n                foreach ($elements as $element) {\n                    $segment\n                        ->filter($element)\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Exceptions\n                foreach ($exceptions as $exception) {\n                    $segment\n                        ->filter($exception)\n                        ->each(function(HtmlPageCrawler $node) {\n                            $node->filter('.inverted')->removeClass('inverted');\n                        })\n                    ;\n                }\n            })\n        ;\n\n        $output = $dom->saveHTML();\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n *\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Check if content type is text/html\n        if ($modx->resource->get('content_type') !== 1) {\n            break;\n        }\n\n        // Get processed output of resource\n        $output = &$modx->resource->_output;\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($output);\n\n        // Do your thing\n        $dom->filter('.inverted.segment')\n            ->each(function (HtmlPageCrawler $segment) {\n\n                // Define elements that need to receive the inverted class\n                $elements = array(\n                    '.header',\n                    '.grid',\n                    'a:not(.button)',\n                    '.button:not(.primary):not(.secondary)',\n                    '.lead',\n                    '.list',\n                    '.quote',\n                    '.divider:not(.hidden)',\n                    '.basic.form',\n                    '.accordion:not(.styled)',\n                );\n\n                // Revert inverted styling inside these nested elements\n                $exceptions = array(\n                    '.segment:not(.inverted):not(.transparent)',\n                    '.card',\n                    '.tabbed.menu',\n                    '.accordion:not(.inverted)',\n                    '.message',\n                    '.leaflet-container',\n                );\n\n                // Prevent elements from having the same color as their parent background\n                if ($segment->hasClass('primary-color')) {\n                    $segment\n                        ->filter('.primary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                    $segment\n                        ->filter('.bottom.attached.primary.button')\n                        ->removeClass('primary')\n                        ->addClass('secondary')\n                    ;\n                }\n                if ($segment->hasClass('secondary-color')) {\n                    $segment\n                        ->filter('.secondary.button')\n                        ->removeClass('basic')\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Elements\n                foreach ($elements as $element) {\n                    $segment\n                        ->filter($element)\n                        ->addClass('inverted')\n                    ;\n                }\n\n                // Exceptions\n                foreach ($exceptions as $exception) {\n                    $segment\n                        ->filter($exception)\n                        ->each(function(HtmlPageCrawler $node) {\n                            $node->filter('.inverted')->removeClass('inverted');\n                        })\n                    ;\n                }\n            })\n        ;\n\n        $output = $dom->saveHTML();\n\n        break;\n}"

-----


/**
 *
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

        // Do your thing
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
                            $node->filter('.inverted')->removeClass('inverted');
                        })
                    ;
                }
            })
        ;

        $output = $dom->saveHTML();

        break;
}