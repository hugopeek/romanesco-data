id: 136
name: TableOfContents
category: f_content
snippet: "/**\n *\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\n\n// Check if content type is text/html\n//if ($modx->resource->get('content_type') !== 1) {\n//    return '';\n//}\n\n// Get processed output of resource\n$content = $modx->resource->_content;\n$resourceURI = $modx->resource->get('uri');\n\n// Feed output to HtmlPageDom\n$dom = new HtmlPageCrawler($content);\n\n// Get all headings on the page\n$toc = $dom\n    ->filter('h1,h2,h3,h4,h5,h6')\n    ->each(function(HtmlPageCrawler $node){\n        $text = $node->getInnerHtml();\n        $anchor = $node->getAttribute('id');\n        $level = $node->nodeName();\n\n        if (isset($anchor)) {\n            return array(\n                \"text\" => $text,\n                \"anchor\" => $anchor,\n                \"level\" => $level,\n            );\n        }\n\n        return '';\n    })\n;\n\n// Remove empty headings from array (why are they there?)\n$toc = array_filter($toc);\n\n$output = array();\n$idx = 0;\n\nforeach ($toc as $index => $item) {\n    $output[] = $modx->getChunk($tpl, array(\n        'link' => $resourceURI . '#' . $item['anchor'],\n        'menutitle' => $item['text'],\n        'classnames' => $item['level'],\n        'idx' => $idx++,\n    ));\n}\n\n//$toc->html();\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.tableofcontents.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.tableofcontents.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n *\n */\n\n$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $corePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\n\n// Check if content type is text/html\n//if ($modx->resource->get('content_type') !== 1) {\n//    return '';\n//}\n\n// Get processed output of resource\n$content = $modx->resource->_content;\n$resourceURI = $modx->resource->get('uri');\n\n// Feed output to HtmlPageDom\n$dom = new HtmlPageCrawler($content);\n\n// Get all headings on the page\n$toc = $dom\n    ->filter('h1,h2,h3,h4,h5,h6')\n    ->each(function(HtmlPageCrawler $node){\n        $text = $node->getInnerHtml();\n        $anchor = $node->getAttribute('id');\n        $level = $node->nodeName();\n\n        if (isset($anchor)) {\n            return array(\n                \"text\" => $text,\n                \"anchor\" => $anchor,\n                \"level\" => $level,\n            );\n        }\n\n        return '';\n    })\n;\n\n// Remove empty headings from array (why are they there?)\n$toc = array_filter($toc);\n\n$output = array();\n$idx = 0;\n\nforeach ($toc as $index => $item) {\n    $output[] = $modx->getChunk($tpl, array(\n        'link' => $resourceURI . '#' . $item['anchor'],\n        'menutitle' => $item['text'],\n        'classnames' => $item['level'],\n        'idx' => $idx++,\n    ));\n}\n\n//$toc->html();\n\nreturn implode($output);"

-----


/**
 *
 */

$corePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');
$tpl = $modx->getOption('tpl', $scriptProperties, 'tocNavItem');

if (!class_exists('\Wa72\HtmlPageDom\HtmlPageCrawler')) {
    require $corePath . 'vendor/autoload.php';
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;


// Check if content type is text/html
//if ($modx->resource->get('content_type') !== 1) {
//    return '';
//}

// Get processed output of resource
$content = $modx->resource->_content;
$resourceURI = $modx->resource->get('uri');

// Feed output to HtmlPageDom
$dom = new HtmlPageCrawler($content);

// Get all headings on the page
$toc = $dom
    ->filter('h1,h2,h3,h4,h5,h6')
    ->each(function(HtmlPageCrawler $node){
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

        return '';
    })
;

// Remove empty headings from array (why are they there?)
$toc = array_filter($toc);

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

//$toc->html();

return implode($output);