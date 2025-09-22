id: 126
name: clipString
description: 'Trim the edges of a string. The given value represents the number of characters that will be clipped. If the value is negative, they will be clipped from the end of the string.'
category: f_modifier
snippet: "/**\n * clipString\n *\n * Trim a certain amount of characters from the edges of a string.\n *\n * If a negative value is used, this number of characters will be clipped from\n * the end. Otherwise, they are clipped from the start of the string.\n *\n * If no value is given, whitespace is trimmed from the edges.\n *\n * Usage examples:\n *\n * [[*your_tv:clipString=`-1`]]\n * (if the value of your_tv is 'https', this will return 'http')\n *\n * [[clipString?\n *     &input=`[[+some_string]]`\n *     &clip=`1`\n * ]]\n * (if your string is 'your website', this will return 'our website')\n *\n * You can also clip both edges:\n *\n * [[*your_tv:clipString=`8`:clipString=`-1`]]\n * (if your_tv is 'https://your_website/', this will return 'your_website')\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$clip = (int) $modx->getOption('clip', $scriptProperties, $options);\n\n// Output filters are also processed when the input is empty, so check for that\nif ($input == '') { return ''; }\n\n// Only trim whitespace if clip is not defined\nif (!$clip) {\n    return trim($input);\n}\n\n// Decide whether to clip the start or end of the string\nif ($clip < 0) {\n    return mb_substr($input, 0, $clip);\n} else {\n    return mb_substr($input, $clip);\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * clipString
 *
 * Trim a certain amount of characters from the edges of a string.
 *
 * If a negative value is used, this number of characters will be clipped from
 * the end. Otherwise, they are clipped from the start of the string.
 *
 * If no value is given, whitespace is trimmed from the edges.
 *
 * Usage examples:
 *
 * [[*your_tv:clipString=`-1`]]
 * (if the value of your_tv is 'https', this will return 'http')
 *
 * [[clipString?
 *     &input=`[[+some_string]]`
 *     &clip=`1`
 * ]]
 * (if your string is 'your website', this will return 'our website')
 *
 * You can also clip both edges:
 *
 * [[*your_tv:clipString=`8`:clipString=`-1`]]
 * (if your_tv is 'https://your_website/', this will return 'your_website')
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$clip = (int) $modx->getOption('clip', $scriptProperties, $options);

// Output filters are also processed when the input is empty, so check for that
if ($input == '') { return ''; }

// Only trim whitespace if clip is not defined
if (!$clip) {
    return trim($input);
}

// Decide whether to clip the start or end of the string
if ($clip < 0) {
    return mb_substr($input, 0, $clip);
} else {
    return mb_substr($input, $clip);
}