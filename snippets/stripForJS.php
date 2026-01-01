id: 117
name: stripForJS
description: "Prepare the input for use in Javascript. This means escaping certain characters to make sure the surrounding HTML doesn't break."
category: f_modifier
snippet: "/**\n * stripForJS\n *\n * Prepare the input for use in Javascript. This means escaping certain\n * characters to make sure the surrounding HTML doesn't break.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$output = $input;\n$output = str_replace('/', '\\/', $output);\n$output = str_replace(\"'\", \"\\'\", $output);\n$output = str_replace(\"\\n\", '', $output);\n$output = preg_replace(\"/(>+(\\s)*<+)/\", '><', $output);\n$output = preg_replace(\"/\\s+/\", ' ', $output);\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * stripForJS
 *
 * Prepare the input for use in Javascript. This means escaping certain
 * characters to make sure the surrounding HTML doesn't break.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$output = $input;
$output = str_replace('/', '\/', $output);
$output = str_replace("'", "\'", $output);
$output = str_replace("\n", '', $output);
$output = preg_replace("/(>+(\s)*<+)/", '><', $output);
$output = preg_replace("/\s+/", ' ', $output);
return $output;