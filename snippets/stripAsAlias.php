id: 66
name: stripAsAlias
description: 'Turn input into lowercase-hyphen-separated-alias-format and strip unwanted special characters. Useful for creating anchor links based on headings, for example.'
category: f_modifier
snippet: "/**\n * stripAsAlias\n *\n * Turn input into lowercase-hyphen-separated-alias-format and strip unwanted\n * special characters. Useful for creating anchor links based on headings, for\n * example.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\nreturn $modx->filterPathSegment($input, [\n    'friendly_alias_restrict_chars' => 'alphanumeric'\n]);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * stripAsAlias
 *
 * Turn input into lowercase-hyphen-separated-alias-format and strip unwanted
 * special characters. Useful for creating anchor links based on headings, for
 * example.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

return $modx->filterPathSegment($input, [
    'friendly_alias_restrict_chars' => 'alphanumeric'
]);