id: 62
name: fbResetNonAlpha
category: f_fb_modifier
snippet: "/**\n * fbResetNonAlpha\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = preg_replace('/\\[and]/', '&', $input);\n$input = preg_replace('/\\[qmark]/', '?', $input);\n$input = preg_replace('/\\[semicolon]/', ';', $input);\n$input = preg_replace('/\\[equals]/', '=', $input);\n\nreturn $input;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.fbresetnonalpha.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.fbresetnonalpha.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbResetNonAlpha
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = preg_replace('/\[and]/', '&', $input);
$input = preg_replace('/\[qmark]/', '?', $input);
$input = preg_replace('/\[semicolon]/', ';', $input);
$input = preg_replace('/\[equals]/', '=', $input);

return $input;