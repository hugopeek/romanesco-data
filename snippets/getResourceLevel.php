id: 82
name: getResourceLevel
description: 'Show the level of a given resource based on the number of parent IDs. Useful for example if you only want to show a breadcrumb trail on pages that are two or three levels deep.'
category: f_resource
snippet: "/**\n * @var modX $modx\n */\n\n$parents = $modx->getParentIds($modx->resource->get('id'));\nreturn count($parents);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.getresourcelevel.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.getresourcelevel.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * @var modX $modx\n */\n\n$parents = $modx->getParentIds($modx->resource->get('id'));\nreturn count($parents);"

-----


/**
 * @var modX $modx
 */

$parents = $modx->getParentIds($modx->resource->get('id'));
return count($parents);