id: 27
name: updateStyling
category: c_settings
plugincode: "// Check if exec function is available on the server\nif(@exec('echo EXEC') !== 'EXEC'){\n    $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Exec function not available');\n    return false;\n}\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'ClientConfig_ConfigChange':\n        $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');\n        $path .= 'model/clientconfig/';\n        $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);\n        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);\n\n        // Get current configuration settings (before save)\n        $currentSettings = $clientConfig->getSettings();\n\n        // Get saved values\n        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';\n        $savedSettings = json_decode($savedSettings, true);\n        if (!is_array($savedSettings)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');\n            break;\n        }\n\n        // Continue with theme related settings only\n        if (!function_exists('filterThemeSettings')) {\n            function filterThemeSettings($settings) {\n                return array_filter(\n                    $settings,\n                    function ($key) {\n                        if (strpos($key, 'theme_') === 0 || strpos($key, 'logo_') === 0) {\n                            return $key;\n                        }\n                        else {\n                            return false;\n                        }\n                    },\n                    ARRAY_FILTER_USE_KEY\n                );\n            }\n        }\n        $currentSettingsTheme = filterThemeSettings($currentSettings);\n        $savedSettingsTheme = filterThemeSettings($savedSettings);\n\n        // Remove leading '/' slash from path values\n        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed\n        if ($currentSettingsTheme['logo_path'][0] === '/' || $currentSettingsTheme['logo_badge_path'][0] === '/') {\n            $currentSettingsTheme['logo_path'] = substr($currentSettingsTheme['logo_path'], 1);\n            $currentSettingsTheme['logo_badge_path'] = substr($currentSettingsTheme['logo_badge_path'], 1);\n        }\n\n        // Add media source to saved paths\n        $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);\n        $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);\n\n        // Compare saved settings to current settings\n        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);\n        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);\n\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'updated settings: ' . print_r($updatedSettings));\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'deleted settings: ' . print_r($deletedSettings));\n\n        $output = array();\n\n        // Regenerate styling elements if theme settings were updated or deleted\n        if ($updatedSettings || $deletedSettings) {\n            // Clear cache, to ensure build process uses the latest values\n            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));\n            if ($modx->getOption('clientconfig.clear_cache', null, true)) {\n                $modx->getCacheManager()->delete('',array(xPDO::OPT_CACHE_KEY => 'resource'));\n            }\n\n            //$command = '/home/hugo/.npm-global/bin/gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ./logs/romanesco.log 2>./logs/error.log &';\n            //$command = 'gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &';\n\n            // Create directory for logs (if it doesn't exist already)\n            //exec('cd ' . escapeshellcmd($modx->getOption('core_path')) . ' && mkdir -p logs 2>&1', $output);\n\n            // Terminate any existing gulp processes\n            $killCommand = \"ps aux | grep '[g]ulpfile \" . $modx->getOption('assets_path') . \"semantic/gulpfile.js' | awk '{print $2}'\";\n            exec(\n                'kill $(' . $killCommand . ') 2> /dev/null',\n                $output,\n                $return_kill\n            );\n\n            // Run gulp process to generate new CSS\n            exec(\n                'gulp build-css' .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .\n                ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log' .\n                ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &',\n                //' 2>&1 | tee -a ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log',\n                $output,\n                $return_css\n            );\n\n            // Update favicon if a new logo image was provided\n            if (array_key_exists('logo_badge_path', $updatedSettings)) {\n                //$modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Logo badge was changed');\n                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];\n\n                exec(\n                    'gulp generate-favicon' .\n                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/generate-favicons.js' .\n                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .\n                    ' --img ' . escapeshellarg($logoBadgePath) .\n                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .\n                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .\n                    ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .\n                    ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log &',\n                    $output,\n                    $return_favicon\n                );\n\n                // Bump favicon version number to force refresh\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', $version->get('value') + 0.1);\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Prevent favicons from being loaded if badge image was removed\n            if (array_key_exists('logo_badge_path', $deletedSettings)) {\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', '');\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Clear cache\n            $modx->cacheManager->refresh();\n        }\n\n        // Report any validation errors in log\n        if (array_filter($output)) {\n            foreach ($output as $line) {\n                $errorMsg .= \"\\n\" . $line;\n            }\n            return (\" Report: \" . $errorMsg);\n        }\n\n        break;\n}"
properties: 'a:0:{}'
content: "// Check if exec function is available on the server\nif(@exec('echo EXEC') !== 'EXEC'){\n    $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Exec function not available');\n    return false;\n}\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'ClientConfig_ConfigChange':\n        $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');\n        $path .= 'model/clientconfig/';\n        $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);\n        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);\n\n        // Get current configuration settings (before save)\n        $currentSettings = $clientConfig->getSettings();\n\n        // Get saved values\n        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';\n        $savedSettings = json_decode($savedSettings, true);\n        if (!is_array($savedSettings)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');\n            break;\n        }\n\n        // Continue with theme related settings only\n        if (!function_exists('filterThemeSettings')) {\n            function filterThemeSettings($settings) {\n                return array_filter(\n                    $settings,\n                    function ($key) {\n                        if (strpos($key, 'theme_') === 0 || strpos($key, 'logo_') === 0) {\n                            return $key;\n                        }\n                        else {\n                            return false;\n                        }\n                    },\n                    ARRAY_FILTER_USE_KEY\n                );\n            }\n        }\n        $currentSettingsTheme = filterThemeSettings($currentSettings);\n        $savedSettingsTheme = filterThemeSettings($savedSettings);\n\n        // Remove leading '/' slash from path values\n        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed\n        if ($currentSettingsTheme['logo_path'][0] === '/' || $currentSettingsTheme['logo_badge_path'][0] === '/') {\n            $currentSettingsTheme['logo_path'] = substr($currentSettingsTheme['logo_path'], 1);\n            $currentSettingsTheme['logo_badge_path'] = substr($currentSettingsTheme['logo_badge_path'], 1);\n        }\n\n        // Add media source to saved paths\n        $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);\n        $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);\n\n        // Compare saved settings to current settings\n        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);\n        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);\n\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'updated settings: ' . print_r($updatedSettings));\n        //$modx->log(modX::LOG_LEVEL_ERROR, 'deleted settings: ' . print_r($deletedSettings));\n\n        $output = array();\n\n        // Regenerate styling elements if theme settings were updated or deleted\n        if ($updatedSettings || $deletedSettings) {\n            // Clear cache, to ensure build process uses the latest values\n            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));\n            if ($modx->getOption('clientconfig.clear_cache', null, true)) {\n                $modx->getCacheManager()->delete('',array(xPDO::OPT_CACHE_KEY => 'resource'));\n            }\n\n            //$command = '/home/hugo/.npm-global/bin/gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ./logs/romanesco.log 2>./logs/error.log &';\n            //$command = 'gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &';\n\n            // Create directory for logs (if it doesn't exist already)\n            //exec('cd ' . escapeshellcmd($modx->getOption('core_path')) . ' && mkdir -p logs 2>&1', $output);\n\n            // Terminate any existing gulp processes\n            $killCommand = \"ps aux | grep '[g]ulpfile \" . $modx->getOption('assets_path') . \"semantic/gulpfile.js' | awk '{print $2}'\";\n            exec(\n                'kill $(' . $killCommand . ') 2> /dev/null',\n                $output,\n                $return_kill\n            );\n\n            // Run gulp process to generate new CSS\n            exec(\n                'gulp build-css' .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .\n                ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log' .\n                ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &',\n                //' 2>&1 | tee -a ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log',\n                $output,\n                $return_css\n            );\n\n            // Update favicon if a new logo image was provided\n            if (array_key_exists('logo_badge_path', $updatedSettings)) {\n                //$modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Logo badge was changed');\n                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];\n\n                exec(\n                    'gulp generate-favicon' .\n                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/generate-favicons.js' .\n                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .\n                    ' --img ' . escapeshellarg($logoBadgePath) .\n                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .\n                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .\n                    ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .\n                    ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log &',\n                    $output,\n                    $return_favicon\n                );\n\n                // Bump favicon version number to force refresh\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', $version->get('value') + 0.1);\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Prevent favicons from being loaded if badge image was removed\n            if (array_key_exists('logo_badge_path', $deletedSettings)) {\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', '');\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Clear cache\n            $modx->cacheManager->refresh();\n        }\n\n        // Report any validation errors in log\n        if (array_filter($output)) {\n            foreach ($output as $line) {\n                $errorMsg .= \"\\n\" . $line;\n            }\n            return (\" Report: \" . $errorMsg);\n        }\n\n        break;\n}"

-----


// Check if exec function is available on the server
if(@exec('echo EXEC') !== 'EXEC'){
    $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Exec function not available');
    return false;
}

$eventName = $modx->event->name;

switch($eventName) {
    case 'ClientConfig_ConfigChange':
        $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');
        $path .= 'model/clientconfig/';
        $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);
        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);

        // Get current configuration settings (before save)
        $currentSettings = $clientConfig->getSettings();

        // Get saved values
        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';
        $savedSettings = json_decode($savedSettings, true);
        if (!is_array($savedSettings)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');
            break;
        }

        // Continue with theme related settings only
        if (!function_exists('filterThemeSettings')) {
            function filterThemeSettings($settings) {
                return array_filter(
                    $settings,
                    function ($key) {
                        if (strpos($key, 'theme_') === 0 || strpos($key, 'logo_') === 0) {
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
        if ($currentSettingsTheme['logo_path'][0] === '/' || $currentSettingsTheme['logo_badge_path'][0] === '/') {
            $currentSettingsTheme['logo_path'] = substr($currentSettingsTheme['logo_path'], 1);
            $currentSettingsTheme['logo_badge_path'] = substr($currentSettingsTheme['logo_badge_path'], 1);
        }

        // Add media source to saved paths
        $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);
        $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);

        // Compare saved settings to current settings
        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);
        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);

        //$modx->log(modX::LOG_LEVEL_ERROR, 'updated settings: ' . print_r($updatedSettings));
        //$modx->log(modX::LOG_LEVEL_ERROR, 'deleted settings: ' . print_r($deletedSettings));

        $output = array();

        // Regenerate styling elements if theme settings were updated or deleted
        if ($updatedSettings || $deletedSettings) {
            // Clear cache, to ensure build process uses the latest values
            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));
            if ($modx->getOption('clientconfig.clear_cache', null, true)) {
                $modx->getCacheManager()->delete('',array(xPDO::OPT_CACHE_KEY => 'resource'));
            }

            //$command = '/home/hugo/.npm-global/bin/gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ./logs/romanesco.log 2>./logs/error.log &';
            //$command = 'gulp --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js build-css > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &';

            // Create directory for logs (if it doesn't exist already)
            //exec('cd ' . escapeshellcmd($modx->getOption('core_path')) . ' && mkdir -p logs 2>&1', $output);

            // Terminate any existing gulp processes
            $killCommand = "ps aux | grep '[g]ulpfile " . $modx->getOption('assets_path') . "semantic/gulpfile.js' | awk '{print $2}'";
            exec(
                'kill $(' . $killCommand . ') 2> /dev/null',
                $output,
                $return_kill
            );

            // Run gulp process to generate new CSS
            exec(
                'gulp build-css' .
                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .
                ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log' .
                ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log &',
                //' 2>&1 | tee -a ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/romanesco.log',
                $output,
                $return_css
            );

            // Update favicon if a new logo image was provided
            if (array_key_exists('logo_badge_path', $updatedSettings)) {
                //$modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Logo badge was changed');
                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];

                exec(
                    'gulp generate-favicon' .
                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/generate-favicons.js' .
                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .
                    ' --img ' . escapeshellarg($logoBadgePath) .
                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .
                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .
                    ' > ' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .
                    ' 2>' .escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log &',
                    $output,
                    $return_favicon
                );

                // Bump favicon version number to force refresh
                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));
                if ($version) {
                    $version->set('value', $version->get('value') + 0.1);
                    $version->save();
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');
                }
            }

            // Prevent favicons from being loaded if badge image was removed
            if (array_key_exists('logo_badge_path', $deletedSettings)) {
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
            foreach ($output as $line) {
                $errorMsg .= "\n" . $line;
            }
            return (" Report: " . $errorMsg);
        }

        break;
}