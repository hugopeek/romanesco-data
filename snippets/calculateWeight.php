id: 174
name: calculateWeight
description: 'Set size value by comparing input number to a median number.'
category: f_modifier
snippet: "/**\n * calculateWeight snippet\n *\n * Match input number with a size attribute.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$median = $modx->getOption('median', $scriptProperties, $options);\n\nif (!$input) return '';\n\n$input = (int)$input;\n$median = (int)$median ?: 5;\n$output = 'mini';\n\nif ($input > ($median / 3)) $output = 'tiny';\nif ($input > ($median / 2)) $output = 'small';\nif ($input > ($median / 1)) $output = 'medium';\nif ($input > ($median * 1.5)) $output = 'large';\nif ($input > ($median * 2)) $output = 'big';\nif ($input > ($median * 2.5)) $output = 'huge';\nif ($input > ($median * 3)) $output = 'massive';\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * calculateWeight snippet
 *
 * Match input number with a size attribute.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$median = $modx->getOption('median', $scriptProperties, $options);

if (!$input) return '';

$input = (int)$input;
$median = (int)$median ?: 5;
$output = 'mini';

if ($input > ($median / 3)) $output = 'tiny';
if ($input > ($median / 2)) $output = 'small';
if ($input > ($median / 1)) $output = 'medium';
if ($input > ($median * 1.5)) $output = 'large';
if ($input > ($median * 2)) $output = 'big';
if ($input > ($median * 2.5)) $output = 'huge';
if ($input > ($median * 3)) $output = 'massive';

return $output;