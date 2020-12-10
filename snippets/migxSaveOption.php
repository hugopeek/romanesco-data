id: 128
name: migxSaveOption
description: 'After save hook for MIGXdb. Gets and sets the group (parent) ID inside a nested configuration. Also generates an alias if none is present and increments the sort order.'
category: f_toolshed
snippet: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n$parent = $modx->getObject('rmOptionGroup', array('id' => $co_id));\n\n// Set key and ID of parent object\nif (is_object($object)) {\n    $object->set('key', $parent->get('key'));\n    $object->set('group', $co_id);\n    $object->save();\n}\n\n// Generate alias if empty\nif (!$object->get('alias')) {\n    $alias = $modx->runSnippet('stripAsAlias', (array('input' => $object->get('name'))));\n\n    $object->set('alias', $alias);\n    $object->save();\n}\n\n// Increment sort order of new items\nif ($properties['object_id'] === 'new') {\n\n    // Ask for last position\n    $q = $modx->newQuery('rmOption');\n    $q->select(array(\n        \"max(position)\",\n    ));\n    $lastPosition = $modx->getValue($q->prepare());\n\n    // Set and Save\n    $object->set('position', ++$lastPosition);\n    $object->save();\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.migxsaveoption.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.migxsaveoption.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n$parent = $modx->getObject('rmOptionGroup', array('id' => $co_id));\n\n// Set key and ID of parent object\nif (is_object($object)) {\n    $object->set('key', $parent->get('key'));\n    $object->set('group', $co_id);\n    $object->save();\n}\n\n// Generate alias if empty\nif (!$object->get('alias')) {\n    $alias = $modx->runSnippet('stripAsAlias', (array('input' => $object->get('name'))));\n\n    $object->set('alias', $alias);\n    $object->save();\n}\n\n// Increment sort order of new items\nif ($properties['object_id'] === 'new') {\n\n    // Ask for last position\n    $q = $modx->newQuery('rmOption');\n    $q->select(array(\n        \"max(position)\",\n    ));\n    $lastPosition = $modx->getValue($q->prepare());\n\n    // Set and Save\n    $object->set('position', ++$lastPosition);\n    $object->save();\n}\n\nreturn '';"

-----


$object = &$modx->getOption('object', $scriptProperties, null);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

$co_id = $modx->getOption('co_id', $properties, 0);
$parent = $modx->getObject('rmOptionGroup', array('id' => $co_id));

// Set key and ID of parent object
if (is_object($object)) {
    $object->set('key', $parent->get('key'));
    $object->set('group', $co_id);
    $object->save();
}

// Generate alias if empty
if (!$object->get('alias')) {
    $alias = $modx->runSnippet('stripAsAlias', (array('input' => $object->get('name'))));

    $object->set('alias', $alias);
    $object->save();
}

// Increment sort order of new items
if ($properties['object_id'] === 'new') {

    // Ask for last position
    $q = $modx->newQuery('rmOption');
    $q->select(array(
        "max(position)",
    ));
    $lastPosition = $modx->getValue($q->prepare());

    // Set and Save
    $object->set('position', ++$lastPosition);
    $object->save();
}

return '';