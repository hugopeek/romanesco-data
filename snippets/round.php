id: 145
name: round
description: 'Round a decimal value to a whole number or with a specified amount of decimals. Usage: [[+value:round=`2`]] returns the value with 2 decimals, [[+value:round]] returns a whole number.'
category: f_modifier
snippet: "/**\n * round\n *\n * The wheels on the bus... Round a decimal value to a whole number or with a\n * specified amount of decimals.\n *\n * @example [[+value:round=`2`]] returns the value with 2 decimals\n * @example [[+value:round]] returns a whole number\n *\n * You can also round to the next higher or lower whole number:\n *\n * @example [[+value:round=`up`]]\n * @example [[+value:round=`down`]]\n *\n * Comma separator for decimals will be converted to a dot, so don't use ',' as\n * thousands separator!\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\nif ($input == '') return '';\nif (!$options) $options = 0;\n$input = str_replace(',', '.', $input); // Darn you Europeans\n\nif ($options == 'up') return ceil($input);\nif ($options == 'down') return floor($input);\n\nreturn round($input,$options);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

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
 * You can also round to the next higher or lower whole number:
 *
 * @example [[+value:round=`up`]]
 * @example [[+value:round=`down`]]
 *
 * Comma separator for decimals will be converted to a dot, so don't use ',' as
 * thousands separator!
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

if ($input == '') return '';
if (!$options) $options = 0;
$input = str_replace(',', '.', $input); // Darn you Europeans

if ($options == 'up') return ceil($input);
if ($options == 'down') return floor($input);

return round($input,$options);