id: 44
name: AutomaticReferences
description: 'Turn references to an external link (the ones you can create under TVs > Links) into an actual link. Links are referenced by their number value and must be enclosed in square brackets: [12].'
category: c_content
plugincode: "/**\n * AutomaticReferences plugin\n *\n * Turn references to an external link (the ones you created under TVs > Links)\n * into an actual link. Links are referenced by their number value and must be\n * enclosed in square brackets: [12].\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'externalNavItemLabel');\n\n// Load HtmlPageDom\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n\n        // Generate links if requested\n        if ($modx->resource->getTVValue('auto_references')) {\n\n            // Get external links for this resource\n            $linkObject = $modx->getIterator('rmExternalLink', [\n                'resource_id' => $modx->resource->get('id'),\n                'deleted' => 0\n            ]);\n\n            if (!is_object($linkObject)) break;\n\n            // Prepare array with link tags\n            $links = [];\n            foreach ($linkObject as $link) {\n                $links[$link->get('number')] = $modx->getChunk($tpl, $link->toArray());\n            }\n\n            // Feed output to HtmlPageDom\n            $dom = new HtmlPageCrawler($content);\n\n            // Search the content area for references\n            $dom->filter('#content')\n                ->filter('p,li')\n                ->each(function (HtmlPageCrawler $node) use ($links) {\n                    $text = $node->getInnerHtml();\n\n                    // Only accept numeric values inside square brackets\n                    preg_match_all('/\\[[0-9]+\\]/', $text, $matches);\n\n                    // Filter duplicate matches to avoid glitches\n                    $matches = array_unique($matches[0]);\n\n                    if (!$matches) return true;\n\n                    foreach ($matches as $match) {\n                        $number = filter_var($match, FILTER_SANITIZE_NUMBER_INT);\n                        $text = str_replace($match, $links[$number], $text);\n                    }\n\n                    $node->setInnerHtml($text);\n                    return true;\n                })\n            ;\n\n            $content = $dom->saveHTML();\n        }\n\n        break;\n}\n\nreturn true;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:43:"romanesco.automaticreferences.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * AutomaticReferences plugin\n *\n * Turn references to an external link (the ones you created under TVs > Links)\n * into an actual link. Links are referenced by their number value and must be\n * enclosed in square brackets: [12].\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'externalNavItemLabel');\n\n// Load HtmlPageDom\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n\n        // Generate links if requested\n        if ($modx->resource->getTVValue('auto_references')) {\n\n            // Get external links for this resource\n            $linkObject = $modx->getIterator('rmExternalLink', [\n                'resource_id' => $modx->resource->get('id'),\n                'deleted' => 0\n            ]);\n\n            if (!is_object($linkObject)) break;\n\n            // Prepare array with link tags\n            $links = [];\n            foreach ($linkObject as $link) {\n                $links[$link->get('number')] = $modx->getChunk($tpl, $link->toArray());\n            }\n\n            // Feed output to HtmlPageDom\n            $dom = new HtmlPageCrawler($content);\n\n            // Search the content area for references\n            $dom->filter('#content')\n                ->filter('p,li')\n                ->each(function (HtmlPageCrawler $node) use ($links) {\n                    $text = $node->getInnerHtml();\n\n                    // Only accept numeric values inside square brackets\n                    preg_match_all('/\\[[0-9]+\\]/', $text, $matches);\n\n                    // Filter duplicate matches to avoid glitches\n                    $matches = array_unique($matches[0]);\n\n                    if (!$matches) return true;\n\n                    foreach ($matches as $match) {\n                        $number = filter_var($match, FILTER_SANITIZE_NUMBER_INT);\n                        $text = str_replace($match, $links[$number], $text);\n                    }\n\n                    $node->setInnerHtml($text);\n                    return true;\n                })\n            ;\n\n            $content = $dom->saveHTML();\n        }\n\n        break;\n}\n\nreturn true;"

-----


/**
 * AutomaticReferences plugin
 *
 * Turn references to an external link (the ones you created under TVs > Links)
 * into an actual link. Links are referenced by their number value and must be
 * enclosed in square brackets: [12].
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');
$tpl = $modx->getOption('tpl', $scriptProperties, 'externalNavItemLabel');

// Load HtmlPageDom
if (!class_exists('\Wa72\HtmlPageDom\HtmlPageCrawler')) {
    require $corePath . 'vendor/autoload.php';
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        // Get processed output of resource
        $content = &$modx->resource->_output;

        // Generate links if requested
        if ($modx->resource->getTVValue('auto_references')) {

            // Get external links for this resource
            $linkObject = $modx->getIterator('rmExternalLink', [
                'resource_id' => $modx->resource->get('id'),
                'deleted' => 0
            ]);

            if (!is_object($linkObject)) break;

            // Prepare array with link tags
            $links = [];
            foreach ($linkObject as $link) {
                $links[$link->get('number')] = $modx->getChunk($tpl, $link->toArray());
            }

            // Feed output to HtmlPageDom
            $dom = new HtmlPageCrawler($content);

            // Search the content area for references
            $dom->filter('#content')
                ->filter('p,li')
                ->each(function (HtmlPageCrawler $node) use ($links) {
                    $text = $node->getInnerHtml();

                    // Only accept numeric values inside square brackets
                    preg_match_all('/\[[0-9]+\]/', $text, $matches);

                    // Filter duplicate matches to avoid glitches
                    $matches = array_unique($matches[0]);

                    if (!$matches) return true;

                    foreach ($matches as $match) {
                        $number = filter_var($match, FILTER_SANITIZE_NUMBER_INT);
                        $text = str_replace($match, $links[$number], $text);
                    }

                    $node->setInnerHtml($text);
                    return true;
                })
            ;

            $content = $dom->saveHTML();
        }

        break;
}

return true;