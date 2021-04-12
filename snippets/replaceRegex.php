id: 156
name: replaceRegex
description: 'Find patterns with regex and replace them. By default, it removes all matches. If you want to replace each match with something else, you have to use a regular snippet call.'
category: f_modifiers
snippet: "/**\n * replaceRegex\n *\n * Find patterns with regex and replace them.\n *\n * By default, it removes all matches. If you want to replace each match with\n * something else, you have to use a regular snippet call.\n *\n * @example [[*content:replaceRegex=`^---[\\s\\S]+?---[\\s]+`]]\n * (removes YAML front matter)\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$regex = $modx->getOption('pattern', $scriptProperties, $options);\n$replace = $modx->getOption('replacement', $scriptProperties, '');\nif ($input) {\n    return preg_replace('/' . $regex . '/', $replace, $input);\n}\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.replaceregex.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.replaceregex.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * replaceRegex\n *\n * Find patterns with regex and replace them.\n *\n * By default, it removes all matches. If you want to replace each match with\n * something else, you have to use a regular snippet call.\n *\n * @example [[*content:replaceRegex=`^---[\\s\\S]+?---[\\s]+`]]\n * (removes YAML front matter)\n *\n * @var modX $modx\n * @var array $scriptProperties;\n * @var string $input;\n * @var string $options;\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$regex = $modx->getOption('pattern', $scriptProperties, $options);\n$replace = $modx->getOption('replacement', $scriptProperties, '');\nif ($input) {\n    return preg_replace('/' . $regex . '/', $replace, $input);\n}\nreturn '';"

-----


/**
 * replaceRegex
 *
 * Find patterns with regex and replace them.
 *
 * By default, it removes all matches. If you want to replace each match with
 * something else, you have to use a regular snippet call.
 *
 * @example [[*content:replaceRegex=`^---[\s\S]+?---[\s]+`]]
 * (removes YAML front matter)
 *
 * @var modX $modx
 * @var array $scriptProperties;
 * @var string $input;
 * @var string $options;
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$regex = $modx->getOption('pattern', $scriptProperties, $options);
$replace = $modx->getOption('replacement', $scriptProperties, '');
if ($input) {
    return preg_replace('/' . $regex . '/', $replace, $input);
}
return '';