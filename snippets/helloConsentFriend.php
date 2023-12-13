id: 168
name: helloConsentFriend
description: 'Checks whether ConsentFriend is installed, enabled and has given service activated.'
category: f_framework
snippet: "/**\n * helloConsentFriend\n *\n * Ask our friend if he's on duty and (optionally) if given service is active.\n * Returns true or false by default, or any output forwarded through the true\n * and false parameters.\n *\n * When true, it means that ConsentFriend should handle rendering of the code\n * snippet. So you can use this in conditionals to prevent these code snippets\n * from being parsed without consent.\n *\n * [[helloConsentFriend?\n *     &service=`youtube`\n *     &true=`You need to give your consent in order to play YouTube videos.`\n *     &false=`[[$videoYoutube? &video=`123`]]`\n * ]]\n *\n * If no service is given, it will only evaluate if the ConsentFriend plugin is\n * active, and return true or false accordingly.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$service = $modx->getOption('service', $scriptProperties, '');\n$true = $modx->getOption('true', $scriptProperties, true);\n$false = $modx->getOption('false', $scriptProperties, false);\n\n// Exit if plugin is not available, or not enabled\n$plugin = $modx->getObject('modPlugin', [\n    'name' => 'ConsentFriend'\n]);\nif (!is_object($plugin) || $plugin->get('disabled')) {\n    $modx->log(modX::LOG_LEVEL_INFO, 'ConsentFriend not present!');\n    return $false;\n}\n\n// ConsentFriend is present\n$result = true;\n\n// Check if service is active\nif ($service) {\n    $query = $modx->newQuery('ConsentfriendServices', [\n        'name' => $service\n    ]);\n    $query->select('active');\n    $result = (bool)$modx->getValue($query->prepare());\n}\n\n// Output either to placeholder, or directly\n$output = $result ? $true : $false;\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:42:"romanesco.helloconsentfriend.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:43:"romanesco.helloconsentfriend.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * helloConsentFriend
 *
 * Ask our friend if he's on duty and (optionally) if given service is active.
 * Returns true or false by default, or any output forwarded through the true
 * and false parameters.
 *
 * When true, it means that ConsentFriend should handle rendering of the code
 * snippet. So you can use this in conditionals to prevent these code snippets
 * from being parsed without consent.
 *
 * [[helloConsentFriend?
 *     &service=`youtube`
 *     &true=`You need to give your consent in order to play YouTube videos.`
 *     &false=`[[$videoYoutube? &video=`123`]]`
 * ]]
 *
 * If no service is given, it will only evaluate if the ConsentFriend plugin is
 * active, and return true or false accordingly.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$service = $modx->getOption('service', $scriptProperties, '');
$true = $modx->getOption('true', $scriptProperties, true);
$false = $modx->getOption('false', $scriptProperties, false);

// Exit if plugin is not available, or not enabled
$plugin = $modx->getObject('modPlugin', [
    'name' => 'ConsentFriend'
]);
if (!is_object($plugin) || $plugin->get('disabled')) {
    $modx->log(modX::LOG_LEVEL_INFO, 'ConsentFriend not present!');
    return $false;
}

// ConsentFriend is present
$result = true;

// Check if service is active
if ($service) {
    $query = $modx->newQuery('ConsentfriendServices', [
        'name' => $service
    ]);
    $query->select('active');
    $result = (bool)$modx->getValue($query->prepare());
}

// Output either to placeholder, or directly
$output = $result ? $true : $false;
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}

return $output;