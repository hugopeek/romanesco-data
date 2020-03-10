id: 143
name: jsonGetObject
description: 'Search a JSON object for specific item and return the entire array. This is initially intended to turn CB repeater elements into CSS, without having to change the internal templating in CB.'
category: f_json
snippet: "/**\n * jsonGetObject\n *\n * Search a JSON object for specific item and return the entire array.\n *\n * This is initially intended to turn CB repeater elements into CSS, without\n * having to change the internal templating in ContentBlocks.\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$json = $modx->getOption('json', $scriptProperties, '');\n$object = $modx->getOption('object', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');\n\n$jsonArray = json_decode($json, true);\n$output = array();\n\n// Search array for given object\n$result = $romanesco->recursiveArraySearch($jsonArray,$object);\n\n// Flatten first level, since that's always the full JSON object itself\n$result = $result[0];\n\n// Return result directly if it's no longer an array\nif (!is_array($result)) {\n    return $result;\n}\n\n// Flat arrays can be forwarded directly to the tpl chunk\nif (!$result[0]) {\n    return $modx->getChunk($tpl, $result);\n}\n\n// Loop over multidimensional arrays\nif ($result[0]) {\n    foreach ($result as $row) {\n        $output[] = $modx->getChunk($tpl, $row);\n    }\n    return implode($outputSeparator,$output);\n}\n\nreturn '';\n\n// @todo: Investigate approach below, where recursiveArraySearch can find multiple instances using 'yield' instead of 'return'.\n//foreach ($romanesco->recursiveArraySearch($jsonArray,$object) as $result) {\n//    // Flatten first level, since that's always the full JSON object itself\n//    $result = $result[0];\n//\n//    // Return result directly if it's no longer an array\n//    if (!is_array($result)) {\n//        $output[] = $result;\n//    }\n//\n//    // Flat arrays can be forwarded directly to the tpl chunk\n//    if (!$result[0]) {\n//        $output[] = $modx->getChunk($tpl, $result);\n//    }\n//\n//    // Loop over multidimensional arrays\n//    if ($result[0]) {\n//        $rows = array();\n//        foreach ($result as $row) {\n//            $rows[] = $modx->getChunk($tpl, $row);\n//        }\n//        $output[] = implode($outputSeparator,$rows);\n//    }\n//}\n//\n//return implode(',',$output);"
properties: 'a:0:{}'
content: "/**\n * jsonGetObject\n *\n * Search a JSON object for specific item and return the entire array.\n *\n * This is initially intended to turn CB repeater elements into CSS, without\n * having to change the internal templating in ContentBlocks.\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$json = $modx->getOption('json', $scriptProperties, '');\n$object = $modx->getOption('object', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');\n\n$jsonArray = json_decode($json, true);\n$output = array();\n\n// Search array for given object\n$result = $romanesco->recursiveArraySearch($jsonArray,$object);\n\n// Flatten first level, since that's always the full JSON object itself\n$result = $result[0];\n\n// Return result directly if it's no longer an array\nif (!is_array($result)) {\n    return $result;\n}\n\n// Flat arrays can be forwarded directly to the tpl chunk\nif (!$result[0]) {\n    return $modx->getChunk($tpl, $result);\n}\n\n// Loop over multidimensional arrays\nif ($result[0]) {\n    foreach ($result as $row) {\n        $output[] = $modx->getChunk($tpl, $row);\n    }\n    return implode($outputSeparator,$output);\n}\n\nreturn '';\n\n// @todo: Investigate approach below, where recursiveArraySearch can find multiple instances using 'yield' instead of 'return'.\n//foreach ($romanesco->recursiveArraySearch($jsonArray,$object) as $result) {\n//    // Flatten first level, since that's always the full JSON object itself\n//    $result = $result[0];\n//\n//    // Return result directly if it's no longer an array\n//    if (!is_array($result)) {\n//        $output[] = $result;\n//    }\n//\n//    // Flat arrays can be forwarded directly to the tpl chunk\n//    if (!$result[0]) {\n//        $output[] = $modx->getChunk($tpl, $result);\n//    }\n//\n//    // Loop over multidimensional arrays\n//    if ($result[0]) {\n//        $rows = array();\n//        foreach ($result as $row) {\n//            $rows[] = $modx->getChunk($tpl, $row);\n//        }\n//        $output[] = implode($outputSeparator,$rows);\n//    }\n//}\n//\n//return implode(',',$output);"

-----


/**
 * jsonGetObject
 *
 * Search a JSON object for specific item and return the entire array.
 *
 * This is initially intended to turn CB repeater elements into CSS, without
 * having to change the internal templating in ContentBlocks.
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
if (!$result[0]) {
    return $modx->getChunk($tpl, $result);
}

// Loop over multidimensional arrays
if ($result[0]) {
    foreach ($result as $row) {
        $output[] = $modx->getChunk($tpl, $row);
    }
    return implode($outputSeparator,$output);
}

return '';

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