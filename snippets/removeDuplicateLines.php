id: 146
name: removeDuplicateLines
description: 'Scan input for duplicate lines and remove them from the output.'
category: f_modifier
snippet: "/**\n * removeDuplicateLines\n *\n * Scan input for duplicate lines and remove them from the output.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$lines = $modx->getOption('input', $scriptProperties, $input);\n$file = $modx->getOption('file', $scriptProperties, '');\n\n// Check first if we're dealing with an external file\nif ($file) {\n    $lines = file_get_contents($file);\n}\n\n// Create an array of all lines inside the input\n$lines = explode(\"\\n\", $lines);\n$i = 0;\n\n// Check if the lines array contains duplicates\n$output = array_unique($lines);\n$output = array_filter($output);\n\nif (is_array($output)) {\n    return implode(\"\\n\", $output);\n} else {\n    return $output;\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * removeDuplicateLines
 *
 * Scan input for duplicate lines and remove them from the output.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$lines = $modx->getOption('input', $scriptProperties, $input);
$file = $modx->getOption('file', $scriptProperties, '');

// Check first if we're dealing with an external file
if ($file) {
    $lines = file_get_contents($file);
}

// Create an array of all lines inside the input
$lines = explode("\n", $lines);
$i = 0;

// Check if the lines array contains duplicates
$output = array_unique($lines);
$output = array_filter($output);

if (is_array($output)) {
    return implode("\n", $output);
} else {
    return $output;
}