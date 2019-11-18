id: 72
name: jsonGetValue
description: 'Get the value of a specific key from a JSON string.'
category: f_json
snippet: "$input = $modx->getOption('json', $scriptProperties, $input);\n$key = $modx->getOption('key', $scriptProperties, $options);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n// @todo: test if input is valid JSON, otherwise NULL is returned\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n// Single result from flat array\nif ($array[$key]) {\n    $output = $array[$key];\n\n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n};\n\n// Multiple keys from multidimensional array\nif (is_array($array[0])) {\n    foreach ($array as $item) {\n        $output[] = $item[0][$key];\n\n        if ($tpl) {\n            $output[] = $modx->getChunk($tpl, array(\n                'content' => $output\n            ));\n        }\n    }\n\n    $output = implode(',',$output);\n}\n\n// Output either to placeholder, or directly\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\n\nreturn $output;"
properties: 'a:0:{}'
content: "$input = $modx->getOption('json', $scriptProperties, $input);\n$key = $modx->getOption('key', $scriptProperties, $options);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n// @todo: test if input is valid JSON, otherwise NULL is returned\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n// Single result from flat array\nif ($array[$key]) {\n    $output = $array[$key];\n\n    if ($tpl) {\n        $output = $modx->getChunk($tpl, array(\n            'content' => $output\n        ));\n    }\n};\n\n// Multiple keys from multidimensional array\nif (is_array($array[0])) {\n    foreach ($array as $item) {\n        $output[] = $item[0][$key];\n\n        if ($tpl) {\n            $output[] = $modx->getChunk($tpl, array(\n                'content' => $output\n            ));\n        }\n    }\n\n    $output = implode(',',$output);\n}\n\n// Output either to placeholder, or directly\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\n\nreturn $output;"

-----


$input = $modx->getOption('json', $scriptProperties, $input);
$key = $modx->getOption('key', $scriptProperties, $options);
$tpl = $modx->getOption('tpl', $scriptProperties, '');
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, ',');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// @todo: test if input is valid JSON, otherwise NULL is returned
$input = utf8_encode($input);
$array = json_decode($input, true);

// Single result from flat array
if ($array[$key]) {
    $output = $array[$key];

    if ($tpl) {
        $output = $modx->getChunk($tpl, array(
            'content' => $output
        ));
    }
};

// Multiple keys from multidimensional array
if (is_array($array[0])) {
    foreach ($array as $item) {
        $output[] = $item[0][$key];

        if ($tpl) {
            $output[] = $modx->getChunk($tpl, array(
                'content' => $output
            ));
        }
    }

    $output = implode(',',$output);
}

// Output either to placeholder, or directly
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}

return $output;