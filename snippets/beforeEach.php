id: 155
name: beforeEach
description: 'Grab a comma-separated list and prefix all items with given value. Optionally, the separator can be changed and output can be forwarded to a placeholder.'
category: f_modifier
snippet: "/**\n * beforeEach snippet\n *\n * Grab a comma-separated list and prefix all items with given value.\n * Optionally, the separator can be changed and output can be forwarded to a\n * placeholder.\n *\n * Usage examples:\n *\n * [[++navbar_exclude_resources:beforeEach=`-`]]\n *\n * [[beforeEach?\n *     &input=`[[++navbar_exclude_resources]]`\n *     &before=`-`\n *     &toPlaceholder=`excluded_resources`\n * ]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$before = $modx->getOption('before', $scriptProperties, $options ?? '-');\n$separator = $modx->getOption('separator', $scriptProperties, ',');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\nif (!$input) return '';\n\n$resourceArray = explode($separator,$input);\n\n$output = array();\nforeach ($resourceArray as $input) {\n    $input = trim($input);\n\n    // Maybe value is already prefixed\n    if ($input[0] == $before) {\n        $output[] = $input;\n    } else {\n        $output[] = $before . $input;\n    }\n}\n\n$output = implode($separator, $output);\n\nif ($placeholder) {\n    $modx->toPlaceholder($placeholder, $output);\n    return;\n} else {\n    return $output;\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * beforeEach snippet
 *
 * Grab a comma-separated list and prefix all items with given value.
 * Optionally, the separator can be changed and output can be forwarded to a
 * placeholder.
 *
 * Usage examples:
 *
 * [[++navbar_exclude_resources:beforeEach=`-`]]
 *
 * [[beforeEach?
 *     &input=`[[++navbar_exclude_resources]]`
 *     &before=`-`
 *     &toPlaceholder=`excluded_resources`
 * ]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$before = $modx->getOption('before', $scriptProperties, $options ?? '-');
$separator = $modx->getOption('separator', $scriptProperties, ',');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

if (!$input) return '';

$resourceArray = explode($separator,$input);

$output = array();
foreach ($resourceArray as $input) {
    $input = trim($input);

    // Maybe value is already prefixed
    if ($input[0] == $before) {
        $output[] = $input;
    } else {
        $output[] = $before . $input;
    }
}

$output = implode($separator, $output);

if ($placeholder) {
    $modx->toPlaceholder($placeholder, $output);
    return;
} else {
    return $output;
}