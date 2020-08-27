id: 25
name: setResourceIDs
description: 'Looks for resource IDs of key Romanesco pages that were built by the Romanesco Backyard package. Updates system setting with corresponding ID if resource is found. Disabled by default.'
category: c_settings
plugincode: "/**\n * setResourceIDs\n *\n * This plugin looks for resource IDs of Romanesco pages that were built by the\n * Romanesco Backyard package. When a resource is found, the referring system\n * setting is updated with the corresponding ID.\n *\n * It's deactivated by default, because the Backyard package includes a resolver\n * that does the same thing.\n *\n * @var modX $modx\n *\n * @package romanesco\n */\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'OnDocFormSave':\n\n        //$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n        //$assetsPath = $modx->getOption('assets_path');\n\n        if (!function_exists('setResourceID')) {\n            function setResourceID($systemSetting, $contextKey, $alias)\n            {\n                global $modx;\n\n                // Get the resource\n                $query = $modx->newQuery('modResource');\n                $query->where(array(\n                    'context_key' => $contextKey,\n                    'alias' => $alias,\n                ));\n                $query->select('id');\n                $resourceID = $modx->getValue($query->prepare());\n\n                if (!$resourceID) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);\n                    return;\n                }\n\n                // Update system setting\n                $setting = $modx->getObject('modSystemSetting', array('key' => $systemSetting));\n\n                if ($setting) {\n                    $setting->set('value', $resourceID);\n                    $setting->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find system setting with key: ' . $systemSetting);\n                }\n            }\n        }\n\n        if (!function_exists('setContextSetting')) {\n            function setContextSetting($contextSetting, $contextKey, $alias)\n            {\n                global $modx;\n\n                // Get the resource\n                $query = $modx->newQuery('modResource');\n                $query->where(array(\n                    'context_key' => $contextKey,\n                    'alias' => $alias,\n                ));\n                $query->select('id');\n                $resourceID = $modx->getValue($query->prepare());\n\n                if (!$resourceID) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);\n                    return;\n                }\n\n                // Update context setting\n                $setting = $modx->getObject('modContextSetting', array(\n                    'context_key' => $contextKey,\n                    'key' => $contextSetting\n                ));\n\n                if ($setting) {\n                    $setting->set('value', $resourceID);\n                    $setting->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find context setting with key: ' . $contextSetting);\n                }\n            }\n        }\n\n        // Find resources and set correct IDs\n        setResourceID('romanesco.footer_container_id', 'global','footers');\n        setResourceID('romanesco.cta_container_id', 'global','call-to-actions');\n        setResourceID('romanesco.global_backgrounds_id', 'global','backgrounds');\n        setResourceID('formblocks.container_id', 'global','forms');\n        setResourceID('romanesco.dashboard_id', 'hub','dashboard');\n        setResourceID('romanesco.pattern_container_id', 'hub','patterns');\n        setResourceID('romanesco.backyard_container_id', 'hub','backyard');\n\n        // Set site_start for Project Hub context\n        setContextSetting('site_start', 'hub','dashboard');\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n * setResourceIDs\n *\n * This plugin looks for resource IDs of Romanesco pages that were built by the\n * Romanesco Backyard package. When a resource is found, the referring system\n * setting is updated with the corresponding ID.\n *\n * It's deactivated by default, because the Backyard package includes a resolver\n * that does the same thing.\n *\n * @var modX $modx\n *\n * @package romanesco\n */\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'OnDocFormSave':\n\n        //$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n        //$assetsPath = $modx->getOption('assets_path');\n\n        if (!function_exists('setResourceID')) {\n            function setResourceID($systemSetting, $contextKey, $alias)\n            {\n                global $modx;\n\n                // Get the resource\n                $query = $modx->newQuery('modResource');\n                $query->where(array(\n                    'context_key' => $contextKey,\n                    'alias' => $alias,\n                ));\n                $query->select('id');\n                $resourceID = $modx->getValue($query->prepare());\n\n                if (!$resourceID) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);\n                    return;\n                }\n\n                // Update system setting\n                $setting = $modx->getObject('modSystemSetting', array('key' => $systemSetting));\n\n                if ($setting) {\n                    $setting->set('value', $resourceID);\n                    $setting->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find system setting with key: ' . $systemSetting);\n                }\n            }\n        }\n\n        if (!function_exists('setContextSetting')) {\n            function setContextSetting($contextSetting, $contextKey, $alias)\n            {\n                global $modx;\n\n                // Get the resource\n                $query = $modx->newQuery('modResource');\n                $query->where(array(\n                    'context_key' => $contextKey,\n                    'alias' => $alias,\n                ));\n                $query->select('id');\n                $resourceID = $modx->getValue($query->prepare());\n\n                if (!$resourceID) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);\n                    return;\n                }\n\n                // Update context setting\n                $setting = $modx->getObject('modContextSetting', array(\n                    'context_key' => $contextKey,\n                    'key' => $contextSetting\n                ));\n\n                if ($setting) {\n                    $setting->set('value', $resourceID);\n                    $setting->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find context setting with key: ' . $contextSetting);\n                }\n            }\n        }\n\n        // Find resources and set correct IDs\n        setResourceID('romanesco.footer_container_id', 'global','footers');\n        setResourceID('romanesco.cta_container_id', 'global','call-to-actions');\n        setResourceID('romanesco.global_backgrounds_id', 'global','backgrounds');\n        setResourceID('formblocks.container_id', 'global','forms');\n        setResourceID('romanesco.dashboard_id', 'hub','dashboard');\n        setResourceID('romanesco.pattern_container_id', 'hub','patterns');\n        setResourceID('romanesco.backyard_container_id', 'hub','backyard');\n\n        // Set site_start for Project Hub context\n        setContextSetting('site_start', 'hub','dashboard');\n\n        break;\n}"

-----


/**
 * setResourceIDs
 *
 * This plugin looks for resource IDs of Romanesco pages that were built by the
 * Romanesco Backyard package. When a resource is found, the referring system
 * setting is updated with the corresponding ID.
 *
 * It's deactivated by default, because the Backyard package includes a resolver
 * that does the same thing.
 *
 * @var modX $modx
 *
 * @package romanesco
 */

$eventName = $modx->event->name;

switch($eventName) {
    case 'OnDocFormSave':

        //$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
        //$assetsPath = $modx->getOption('assets_path');

        if (!function_exists('setResourceID')) {
            function setResourceID($systemSetting, $contextKey, $alias)
            {
                global $modx;

                // Get the resource
                $query = $modx->newQuery('modResource');
                $query->where(array(
                    'context_key' => $contextKey,
                    'alias' => $alias,
                ));
                $query->select('id');
                $resourceID = $modx->getValue($query->prepare());

                if (!$resourceID) {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);
                    return;
                }

                // Update system setting
                $setting = $modx->getObject('modSystemSetting', array('key' => $systemSetting));

                if ($setting) {
                    $setting->set('value', $resourceID);
                    $setting->save();
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find system setting with key: ' . $systemSetting);
                }
            }
        }

        if (!function_exists('setContextSetting')) {
            function setContextSetting($contextSetting, $contextKey, $alias)
            {
                global $modx;

                // Get the resource
                $query = $modx->newQuery('modResource');
                $query->where(array(
                    'context_key' => $contextKey,
                    'alias' => $alias,
                ));
                $query->select('id');
                $resourceID = $modx->getValue($query->prepare());

                if (!$resourceID) {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find resource ID for: ' . $alias);
                    return;
                }

                // Update context setting
                $setting = $modx->getObject('modContextSetting', array(
                    'context_key' => $contextKey,
                    'key' => $contextSetting
                ));

                if ($setting) {
                    $setting->set('value', $resourceID);
                    $setting->save();
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find context setting with key: ' . $contextSetting);
                }
            }
        }

        // Find resources and set correct IDs
        setResourceID('romanesco.footer_container_id', 'global','footers');
        setResourceID('romanesco.cta_container_id', 'global','call-to-actions');
        setResourceID('romanesco.global_backgrounds_id', 'global','backgrounds');
        setResourceID('formblocks.container_id', 'global','forms');
        setResourceID('romanesco.dashboard_id', 'hub','dashboard');
        setResourceID('romanesco.pattern_container_id', 'hub','patterns');
        setResourceID('romanesco.backyard_container_id', 'hub','backyard');

        // Set site_start for Project Hub context
        setContextSetting('site_start', 'hub','dashboard');

        break;
}