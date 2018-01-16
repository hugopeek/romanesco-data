id: 121
name: splitString
description: 'Divide string into multiple sections, based on a delimiter. If used as a regular snippet, each part is output to a separate placeholder. If used as output modifier, you need to specify the number of the part you want to get.'
category: f_modifiers
snippet: "/**\n * splitString\n *\n * Divide string into multiple sections, based on a delimiter.\n *\n * If used as a regular snippet, each part is output to a separate placeholder.\n *\n * If used as output modifier, you need to specify the number of the part you\n * want to get. For example, if your string is:\n *\n * 'Ubuntu|300,700,300italic,700italic|latin'\n *\n * Then [[+placeholder:splitString=`1`]] will return 'Ubuntu'.\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$options = $modx->getOption('options', $scriptProperties, $options);\n$delimiter = $modx->getOption('delimiter', $scriptProperties, '|');\n$prefix = $modx->getOption('prefix', $scriptProperties, 'snippet');\n\n// Output filters are also processed when the input is empty, so check for that\nif ($input == '') { return ''; }\n\n// Break up the string\n$output = explode($delimiter,$input);\n$idx = 0;\n\n// If snippet is used as output modifier, return matching section\nif ($options) {\n    return $output[$options - 1];\n}\n\n// Process each section individually\nforeach ($output as $value) {\n    $idx++;\n    $modx->toPlaceholder($idx, trim($value), $prefix);\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * splitString\n *\n * Divide string into multiple sections, based on a delimiter.\n *\n * If used as a regular snippet, each part is output to a separate placeholder.\n *\n * If used as output modifier, you need to specify the number of the part you\n * want to get. For example, if your string is:\n *\n * 'Ubuntu|300,700,300italic,700italic|latin'\n *\n * Then [[+placeholder:splitString=`1`]] will return 'Ubuntu'.\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$options = $modx->getOption('options', $scriptProperties, $options);\n$delimiter = $modx->getOption('delimiter', $scriptProperties, '|');\n$prefix = $modx->getOption('prefix', $scriptProperties, 'snippet');\n\n// Output filters are also processed when the input is empty, so check for that\nif ($input == '') { return ''; }\n\n// Break up the string\n$output = explode($delimiter,$input);\n$idx = 0;\n\n// If snippet is used as output modifier, return matching section\nif ($options) {\n    return $output[$options - 1];\n}\n\n// Process each section individually\nforeach ($output as $value) {\n    $idx++;\n    $modx->toPlaceholder($idx, trim($value), $prefix);\n}\n\nreturn '';"

-----


/**
 * splitString
 *
 * Divide string into multiple sections, based on a delimiter.
 *
 * If used as a regular snippet, each part is output to a separate placeholder.
 *
 * If used as output modifier, you need to specify the number of the part you
 * want to get. For example, if your string is:
 *
 * 'Ubuntu|300,700,300italic,700italic|latin'
 *
 * Then [[+placeholder:splitString=`1`]] will return 'Ubuntu'.
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$options = $modx->getOption('options', $scriptProperties, $options);
$delimiter = $modx->getOption('delimiter', $scriptProperties, '|');
$prefix = $modx->getOption('prefix', $scriptProperties, 'snippet');

// Output filters are also processed when the input is empty, so check for that
if ($input == '') { return ''; }

// Break up the string
$output = explode($delimiter,$input);
$idx = 0;

// If snippet is used as output modifier, return matching section
if ($options) {
    return $output[$options - 1];
}

// Process each section individually
foreach ($output as $value) {
    $idx++;
    $modx->toPlaceholder($idx, trim($value), $prefix);
}

return '';