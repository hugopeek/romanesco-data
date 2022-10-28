id: 134
name: SeoTabIndexation
description: 'If SeoTab (StercSEO) is installed, this snippet displays the indexation setting for given resource.'
category: f_resource
snippet: "/**\n * SeoTabIndexation\n *\n * If SeoTab (StercSEO) is installed, this snippet displays the indexation\n * setting for given resource.\n *\n * Can be used as output modifier:\n *\n * [[+id:SeoTabIndexation]]\n */\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $input);\n$resource = $modx->getObject('modResource', $resourceID);\n$seoTab = $modx->getObject('modPlugin', array('name'=>'StercSEO','disabled'=>0));\n//$resource =& $modx->event->params['resource'];\n\n// First, check if SEOTab plugin is installed, and active\nif (!is_object($seoTab) || !is_object($resource)) {\n    return '';\n}\n\n$properties = $resource->getProperties('stercseo');\n\nif ($properties['index'] == 1) {\n    $index = 'index';\n} else {\n    $index = 'noindex';\n}\n\nif ($properties['follow'] == 1) {\n    $follow = 'follow';\n} else {\n    $follow = 'nofollow';\n}\n\nreturn $index . '/' . $follow;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.seotabindexation.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.seotabindexation.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * SeoTabIndexation\n *\n * If SeoTab (StercSEO) is installed, this snippet displays the indexation\n * setting for given resource.\n *\n * Can be used as output modifier:\n *\n * [[+id:SeoTabIndexation]]\n */\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $input);\n$resource = $modx->getObject('modResource', $resourceID);\n$seoTab = $modx->getObject('modPlugin', array('name'=>'StercSEO','disabled'=>0));\n//$resource =& $modx->event->params['resource'];\n\n// First, check if SEOTab plugin is installed, and active\nif (!is_object($seoTab) || !is_object($resource)) {\n    return '';\n}\n\n$properties = $resource->getProperties('stercseo');\n\nif ($properties['index'] == 1) {\n    $index = 'index';\n} else {\n    $index = 'noindex';\n}\n\nif ($properties['follow'] == 1) {\n    $follow = 'follow';\n} else {\n    $follow = 'nofollow';\n}\n\nreturn $index . '/' . $follow;"

-----


/**
 * SeoTabIndexation
 *
 * If SeoTab (StercSEO) is installed, this snippet displays the indexation
 * setting for given resource.
 *
 * Can be used as output modifier:
 *
 * [[+id:SeoTabIndexation]]
 */

$resourceID = $modx->getOption('resource', $scriptProperties, $input);
$resource = $modx->getObject('modResource', $resourceID);
$seoTab = $modx->getObject('modPlugin', array('name'=>'StercSEO','disabled'=>0));
//$resource =& $modx->event->params['resource'];

// First, check if SEOTab plugin is installed, and active
if (!is_object($seoTab) || !is_object($resource)) {
    return '';
}

$properties = $resource->getProperties('stercseo');

if ($properties['index'] == 1) {
    $index = 'index';
} else {
    $index = 'noindex';
}

if ($properties['follow'] == 1) {
    $follow = 'follow';
} else {
    $follow = 'nofollow';
}

return $index . '/' . $follow;