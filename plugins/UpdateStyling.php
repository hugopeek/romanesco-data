id: 28
name: UpdateStyling
description: 'Fires when theme settings are changed under Configuration. It updates Semantic UI theme.variables and triggers a new SUI build in the background. Requires NPM and EXEC function on the server.'
category: c_configuration
plugincode: "/**\n * UpdateStyling\n *\n * This plugin is activated when certain theme settings are changed in the\n * ClientConfig CMP.\n *\n * It changes some variables used by Semantic UI to generate the CSS, and it\n * triggers a new SUI build in the background. This requires NPM to be available\n * on the server.\n *\n * It also generates favicon images if a logo badge is provided. This relies on\n * a few Gulp dependencies (see package.json) and the Real Favicon service:\n * https://realfavicongenerator.net/favicon/gulp\n *\n * Update May 15, 2020:\n * This plugin is now able to process context-aware configuration settings.\n *\n * Update May 20, 2020:\n * The plugin no longer relies on an assets/css/theme.variables resource to be\n * present in MODX. The settings are directly written to a static file now.\n *\n * @var modX $modx\n * @package romanesco\n */\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'ClientConfig_BeforeCacheUpdate':\n        $corePath = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');\n        $clientConfig = $modx->getService('clientconfig','ClientConfig', $corePath . 'model/clientconfig/', array('core_path' => $corePath));\n        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);\n        $output = array();\n\n        // Get saved values\n        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';\n        $savedSettings = json_decode($savedSettings, true);\n        if (!is_array($savedSettings)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');\n            break;\n        }\n\n        // Get current configuration settings (before save) for active context\n        $currentContext = $savedSettings['context'] ?? '';\n        $currentSettings = $clientConfig->getSettings($currentContext);\n        if ($clientConfig instanceof ClientConfig) {\n            $cacheOptions = array(xPDO::OPT_CACHE_KEY => 'system_settings');\n            $settings = $modx->getCacheManager()->get('clientconfig', $cacheOptions);\n        }\n\n        // Continue with theme related settings only\n        if (!function_exists('filterThemeSettings')) {\n            function filterThemeSettings($settings): array\n            {\n                return array_filter(\n                    $settings,\n                    function ($key) {\n                        if (str_starts_with($key, 'theme_') || str_starts_with($key, 'logo_')) {\n                            return $key;\n                        }\n                        else {\n                            return false;\n                        }\n                    },\n                    ARRAY_FILTER_USE_KEY\n                );\n            }\n        }\n        $currentSettingsTheme = filterThemeSettings($currentSettings);\n        $savedSettingsTheme = filterThemeSettings($savedSettings);\n\n        // Remove leading '/' slash from path values\n        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed\n        $currentSettingsTheme['logo_path'] = ltrim($currentSettingsTheme['logo_path'],'/') ?? '';\n        $currentSettingsTheme['logo_inverted_path'] = ltrim($currentSettingsTheme['logo_inverted_path'],'/') ?? '';\n        $currentSettingsTheme['logo_badge_path'] = ltrim($currentSettingsTheme['logo_badge_path'],'/') ?? '';\n        $currentSettingsTheme['logo_badge_inverted_path'] = ltrim($currentSettingsTheme['logo_badge_inverted_path'],'/') ?? '';\n\n        // Add media source to saved paths\n        if (isset($savedSettingsTheme['logo_path'])) {\n            $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);\n        }\n        if (isset($savedSettingsTheme['logo_inverted_path'])) {\n            $savedSettingsTheme['logo_inverted_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_inverted_path']);\n        }\n        if (isset($savedSettingsTheme['logo_badge_path'])) {\n            $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);\n        }\n        if (isset($savedSettingsTheme['logo_badge_inverted_path'])) {\n            $savedSettingsTheme['logo_badge_inverted_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_inverted_path']);\n        }\n\n        // Compare saved settings to current settings\n        $updatedSettings = array_diff_assoc($savedSettingsTheme, $currentSettingsTheme);\n        $modx->log(modX::LOG_LEVEL_INFO, '[UpdateStyling] ' . print_r($updatedSettings, true));\n\n        // Regenerate styling elements if theme settings were updated or deleted\n        if ($updatedSettings) {\n            $corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n            $romanesco = $modx->getService('romanesco','Romanesco', $corePath . 'model/romanescobackyard/', array('core_path' => $corePath));\n            if (!($romanesco instanceof Romanesco)) {\n                $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n                break;\n            }\n\n            // Clear cache, to ensure build process uses the latest values\n            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));\n\n            // Grab variables after cache rebuild\n            $latestSettings = $clientConfig->getSettings($currentContext);\n\n            // Generate theme.variables file\n            if (!$romanesco->generateThemeVariables($latestSettings, $currentContext)) {\n                $modx->log(modX::LOG_LEVEL_ERROR, \"[Romanesco] Could not generate theme.variables for context $currentContext\");\n                break;\n            }\n\n            // Generate custom CSS for this context\n            if (!$romanesco->generateCustomCSS($currentContext, 1)) {\n                $modx->log(modX::LOG_LEVEL_ERROR, \"[Romanesco] Could not generate custom CSS for context $currentContext\");\n                break;\n            }\n\n            // Generate favicon if a new logo image was provided\n            if (isset($updatedSettings['logo_badge_path'])) {\n                if (!$romanesco->generateFavicons($latestSettings)) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, \"[Romanesco] Could not generate favicon for context $currentContext\");\n                    break;\n                }\n            }\n\n            // Prevent favicons from being loaded if badge image is not present at this point\n            if (!isset($latestSettings['logo_badge_path'])) {\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', '');\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Clear cache\n            $modx->cacheManager->refresh();\n        }\n\n        // Report any validation errors in log\n        if (array_filter($output)) {\n            $errorMsg = '';\n            foreach ($output as $line) {\n                $errorMsg .= \"\\n\" . $line;\n            }\n            return (\" Report: \" . $errorMsg);\n        }\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.updatestyling.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * UpdateStyling
 *
 * This plugin is activated when certain theme settings are changed in the
 * ClientConfig CMP.
 *
 * It changes some variables used by Semantic UI to generate the CSS, and it
 * triggers a new SUI build in the background. This requires NPM to be available
 * on the server.
 *
 * It also generates favicon images if a logo badge is provided. This relies on
 * a few Gulp dependencies (see package.json) and the Real Favicon service:
 * https://realfavicongenerator.net/favicon/gulp
 *
 * Update May 15, 2020:
 * This plugin is now able to process context-aware configuration settings.
 *
 * Update May 20, 2020:
 * The plugin no longer relies on an assets/css/theme.variables resource to be
 * present in MODX. The settings are directly written to a static file now.
 *
 * @var modX $modx
 * @package romanesco
 */

$eventName = $modx->event->name;

switch($eventName) {
    case 'ClientConfig_BeforeCacheUpdate':
        $corePath = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');
        $clientConfig = $modx->getService('clientconfig','ClientConfig', $corePath . 'model/clientconfig/', array('core_path' => $corePath));
        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);
        $output = array();

        // Get saved values
        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';
        $savedSettings = json_decode($savedSettings, true);
        if (!is_array($savedSettings)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');
            break;
        }

        // Get current configuration settings (before save) for active context
        $currentContext = $savedSettings['context'] ?? '';
        $currentSettings = $clientConfig->getSettings($currentContext);
        if ($clientConfig instanceof ClientConfig) {
            $cacheOptions = array(xPDO::OPT_CACHE_KEY => 'system_settings');
            $settings = $modx->getCacheManager()->get('clientconfig', $cacheOptions);
        }

        // Continue with theme related settings only
        if (!function_exists('filterThemeSettings')) {
            function filterThemeSettings($settings): array
            {
                return array_filter(
                    $settings,
                    function ($key) {
                        if (str_starts_with($key, 'theme_') || str_starts_with($key, 'logo_')) {
                            return $key;
                        }
                        else {
                            return false;
                        }
                    },
                    ARRAY_FILTER_USE_KEY
                );
            }
        }
        $currentSettingsTheme = filterThemeSettings($currentSettings);
        $savedSettingsTheme = filterThemeSettings($savedSettings);

        // Remove leading '/' slash from path values
        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed
        $currentSettingsTheme['logo_path'] = ltrim($currentSettingsTheme['logo_path'],'/') ?? '';
        $currentSettingsTheme['logo_inverted_path'] = ltrim($currentSettingsTheme['logo_inverted_path'],'/') ?? '';
        $currentSettingsTheme['logo_badge_path'] = ltrim($currentSettingsTheme['logo_badge_path'],'/') ?? '';
        $currentSettingsTheme['logo_badge_inverted_path'] = ltrim($currentSettingsTheme['logo_badge_inverted_path'],'/') ?? '';

        // Add media source to saved paths
        if (isset($savedSettingsTheme['logo_path'])) {
            $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);
        }
        if (isset($savedSettingsTheme['logo_inverted_path'])) {
            $savedSettingsTheme['logo_inverted_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_inverted_path']);
        }
        if (isset($savedSettingsTheme['logo_badge_path'])) {
            $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);
        }
        if (isset($savedSettingsTheme['logo_badge_inverted_path'])) {
            $savedSettingsTheme['logo_badge_inverted_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_inverted_path']);
        }

        // Compare saved settings to current settings
        $updatedSettings = array_diff_assoc($savedSettingsTheme, $currentSettingsTheme);
        $modx->log(modX::LOG_LEVEL_INFO, '[UpdateStyling] ' . print_r($updatedSettings, true));

        // Regenerate styling elements if theme settings were updated or deleted
        if ($updatedSettings) {
            $corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
            $romanesco = $modx->getService('romanesco','Romanesco', $corePath . 'model/romanescobackyard/', array('core_path' => $corePath));
            if (!($romanesco instanceof Romanesco)) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
                break;
            }

            // Clear cache, to ensure build process uses the latest values
            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));

            // Grab variables after cache rebuild
            $latestSettings = $clientConfig->getSettings($currentContext);

            // Generate theme.variables file
            if (!$romanesco->generateThemeVariables($latestSettings, $currentContext)) {
                $modx->log(modX::LOG_LEVEL_ERROR, "[Romanesco] Could not generate theme.variables for context $currentContext");
                break;
            }

            // Generate custom CSS for this context
            if (!$romanesco->generateCustomCSS($currentContext, 1)) {
                $modx->log(modX::LOG_LEVEL_ERROR, "[Romanesco] Could not generate custom CSS for context $currentContext");
                break;
            }

            // Generate favicon if a new logo image was provided
            if (isset($updatedSettings['logo_badge_path'])) {
                if (!$romanesco->generateFavicons($latestSettings)) {
                    $modx->log(modX::LOG_LEVEL_ERROR, "[Romanesco] Could not generate favicon for context $currentContext");
                    break;
                }
            }

            // Prevent favicons from being loaded if badge image is not present at this point
            if (!isset($latestSettings['logo_badge_path'])) {
                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));
                if ($version) {
                    $version->set('value', '');
                    $version->save();
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');
                }
            }

            // Clear cache
            $modx->cacheManager->refresh();
        }

        // Report any validation errors in log
        if (array_filter($output)) {
            $errorMsg = '';
            foreach ($output as $line) {
                $errorMsg .= "\n" . $line;
            }
            return (" Report: " . $errorMsg);
        }

        break;
}