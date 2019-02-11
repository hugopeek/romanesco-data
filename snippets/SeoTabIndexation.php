id: 134
name: SeoTabIndexation
description: 'If SeoTab (StercSEO) is installed, this snippet displays the indexation setting for given resource.'
category: f_resources
snippet: "/**\n * SeoTabIndexation\n *\n * If SeoTab (StercSEO) is installed, this snippet displays the indexation\n * setting for given resource.\n *\n * Can be used as output modifier:\n *\n * [[+id:SeoTabIndexation]]\n */\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $input);\n$resource = $modx->getObject('modResource', $resourceID);\n//$resource =& $modx->event->params['resource'];\n\nif (is_object($resource)) {\n    $properties = $resource->getProperties('stercseo');\n}\n\nif (!$properties) {\n    return '';\n}\n\nif ($properties['index'] == 1) {\n    $index = 'index';\n} else {\n    $index = 'noindex';\n}\n\nif ($properties['follow'] == 1) {\n    $follow = 'follow';\n} else {\n    $follow = 'nofollow';\n}\n\nreturn $index . '/' . $follow;"
properties: 'a:0:{}'
content: "/**\n * SeoTabIndexation\n *\n * If SeoTab (StercSEO) is installed, this snippet displays the indexation\n * setting for given resource.\n *\n * Can be used as output modifier:\n *\n * [[+id:SeoTabIndexation]]\n */\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $input);\n$resource = $modx->getObject('modResource', $resourceID);\n//$resource =& $modx->event->params['resource'];\n\nif (is_object($resource)) {\n    $properties = $resource->getProperties('stercseo');\n}\n\nif (!$properties) {\n    return '';\n}\n\nif ($properties['index'] == 1) {\n    $index = 'index';\n} else {\n    $index = 'noindex';\n}\n\nif ($properties['follow'] == 1) {\n    $follow = 'follow';\n} else {\n    $follow = 'nofollow';\n}\n\nreturn $index . '/' . $follow;"

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
if (!is_object($seoTab)) {
    return '';
}

if (is_object($resource)) {
    $properties = $resource->getProperties('stercseo');
}

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