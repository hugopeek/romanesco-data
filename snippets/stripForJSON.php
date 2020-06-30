id: 150
name: stripForJSON
description: 'Prepare the input for being used in JSON. This means escaping backslashes and double quotes.'
category: f_json
snippet: "/**\n * stripForJSON\n *\n * Escape backslashes and double quotes.\n */\n$input = $modx->getOption('input', $scriptProperties, $input);\nif ($input == '') { return ''; }\nreturn str_replace('\\n','',json_encode($input));"
properties: 'a:0:{}'
content: "/**\n * stripForJSON\n *\n * Escape backslashes and double quotes.\n */\n$input = $modx->getOption('input', $scriptProperties, $input);\nif ($input == '') { return ''; }\nreturn str_replace('\\n','',json_encode($input));"

-----


/**
 * stripForJSON
 *
 * Escape backslashes and double quotes.
 */
$input = $modx->getOption('input', $scriptProperties, $input);
if ($input == '') { return ''; }
return str_replace('\n','',json_encode($input));