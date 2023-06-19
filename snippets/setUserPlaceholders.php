id: 83
name: setUserPlaceholders
description: 'Make any extended fields that are attached to a MODX user available as placeholder.'
category: f_user
snippet: "/**\n * setUserPlaceholders\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$userId = $modx->getOption('userId', $scriptProperties, '');\n\n// Get a specific user\n$user = $modx->getObject('modUser', $userId);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    $profile = $user->getOne('Profile');\n} else {\n    $modx->log(modX::LOG_LEVEL_WARN, '[setUserPlaceholders] User not found in MODX');\n    return '';\n}\n\n// Get extended fields of this user\nif ($profile) {\n    $extended = $profile->get('extended');\n    $modx->toPlaceholders($extended, '');\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[setUserPlaceholders] Could not find profile for user: ' . $user->get('username'));\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:43:"romanesco.setuserplaceholders.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:44:"romanesco.setuserplaceholders.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * setUserPlaceholders
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$userId = $modx->getOption('userId', $scriptProperties, '');

// Get a specific user
$user = $modx->getObject('modUser', $userId);

// Get user profile and fail gracefully if user doesn't exist
if ($user) {
    $profile = $user->getOne('Profile');
} else {
    $modx->log(modX::LOG_LEVEL_WARN, '[setUserPlaceholders] User not found in MODX');
    return '';
}

// Get extended fields of this user
if ($profile) {
    $extended = $profile->get('extended');
    $modx->toPlaceholders($extended, '');
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[setUserPlaceholders] Could not find profile for user: ' . $user->get('username'));
}

return '';