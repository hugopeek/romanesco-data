id: 179
name: formatNumber
description: 'Add decimal and thousands separator to number string. The appropriate separation characters are determined by the locale setting. Non-numeric input is ignored and returned as-is.'
category: f_modifier
snippet: "/**\n * formatNumber\n *\n * Add decimal and thousands separator to number string.\n * The appropriate separation characters are determined by the locale setting.\n *\n * Only works on numeric strings. If input is not numeric, it is returned as-is.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$locale = $modx->getOption('locale') ?: 'en_US';\nif (is_numeric($input)) {\n    $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);\n    return $formatter->format((float)$input);\n}\nreturn $input;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * formatNumber
 *
 * Add decimal and thousands separator to number string.
 * The appropriate separation characters are determined by the locale setting.
 *
 * Only works on numeric strings. If input is not numeric, it is returned as-is.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$locale = $modx->getOption('locale') ?: 'en_US';
if (is_numeric($input)) {
    $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
    return $formatter->format((float)$input);
}
return $input;