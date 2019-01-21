id: 83
name: setUserPlaceholders
description: 'Make any extended fields that are attached to a MODX user available as placeholder.'
category: f_framework
snippet: "/* setUserPlaceholders snippet */\n$userId = $modx->getOption('userId', $scriptProperties, '');\n\n// Get a specific user\n$user = $modx->getObject('modUser', $userId);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    $profile = $user->getOne('Profile');\n} else {\n    $modx->log(modX::LOG_LEVEL_WARN, '[setUserPlaceholders] User not found in MODX');\n    return '';\n}\n\n// Get extended fields of this user\nif ($profile) {\n    $extended = $profile->get('extended');\n    $modx->toPlaceholders($extended, '');\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[setUserPlaceholders] Could not find profile for user: ' . $user->get('username'));\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "/* setUserPlaceholders snippet */\n$userId = $modx->getOption('userId', $scriptProperties, '');\n\n// Get a specific user\n$user = $modx->getObject('modUser', $userId);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    $profile = $user->getOne('Profile');\n} else {\n    $modx->log(modX::LOG_LEVEL_WARN, '[setUserPlaceholders] User not found in MODX');\n    return '';\n}\n\n// Get extended fields of this user\nif ($profile) {\n    $extended = $profile->get('extended');\n    $modx->toPlaceholders($extended, '');\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[setUserPlaceholders] Could not find profile for user: ' . $user->get('username'));\n}\n\nreturn '';"

-----


/* setUserPlaceholders snippet */
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