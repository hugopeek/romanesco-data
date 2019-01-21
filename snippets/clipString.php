id: 126
name: clipString
description: 'Trim the edges of a string. The given value represents the number of characters that will be clipped. If the value is negative, they will be clipped from the end of the string.'
category: f_modifiers
snippet: "/**\n * clipString\n *\n * Trim the edges of a string.\n *\n * If a negative value is used, this number of characters will be clipped from\n * the end. Otherwise, they are clipped from the start of the string.\n *\n * Usage examples:\n *\n * [[*your_tv:clipString=`-1`]]\n * (if the value of your_tv is 'https', this will return 'http')\n *\n * [[clipString?\n *     &input=`[[+some_string]]`\n *     &clip=`1`\n * ]]\n * (if your string is 'your website', this will return 'our website')\n *\n * You can also clip both edges:\n *\n * [[*your_tv:clipString=`8`:clipString=`-1`]]\n * (if your_tv is 'https://your_website/', this will return 'your_website')\n *\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$clip = $modx->getOption('clip', $scriptProperties, $options);\n\n// Output filters are also processed when the input is empty, so check for that\nif ($input == '') { return ''; }\n\n// Decide whether to clip the start or end of the string\nif ($clip < 0) {\n    return mb_substr($input, 0, $clip);\n} else {\n    return mb_substr($input, $clip);\n}"
properties: 'a:0:{}'
content: "/**\n * clipString\n *\n * Trim the edges of a string.\n *\n * If a negative value is used, this number of characters will be clipped from\n * the end. Otherwise, they are clipped from the start of the string.\n *\n * Usage examples:\n *\n * [[*your_tv:clipString=`-1`]]\n * (if the value of your_tv is 'https', this will return 'http')\n *\n * [[clipString?\n *     &input=`[[+some_string]]`\n *     &clip=`1`\n * ]]\n * (if your string is 'your website', this will return 'our website')\n *\n * You can also clip both edges:\n *\n * [[*your_tv:clipString=`8`:clipString=`-1`]]\n * (if your_tv is 'https://your_website/', this will return 'your_website')\n *\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$clip = $modx->getOption('clip', $scriptProperties, $options);\n\n// Output filters are also processed when the input is empty, so check for that\nif ($input == '') { return ''; }\n\n// Decide whether to clip the start or end of the string\nif ($clip < 0) {\n    return mb_substr($input, 0, $clip);\n} else {\n    return mb_substr($input, $clip);\n}"

-----


/**
 * clipString
 *
 * Trim the edges of a string.
 *
 * If a negative value is used, this number of characters will be clipped from
 * the end. Otherwise, they are clipped from the start of the string.
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
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$clip = $modx->getOption('clip', $scriptProperties, $options);

// Output filters are also processed when the input is empty, so check for that
if ($input == '') { return ''; }

// Decide whether to clip the start or end of the string
if ($clip < 0) {
    return mb_substr($input, 0, $clip);
} else {
    return mb_substr($input, $clip);
}