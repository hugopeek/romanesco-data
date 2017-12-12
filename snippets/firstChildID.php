id: 113
name: firstChildID
description: 'The name says it all: retrieve the first child ID of a given ID.'
category: f_basic
snippet: "/**\n * firstChildID\n * Finds the first child from the given id\n * Returns the first child id or the given id on failure\n *\n * @author Bert Oost <bert@oostdesign.nl> at OostDesign.nl\n *\n * Examples:\n *\n * As output filter:\n *   [[*id:firstChildID]]\n *\n * As snippet:\n *   [[firstChildID? &id=`[[*id]]`]]\n */\n\n$id = (isset($input) && !empty($input)) ? $input : false;\nif(empty($id)) { $id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id')); }\n\n// select the first child\n$c = $modx->newQuery('modResource');\n$c->select(array('id'));\n$c->where(array(\n    'parent' => $id,\n    'published' => true,\n));\n$c->sortby('menuindex', 'ASC');\n$c->limit(1);\n\n$child = $modx->getObject('modResource', $c);\nif(!empty($child) && $child instanceof modResource) {\n    return $child->get('id');\n}\n\nreturn $id;"
properties: 'a:0:{}'
content: "/**\n * firstChildID\n * Finds the first child from the given id\n * Returns the first child id or the given id on failure\n *\n * @author Bert Oost <bert@oostdesign.nl> at OostDesign.nl\n *\n * Examples:\n *\n * As output filter:\n *   [[*id:firstChildID]]\n *\n * As snippet:\n *   [[firstChildID? &id=`[[*id]]`]]\n */\n\n$id = (isset($input) && !empty($input)) ? $input : false;\nif(empty($id)) { $id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id')); }\n\n// select the first child\n$c = $modx->newQuery('modResource');\n$c->select(array('id'));\n$c->where(array(\n    'parent' => $id,\n    'published' => true,\n));\n$c->sortby('menuindex', 'ASC');\n$c->limit(1);\n\n$child = $modx->getObject('modResource', $c);\nif(!empty($child) && $child instanceof modResource) {\n    return $child->get('id');\n}\n\nreturn $id;"

-----


/**
 * firstChildID
 * Finds the first child from the given id
 * Returns the first child id or the given id on failure
 *
 * @author Bert Oost <bert@oostdesign.nl> at OostDesign.nl
 *
 * Examples:
 *
 * As output filter:
 *   [[*id:firstChildID]]
 *
 * As snippet:
 *   [[firstChildID? &id=`[[*id]]`]]
 */

$id = (isset($input) && !empty($input)) ? $input : false;
if(empty($id)) { $id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id')); }

// select the first child
$c = $modx->newQuery('modResource');
$c->select(array('id'));
$c->where(array(
    'parent' => $id,
    'published' => true,
));
$c->sortby('menuindex', 'ASC');
$c->limit(1);

$child = $modx->getObject('modResource', $c);
if(!empty($child) && $child instanceof modResource) {
    return $child->get('id');
}

return $id;