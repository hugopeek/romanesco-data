id: 132
name: migxSaveRelated
description: 'Aftersave hook for MIGXdb. Sets source and target IDs in opposite direction also, to establish a double cross-link. Yeah, better watch your back with those!'
category: f_dat_migx
snippet: "/**\n * migxSaveRelated\n *\n * Aftersave hook for MIGXdb. Sets source and target IDs in opposite direction\n * also, to establish a double cross-link. Yeah, watch your back with those!\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$objectID = $object->get('id');\n$crosslinkID = $object->get('crosslink_id');\n$source = $object->get('source');\n$destination = $object->get('destination');\n$title = $object->get('title');\n$description = $object->get('description');\n$createdon = $object->get('createdon');\n$createdby = $object->get('createdby');\n$weight = $object->get('weight');\n\n// Set current resource as source (if no source was set)\nif (!$source && isset($properties['resource_id'])) {\n    $object->set('source', $properties['resource_id']);\n    $object->save();\n\n    // Update source variable\n    $source = $object->get('source');\n}\n\n// Check if cross-link exists already\n$existingSrc = $modx->getObject('FractalFarming\\Romanesco\\Model\\LinkRelated', array('source' => $source, 'destination' => $destination));\n$existingDest = $modx->getObject('FractalFarming\\Romanesco\\Model\\LinkRelated', array('source' => $destination, 'destination' => $source));\n\n// Create another cross-link in the opposite direction\nif (is_object($existingSrc) && !is_object($existingDest)) {\n    $newSrc = $modx->newObject('FractalFarming\\Romanesco\\Model\\LinkRelated', array(\n        'crosslink_id' => $objectID,\n        'source' => $destination,\n        'destination' => $source,\n        'title' => $title,\n        'description' => $description,\n        'createdon' => $createdon,\n        'createdby' => $createdby,\n        'weight' => $weight,\n    ));\n    $newSrc->save();\n\n    // Set crosslink ID of source\n    $object->set('crosslink_id', $newSrc->get('id'));\n    $object->save();\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * migxSaveRelated
 *
 * Aftersave hook for MIGXdb. Sets source and target IDs in opposite direction
 * also, to establish a double cross-link. Yeah, watch your back with those!
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties);
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
if (!$source && isset($properties['resource_id'])) {
    $object->set('source', $properties['resource_id']);
    $object->save();

    // Update source variable
    $source = $object->get('source');
}

// Check if cross-link exists already
$existingSrc = $modx->getObject('FractalFarming\Romanesco\Model\LinkRelated', array('source' => $source, 'destination' => $destination));
$existingDest = $modx->getObject('FractalFarming\Romanesco\Model\LinkRelated', array('source' => $destination, 'destination' => $source));

// Create another cross-link in the opposite direction
if (is_object($existingSrc) && !is_object($existingDest)) {
    $newSrc = $modx->newObject('FractalFarming\Romanesco\Model\LinkRelated', array(
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