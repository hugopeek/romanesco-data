id: 171
name: migxSaveTask
description: 'Aftersave hook for MIGXdb. Links the task to the parent object.'
category: f_data
snippet: "/**\n * migxSaveTask\n *\n * Aftersave hook for MIGXdb. Links the task to the parent object.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n\nif (!is_object($object)) return;\n\n// Attach object to parent\n$object->set('parent_id', $co_id);\n\n// If co_id is 0, then parent might be a resource\nif (!$co_id && $properties['resource_id']) {\n    $object->set('parent_id', $properties['resource_id']);\n}\n\n$object->save();\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.migxsavetask.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.migxsavetask.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * migxSaveTask
 *
 * Aftersave hook for MIGXdb. Links the task to the parent object.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

$co_id = $modx->getOption('co_id', $properties, 0);

if (!is_object($object)) return;

// Attach object to parent
$object->set('parent_id', $co_id);

// If co_id is 0, then parent might be a resource
if (!$co_id && $properties['resource_id']) {
    $object->set('parent_id', $properties['resource_id']);
}

$object->save();

return '';