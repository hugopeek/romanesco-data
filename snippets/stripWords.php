id: 67
name: stripWords
description: 'As opposed to the native MODX stripString modifier (which only allows you to strip a single value), stripWords lets you enter multiple (comma separated) values.'
category: f_modifiers
snippet: "/**\n * stripWords\n *\n * As opposed to the native MODX stripString modifier (which only allows you to\n * strip a single value), stripWords lets you enter multiple (comma separated)\n * values.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.stripwords.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.stripwords.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * stripWords\n *\n * As opposed to the native MODX stripString modifier (which only allows you to\n * strip a single value), stripWords lets you enter multiple (comma separated)\n * values.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"

-----


/**
 * stripWords
 *
 * As opposed to the native MODX stripString modifier (which only allows you to
 * strip a single value), stripWords lets you enter multiple (comma separated)
 * values.
 *
 * @var modX $modx
 * @var array $scriptProperties;
 * @var string $input;
 * @var string $options;
 */

$words = array_map('trim', explode(',', $options));
return str_replace($words, '', $input);