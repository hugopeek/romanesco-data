id: 114
name: tvToJSON
description: 'Output the properties of given TV to a JSON object. The output could be used by jsonToHTML to generate an HTML table.'
category: f_json
snippet: "/**\n * tvToJSON\n *\n * Output the properties of given TV to a JSON object.\n * The output could be used by jsonToHTML.\n *\n * Initially intended for use in the front-end library. TV settings can now be\n * loaded automatically, instead of copy/pasting the JSON from the GPM config by hand.\n *\n * Usage example:\n * [[tvToJSON? &tv=`[[+pattern_name]]`]]\n *\n * For GPM compatible output:\n * [[!tvToJSON?\n *     &tv=`overview_img_landscape`\n *     &showName=`1`\n *     &showSource=`0`\n *     &optionsDelimiter=`0`\n * ]]\n *\n */\n\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n$showName = $modx->getOption('showName', $scriptProperties, 0);\n$showSource = $modx->getOption('showSource', $scriptProperties, 1);\n$optionsDelimiter = $modx->getOption('optionsDelimiter', $scriptProperties, '<br>');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));\n\nif (!is_object($tv)) {\n    return '';\n}\n\n// Render category name for clarity\n$query = $modx->newQuery('modCategory', array(\n    'id' => $tv->get('category')\n));\n$query->select('category');\n$catName = $modx->getValue($query->prepare());\n\n// Render media source name for clarity\n$sourceID = $tv->get('source');\nif ($sourceID != false) {\n    $query = $modx->newQuery('modMediaSource', array(\n        'id' => $sourceID\n    ));\n    $query->select('name');\n    $sourceName = $modx->getValue($query->prepare());\n}\n\n// Check if TV has input / output properties\nif ($tv->get('input_properties')) {\n    $inputProperties = array_diff($tv->get('input_properties'),array(null));\n}\nif ($tv->get('output_properties')) {\n    $outputProperties = array_diff($tv->get('output_properties'),array(null));\n}\n\n// Control output delimiter of input options\n$inputOptions = $tv->get('elements');\nif ($optionsDelimiter) {\n    $inputOptions = str_replace('||', $optionsDelimiter, $inputOptions);\n}\n\n// Create a new object with altered elements\n// The new key names mimic the properties used by GPM\n$tvAltered = array(\n    'caption' => $tv->get('caption'),\n    'name' => $tv->get('name'),\n    'description' => $tv->get('description'),\n    'type' => $tv->get('type'),\n    'category' => $catName,\n    'sortOrder' => $tv->get('rank'),\n    'inputOptionValues' => $inputOptions,\n    'defaultValue' => $tv->get('default_text'),\n    'inputProperties' => $inputProperties ?? '',\n    'outputProperties' => $outputProperties ?? '',\n    'display' => $tv->get('display'),\n    'mediaSource' => $sourceName ?? '', // Not a GPM property, but good to know anyway\n);\n\n// Remove undesired keys\n$tvAltered = array_diff($tvAltered,array(null,'description'));\n\nif ($tvAltered['display'] == 'default') {\n    unset($tvAltered['display']);\n}\nif ($tvAltered['inputProperties']['allowBlank'] == 'true') {\n    unset($tvAltered['inputProperties']['allowBlank']);\n}\nif (!$showName) {\n    unset($tvAltered['name']);\n}\nif (!$showSource) {\n    unset($tvAltered['mediaSource']);\n}\nif (empty($tvAltered['inputProperties'])) {\n    unset($tvAltered['inputProperties']);\n}\nif (empty($tvAltered['outputProperties'])) {\n    unset($tvAltered['outputProperties']);\n}\n\n// Output as JSON object\nreturn json_encode($tvAltered);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:32:"romanesco.tvtojson.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:33:"romanesco.tvtojson.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * tvToJSON
 *
 * Output the properties of given TV to a JSON object.
 * The output could be used by jsonToHTML.
 *
 * Initially intended for use in the front-end library. TV settings can now be
 * loaded automatically, instead of copy/pasting the JSON from the GPM config by hand.
 *
 * Usage example:
 * [[tvToJSON? &tv=`[[+pattern_name]]`]]
 *
 * For GPM compatible output:
 * [[!tvToJSON?
 *     &tv=`overview_img_landscape`
 *     &showName=`1`
 *     &showSource=`0`
 *     &optionsDelimiter=`0`
 * ]]
 *
 */

$tvName = $modx->getOption('tv', $scriptProperties, '');
$showName = $modx->getOption('showName', $scriptProperties, 0);
$showSource = $modx->getOption('showSource', $scriptProperties, 1);
$optionsDelimiter = $modx->getOption('optionsDelimiter', $scriptProperties, '<br>');

// Get the TV by name
$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));

if (!is_object($tv)) {
    return '';
}

// Render category name for clarity
$query = $modx->newQuery('modCategory', array(
    'id' => $tv->get('category')
));
$query->select('category');
$catName = $modx->getValue($query->prepare());

// Render media source name for clarity
$sourceID = $tv->get('source');
if ($sourceID != false) {
    $query = $modx->newQuery('modMediaSource', array(
        'id' => $sourceID
    ));
    $query->select('name');
    $sourceName = $modx->getValue($query->prepare());
}

// Check if TV has input / output properties
if ($tv->get('input_properties')) {
    $inputProperties = array_diff($tv->get('input_properties'),array(null));
}
if ($tv->get('output_properties')) {
    $outputProperties = array_diff($tv->get('output_properties'),array(null));
}

// Control output delimiter of input options
$inputOptions = $tv->get('elements');
if ($optionsDelimiter) {
    $inputOptions = str_replace('||', $optionsDelimiter, $inputOptions);
}

// Create a new object with altered elements
// The new key names mimic the properties used by GPM
$tvAltered = array(
    'caption' => $tv->get('caption'),
    'name' => $tv->get('name'),
    'description' => $tv->get('description'),
    'type' => $tv->get('type'),
    'category' => $catName,
    'sortOrder' => $tv->get('rank'),
    'inputOptionValues' => $inputOptions,
    'defaultValue' => $tv->get('default_text'),
    'inputProperties' => $inputProperties ?? '',
    'outputProperties' => $outputProperties ?? '',
    'display' => $tv->get('display'),
    'mediaSource' => $sourceName ?? '', // Not a GPM property, but good to know anyway
);

// Remove undesired keys
$tvAltered = array_diff($tvAltered,array(null,'description'));

if ($tvAltered['display'] == 'default') {
    unset($tvAltered['display']);
}
if ($tvAltered['inputProperties']['allowBlank'] == 'true') {
    unset($tvAltered['inputProperties']['allowBlank']);
}
if (!$showName) {
    unset($tvAltered['name']);
}
if (!$showSource) {
    unset($tvAltered['mediaSource']);
}
if (empty($tvAltered['inputProperties'])) {
    unset($tvAltered['inputProperties']);
}
if (empty($tvAltered['outputProperties'])) {
    unset($tvAltered['outputProperties']);
}

// Output as JSON object
return json_encode($tvAltered);