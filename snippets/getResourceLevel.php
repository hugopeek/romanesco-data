id: 82
name: getResourceLevel
description: 'Show the level of a given resource based on the number of parent IDs. Useful for example if you only want to show a breadcrumb trail on pages that are two or three levels deep.'
category: f_resources
snippet: "$id = isset($id) ? $id : $modx->resource->get('id');\n$pids = $modx->getParentIds($id, 100, array('context' => 'web'));\nreturn count($pids);"
properties: 'a:0:{}'
content: "$id = isset($id) ? $id : $modx->resource->get('id');\n$pids = $modx->getParentIds($id, 100, array('context' => 'web'));\nreturn count($pids);"

-----


$id = isset($id) ? $id : $modx->resource->get('id');
$pids = $modx->getParentIds($id, 100, array('context' => 'web'));
return count($pids);