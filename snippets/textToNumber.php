id: 94
name: textToNumber
description: 'Turn a written number into an actual numeric value. In other words: turn "three" into "3". Can come in handy if you want to use the Semantic UI column width classes for other purposes.'
category: f_modifiers
snippet: "/**\n * textToNumber\n *\n * Turn a written number into an actual one.\n *\n * Written numbers are used for setting column counts in SemanticUI, but as\n * numeric values they can be reused in other places too.\n * For example: to set the number of visible slides in the JS configuration.\n *\n * Easter egg: reverses to numberToText functionality when input is numeric\n *\n * NB: there seems to be an issue when using this snippet inside a CB template\n * as output modifier. It messes up the cache and prevents the alternative from\n * working properly. To fix: remove all modifiers, replace with full snippet\n * call and clear cache (from top menu, NOT by saving resource).\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$output = '';\n\n$numbers = array(\n    '1' => 'one',\n    '2' => 'two',\n    '3' => 'three',\n    '4' => 'four',\n    '5' => 'five',\n    '6' => 'six',\n    '7' => 'seven',\n    '8' => 'eight',\n    '9' => 'nine',\n    '10' => 'ten',\n    '11' => 'eleven',\n    '12' => 'twelve',\n    '13' => 'thirteen',\n    '14' => 'fourteen',\n    '15' => 'fifteen',\n    '16' => 'sixteen'\n);\n\nif (is_numeric($input)) {\n    $output = $numbers[$input];\n} else {\n    $output = array_search($input, $numbers);\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.texttonumber.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.texttonumber.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * textToNumber\n *\n * Turn a written number into an actual one.\n *\n * Written numbers are used for setting column counts in SemanticUI, but as\n * numeric values they can be reused in other places too.\n * For example: to set the number of visible slides in the JS configuration.\n *\n * Easter egg: reverses to numberToText functionality when input is numeric\n *\n * NB: there seems to be an issue when using this snippet inside a CB template\n * as output modifier. It messes up the cache and prevents the alternative from\n * working properly. To fix: remove all modifiers, replace with full snippet\n * call and clear cache (from top menu, NOT by saving resource).\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$output = '';\n\n$numbers = array(\n    '1' => 'one',\n    '2' => 'two',\n    '3' => 'three',\n    '4' => 'four',\n    '5' => 'five',\n    '6' => 'six',\n    '7' => 'seven',\n    '8' => 'eight',\n    '9' => 'nine',\n    '10' => 'ten',\n    '11' => 'eleven',\n    '12' => 'twelve',\n    '13' => 'thirteen',\n    '14' => 'fourteen',\n    '15' => 'fifteen',\n    '16' => 'sixteen'\n);\n\nif (is_numeric($input)) {\n    $output = $numbers[$input];\n} else {\n    $output = array_search($input, $numbers);\n}\n\nreturn $output;"

-----


/**
 * textToNumber
 *
 * Turn a written number into an actual one.
 *
 * Written numbers are used for setting column counts in SemanticUI, but as
 * numeric values they can be reused in other places too.
 * For example: to set the number of visible slides in the JS configuration.
 *
 * Easter egg: reverses to numberToText functionality when input is numeric
 *
 * NB: there seems to be an issue when using this snippet inside a CB template
 * as output modifier. It messes up the cache and prevents the alternative from
 * working properly. To fix: remove all modifiers, replace with full snippet
 * call and clear cache (from top menu, NOT by saving resource).
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$output = '';

$numbers = array(
    '1' => 'one',
    '2' => 'two',
    '3' => 'three',
    '4' => 'four',
    '5' => 'five',
    '6' => 'six',
    '7' => 'seven',
    '8' => 'eight',
    '9' => 'nine',
    '10' => 'ten',
    '11' => 'eleven',
    '12' => 'twelve',
    '13' => 'thirteen',
    '14' => 'fourteen',
    '15' => 'fifteen',
    '16' => 'sixteen'
);

if (is_numeric($input)) {
    $output = $numbers[$input];
} else {
    $output = array_search($input, $numbers);
}

return $output;