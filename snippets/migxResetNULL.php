id: 139
name: migxResetNULL
description: 'After save hook for MIGXdb. Prevents database fields with default value of NULL from being set to 0 after a save action in MIGX.'
category: f_data
snippet: "/**\n * migxResetNULL\n *\n * After save hook for MIGXdb. Prevents database fields with default value of\n * NULL from being set to 0 after a save action in MIGX.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, '');\n$configs = $modx->getOption('configs', $properties, '');\n\n// Compare values in properties to newly saved object\nforeach ($properties as $key => $value) {\n    $objectValue = $object->get($key);\n\n    // Reset to NULL if property value is empty and object value is 0\n    if ($objectValue === 0 && $value === '') {\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'NULL was reset for: ' . $key);\n        $object->set($key, NULL);\n        $object->save();\n    }\n}\n\nreturn true;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.migxresetnull.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.migxresetnull.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * migxResetNULL\n *\n * After save hook for MIGXdb. Prevents database fields with default value of\n * NULL from being set to 0 after a save action in MIGX.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties, null);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, '');\n$configs = $modx->getOption('configs', $properties, '');\n\n// Compare values in properties to newly saved object\nforeach ($properties as $key => $value) {\n    $objectValue = $object->get($key);\n\n    // Reset to NULL if property value is empty and object value is 0\n    if ($objectValue === 0 && $value === '') {\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'NULL was reset for: ' . $key);\n        $object->set($key, NULL);\n        $object->save();\n    }\n}\n\nreturn true;"

-----


/**
 * migxResetNULL
 *
 * After save hook for MIGXdb. Prevents database fields with default value of
 * NULL from being set to 0 after a save action in MIGX.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties, null);
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