id: 161
name: migxSaveExternalLink
description: 'Aftersave hook for MIGXdb. Increments the link number per resource, so you don''t have to fiddle with that manually (as long as you enter the links in the correct order).'
category: f_dat_migx
snippet: "/**\n * migxSaveExternalLink\n *\n * Aftersave hook for MIGXdb. Increments the link number per resource, so you\n * don't have to fiddle with that manually (as long as you enter the links in\n * the correct order).\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n// Set lowest new number available\nif ($properties['object_id'] === 'new' && isset($properties['resource_id'])) {\n\n    // Ask for highest number so far\n    $q = $modx->newQuery('FractalFarming\\Romanesco\\Model\\LinkExternal', array('resource_id' => $properties['resource_id']));\n    $q->select(array(\n        \"max(number)\",\n    ));\n    $lastNumber = $modx->getValue($q->prepare());\n\n    // Set and Save\n    $object->set('number', ++$lastNumber);\n    $object->save();\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * migxSaveExternalLink
 *
 * Aftersave hook for MIGXdb. Increments the link number per resource, so you
 * don't have to fiddle with that manually (as long as you enter the links in
 * the correct order).
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

// Set lowest new number available
if ($properties['object_id'] === 'new' && isset($properties['resource_id'])) {

    // Ask for highest number so far
    $q = $modx->newQuery('FractalFarming\Romanesco\Model\LinkExternal', array('resource_id' => $properties['resource_id']));
    $q->select(array(
        "max(number)",
    ));
    $lastNumber = $modx->getValue($q->prepare());

    // Set and Save
    $object->set('number', ++$lastNumber);
    $object->save();
}

return '';