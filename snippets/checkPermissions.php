id: 125
name: checkPermissions
description: 'Check if user is allowed to access the given (or current) context and redirect to unauthorized page if that''s not the case.'
category: f_users
snippet: "/**\n * checkPermissions\n *\n * Check if user has access permissions for a certain context and redirect to\n * unauthorized page if that's not the case.\n */\n\n$context = $modx->getOption('context', $scriptProperties, $modx->context->get('key'));\n$redirectTo = $modx->getOption('redirectTo', $scriptProperties, null);\n\n// If a context is specified, make sure we're in it\nif ($context !== $modx->context->get('key')) {\n    return '';\n}\n\n// Exclude the unauthorized page from any redirects\nif ($modx->resource->get('id') == $modx->getOption('unauthorized_page')) {\n    return '';\n}\n\nif (!$modx->user->hasSessionContext($context)) {\n    if (!empty($redirectTo)) {\n        $redirectParams = !empty($redirectParams) ? $modx->fromJSON($redirectParams) : '';\n        $url = $modx->makeUrl($redirectTo,'',$redirectParams,'full');\n        $modx->sendRedirect($url);\n    } else {\n        $modx->sendUnauthorizedPage();\n    }\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * checkPermissions\n *\n * Check if user has access permissions for a certain context and redirect to\n * unauthorized page if that's not the case.\n */\n\n$context = $modx->getOption('context', $scriptProperties, $modx->context->get('key'));\n$redirectTo = $modx->getOption('redirectTo', $scriptProperties, null);\n\n// If a context is specified, make sure we're in it\nif ($context !== $modx->context->get('key')) {\n    return '';\n}\n\n// Exclude the unauthorized page from any redirects\nif ($modx->resource->get('id') == $modx->getOption('unauthorized_page')) {\n    return '';\n}\n\nif (!$modx->user->hasSessionContext($context)) {\n    if (!empty($redirectTo)) {\n        $redirectParams = !empty($redirectParams) ? $modx->fromJSON($redirectParams) : '';\n        $url = $modx->makeUrl($redirectTo,'',$redirectParams,'full');\n        $modx->sendRedirect($url);\n    } else {\n        $modx->sendUnauthorizedPage();\n    }\n}\n\nreturn '';"

-----


/**
 * checkPermissions
 *
 * Check if user has access permissions for a certain context and redirect to
 * unauthorized page if that's not the case.
 */

$context = $modx->getOption('context', $scriptProperties, $modx->context->get('key'));
$redirectTo = $modx->getOption('redirectTo', $scriptProperties, null);

// If a context is specified, make sure we're in it
if ($context !== $modx->context->get('key')) {
    return '';
}

// Exclude the unauthorized page from any redirects
if ($modx->resource->get('id') == $modx->getOption('unauthorized_page')) {
    return '';
}

if (!$modx->user->hasSessionContext($context)) {
    if (!empty($redirectTo)) {
        $redirectParams = !empty($redirectParams) ? $modx->fromJSON($redirectParams) : '';
        $url = $modx->makeUrl($redirectTo,'',$redirectParams,'full');
        $modx->sendRedirect($url);
    } else {
        $modx->sendUnauthorizedPage();
    }
}

return '';