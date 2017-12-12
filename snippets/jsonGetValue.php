id: 72
name: jsonGetValue
description: 'Get the value of a specific key from a JSON string.'
category: f_json
snippet: "$input = $modx->getOption('json', $scriptProperties);\n$key = $modx->getOption('key', $scriptProperties);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n$output = $array[$key];\n\nif ($tpl) {\n    $output = $modx->getChunk($tpl, array(\n        'content' => $output\n    ));\n}\n\nreturn $output;"
properties: 'a:0:{}'
content: "$input = $modx->getOption('json', $scriptProperties);\n$key = $modx->getOption('key', $scriptProperties);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n\n$input = utf8_encode($input);\n$array = json_decode($input, true);\n\n$output = $array[$key];\n\nif ($tpl) {\n    $output = $modx->getChunk($tpl, array(\n        'content' => $output\n    ));\n}\n\nreturn $output;"

-----


$input = $modx->getOption('json', $scriptProperties);
$key = $modx->getOption('key', $scriptProperties);
$tpl = $modx->getOption('tpl', $scriptProperties, '');

$input = utf8_encode($input);
$array = json_decode($input, true);

$output = $array[$key];

if ($tpl) {
    $output = $modx->getChunk($tpl, array(
        'content' => $output
    ));
}

return $output;