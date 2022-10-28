id: 112
name: filterLine
description: 'Search the input for lines containing a specific string. And then return those lines.'
category: f_modifier
snippet: "/**\n * filterLine\n *\n * Search input for lines containing a specific string. And then return those\n * lines.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$lines = $modx->getOption('input', $scriptProperties, $input);\n$file = $modx->getOption('file', $scriptProperties, '');\n$search = $modx->getOption('searchString', $scriptProperties, $options);\n$limit = $modx->getOption('limit', $scriptProperties, 10);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n\n// Check first if we're dealing with an external file\nif ($file) {\n    $lines = file_get_contents($file);\n}\n\n// Create an array of all lines inside the input\n$lines = explode(\"\\n\", $lines);\n$i = 0;\n$output = [];\n\n// Check if the line contains the string we're looking for, and print if it does\nforeach ($lines as $line) {\n    if(strpos($line, $search) !== false) {\n        $output[] = $line;\n\n        $i++;\n        if($i >= $limit) {\n            break;\n        }\n\n        if ($tpl) {\n            $output[] = $modx->getChunk($tpl, array(\n                'content' => $line,\n            ));\n        }\n    }\n}\n\nif ($output) {\n    return implode('<br>', $output);\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.filterline.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.filterline.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * filterLine\n *\n * Search input for lines containing a specific string. And then return those\n * lines.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$lines = $modx->getOption('input', $scriptProperties, $input);\n$file = $modx->getOption('file', $scriptProperties, '');\n$search = $modx->getOption('searchString', $scriptProperties, $options);\n$limit = $modx->getOption('limit', $scriptProperties, 10);\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n\n// Check first if we're dealing with an external file\nif ($file) {\n    $lines = file_get_contents($file);\n}\n\n// Create an array of all lines inside the input\n$lines = explode(\"\\n\", $lines);\n$i = 0;\n$output = [];\n\n// Check if the line contains the string we're looking for, and print if it does\nforeach ($lines as $line) {\n    if(strpos($line, $search) !== false) {\n        $output[] = $line;\n\n        $i++;\n        if($i >= $limit) {\n            break;\n        }\n\n        if ($tpl) {\n            $output[] = $modx->getChunk($tpl, array(\n                'content' => $line,\n            ));\n        }\n    }\n}\n\nif ($output) {\n    return implode('<br>', $output);\n}\n\nreturn '';"

-----


/**
 * filterLine
 *
 * Search input for lines containing a specific string. And then return those
 * lines.
 *
 * @var modX $modx
 * @var array $scriptProperties;
 * @var string $input;
 * @var string $options;
 */

$lines = $modx->getOption('input', $scriptProperties, $input);
$file = $modx->getOption('file', $scriptProperties, '');
$search = $modx->getOption('searchString', $scriptProperties, $options);
$limit = $modx->getOption('limit', $scriptProperties, 10);
$tpl = $modx->getOption('tpl', $scriptProperties, '');

// Check first if we're dealing with an external file
if ($file) {
    $lines = file_get_contents($file);
}

// Create an array of all lines inside the input
$lines = explode("\n", $lines);
$i = 0;
$output = [];

// Check if the line contains the string we're looking for, and print if it does
foreach ($lines as $line) {
    if(strpos($line, $search) !== false) {
        $output[] = $line;

        $i++;
        if($i >= $limit) {
            break;
        }

        if ($tpl) {
            $output[] = $modx->getChunk($tpl, array(
                'content' => $line,
            ));
        }
    }
}

if ($output) {
    return implode('<br>', $output);
}

return '';