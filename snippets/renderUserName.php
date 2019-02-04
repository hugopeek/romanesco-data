id: 138
name: renderUserName
description: 'Gets user by ID and returns the username. Mainly intended as snippet renderer for Collections.'
category: f_users
snippet: "// Get a specific user\n$input = $modx->getOption('input', $scriptProperties, '');\n$user = $modx->getObject('modUser', $input);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    return $user->get('username');\n} else {\n    return '';\n}"
properties: 'a:0:{}'
content: "// Get a specific user\n$input = $modx->getOption('input', $scriptProperties, '');\n$user = $modx->getObject('modUser', $input);\n\n// Get user profile and fail gracefully if user doesn't exist\nif ($user) {\n    return $user->get('username');\n} else {\n    return '';\n}"

-----


// Get a specific user
$input = $modx->getOption('input', $scriptProperties, '');
$user = $modx->getObject('modUser', $input);

// Get user profile and fail gracefully if user doesn't exist
if ($user) {
    return $user->get('username');
} else {
    return '';
}