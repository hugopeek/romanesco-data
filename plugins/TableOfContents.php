id: 37
name: TableOfContents
description: 'Generate a menu with internal links to all headings in the content. The headers need to have an anchor, which can be automatically attached by switching on the auto_anchors TV.'
category: c_content
plugincode: "/**\n * TableOfContents plugin\n *\n * Generate a menu with internal links to all headings in the content.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nif (!class_exists(\\Wa72\\HtmlPageDom\\HtmlPageCrawler::class)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');\n    return;\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\n$tpl = $modx->getPlaceholder('toc.tpl') ?? 'tocNavItem';\n$target = $modx->getPlaceholder('toc.target');\n\n// Abort if ToC target is not set\nif (!$target) {\n    return true;\n}\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n        $resourceURI = $modx->resource->get('uri');\n        $headings = $modx->resource->getTVValue('toc_headings');\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($content);\n\n        // Generate anchors if requested\n        if ($modx->resource->getTVValue('auto_anchors')) {\n            $dom->filter('#content')\n                ->filter('h1,h2,h3,h4,h5,h6')\n                ->each(function (HtmlPageCrawler $node) {\n                    $text = $node->getInnerHtml();\n                    $anchor = $node->getAttribute('id');\n\n                    // This is only needed if no anchor is currently present\n                    if (!isset($anchor)) {\n                        $text = strip_tags($text); // strip HTML\n                        $text = strtolower($text); // convert to lowercase\n                        $text = preg_replace('/[^.A-Za-z0-9 _-]/', '', $text); // strip non-alphanumeric characters\n                        $text = preg_replace('/\\s+/', '-', $text); // convert white-space to dash\n                        $text = preg_replace('/-+/', '-', $text);  // convert multiple dashes to one\n                        $text = trim($text, '-'); // trim excess\n\n                        $node->setAttribute('id', $text);\n                        return true;\n                    }\n\n                    return false;\n                })\n            ;\n        }\n\n        // Get allowed headings on the page\n        $toc = $dom\n            ->filter('#content')\n            ->filter(strtolower($headings))\n            ->each(function (HtmlPageCrawler $node) {\n                $text = $node->getInnerHtml();\n                $anchor = $node->getAttribute('id');\n                $level = $node->nodeName();\n\n                // Remove nested troublemakers\n                $text = strip_tags($text);\n\n                if (isset($anchor)) {\n                    return array(\n                        \"text\" => $text,\n                        \"anchor\" => $anchor,\n                        \"level\" => $level,\n                    );\n                }\n\n                return false;\n            })\n        ;\n\n        // Remove empty headings from array (why are they there?)\n        $toc = array_filter($toc);\n\n        // Create menu\n        $output = array();\n        $idx = 0;\n        foreach ($toc as $index => $item) {\n            $output[] = $modx->getChunk($tpl, array(\n                'link' => $resourceURI . '#' . $item['anchor'],\n                'menutitle' => $item['text'] ?? '',\n                'classnames' => $item['level'] ?? '',\n                'idx' => $idx++,\n            ));\n        }\n\n        // Append menu to HTML container\n        $dom->filter($target)->append(implode($output));\n        $content = $dom->saveHTML();\n\n        break;\n\n}\n\nreturn true;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.tableofcontents.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * TableOfContents plugin
 *
 * Generate a menu with internal links to all headings in the content.
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

$tpl = $modx->getPlaceholder('toc.tpl') ?? 'tocNavItem';
$target = $modx->getPlaceholder('toc.target');

// Abort if ToC target is not set
if (!$target) {
    return true;
}

switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        // Get processed output of resource
        $content = &$modx->resource->_output;
        $resourceURI = $modx->resource->get('uri');
        $headings = $modx->resource->getTVValue('toc_headings');

        // Feed output to HtmlPageDom
        $dom = new HtmlPageCrawler($content);

        // Generate anchors if requested
        if ($modx->resource->getTVValue('auto_anchors')) {
            $dom->filter('#content')
                ->filter('h1,h2,h3,h4,h5,h6')
                ->each(function (HtmlPageCrawler $node) {
                    $text = $node->getInnerHtml();
                    $anchor = $node->getAttribute('id');

                    // This is only needed if no anchor is currently present
                    if (!isset($anchor)) {
                        $text = strip_tags($text); // strip HTML
                        $text = strtolower($text); // convert to lowercase
                        $text = preg_replace('/[^.A-Za-z0-9 _-]/', '', $text); // strip non-alphanumeric characters
                        $text = preg_replace('/\s+/', '-', $text); // convert white-space to dash
                        $text = preg_replace('/-+/', '-', $text);  // convert multiple dashes to one
                        $text = trim($text, '-'); // trim excess

                        $node->setAttribute('id', $text);
                        return true;
                    }

                    return false;
                })
            ;
        }

        // Get allowed headings on the page
        $toc = $dom
            ->filter('#content')
            ->filter(strtolower($headings))
            ->each(function (HtmlPageCrawler $node) {
                $text = $node->getInnerHtml();
                $anchor = $node->getAttribute('id');
                $level = $node->nodeName();

                // Remove nested troublemakers
                $text = strip_tags($text);

                if (isset($anchor)) {
                    return array(
                        "text" => $text,
                        "anchor" => $anchor,
                        "level" => $level,
                    );
                }

                return false;
            })
        ;

        // Remove empty headings from array (why are they there?)
        $toc = array_filter($toc);

        // Create menu
        $output = array();
        $idx = 0;
        foreach ($toc as $index => $item) {
            $output[] = $modx->getChunk($tpl, array(
                'link' => $resourceURI . '#' . $item['anchor'],
                'menutitle' => $item['text'] ?? '',
                'classnames' => $item['level'] ?? '',
                'idx' => $idx++,
            ));
        }

        // Append menu to HTML container
        $dom->filter($target)->append(implode($output));
        $content = $dom->saveHTML();

        break;

}

return true;