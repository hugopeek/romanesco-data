id: 60
name: fbValidateProcessJSON
description: 'Generates the correct strings for the FormIt &validate property.'
category: f_formblocks
snippet: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n * @var $scriptProperties\n */\n\n$input = $modx->getOption('json', $scriptProperties);\n$fields = $modx->fromJSON($input);\n$id = $modx->resource->get('id');\n$prefix = !empty($prefix) ? $prefix: 'fb' . $id . '-';\n$emailField = $modx->getOption('emailField', $scriptProperties);\n\n//$jsonString = $modx->getOption('json', $scriptProperties);\n//$array = json_decode($jsonString, true);\n\n// Function to strip required field names correctly\nif (!function_exists('stripResults')) {\n    function stripResults($input) {\n        global $modx;\n        return $modx->runSnippet('fbStripAsAlias', array('input' => $input));\n    }\n}\n\n// Go through JSON array and collect all required fields\n//$fields = search($array, 'field_required', '1');\n$output = array();\n\nforeach ($fields as $field) {\n    if ($field['settings']['field_required'] != 1) {\n        continue;\n    }\n\n    // Special treatment for date fields\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \"-start:isDate:required,\";\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \"-end:isDate:required,\";\n        continue;\n    }\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \":isDate:required,\";\n        continue;\n    }\n\n    // All remaining fields\n    $output[] = $prefix . stripResults($field['settings']['field_name']) . \":required,\";\n}\n\nreturn implode('', $output);"
properties: 'a:0:{}'
content: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n * @var $scriptProperties\n */\n\n$input = $modx->getOption('json', $scriptProperties);\n$fields = $modx->fromJSON($input);\n$id = $modx->resource->get('id');\n$prefix = !empty($prefix) ? $prefix: 'fb' . $id . '-';\n$emailField = $modx->getOption('emailField', $scriptProperties);\n\n//$jsonString = $modx->getOption('json', $scriptProperties);\n//$array = json_decode($jsonString, true);\n\n// Function to strip required field names correctly\nif (!function_exists('stripResults')) {\n    function stripResults($input) {\n        global $modx;\n        return $modx->runSnippet('fbStripAsAlias', array('input' => $input));\n    }\n}\n\n// Go through JSON array and collect all required fields\n//$fields = search($array, 'field_required', '1');\n$output = array();\n\nforeach ($fields as $field) {\n    if ($field['settings']['field_required'] != 1) {\n        continue;\n    }\n\n    // Special treatment for date fields\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \"-start:isDate:required,\";\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \"-end:isDate:required,\";\n        continue;\n    }\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {\n        $output[] = $prefix . stripResults($field['settings']['field_name']) . \":isDate:required,\";\n        continue;\n    }\n\n    // All remaining fields\n    $output[] = $prefix . stripResults($field['settings']['field_name']) . \":required,\";\n}\n\nreturn implode('', $output);"

-----


/**
 * fbValidateProcessJSON
 *
 * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.
 *
 * @author Hugo Peek
 * @var $scriptProperties
 */

// Function to strip required field names correctly
if (!function_exists('stripResults')) {
    function stripResults($input) {
        global $modx;
        return $modx->runSnippet('fbStripAsAlias', array('input' => $input));
    }
}

$formID = $modx->getOption('formID', $scriptProperties,'');

if ($formID) {
    $resource = $modx->getObject('modResource', $formID);
} else {
    $resource = $modx->resource;
    $formID = $resource->get('id');
}

if (!is_object($resource) || !($resource instanceof modResource)) return '';

$prefix = $modx->getOption('prefix', $scriptProperties,'fb' . $formID . '-');
$cbData = $resource->getProperty('linear', 'contentblocks');
$output = array();

// Go through CB data and collect all required fields
foreach ($cbData as $field) {
    if ($field['settings']['field_required'] != 1) {
        continue;
    }

    // Special treatment for date fields
    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {
        $output[] = $prefix . stripResults($field['settings']['field_name']) . "-start:isDate:required,";
        $output[] = $prefix . stripResults($field['settings']['field_name']) . "-end:isDate:required,";
        continue;
    }
    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {
        $output[] = $prefix . stripResults($field['settings']['field_name']) . ":isDate:required,";
        continue;
    }

    // All remaining fields
    $output[] = $prefix . stripResults($field['settings']['field_name']) . ":required,";
}

return implode('', $output);