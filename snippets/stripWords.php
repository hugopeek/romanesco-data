id: 67
name: stripWords
description: 'Opposed to the native MODX stripString modifier (which only allows you to strip a single value), stripWords lets you enter multiple (comma separated) values.'
category: f_modifiers
snippet: "/* stripWords snippet */\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.stripwords.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.stripwords.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/* stripWords snippet */\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"

-----


/* stripWords snippet */
$words = array_map('trim', explode(',', $options));
return str_replace($words, '', $input);