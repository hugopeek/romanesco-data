id: 143
name: jsonGetObject
description: 'Search a JSON object for specific item and return the entire array. This is initially intended to turn CB repeater elements into CSS, without having to change the internal templating in CB.'
category: f_json
snippet: "/**\n * jsonGetObject\n *\n * Search a JSON object for specific item and return the entire array.\n *\n * This is initially intended to turn CB repeater elements into CSS, without\n * having to change the internal templating in ContentBlocks.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$json = $modx->getOption('json', $scriptProperties, '');\n$object = $modx->getOption('object', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');\n\n$jsonArray = json_decode($json, true);\n$output = array();\n\n//$modx->log(modX::LOG_LEVEL_ERROR, print_r($jsonArray,1));\n\n// Return directly if JSON input is not present or valid\nif (!$jsonArray) {\n    $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] No valid JSON input provided.');\n    switch (json_last_error()) {\n        case JSON_ERROR_NONE:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] No errors');\n            break;\n        case JSON_ERROR_DEPTH:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Maximum stack depth exceeded');\n            break;\n        case JSON_ERROR_STATE_MISMATCH:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Underflow or the modes mismatch');\n            break;\n        case JSON_ERROR_CTRL_CHAR:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Unexpected control character found');\n            break;\n        case JSON_ERROR_SYNTAX:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Syntax error, malformed JSON');\n            break;\n        case JSON_ERROR_UTF8:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Malformed UTF-8 characters, possibly incorrectly encoded');\n            break;\n        default:\n            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Unknown error');\n            break;\n    }\n    return '';\n}\n\n// Search array for given object\n$result = $romanesco->recursiveArraySearch($jsonArray,$object);\n\n// Flatten first level, since that's always the full JSON object itself\n$result = $result[0];\n\n// Return result if it's no longer an array\nif (!is_array($result)) {\n    return $result;\n}\n\n// Flat arrays can be forwarded directly to the tpl chunk\nif (!isset($result[0])) {\n    return $modx->getChunk($tpl, $result);\n}\n\n// Loop over multidimensional arrays\n$idx = 1;\nforeach ($result as $row) {\n    $row['idx'] = $idx++;\n    $output[] = $modx->getChunk($tpl, $row);\n}\nreturn implode($outputSeparator,$output);\n\n// @todo: Investigate approach below, where recursiveArraySearch can find multiple instances using 'yield' instead of 'return'.\n//foreach ($romanesco->recursiveArraySearch($jsonArray,$object) as $result) {\n//    // Flatten first level, since that's always the full JSON object itself\n//    $result = $result[0];\n//\n//    // Return result directly if it's no longer an array\n//    if (!is_array($result)) {\n//        $output[] = $result;\n//    }\n//\n//    // Flat arrays can be forwarded directly to the tpl chunk\n//    if (!$result[0]) {\n//        $output[] = $modx->getChunk($tpl, $result);\n//    }\n//\n//    // Loop over multidimensional arrays\n//    if ($result[0]) {\n//        $rows = array();\n//        foreach ($result as $row) {\n//            $rows[] = $modx->getChunk($tpl, $row);\n//        }\n//        $output[] = implode($outputSeparator,$rows);\n//    }\n//}\n//\n//return implode(',',$output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.jsongetobject.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.jsongetobject.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * jsonGetObject
 *
 * Search a JSON object for specific item and return the entire array.
 *
 * This is initially intended to turn CB repeater elements into CSS, without
 * having to change the internal templating in ContentBlocks.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) return;

$json = $modx->getOption('json', $scriptProperties, '');
$object = $modx->getOption('object', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, '');
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');

$jsonArray = json_decode($json, true);
$output = array();

//$modx->log(modX::LOG_LEVEL_ERROR, print_r($jsonArray,1));

// Return directly if JSON input is not present or valid
if (!$jsonArray) {
    $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] No valid JSON input provided.');
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] No errors');
            break;
        case JSON_ERROR_DEPTH:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Maximum stack depth exceeded');
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Underflow or the modes mismatch');
            break;
        case JSON_ERROR_CTRL_CHAR:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Unexpected control character found');
            break;
        case JSON_ERROR_SYNTAX:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Syntax error, malformed JSON');
            break;
        case JSON_ERROR_UTF8:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Malformed UTF-8 characters, possibly incorrectly encoded');
            break;
        default:
            $modx->log(modX::LOG_LEVEL_INFO, '[jsonGetObject] Unknown error');
            break;
    }
    return '';
}

// Search array for given object
$result = $romanesco->recursiveArraySearch($jsonArray,$object);

// Flatten first level, since that's always the full JSON object itself
$result = $result[0];

// Return result if it's no longer an array
if (!is_array($result)) {
    return $result;
}

// Flat arrays can be forwarded directly to the tpl chunk
if (!isset($result[0])) {
    return $modx->getChunk($tpl, $result);
}

// Loop over multidimensional arrays
$idx = 1;
foreach ($result as $row) {
    $row['idx'] = $idx++;
    $output[] = $modx->getChunk($tpl, $row);
}
return implode($outputSeparator,$output);

// @todo: Investigate approach below, where recursiveArraySearch can find multiple instances using 'yield' instead of 'return'.
//foreach ($romanesco->recursiveArraySearch($jsonArray,$object) as $result) {
//    // Flatten first level, since that's always the full JSON object itself
//    $result = $result[0];
//
//    // Return result directly if it's no longer an array
//    if (!is_array($result)) {
//        $output[] = $result;
//    }
//
//    // Flat arrays can be forwarded directly to the tpl chunk
//    if (!$result[0]) {
//        $output[] = $modx->getChunk($tpl, $result);
//    }
//
//    // Loop over multidimensional arrays
//    if ($result[0]) {
//        $rows = array();
//        foreach ($result as $row) {
//            $rows[] = $modx->getChunk($tpl, $row);
//        }
//        $output[] = implode($outputSeparator,$rows);
//    }
//}
//
//return implode(',',$output);