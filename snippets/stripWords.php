id: 67
name: stripWords
description: 'Opposed to the native MODX stripString modifier (which only allows you to strip a single value), stripWords lets you enter multiple (comma separated) values.'
category: f_modifiers
snippet: "/* stripWords snippet */\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"
properties: 'a:0:{}'
content: "/* stripWords snippet */\n$words = array_map('trim', explode(',', $options));\nreturn str_replace($words, '', $input);"

-----


/* stripWords snippet */
$words = array_map('trim', explode(',', $options));
return str_replace($words, '', $input);