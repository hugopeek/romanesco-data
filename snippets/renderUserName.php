id: 138
name: renderUserName
description: 'Gets user by ID and returns the username. Mainly intended as snippet renderer for Collections, but can be used independently or as output modifier too.'
category: f_user
snippet: "/**\n * renderUserName\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n// Get a specific user\n$id = $modx->getOption('id', $scriptProperties, $input);\n$user = $modx->getObject('modUser', $id);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    return $user->get('username');\n} else {\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderUserName
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

// Get a specific user
$id = $modx->getOption('id', $scriptProperties, $input);
$user = $modx->getObject('modUser', $id);

// Get user profile and fail gracefully if user doesn't exist
if ($user) {
    return $user->get('username');
} else {
    return '';
}