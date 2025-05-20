id: 156
name: replaceRegex
description: 'Find patterns with regex and replace them. By default, it removes all matches. If you want to replace each match with something else, you have to use a regular snippet call.'
category: f_modifier
snippet: "/**\n * replaceRegex\n *\n * Find patterns with regex and replace them.\n *\n * By default, it removes all matches. If you want to replace each match with\n * another string, you can use the double == format to define a replacement in\n * the output modifier, or specify the replace property in the snippet call.\n *\n * Usage:\n * [[*content:replaceRegex=`&uid=(.+)==&uid=`[[+unique_idx]]``]]\n * [[*content:replaceRegex=`^---[\\s\\S]+?---[\\s]+`]] (removes YAML front matter)\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$regex = $modx->getOption('pattern', $scriptProperties, $options);\n$replace = $modx->getOption('replace', $scriptProperties, '');\n\nif (!$input) return '';\n\nif (str_contains($regex, '==')) {\n    $regex = explode('==', $regex);\n    $pattern = $regex[0];\n    $replace = $regex[1];\n} else {\n    $pattern = $regex;\n}\n\nreturn preg_replace('/'.$pattern.'/', $replace, $input);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * replaceRegex
 *
 * Find patterns with regex and replace them.
 *
 * By default, it removes all matches. If you want to replace each match with
 * another string, you can use the double == format to define a replacement in
 * the output modifier, or specify the replace property in the snippet call.
 *
 * Usage:
 * [[*content:replaceRegex=`&uid=(.+)==&uid=`[[+unique_idx]]``]]
 * [[*content:replaceRegex=`^---[\s\S]+?---[\s]+`]] (removes YAML front matter)
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$regex = $modx->getOption('pattern', $scriptProperties, $options);
$replace = $modx->getOption('replace', $scriptProperties, '');

if (!$input) return '';

if (str_contains($regex, '==')) {
    $regex = explode('==', $regex);
    $pattern = $regex[0];
    $replace = $regex[1];
} else {
    $pattern = $regex;
}

return preg_replace('/'.$pattern.'/', $replace, $input);