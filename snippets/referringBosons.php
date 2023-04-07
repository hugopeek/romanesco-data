id: 108
name: referringBosons
category: f_hub
snippet: "$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n$pattern = $modx->getOption('pattern', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n$output = array();\n$fieldURI = 'patterns/bosons/fields';\n$layoutURI = 'patterns/bosons/layouts';\n\n// First, we need to know which CB elements contain the pattern name\n// Let's start searching inside fields first, since they're the most common\n$result = $modx->getCollection('cbField', array(\n    'template:LIKE' => '%' . $pattern . '%',\n    'OR:properties:LIKE' => '%' . $pattern . '%',\n    'OR:settings:LIKE' => '%' . $pattern . '%'\n));\n\n// Proceed if any matches are present\nif ($result) {\n    // Turn each match into a list item with a link\n    foreach ($result as $field) {\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $field->get('name'),\n            'link' => $fieldURI,\n            'label_classes' => 'blue'\n        ));\n    }\n\n    return implode($output);\n}\n\n// Maybe the field type is Chunk, meaning it is referenced by ID instead of name\n$query = $modx->newQuery('modChunk');\n$query->where(array(\n    'name' => $pattern\n));\n$query->select('id');\n$patternID = $modx->getValue($query->prepare());\n\n$result = $modx->getObject('cbField', array(\n    'properties:LIKE' => '%\"chunk\":\"' . $patternID . '\"%'\n));\n\nif ($result) {\n    return $modx->getChunk($tpl, array(\n        'name' => $result->get('name'),\n        'link' => $fieldURI,\n        'assigned' => 1\n    ));\n}\n\n// If no fields where found, try the layouts table instead\n$result = $modx->getCollection('cbLayout', array(\n    'template:LIKE' => '%' . $pattern . '%',\n    'OR:settings:LIKE' => '%' . $pattern . '%'\n));\n\n// Proceed if any matches are present\nif ($result) {\n    // Turn each match into a list item with a link\n    foreach ($result as $layout) {\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $layout->get('name'),\n            'link' => $layoutURI,\n            'label_classes' => 'purple'\n        ));\n    }\n\n    return implode($output);\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.referringbosons.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.referringbosons.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');

$pattern = $modx->getOption('pattern', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');

$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));

$output = array();
$fieldURI = 'patterns/bosons/fields';
$layoutURI = 'patterns/bosons/layouts';

// First, we need to know which CB elements contain the pattern name
// Let's start searching inside fields first, since they're the most common
$result = $modx->getCollection('cbField', array(
    'template:LIKE' => '%' . $pattern . '%',
    'OR:properties:LIKE' => '%' . $pattern . '%',
    'OR:settings:LIKE' => '%' . $pattern . '%'
));

// Proceed if any matches are present
if ($result) {
    // Turn each match into a list item with a link
    foreach ($result as $field) {
        $output[] = $modx->getChunk($tpl, array(
            'name' => $field->get('name'),
            'link' => $fieldURI,
            'label_classes' => 'blue'
        ));
    }

    return implode($output);
}

// Maybe the field type is Chunk, meaning it is referenced by ID instead of name
$query = $modx->newQuery('modChunk');
$query->where(array(
    'name' => $pattern
));
$query->select('id');
$patternID = $modx->getValue($query->prepare());

$result = $modx->getObject('cbField', array(
    'properties:LIKE' => '%"chunk":"' . $patternID . '"%'
));

if ($result) {
    return $modx->getChunk($tpl, array(
        'name' => $result->get('name'),
        'link' => $fieldURI,
        'assigned' => 1
    ));
}

// If no fields where found, try the layouts table instead
$result = $modx->getCollection('cbLayout', array(
    'template:LIKE' => '%' . $pattern . '%',
    'OR:settings:LIKE' => '%' . $pattern . '%'
));

// Proceed if any matches are present
if ($result) {
    // Turn each match into a list item with a link
    foreach ($result as $layout) {
        $output[] = $modx->getChunk($tpl, array(
            'name' => $layout->get('name'),
            'link' => $layoutURI,
            'label_classes' => 'purple'
        ));
    }

    return implode($output);
}

return '';