id: 129
name: migxSaveOptionGroup
description: 'After save hook for MIGXdb. Updates existing keys in child options if you change this setting in Group. Also increments the sort order.'
category: f_toolshed
snippet: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n// Update key in child options if you change it\nif (is_object($object)) {\n    $children = $modx->getCollection('rmOption', array('group' => $object->get('id')));\n\n    foreach ($children as $child) {\n        $child = $modx->getObject('rmOption', array('id' => $child->get('id')));\n\n        $child->set('key', $properties['key']);\n        $child->save();\n    }\n}\n\n// Increment sort order of new items\n//if ($properties['object_id'] === 'new') {\n//\n//    // Ask for last position\n//    $q = $modx->newQuery('rmOptionGroup');\n//    $q->select(array(\n//        \"max(position)\",\n//    ));\n//    $lastPosition = $modx->getValue($q->prepare());\n//\n//    // Set and Save\n//    $object->set('position', ++$lastPosition);\n//    $object->save();\n//}\n\nreturn '';"
properties: 'a:0:{}'
content: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n// Update key in child options if you change it\nif (is_object($object)) {\n    $children = $modx->getCollection('rmOption', array('group' => $object->get('id')));\n\n    foreach ($children as $child) {\n        $child = $modx->getObject('rmOption', array('id' => $child->get('id')));\n\n        $child->set('key', $properties['key']);\n        $child->save();\n    }\n}\n\n// Increment sort order of new items\n//if ($properties['object_id'] === 'new') {\n//\n//    // Ask for last position\n//    $q = $modx->newQuery('rmOptionGroup');\n//    $q->select(array(\n//        \"max(position)\",\n//    ));\n//    $lastPosition = $modx->getValue($q->prepare());\n//\n//    // Set and Save\n//    $object->set('position', ++$lastPosition);\n//    $object->save();\n//}\n\nreturn '';"

-----


$object = &$modx->getOption('object', $scriptProperties, null);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

// Update key in child options if you change it
if (is_object($object)) {
    $children = $modx->getCollection('rmOption', array('group' => $object->get('id')));

    foreach ($children as $child) {
        $child = $modx->getObject('rmOption', array('id' => $child->get('id')));

        $child->set('key', $properties['key']);
        $child->save();
    }
}

// Increment sort order of new items
//if ($properties['object_id'] === 'new') {
//
//    // Ask for last position
//    $q = $modx->newQuery('rmOptionGroup');
//    $q->select(array(
//        "max(position)",
//    ));
//    $lastPosition = $modx->getValue($q->prepare());
//
//    // Set and Save
//    $object->set('position', ++$lastPosition);
//    $object->save();
//}

return '';