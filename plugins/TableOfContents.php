id: 37
name: TableOfContents
category: c_content
plugincode: "/**\n * TableOfContents plugin\n *\n * Generate a menu with internal links to all headings in the content.\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$template = $modx->resource->get('template');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');\n\n// Get template name\n$query = $modx->newQuery('modTemplate');\n$query->where(array(\n    'id' => $template\n));\n$query->select('templatename');\n$templateName = $modx->getValue($query->prepare());\n\n// Abort if resource template is not a ToC or Note template\nif (strpos($templateName, 'Note') === false && strpos($templateName, 'ToC') === false) {\n    return true;\n}\n\n// Load HtmlPageDom\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n        $resourceURI = $modx->resource->get('uri');\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($content);\n\n        // Generate anchors if requested\n        if ($modx->resource->getTVValue('auto_anchors')) {\n            $dom->filter('#content')\n                ->filter('h1,h2,h3,h4,h5,h6')\n                ->each(function (HtmlPageCrawler $node) {\n                    $text = $node->getInnerHtml();\n                    $anchor = $node->getAttribute('id');\n\n                    // This is only needed if no anchor is currently present\n                    if (!isset($anchor)) {\n                        $text = strip_tags($text); // strip HTML\n                        $text = strtolower($text); // convert to lowercase\n                        $text = preg_replace('/[^.A-Za-z0-9 _-]/', '', $text); // strip non-alphanumeric characters\n                        $text = preg_replace('/\\s+/', '-', $text); // convert white-space to dash\n                        $text = preg_replace('/-+/', '-', $text);  // convert multiple dashes to one\n                        $text = trim($text, '-'); // trim excess\n\n                        $node->setAttribute('id', $text);\n                        return true;\n                    }\n\n                    return false;\n                }\n            );\n        }\n\n        // Get all headings on the page\n        $toc = $dom\n            ->filter('#content')\n            ->filter('h1,h2,h3,h4,h5,h6')\n            ->each(function (HtmlPageCrawler $node) {\n                $text = $node->getInnerHtml();\n                $anchor = $node->getAttribute('id');\n                $level = $node->nodeName();\n\n                if (isset($anchor)) {\n                    return array(\n                        \"text\" => $text,\n                        \"anchor\" => $anchor,\n                        \"level\" => $level,\n                    );\n                }\n\n                return false;\n            });\n\n        // Remove empty headings from array (why are they there?)\n        $toc = array_filter($toc);\n\n        // Create menu\n        $output = array();\n        $idx = 0;\n        foreach ($toc as $index => $item) {\n            $output[] = $modx->getChunk($tpl, array(\n                'link' => $resourceURI . '#' . $item['anchor'],\n                'menutitle' => $item['text'],\n                'classnames' => $item['level'],\n                'idx' => $idx++,\n            ));\n        }\n\n        // Append menu to HTML container\n        $dom->filter('#submenu.toc')->append(implode($output));\n        $content = $dom->saveHTML();\n\n        break;\n\n}\n\nreturn true;"
properties: 'a:0:{}'
content: "/**\n * TableOfContents plugin\n *\n * Generate a menu with internal links to all headings in the content.\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$template = $modx->resource->get('template');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');\n\n// Get template name\n$query = $modx->newQuery('modTemplate');\n$query->where(array(\n    'id' => $template\n));\n$query->select('templatename');\n$templateName = $modx->getValue($query->prepare());\n\n// Abort if resource template is not a ToC or Note template\nif (strpos($templateName, 'Note') === false && strpos($templateName, 'ToC') === false) {\n    return true;\n}\n\n// Load HtmlPageDom\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n        $resourceURI = $modx->resource->get('uri');\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($content);\n\n        // Generate anchors if requested\n        if ($modx->resource->getTVValue('auto_anchors')) {\n            $dom->filter('#content')\n                ->filter('h1,h2,h3,h4,h5,h6')\n                ->each(function (HtmlPageCrawler $node) {\n                    $text = $node->getInnerHtml();\n                    $anchor = $node->getAttribute('id');\n\n                    // This is only needed if no anchor is currently present\n                    if (!isset($anchor)) {\n                        $text = strip_tags($text); // strip HTML\n                        $text = strtolower($text); // convert to lowercase\n                        $text = preg_replace('/[^.A-Za-z0-9 _-]/', '', $text); // strip non-alphanumeric characters\n                        $text = preg_replace('/\\s+/', '-', $text); // convert white-space to dash\n                        $text = preg_replace('/-+/', '-', $text);  // convert multiple dashes to one\n                        $text = trim($text, '-'); // trim excess\n\n                        $node->setAttribute('id', $text);\n                        return true;\n                    }\n\n                    return false;\n                }\n            );\n        }\n\n        // Get all headings on the page\n        $toc = $dom\n            ->filter('#content')\n            ->filter('h1,h2,h3,h4,h5,h6')\n            ->each(function (HtmlPageCrawler $node) {\n                $text = $node->getInnerHtml();\n                $anchor = $node->getAttribute('id');\n                $level = $node->nodeName();\n\n                if (isset($anchor)) {\n                    return array(\n                        \"text\" => $text,\n                        \"anchor\" => $anchor,\n                        \"level\" => $level,\n                    );\n                }\n\n                return false;\n            });\n\n        // Remove empty headings from array (why are they there?)\n        $toc = array_filter($toc);\n\n        // Create menu\n        $output = array();\n        $idx = 0;\n        foreach ($toc as $index => $item) {\n            $output[] = $modx->getChunk($tpl, array(\n                'link' => $resourceURI . '#' . $item['anchor'],\n                'menutitle' => $item['text'],\n                'classnames' => $item['level'],\n                'idx' => $idx++,\n            ));\n        }\n\n        // Append menu to HTML container\n        $dom->filter('#submenu.toc')->append(implode($output));\n        $content = $dom->saveHTML();\n\n        break;\n\n}\n\nreturn true;"

-----


/**
 * TableOfContents plugin
 *
 * Generate a menu with internal links to all headings in the content.
 */

$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');
$template = $modx->resource->get('template');
$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');

// Get template name
$query = $modx->newQuery('modTemplate');
$query->where(array(
    'id' => $template
));
$query->select('templatename');
$templateName = $modx->getValue($query->prepare());

// Abort if resource template is not a ToC or Note template
if (strpos($templateName, 'Note') === false && strpos($templateName, 'ToC') === false) {
    return true;
}

// Load HtmlPageDom
if (!class_exists('\Wa72\HtmlPageDom\HtmlPageCrawler')) {
    require $corePath . 'vendor/autoload.php';
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        // Get processed output of resource
        $content = &$modx->resource->_output;
        $resourceURI = $modx->resource->get('uri');

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
                }
            );
        }

        // Get all headings on the page
        $toc = $dom
            ->filter('#content')
            ->filter('h1,h2,h3,h4,h5,h6')
            ->each(function (HtmlPageCrawler $node) {
                $text = $node->getInnerHtml();
                $anchor = $node->getAttribute('id');
                $level = $node->nodeName();

                if (isset($anchor)) {
                    return array(
                        "text" => $text,
                        "anchor" => $anchor,
                        "level" => $level,
                    );
                }

                return false;
            });

        // Remove empty headings from array (why are they there?)
        $toc = array_filter($toc);

        // Create menu
        $output = array();
        $idx = 0;
        foreach ($toc as $index => $item) {
            $output[] = $modx->getChunk($tpl, array(
                'link' => $resourceURI . '#' . $item['anchor'],
                'menutitle' => $item['text'],
                'classnames' => $item['level'],
                'idx' => $idx++,
            ));
        }

        // Append menu to HTML container
        $dom->filter('#submenu.toc')->append(implode($output));
        $content = $dom->saveHTML();

        break;

}

return true;