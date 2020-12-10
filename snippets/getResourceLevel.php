id: 82
name: getResourceLevel
description: 'Show the level of a given resource based on the number of parent IDs. Useful for example if you only want to show a breadcrumb trail on pages that are two or three levels deep.'
category: f_resources
snippet: "$id = isset($id) ? $id : $modx->resource->get('id');\n$pids = $modx->getParentIds($id, 100, array('context' => 'web'));\nreturn count($pids);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.getresourcelevel.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.getresourcelevel.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$id = isset($id) ? $id : $modx->resource->get('id');\n$pids = $modx->getParentIds($id, 100, array('context' => 'web'));\nreturn count($pids);"

-----


$id = isset($id) ? $id : $modx->resource->get('id');
$pids = $modx->getParentIds($id, 100, array('context' => 'web'));
return count($pids);