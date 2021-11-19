id: 161
name: migxSaveExternalLink
description: 'Aftersave hook for MIGXdb. Increments the link number per resource, so you don''t have to fiddle with that manually (as long as you enter the links in the correct order).'
category: f_toolshed
snippet: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n// Set lowest new number available\nif ($properties['object_id'] === 'new') {\n\n    // Ask for highest number so far\n    $q = $modx->newQuery('rmExternalLink', array('resource_id' => $properties['resource_id']));\n    $q->select(array(\n        \"max(number)\",\n    ));\n    $lastNumber = $modx->getValue($q->prepare());\n\n    // Set and Save\n    $object->set('number', ++$lastNumber);\n    $object->save();\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.migxsaveexternallink.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.migxsaveexternallink.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n// Set lowest new number available\nif ($properties['object_id'] === 'new') {\n\n    // Ask for highest number so far\n    $q = $modx->newQuery('rmExternalLink', array('resource_id' => $properties['resource_id']));\n    $q->select(array(\n        \"max(number)\",\n    ));\n    $lastNumber = $modx->getValue($q->prepare());\n\n    // Set and Save\n    $object->set('number', ++$lastNumber);\n    $object->save();\n}\n\nreturn '';"

-----


$object = &$modx->getOption('object', $scriptProperties, null);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

// Set lowest new number available
if ($properties['object_id'] === 'new') {

    // Ask for highest number so far
    $q = $modx->newQuery('rmExternalLink', array('resource_id' => $properties['resource_id']));
    $q->select(array(
        "max(number)",
    ));
    $lastNumber = $modx->getValue($q->prepare());

    // Set and Save
    $object->set('number', ++$lastNumber);
    $object->save();
}

return '';