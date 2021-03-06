id: 28
name: UpdateStyling
description: 'Fires when theme settings are changed under Configuration. It updates Semantic UI theme.variables and triggers a new SUI build in the background. Requires NPM and EXEC function on the server.'
category: c_settings
plugincode: "/**\n * UpdateStyling\n *\n * This plugin is activated when certain theme settings are changed in the\n * ClientConfig CMP.\n *\n * It changes some variables used by Semantic UI to generate the CSS and it also\n * triggers a new SUI build in the background. This requires NPM to be available\n * on the server, as well as the exec function.\n *\n * NB! ENABLING THE EXEC FUNCTION ON YOUR SERVER IS A POTENTIAL SECURITY RISK!\n * Please make sure your server and MODX install are sufficiently hardened\n * before enabling this functionality. You can always update the SUI styling and\n * run the build process manually, so there's no harm done if this plugin can't\n * be activated.\n *\n * It also generates favicon images if a logo badge is provided. This relies on\n * a few Gulp dependencies (see package.json) and the Real Favicon service:\n * https://realfavicongenerator.net/favicon/gulp\n *\n * Update May 15, 2020:\n * This plugin is now able to process context-aware configuration settings.\n *\n * Update May 20, 2020:\n * The plugin no longer relies on an assets/css/theme.variables resource to be\n * present in MODX. The settings are directly written to a static file now.\n *\n * @var modX $modx\n * @package romanesco\n */\n\n// Check if exec function is available on the server\nif(@exec('echo EXEC') !== 'EXEC'){\n    $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Exec function not available');\n    return false;\n}\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'ClientConfig_ConfigChange':\n        $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');\n        $path .= 'model/clientconfig/';\n        $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);\n        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);\n        $output = array();\n\n        // Get saved values\n        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';\n        $savedSettings = json_decode($savedSettings, true);\n        if (!is_array($savedSettings)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');\n            break;\n        }\n\n        // Get current configuration settings (before save) for active context\n        $currentContext = $savedSettings['context'];\n        $currentSettings = $clientConfig->getSettings($currentContext);\n        if ($clientConfig instanceof ClientConfig) {\n            $cacheOptions = array(xPDO::OPT_CACHE_KEY => 'system_settings');\n            $settings = $modx->getCacheManager()->get('clientconfig', $cacheOptions);\n        }\n\n        // Continue with theme related settings only\n        if (!function_exists('filterThemeSettings')) {\n            function filterThemeSettings($settings) {\n                return array_filter(\n                    $settings,\n                    function ($key) {\n                        if (strpos($key, 'theme_') === 0 || strpos($key, 'logo_') === 0) {\n                            return $key;\n                        }\n                        else {\n                            return false;\n                        }\n                    },\n                    ARRAY_FILTER_USE_KEY\n                );\n            }\n        }\n        $currentSettingsTheme = filterThemeSettings($currentSettings);\n        $savedSettingsTheme = filterThemeSettings($savedSettings);\n\n        // Remove leading '/' slash from path values\n        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed\n        $currentSettingsTheme['logo_path'] = ltrim($currentSettingsTheme['logo_path'],'/');\n        $currentSettingsTheme['logo_badge_path'] = ltrim($currentSettingsTheme['logo_badge_path'],'/');\n\n        // Add media source to saved paths\n        if ($savedSettingsTheme['logo_path']) {\n            $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);\n        }\n        if ($savedSettingsTheme['logo_badge_path']) {\n            $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);\n        }\n\n        // Compare saved settings to current settings\n        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);\n        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);\n\n        // Regenerate styling elements if theme settings were updated or deleted\n        if ($updatedSettings || $deletedSettings) {\n\n            // Set theme.variables path for current context\n            $themesFolder = $modx->getOption('base_path') . 'assets/semantic/src/themes/';\n            if ($currentContext) {\n                $themeVariablesPath = $themesFolder . $currentContext . '/globals/theme.variables';\n            } else {\n                $themeVariablesPath = $themesFolder . 'project/globals/theme.variables';\n            }\n\n            // Clear cache, to ensure build process uses the latest values\n            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));\n\n            // Grab variables after cache rebuild\n            $newSettings = $clientConfig->getSettings($currentContext);\n\n            // NB: for some reason, using getChunk directly here doesn't work (when using settings array as content)\n            $themeVariables = $modx->getObject('modChunk', array('name'=>'themeVariables'));\n            $themeVariables->setCacheable(false);\n\n            // Write to theme.variables\n            $pathInfo = pathinfo($themeVariablesPath);\n            $path = $pathInfo['dirname'];\n\n            if (!file_exists($path)) {\n                mkdir($path, 0755, true);\n            }\n\n            file_put_contents(\n                $themeVariablesPath,\n                $themeVariables->process($newSettings, $themeVariables)\n            );\n\n            // Terminate any existing gulp processes first\n            $killCommand = \"ps aux | grep '[g]ulp build-' | awk '{print $2}'\";\n            exec(\n                'kill $(' . $killCommand . ') 2> /dev/null',\n                $output,\n                $return_kill\n            );\n\n            // Construct build command\n            if ($currentContext) {\n                $distPath = $modx->getObject('modContextSetting', array(\n                    'context_key' => $currentContext,\n                    'key' => 'romanesco.semantic_dist_path'\n                ));\n                $buildCommand = 'gulp build-context --key ' . $currentContext . ' --dist ' . $modx->getOption('base_path') . $distPath->get('value');\n            }\n            else {\n                $buildCommand = 'gulp build-css';\n            }\n\n            // Run gulp process to generate new CSS\n            exec(\n                '\"$HOME/.nvm/nvm-exec\" ' . $buildCommand .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-multicontext-css.js' .\n                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css.log' .\n                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css-error.log &',\n                $output,\n                $return_css\n            );\n\n            // Bump CSS version number to force refresh\n            $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n            if ($versionCSS) {\n                // Only update minor version number (1.0.1<--)\n                $versionArray = explode('.', $versionCSS->get('value'));\n                $versionMinor = array_pop($versionArray);\n                $versionArray[] = $versionMinor + 1;\n\n                $versionCSS->set('value', implode('.', $versionArray));\n                $versionCSS->save();\n            } else {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n            }\n\n            // Update favicon if a new logo image was provided\n            if (array_key_exists('logo_badge_path', $updatedSettings)) {\n                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];\n\n                exec(\n                    '\"$HOME/.nvm/nvm-exec\"' .\n                    ' gulp generate-favicon' .\n                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-favicons.js' .\n                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .\n                    ' --img ' . escapeshellarg($logoBadgePath) .\n                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .\n                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .\n                    ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .\n                    ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon-error.log &',\n                    $output,\n                    $return_favicon\n                );\n\n                // Bump favicon version number to force refresh\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', $version->get('value') + 0.1);\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Prevent favicons from being loaded if badge image was removed\n            if (array_key_exists('logo_badge_path', $deletedSettings)) {\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', '');\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Clear cache\n            $modx->cacheManager->refresh();\n        }\n\n        // Report any validation errors in log\n        if (array_filter($output)) {\n            $errorMsg = '';\n            foreach ($output as $line) {\n                $errorMsg .= \"\\n\" . $line;\n            }\n            return (\" Report: \" . $errorMsg);\n        }\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.updatestyling.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * UpdateStyling\n *\n * This plugin is activated when certain theme settings are changed in the\n * ClientConfig CMP.\n *\n * It changes some variables used by Semantic UI to generate the CSS and it also\n * triggers a new SUI build in the background. This requires NPM to be available\n * on the server, as well as the exec function.\n *\n * NB! ENABLING THE EXEC FUNCTION ON YOUR SERVER IS A POTENTIAL SECURITY RISK!\n * Please make sure your server and MODX install are sufficiently hardened\n * before enabling this functionality. You can always update the SUI styling and\n * run the build process manually, so there's no harm done if this plugin can't\n * be activated.\n *\n * It also generates favicon images if a logo badge is provided. This relies on\n * a few Gulp dependencies (see package.json) and the Real Favicon service:\n * https://realfavicongenerator.net/favicon/gulp\n *\n * Update May 15, 2020:\n * This plugin is now able to process context-aware configuration settings.\n *\n * Update May 20, 2020:\n * The plugin no longer relies on an assets/css/theme.variables resource to be\n * present in MODX. The settings are directly written to a static file now.\n *\n * @var modX $modx\n * @package romanesco\n */\n\n// Check if exec function is available on the server\nif(@exec('echo EXEC') !== 'EXEC'){\n    $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] Exec function not available');\n    return false;\n}\n\n$eventName = $modx->event->name;\n\nswitch($eventName) {\n    case 'ClientConfig_ConfigChange':\n        $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');\n        $path .= 'model/clientconfig/';\n        $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);\n        $imgMediaSource = $modx->getObject('sources.modMediaSource', 15);\n        $output = array();\n\n        // Get saved values\n        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';\n        $savedSettings = json_decode($savedSettings, true);\n        if (!is_array($savedSettings)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');\n            break;\n        }\n\n        // Get current configuration settings (before save) for active context\n        $currentContext = $savedSettings['context'];\n        $currentSettings = $clientConfig->getSettings($currentContext);\n        if ($clientConfig instanceof ClientConfig) {\n            $cacheOptions = array(xPDO::OPT_CACHE_KEY => 'system_settings');\n            $settings = $modx->getCacheManager()->get('clientconfig', $cacheOptions);\n        }\n\n        // Continue with theme related settings only\n        if (!function_exists('filterThemeSettings')) {\n            function filterThemeSettings($settings) {\n                return array_filter(\n                    $settings,\n                    function ($key) {\n                        if (strpos($key, 'theme_') === 0 || strpos($key, 'logo_') === 0) {\n                            return $key;\n                        }\n                        else {\n                            return false;\n                        }\n                    },\n                    ARRAY_FILTER_USE_KEY\n                );\n            }\n        }\n        $currentSettingsTheme = filterThemeSettings($currentSettings);\n        $savedSettingsTheme = filterThemeSettings($savedSettings);\n\n        // Remove leading '/' slash from path values\n        // This somehow gets added by MODX, resulting in these keys being incorrectly flagged as changed\n        $currentSettingsTheme['logo_path'] = ltrim($currentSettingsTheme['logo_path'],'/');\n        $currentSettingsTheme['logo_badge_path'] = ltrim($currentSettingsTheme['logo_badge_path'],'/');\n\n        // Add media source to saved paths\n        if ($savedSettingsTheme['logo_path']) {\n            $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);\n        }\n        if ($savedSettingsTheme['logo_badge_path']) {\n            $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);\n        }\n\n        // Compare saved settings to current settings\n        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);\n        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);\n\n        // Regenerate styling elements if theme settings were updated or deleted\n        if ($updatedSettings || $deletedSettings) {\n\n            // Set theme.variables path for current context\n            $themesFolder = $modx->getOption('base_path') . 'assets/semantic/src/themes/';\n            if ($currentContext) {\n                $themeVariablesPath = $themesFolder . $currentContext . '/globals/theme.variables';\n            } else {\n                $themeVariablesPath = $themesFolder . 'project/globals/theme.variables';\n            }\n\n            // Clear cache, to ensure build process uses the latest values\n            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));\n\n            // Grab variables after cache rebuild\n            $newSettings = $clientConfig->getSettings($currentContext);\n\n            // NB: for some reason, using getChunk directly here doesn't work (when using settings array as content)\n            $themeVariables = $modx->getObject('modChunk', array('name'=>'themeVariables'));\n            $themeVariables->setCacheable(false);\n\n            // Write to theme.variables\n            $pathInfo = pathinfo($themeVariablesPath);\n            $path = $pathInfo['dirname'];\n\n            if (!file_exists($path)) {\n                mkdir($path, 0755, true);\n            }\n\n            file_put_contents(\n                $themeVariablesPath,\n                $themeVariables->process($newSettings, $themeVariables)\n            );\n\n            // Terminate any existing gulp processes first\n            $killCommand = \"ps aux | grep '[g]ulp build-' | awk '{print $2}'\";\n            exec(\n                'kill $(' . $killCommand . ') 2> /dev/null',\n                $output,\n                $return_kill\n            );\n\n            // Construct build command\n            if ($currentContext) {\n                $distPath = $modx->getObject('modContextSetting', array(\n                    'context_key' => $currentContext,\n                    'key' => 'romanesco.semantic_dist_path'\n                ));\n                $buildCommand = 'gulp build-context --key ' . $currentContext . ' --dist ' . $modx->getOption('base_path') . $distPath->get('value');\n            }\n            else {\n                $buildCommand = 'gulp build-css';\n            }\n\n            // Run gulp process to generate new CSS\n            exec(\n                '\"$HOME/.nvm/nvm-exec\" ' . $buildCommand .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-multicontext-css.js' .\n                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css.log' .\n                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css-error.log &',\n                $output,\n                $return_css\n            );\n\n            // Bump CSS version number to force refresh\n            $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n            if ($versionCSS) {\n                // Only update minor version number (1.0.1<--)\n                $versionArray = explode('.', $versionCSS->get('value'));\n                $versionMinor = array_pop($versionArray);\n                $versionArray[] = $versionMinor + 1;\n\n                $versionCSS->set('value', implode('.', $versionArray));\n                $versionCSS->save();\n            } else {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n            }\n\n            // Update favicon if a new logo image was provided\n            if (array_key_exists('logo_badge_path', $updatedSettings)) {\n                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];\n\n                exec(\n                    '\"$HOME/.nvm/nvm-exec\"' .\n                    ' gulp generate-favicon' .\n                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-favicons.js' .\n                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .\n                    ' --img ' . escapeshellarg($logoBadgePath) .\n                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .\n                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .\n                    ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .\n                    ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon-error.log &',\n                    $output,\n                    $return_favicon\n                );\n\n                // Bump favicon version number to force refresh\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', $version->get('value') + 0.1);\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Prevent favicons from being loaded if badge image was removed\n            if (array_key_exists('logo_badge_path', $deletedSettings)) {\n                $version = $modx->getObject('modSystemSetting', array('key' => 'romanesco.favicon_version'));\n                if ($version) {\n                    $version->set('value', '');\n                    $version->save();\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find favicon_version setting');\n                }\n            }\n\n            // Clear cache\n            $modx->cacheManager->refresh();\n        }\n\n        // Report any validation errors in log\n        if (array_filter($output)) {\n            $errorMsg = '';\n            foreach ($output as $line) {\n                $errorMsg .= \"\\n\" . $line;\n            }\n            return (\" Report: \" . $errorMsg);\n        }\n\n        break;\n}"

-----


/**
 * UpdateStyling
 *
 * This plugin is activated when certain theme settings are changed in the
 * ClientConfig CMP.
 *
 * It changes some variables used by Semantic UI to generate the CSS and it also
 * triggers a new SUI build in the background. This requires NPM to be available
 * on the server, as well as the exec function.
 *
 * NB! ENABLING THE EXEC FUNCTION ON YOUR SERVER IS A POTENTIAL SECURITY RISK!
 * Please make sure your server and MODX install are sufficiently hardened
 * before enabling this functionality. You can always update the SUI styling and
 * run the build process manually, so there's no harm done if this plugin can't
 * be activated.
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
        $output = array();

        // Get saved values
        $savedSettings = (!empty($_POST['values'])) ? $_POST['values'] : '[]';
        $savedSettings = json_decode($savedSettings, true);
        if (!is_array($savedSettings)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[UpdateStyling] No values array available');
            break;
        }

        // Get current configuration settings (before save) for active context
        $currentContext = $savedSettings['context'];
        $currentSettings = $clientConfig->getSettings($currentContext);
        if ($clientConfig instanceof ClientConfig) {
            $cacheOptions = array(xPDO::OPT_CACHE_KEY => 'system_settings');
            $settings = $modx->getCacheManager()->get('clientconfig', $cacheOptions);
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
        $currentSettingsTheme['logo_path'] = ltrim($currentSettingsTheme['logo_path'],'/');
        $currentSettingsTheme['logo_badge_path'] = ltrim($currentSettingsTheme['logo_badge_path'],'/');

        // Add media source to saved paths
        if ($savedSettingsTheme['logo_path']) {
            $savedSettingsTheme['logo_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_path']);
        }
        if ($savedSettingsTheme['logo_badge_path']) {
            $savedSettingsTheme['logo_badge_path'] = $imgMediaSource->prepareOutputUrl($savedSettingsTheme['logo_badge_path']);
        }

        // Compare saved settings to current settings
        $updatedSettings = array_diff($savedSettingsTheme, $currentSettingsTheme);
        $deletedSettings = array_diff($currentSettingsTheme, $savedSettingsTheme);

        // Regenerate styling elements if theme settings were updated or deleted
        if ($updatedSettings || $deletedSettings) {

            // Set theme.variables path for current context
            $themesFolder = $modx->getOption('base_path') . 'assets/semantic/src/themes/';
            if ($currentContext) {
                $themeVariablesPath = $themesFolder . $currentContext . '/globals/theme.variables';
            } else {
                $themeVariablesPath = $themesFolder . 'project/globals/theme.variables';
            }

            // Clear cache, to ensure build process uses the latest values
            $modx->getCacheManager()->delete('clientconfig',array(xPDO::OPT_CACHE_KEY => 'system_settings'));

            // Grab variables after cache rebuild
            $newSettings = $clientConfig->getSettings($currentContext);

            // NB: for some reason, using getChunk directly here doesn't work (when using settings array as content)
            $themeVariables = $modx->getObject('modChunk', array('name'=>'themeVariables'));
            $themeVariables->setCacheable(false);

            // Write to theme.variables
            $pathInfo = pathinfo($themeVariablesPath);
            $path = $pathInfo['dirname'];

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            file_put_contents(
                $themeVariablesPath,
                $themeVariables->process($newSettings, $themeVariables)
            );

            // Terminate any existing gulp processes first
            $killCommand = "ps aux | grep '[g]ulp build-' | awk '{print $2}'";
            exec(
                'kill $(' . $killCommand . ') 2> /dev/null',
                $output,
                $return_kill
            );

            // Construct build command
            if ($currentContext) {
                $distPath = $modx->getObject('modContextSetting', array(
                    'context_key' => $currentContext,
                    'key' => 'romanesco.semantic_dist_path'
                ));
                $buildCommand = 'gulp build-context --key ' . $currentContext . ' --dist ' . $modx->getOption('base_path') . $distPath->get('value');
            }
            else {
                $buildCommand = 'gulp build-css';
            }

            // Run gulp process to generate new CSS
            exec(
                '"$HOME/.nvm/nvm-exec" ' . $buildCommand .
                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-multicontext-css.js' .
                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css.log' .
                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/css-error.log &',
                $output,
                $return_css
            );

            // Bump CSS version number to force refresh
            $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));
            if ($versionCSS) {
                // Only update minor version number (1.0.1<--)
                $versionArray = explode('.', $versionCSS->get('value'));
                $versionMinor = array_pop($versionArray);
                $versionArray[] = $versionMinor + 1;

                $versionCSS->set('value', implode('.', $versionArray));
                $versionCSS->save();
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');
            }

            // Update favicon if a new logo image was provided
            if (array_key_exists('logo_badge_path', $updatedSettings)) {
                $logoBadgePath = $modx->getOption('base_path') . $savedSettingsTheme['logo_badge_path'];

                exec(
                    '"$HOME/.nvm/nvm-exec"' .
                    ' gulp generate-favicon' .
                    ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/generate-favicons.js' .
                    ' --name ' . escapeshellarg($modx->getOption('site_name')) .
                    ' --img ' . escapeshellarg($logoBadgePath) .
                    ' --primary ' . escapeshellarg($savedSettingsTheme['theme_color_primary']) .
                    ' --secondary ' . escapeshellarg($savedSettingsTheme['theme_color_secondary']) .
                    ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon.log' .
                    ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/favicon-error.log &',
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
            $errorMsg = '';
            foreach ($output as $line) {
                $errorMsg .= "\n" . $line;
            }
            return (" Report: " . $errorMsg);
        }

        break;
}