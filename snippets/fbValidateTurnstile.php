id: 180
name: fbValidateTurnstile
description: 'Server side validation of Cloudflare Turnstile token.'
category: f_fb_hook
snippet: "/**\n * fbValidateTurnstile\n *\n * Server side validation of Turnstile token.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var FormIt $formit\n * @var fiHooks $hook\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\n\n$secret = $modx->getOption('formblocks.turnstile_secret_key', $scriptProperties, '');\n$token = $hook->getValue('cf-turnstile-response') ?? $_POST['cf-turnstile-response'];\n$remoteip = $_SERVER['HTTP_CF_CONNECTING_IP'] ??\n    $_SERVER['HTTP_X_FORWARDED_FOR'] ??\n    $_SERVER['REMOTE_ADDR'];\n\n$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';\n$data = [\n    'secret' => $secret,\n    'response' => $token\n];\n\nif ($remoteip) {\n    $data['remoteip'] = $remoteip;\n}\n\n$options = [\n    'http' => [\n        'header' => \"Content-type: application/x-www-form-urlencoded\\r\\n\",\n        'method' => 'POST',\n        'content' => http_build_query($data)\n    ]\n];\n\n$context = stream_context_create($options);\n$response = file_get_contents($url, false, $context);\n\nif (!$response) {\n    $response = ['success' => false, 'error-codes' => ['internal-error']];\n} else {\n    $response = json_decode($response, true);\n}\n\nif (isset($response['success']) && !$response['success']) {\n    $errors = implode(', ', $response['error-codes']);\n    $modx->log(modX::LOG_LEVEL_ERROR, \"Turnstile failed to validate: $errors\");\n    $hook->addError('turnstile', \"Cloudflare failed to validate: $errors.\");\n    return false;\n}\n\nreturn true;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbValidateTurnstile
 *
 * Server side validation of Turnstile token.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var FormIt $formit
 * @var fiHooks $hook
 *
 * @package romanesco
 */

use MODX\Revolution\modX;

$secret = $modx->getOption('formblocks.turnstile_secret_key', $scriptProperties, '');
$token = $hook->getValue('cf-turnstile-response') ?? $_POST['cf-turnstile-response'];
$remoteip = $_SERVER['HTTP_CF_CONNECTING_IP'] ??
    $_SERVER['HTTP_X_FORWARDED_FOR'] ??
    $_SERVER['REMOTE_ADDR'];

$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
$data = [
    'secret' => $secret,
    'response' => $token
];

if ($remoteip) {
    $data['remoteip'] = $remoteip;
}

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if (!$response) {
    $response = ['success' => false, 'error-codes' => ['internal-error']];
} else {
    $response = json_decode($response, true);
}

if (isset($response['success']) && !$response['success']) {
    $errors = implode(', ', $response['error-codes']);
    $modx->log(modX::LOG_LEVEL_ERROR, "Turnstile failed to validate: $errors");
    $hook->addError('turnstile', "Cloudflare failed to validate: $errors.");
    return false;
}

return true;