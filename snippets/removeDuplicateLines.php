id: 146
name: removeDuplicateLines
description: 'Scan input for duplicate lines and remove them from the output.'
category: f_modifiers
snippet: "$lines = $modx->getOption('input', $scriptProperties, $input);\n$file = $modx->getOption('file', $scriptProperties, '');\n\n// Check first if we're dealing with an external file\nif ($file) {\n    $lines = file_get_contents($file);\n}\n\n// Create an array of all lines inside the input\n$lines = explode(\"\\n\", strtolower($lines));\n$i = 0;\n\n// Check if the lines array contains duplicates\n$output = array_unique($lines);\n$output = array_filter($output);\n\nif (is_array($output)) {\n    return implode(\"\\n\", $output);\n} else {\n    return $output;\n}"
properties: 'a:0:{}'
content: "$lines = $modx->getOption('input', $scriptProperties, $input);\n$file = $modx->getOption('file', $scriptProperties, '');\n\n// Check first if we're dealing with an external file\nif ($file) {\n    $lines = file_get_contents($file);\n}\n\n// Create an array of all lines inside the input\n$lines = explode(\"\\n\", strtolower($lines));\n$i = 0;\n\n// Check if the lines array contains duplicates\n$output = array_unique($lines);\n$output = array_filter($output);\n\nif (is_array($output)) {\n    return implode(\"\\n\", $output);\n} else {\n    return $output;\n}"

-----


$lines = $modx->getOption('input', $scriptProperties, $input);
$file = $modx->getOption('file', $scriptProperties, '');

// Check first if we're dealing with an external file
if ($file) {
    $lines = file_get_contents($file);
}

// Create an array of all lines inside the input
$lines = explode("\n", strtolower($lines));
$i = 0;

// Check if the lines array contains duplicates
$output = array_unique($lines);
$output = array_filter($output);

if (is_array($output)) {
    return implode("\n", $output);
} else {
    return $output;
}