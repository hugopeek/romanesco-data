id: 145
name: round
description: 'Round a decimal value to a whole number or with a specified amount of decimals. Usage: [[+value:round=`2`]] returns the value with 2 decimals, [[+value:round]] returns a whole number.'
category: f_modifiers
snippet: "/**\n * round\n *\n * The wheels on the bus... Round a decimal value to a whole number or with a\n * specified amount of decimals.\n *\n * @example [[+value:round=`2`]] returns the value with 2 decimals\n * @example [[+value:round]] returns a whole number\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\nif ($input == '') return '';\nif (!$options) $options = 0;\nreturn round($input,$options);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:29:"romanesco.round.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:30:"romanesco.round.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * round\n *\n * The wheels on the bus... Round a decimal value to a whole number or with a\n * specified amount of decimals.\n *\n * @example [[+value:round=`2`]] returns the value with 2 decimals\n * @example [[+value:round]] returns a whole number\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\nif ($input == '') return '';\nif (!$options) $options = 0;\nreturn round($input,$options);"

-----


/**
 * round
 *
 * The wheels on the bus... Round a decimal value to a whole number or with a
 * specified amount of decimals.
 *
 * @example [[+value:round=`2`]] returns the value with 2 decimals
 * @example [[+value:round]] returns a whole number
 *
 * @var modX $modx
 * @var array $scriptProperties;
 * @var string $input;
 * @var string $options;
 */

if ($input == '') return '';
if (!$options) $options = 0;
return round($input,$options);