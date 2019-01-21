id: 132
name: migxSaveRelated
description: 'After save hook for MIGXdb. Sets source and target IDs in opposite direction also, to establish a double cross-link. Yeah, watch your back with those!'
category: f_toolshed
snippet: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$objectID = $object->get('id');\n$crosslinkID = $object->get('crosslink_id');\n$source = $object->get('source');\n$destination = $object->get('destination');\n$title = $object->get('title');\n$description = $object->get('description');\n$createdon = $object->get('createdon');\n$createdby = $object->get('createdby');\n$weight = $object->get('weight');\n\n// Set current resource as source (if no source was set)\nif (!$source) {\n    $object->set('source', $properties['resource_id']);\n    $object->save();\n\n    // Update source variable\n    $source = $object->get('source');\n}\n\n// Check if cross-link exists already\n$existingSrc = $modx->getObject('rmCrosslinkRelated', array('source' => $source, 'destination' => $destination));\n$existingDest = $modx->getObject('rmCrosslinkRelated', array('source' => $destination, 'destination' => $source));\n\n// Create another cross-link in the opposite direction\nif (is_object($existingSrc) && !is_object($existingDest)) {\n    $newSrc = $modx->newObject('rmCrosslinkRelated', array(\n        'crosslink_id' => $objectID,\n        'source' => $destination,\n        'destination' => $source,\n        'title' => $title,\n        'description' => $description,\n        'createdon' => $createdon,\n        'createdby' => $createdby,\n        'weight' => $weight,\n    ));\n    $newSrc->save();\n\n    // Set crosslink ID of source\n    $object->set('crosslink_id', $newSrc->get('id'));\n    $object->save();\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$objectID = $object->get('id');\n$crosslinkID = $object->get('crosslink_id');\n$source = $object->get('source');\n$destination = $object->get('destination');\n$title = $object->get('title');\n$description = $object->get('description');\n$createdon = $object->get('createdon');\n$createdby = $object->get('createdby');\n$weight = $object->get('weight');\n\n// Set current resource as source (if no source was set)\nif (!$source) {\n    $object->set('source', $properties['resource_id']);\n    $object->save();\n\n    // Update source variable\n    $source = $object->get('source');\n}\n\n// Check if cross-link exists already\n$existingSrc = $modx->getObject('rmCrosslinkRelated', array('source' => $source, 'destination' => $destination));\n$existingDest = $modx->getObject('rmCrosslinkRelated', array('source' => $destination, 'destination' => $source));\n\n// Create another cross-link in the opposite direction\nif (is_object($existingSrc) && !is_object($existingDest)) {\n    $newSrc = $modx->newObject('rmCrosslinkRelated', array(\n        'crosslink_id' => $objectID,\n        'source' => $destination,\n        'destination' => $source,\n        'title' => $title,\n        'description' => $description,\n        'createdon' => $createdon,\n        'createdby' => $createdby,\n        'weight' => $weight,\n    ));\n    $newSrc->save();\n\n    // Set crosslink ID of source\n    $object->set('crosslink_id', $newSrc->get('id'));\n    $object->save();\n}\n\nreturn '';"

-----


$object = &$modx->getOption('object', $scriptProperties, null);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

$objectID = $object->get('id');
$crosslinkID = $object->get('crosslink_id');
$source = $object->get('source');
$destination = $object->get('destination');
$title = $object->get('title');
$description = $object->get('description');
$createdon = $object->get('createdon');
$createdby = $object->get('createdby');
$weight = $object->get('weight');

// Set current resource as source (if no source was set)
if (!$source) {
    $object->set('source', $properties['resource_id']);
    $object->save();

    // Update source variable
    $source = $object->get('source');
}

// Check if cross-link exists already
$existingSrc = $modx->getObject('rmCrosslinkRelated', array('source' => $source, 'destination' => $destination));
$existingDest = $modx->getObject('rmCrosslinkRelated', array('source' => $destination, 'destination' => $source));

// Create another cross-link in the opposite direction
if (is_object($existingSrc) && !is_object($existingDest)) {
    $newSrc = $modx->newObject('rmCrosslinkRelated', array(
        'crosslink_id' => $objectID,
        'source' => $destination,
        'destination' => $source,
        'title' => $title,
        'description' => $description,
        'createdon' => $createdon,
        'createdby' => $createdby,
        'weight' => $weight,
    ));
    $newSrc->save();

    // Set crosslink ID of source
    $object->set('crosslink_id', $newSrc->get('id'));
    $object->save();
}

return '';