id: 72
name: jsonGetValue
description: 'Get the value of a specific key from a JSON string.'
category: f_json
snippet: "$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$input = $modx->getOption('json', $scriptProperties, $input);\n$key = $modx->getOption('key', $scriptProperties, $options);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n// @todo: test if input is valid JSON, otherwise NULL is returned\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n// Flatten first level, since that's always the full JSON object itself\n$array = $array[0];\n\n// Single result from flat array\nif ($array[$key]) {\n    $output = $array[$key];\n\n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n};\n\n// Single key from multidimensional array\nif (is_array($array)) {\n    $output = $romanesco->recursiveArraySearch($array, $key);\n    \n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n\n    $output = implode($output);\n}\n\n// Output either to placeholder, or directly\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.jsongetvalue.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.jsongetvalue.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$input = $modx->getOption('json', $scriptProperties, $input);\n$key = $modx->getOption('key', $scriptProperties, $options);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n// @todo: test if input is valid JSON, otherwise NULL is returned\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n// Flatten first level, since that's always the full JSON object itself\n$array = $array[0];\n\n// Single result from flat array\nif ($array[$key]) {\n    $output = $array[$key];\n\n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n};\n\n// Single key from multidimensional array\nif (is_array($array)) {\n    $output = $romanesco->recursiveArraySearch($array, $key);\n    \n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n\n    $output = implode($output);\n}\n\n// Output either to placeholder, or directly\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\n\nreturn $output;"

-----


$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) return;

$input = $modx->getOption('json', $scriptProperties, $input);
$key = $modx->getOption('key', $scriptProperties, $options);
$tpl = $modx->getOption('tpl', $scriptProperties, '');
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// @todo: test if input is valid JSON, otherwise NULL is returned
$input = utf8_encode($input);
$array = json_decode($input, true);

// Flatten first level, since that's always the full JSON object itself
$array = $array[0];

// Single result from flat array
if ($array[$key]) {
    $output = $array[$key];

    if ($tpl) {
        $output = $modx->getChunk($tpl, array(
            'content' => $output
        ));
    }
};

// Single key from multidimensional array
if (is_array($array)) {
    $output = $romanesco->recursiveArraySearch($array, $key);
    
    if ($tpl) {
        $output = $modx->getChunk($tpl, array(
            'content' => $output
        ));
    }

    $output = implode($output);
}

// Output either to placeholder, or directly
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}

return $output;