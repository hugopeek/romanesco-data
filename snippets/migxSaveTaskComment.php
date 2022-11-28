id: 163
name: migxSaveTaskComment
description: 'Aftersave hook for MIGXdb. Links the comment to the parent task.'
category: f_data
snippet: "/**\n * migxSaveTaskComment\n *\n * Aftersave hook for MIGXdb. Links the comment to the parent task.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n\n// Set key and ID of parent object\nif (is_object($object)) {\n    $object->set('task_id', $co_id);\n    $object->save();\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:43:"romanesco.migxsavetaskcomment.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:44:"romanesco.migxsavetaskcomment.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * migxSaveTaskComment\n *\n * Aftersave hook for MIGXdb. Links the comment to the parent task.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n\n// Set key and ID of parent object\nif (is_object($object)) {\n    $object->set('task_id', $co_id);\n    $object->save();\n}\n\nreturn '';"

-----


/**
 * migxSaveTaskComment
 *
 * Aftersave hook for MIGXdb. Links the comment to the parent task.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

$co_id = $modx->getOption('co_id', $properties, 0);

// Set key and ID of parent object
if (is_object($object)) {
    $object->set('task_id', $co_id);
    $object->save();
}

return '';