id: 91
name: getContextSetting
description: 'Retrieve a specific setting from a context of choice. A possible scenario where you might want to "borrow" a setting from another context, is when certain assets are only available in that context. This snippet lets you retrieve the correct site_url.'
category: f_basic
snippet: "/**\n * getContextSetting\n *\n * Useful for retrieving settings from a different context.\n * Used in the Head chunk for always looking for custom css on main domain.\n *\n * @author Bob Ray\n */\n\n$ctx = $modx->getOption('context', $scriptProperties, null);\n$setting = $modx->getOption('setting', $scriptProperties, null);\n\nif ($ctx == null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Context set');\n    return '';\n} elseif ($setting === null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Setting set');\n    return '';\n} else {\n    $csObj = $modx->getObject('modContextSetting',\n        array(\n            'context_key' => $ctx,\n            'key' => $setting\n        )\n    );\n}\n\nif ($csObj) {\n    return $csObj->get('value');\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] Context Setting not found');\n    return '';\n}"
properties: 'a:0:{}'
content: "/**\n * getContextSetting\n *\n * Useful for retrieving settings from a different context.\n * Used in the Head chunk for always looking for custom css on main domain.\n *\n * @author Bob Ray\n */\n\n$ctx = $modx->getOption('context', $scriptProperties, null);\n$setting = $modx->getOption('setting', $scriptProperties, null);\n\nif ($ctx == null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Context set');\n    return '';\n} elseif ($setting === null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Setting set');\n    return '';\n} else {\n    $csObj = $modx->getObject('modContextSetting',\n        array(\n            'context_key' => $ctx,\n            'key' => $setting\n        )\n    );\n}\n\nif ($csObj) {\n    return $csObj->get('value');\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] Context Setting not found');\n    return '';\n}"

-----


/**
 * getContextSetting
 *
 * Useful for retrieving settings from a different context.
 * Used in the Head chunk for always looking for custom css on main domain.
 *
 * @author Bob Ray
 */

$ctx = $modx->getOption('context', $scriptProperties, null);
$setting = $modx->getOption('setting', $scriptProperties, null);

if ($ctx == null) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Context set');
    return '';
} elseif ($setting === null) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Setting set');
    return '';
} else {
    $csObj = $modx->getObject('modContextSetting',
        array(
            'context_key' => $ctx,
            'key' => $setting
        )
    );
}

if ($csObj) {
    return $csObj->get('value');
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] Context Setting not found');
    return '';
}