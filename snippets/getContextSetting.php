id: 91
name: getContextSetting
description: 'Retrieve a specific setting from a context of choice. Useful if you want to "borrow" a setting from another context, e.g. the correct site_url for assets only available in that context.'
category: f_basic
snippet: "/**\n * getContextSetting\n *\n * Useful for retrieving settings from a different context.\n * Used in the Head chunk for always looking for custom css on main domain.\n *\n * @author Bob Ray\n */\n\n$ctx = $modx->getOption('context', $scriptProperties, null);\n$setting = $modx->getOption('setting', $scriptProperties, null);\n\nif ($ctx == null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Context set');\n    return '';\n} elseif ($setting === null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Setting set');\n    return '';\n} else {\n    $csObj = $modx->getObject('modContextSetting',\n        array(\n            'context_key' => $ctx,\n            'key' => $setting\n        )\n    );\n}\n\nif (is_object($csObj)) {\n    return $csObj->get('value');\n} else {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getContextSetting] Context Setting not found');\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.getcontextsetting.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.getcontextsetting.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * getContextSetting\n *\n * Useful for retrieving settings from a different context.\n * Used in the Head chunk for always looking for custom css on main domain.\n *\n * @author Bob Ray\n */\n\n$ctx = $modx->getOption('context', $scriptProperties, null);\n$setting = $modx->getOption('setting', $scriptProperties, null);\n\nif ($ctx == null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Context set');\n    return '';\n} elseif ($setting === null) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getContextSetting] No Setting set');\n    return '';\n} else {\n    $csObj = $modx->getObject('modContextSetting',\n        array(\n            'context_key' => $ctx,\n            'key' => $setting\n        )\n    );\n}\n\nif (is_object($csObj)) {\n    return $csObj->get('value');\n} else {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getContextSetting] Context Setting not found');\n    return '';\n}"

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

if (is_object($csObj)) {
    return $csObj->get('value');
} else {
    $modx->log(modX::LOG_LEVEL_INFO, '[getContextSetting] Context Setting not found');
    return '';
}