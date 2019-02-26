id: 139
name: migxResetNULL
description: 'After save hook for MIGXdb. Prevents database fields with default value of NULL from being set to 0 after a save action in MIGX.'
category: f_toolshed
snippet: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, '');\n$configs = $modx->getOption('configs', $properties, '');\n\n// Compare values in properties to newly saved object\nforeach ($properties as $key => $value) {\n    $objectValue = $object->get($key);\n\n    // Reset to NULL if property value is empty and object value is 0\n    if ($objectValue === 0 && $value === '') {\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'NULL was reset for: ' . $key);\n        $object->set($key, NULL);\n        $object->save();\n    }\n}\n\nreturn true;"
properties: 'a:0:{}'
content: "$object = &$modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, '');\n$configs = $modx->getOption('configs', $properties, '');\n\n// Compare values in properties to newly saved object\nforeach ($properties as $key => $value) {\n    $objectValue = $object->get($key);\n\n    // Reset to NULL if property value is empty and object value is 0\n    if ($objectValue === 0 && $value === '') {\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'NULL was reset for: ' . $key);\n        $object->set($key, NULL);\n        $object->save();\n    }\n}\n\nreturn true;"

-----


$object = &$modx->getOption('object', $scriptProperties, null);
$properties = $modx->getOption('scriptProperties', $scriptProperties, '');
$configs = $modx->getOption('configs', $properties, '');

// Compare values in properties to newly saved object
foreach ($properties as $key => $value) {
    $objectValue = $object->get($key);

    // Reset to NULL if property value is empty and object value is 0
    if ($objectValue === 0 && $value === '') {
        //$modx->log(modX::LOG_LEVEL_ERROR, 'NULL was reset for: ' . $key);
        $object->set($key, NULL);
        $object->save();
    }
}

return true;