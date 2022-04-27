id: 64
name: fbStripNonAlpha
category: f_fb_modifier
snippet: "$input = preg_replace('/[&]/', '[and]', $input);\n$input = preg_replace('/[?]/', '[qmark]', $input);\n$input = preg_replace('/[;]/', '[semicolon]', $input);\n$input = preg_replace('/[=]/', '[equals]', $input);\n$input = preg_replace('/[`]/', '', $input);\n\nreturn $input;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.fbstripnonalpha.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.fbstripnonalpha.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$input = preg_replace('/[&]/', '[and]', $input);\n$input = preg_replace('/[?]/', '[qmark]', $input);\n$input = preg_replace('/[;]/', '[semicolon]', $input);\n$input = preg_replace('/[=]/', '[equals]', $input);\n$input = preg_replace('/[`]/', '', $input);\n\nreturn $input;"

-----


$input = preg_replace('/[&]/', '[and]', $input);
$input = preg_replace('/[?]/', '[qmark]', $input);
$input = preg_replace('/[;]/', '[semicolon]', $input);
$input = preg_replace('/[=]/', '[equals]', $input);
$input = preg_replace('/[`]/', '', $input);

return $input;