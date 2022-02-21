id: 60
name: fbValidateProcessJSON
description: 'Generates the correct strings for the FormIt &validate property.'
category: f_formblocks
snippet: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$formID = $modx->getOption('formID', $scriptProperties,'');\n\nif ($formID) {\n    $resource = $modx->getObject('modResource', $formID);\n} else {\n    $resource = $modx->resource;\n    $formID = $resource->get('id');\n}\n\nif (!is_object($resource) || !($resource instanceof modResource)) return '';\n\n$prefix = $modx->getOption('prefix', $scriptProperties,'fb' . $formID . '-');\n$cbData = $resource->getProperty('linear', 'contentblocks');\n$output = array();\n\n// Go through CB data and collect all required fields\nforeach ($cbData as $field) {\n    if ($field['settings']['field_required'] != 1) {\n        continue;\n    }\n\n    // Get field name and format as alias\n    $fieldName = $field['settings']['field_name_html'];\n    if (!$fieldName) {\n        $fieldName = $field['settings']['field_name'];\n    }\n    $fieldName = $modx->runSnippet('fbStripAsAlias', array('input' => $fieldName));\n\n    // Special treatment for date fields\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {\n        $output[] = $prefix . $fieldName . \"-start:isDate:required,\";\n        $output[] = $prefix . $fieldName . \"-end:isDate:required,\";\n        continue;\n    }\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {\n        $output[] = $prefix . $fieldName . \":isDate:required,\";\n        continue;\n    }\n\n    // All remaining fields\n    $output[] = $prefix . $fieldName . \":required,\";\n}\n\nreturn implode('', $output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:45:"romanesco.fbvalidateprocessjson.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:46:"romanesco.fbvalidateprocessjson.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$formID = $modx->getOption('formID', $scriptProperties,'');\n\nif ($formID) {\n    $resource = $modx->getObject('modResource', $formID);\n} else {\n    $resource = $modx->resource;\n    $formID = $resource->get('id');\n}\n\nif (!is_object($resource) || !($resource instanceof modResource)) return '';\n\n$prefix = $modx->getOption('prefix', $scriptProperties,'fb' . $formID . '-');\n$cbData = $resource->getProperty('linear', 'contentblocks');\n$output = array();\n\n// Go through CB data and collect all required fields\nforeach ($cbData as $field) {\n    if ($field['settings']['field_required'] != 1) {\n        continue;\n    }\n\n    // Get field name and format as alias\n    $fieldName = $field['settings']['field_name_html'];\n    if (!$fieldName) {\n        $fieldName = $field['settings']['field_name'];\n    }\n    $fieldName = $modx->runSnippet('fbStripAsAlias', array('input' => $fieldName));\n\n    // Special treatment for date fields\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {\n        $output[] = $prefix . $fieldName . \"-start:isDate:required,\";\n        $output[] = $prefix . $fieldName . \"-end:isDate:required,\";\n        continue;\n    }\n    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {\n        $output[] = $prefix . $fieldName . \":isDate:required,\";\n        continue;\n    }\n\n    // All remaining fields\n    $output[] = $prefix . $fieldName . \":required,\";\n}\n\nreturn implode('', $output);"

-----


/**
 * fbValidateProcessJSON
 *
 * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.
 *
 * @author Hugo Peek
 * @var modX $modx
 * @var array $scriptProperties
 */

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

    // Get field name and format as alias
    $fieldName = $field['settings']['field_name_html'];
    if (!$fieldName) {
        $fieldName = $field['settings']['field_name'];
    }
    $fieldName = $modx->runSnippet('fbStripAsAlias', array('input' => $fieldName));

    // Special treatment for date fields
    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_range_id', $scriptProperties)) {
        $output[] = $prefix . $fieldName . "-start:isDate:required,";
        $output[] = $prefix . $fieldName . "-end:isDate:required,";
        continue;
    }
    if ($field['field'] == $modx->getOption('formblocks.cb_input_date_id', $scriptProperties)) {
        $output[] = $prefix . $fieldName . ":isDate:required,";
        continue;
    }

    // All remaining fields
    $output[] = $prefix . $fieldName . ":required,";
}

return implode('', $output);