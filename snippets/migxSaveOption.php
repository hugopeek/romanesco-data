id: 128
name: migxSaveOption
description: 'Aftersave hook for MIGXdb. Gets and sets the group (parent) ID inside a nested configuration. Also generates an alias if none is present and increments the sort order.'
category: f_dat_migx
snippet: "/**\n * migxSaveOption\n *\n * Aftersave hook for MIGXdb. Gets and sets the group (parent) ID inside a\n * nested configuration. Also generates an alias if none is present and\n * increments the sort order.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$object = $modx->getOption('object', $scriptProperties);\n$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n$configs = $modx->getOption('configs', $properties, '');\n\n$co_id = $modx->getOption('co_id', $properties, 0);\n$parent = $modx->getObject('FractalFarming\\Romanesco\\Model\\OptionGroup', array('id' => $co_id));\n\n// Set key and ID of parent object\nif (is_object($object)) {\n    $object->set('key', $parent->get('key'));\n    $object->set('group', $co_id);\n}\n\n// Generate alias if empty\nif (!$object->get('alias')) {\n    $object->set('alias', $object->get('name'));\n}\n\n// Make sure alias is formatted as such\n$alias = $modx->filterPathSegment($object->get('alias'), [\n    'friendly_alias_restrict_chars' => 'alphanumeric'\n]);\n$object->set('alias', $alias);\n\n// Increment sort order of new items\nif ($properties['object_id'] === 'new') {\n    // Ask for last position\n    $q = $modx->newQuery('FractalFarming\\Romanesco\\Model\\Option');\n    $q->select(array(\n        \"max(position)\",\n    ));\n    $lastPosition = $modx->getValue($q->prepare());\n    $object->set('position', ++$lastPosition);\n}\n\n$object->save();\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * migxSaveOption
 *
 * Aftersave hook for MIGXdb. Gets and sets the group (parent) ID inside a
 * nested configuration. Also generates an alias if none is present and
 * increments the sort order.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$object = $modx->getOption('object', $scriptProperties);
$properties = $modx->getOption('scriptProperties', $scriptProperties, array());
$configs = $modx->getOption('configs', $properties, '');

$co_id = $modx->getOption('co_id', $properties, 0);
$parent = $modx->getObject('FractalFarming\Romanesco\Model\OptionGroup', array('id' => $co_id));

// Set key and ID of parent object
if (is_object($object)) {
    $object->set('key', $parent->get('key'));
    $object->set('group', $co_id);
}

// Generate alias if empty
if (!$object->get('alias')) {
    $object->set('alias', $object->get('name'));
}

// Make sure alias is formatted as such
$alias = $modx->filterPathSegment($object->get('alias'), [
    'friendly_alias_restrict_chars' => 'alphanumeric'
]);
$object->set('alias', $alias);

// Increment sort order of new items
if ($properties['object_id'] === 'new') {
    // Ask for last position
    $q = $modx->newQuery('FractalFarming\Romanesco\Model\Option');
    $q->select(array(
        "max(position)",
    ));
    $lastPosition = $modx->getValue($q->prepare());
    $object->set('position', ++$lastPosition);
}

$object->save();

return '';