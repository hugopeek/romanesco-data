id: 145
name: round
description: 'Round a decimal value to a whole number or with a specified amount of decimals. Usage: [[+value:round=`2`]] returns the value with 2 decimals, [[+value:round]] returns a whole number.'
category: f_modifiers
snippet: "if ($input == '') return '';\nif (!$options) $options = 0;\nreturn round($input,$options);"
properties: 'a:0:{}'
content: "if ($input == '') return '';\nif (!$options) $options = 0;\nreturn round($input,$options);"

-----


if ($input == '') return '';
if (!$options) $options = 0;
return round($input,$options);