id: 41
name: GenerateCriticalCSS
category: c_performance
plugincode: "/**\n * GenerateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$basePath = $modx->getOption('base_path');\n$cssPath = $modx->getOption('romanesco.custom_css_path');\n$distPath = $modx->getOption('romanesco.semantic_dist_path');\n$context = $modx->resource->get('context_key');\n\n// Abort if critical is not enabled for current context\n//if (!$romanesco->getConfigSetting('critical_css', $context)) return;\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $globalTemplates = [8,9,10,11,19,27];\n        $excludedTemplates = explode(',', $romanesco->getConfigSetting('critical_exclude_templates', $context));\n        $excludedTemplates = array_merge($globalTemplates, $excludedTemplates);\n        $sharedTemplates = explode(',', $romanesco->getConfigSetting('critical_shared_templates', $context));\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n        $uri = ltrim($resource->get('uri'),'/');\n        $uri = rtrim($uri,'/');\n        $criticalPath = rtrim($cssPath,'/') . '/critical/';\n\n        // Empty and excluded templates\n        if (in_array($resource->get('template'), $excludedTemplates) || !is_object($template)) {\n            $resource->setTVValue('critical_css_uri', '');\n            break;\n        }\n\n        // Templates with shared CSS\n        if (in_array($resource->get('template'), $sharedTemplates)) {\n            $uri = strtolower($template->get('templatename'));\n            $uri = str_replace(' ', '', $uri);\n        }\n\n        // Store full path to css file in a TV\n        $resource->setTVValue('critical_css_uri', $criticalPath . $uri . '.css');\n\n        $romanesco->generateCriticalCSS(array(\n            'id' => $id,\n            'uri' => $uri,\n            'cssPath' => $cssPath,\n            'distPath' => $distPath,\n        ));\n\n        break;\n\n    case 'OnWebPagePrerender':\n        if ($_SERVER['HTTPS'] === 'on') {\n            $cssFile = $modx->resource->getTVValue('critical_css_uri');\n            $logo = $romanesco->getConfigSetting('logo_path', $context);\n\n            // Create array of objects for the header\n            $linkObjects = array();\n            if ($cssFile && file_exists($basePath . $cssFile)) {\n                $linkObjects[] = \"</{$cssFile}>; as=style; rel=preload;\";\n            }\n            if ($logo) {\n                $linkObjects[] = \"</assets/img/{$logo}>; as=image; rel=preload; nopush\";\n            }\n            $linkObjects[] = \"</{$distPath}/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush\";\n\n            // Set PHP header\n            header('Link: ' . implode(',',$linkObjects));\n        }\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n * GenerateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$basePath = $modx->getOption('base_path');\n$cssPath = $modx->getOption('romanesco.custom_css_path');\n$distPath = $modx->getOption('romanesco.semantic_dist_path');\n$context = $modx->resource->get('context_key');\n\n// Abort if critical is not enabled for current context\n//if (!$romanesco->getConfigSetting('critical_css', $context)) return;\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $globalTemplates = [8,9,10,11,19,27];\n        $excludedTemplates = explode(',', $romanesco->getConfigSetting('critical_exclude_templates', $context));\n        $excludedTemplates = array_merge($globalTemplates, $excludedTemplates);\n        $sharedTemplates = explode(',', $romanesco->getConfigSetting('critical_shared_templates', $context));\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n        $uri = ltrim($resource->get('uri'),'/');\n        $uri = rtrim($uri,'/');\n        $criticalPath = rtrim($cssPath,'/') . '/critical/';\n\n        // Empty and excluded templates\n        if (in_array($resource->get('template'), $excludedTemplates) || !is_object($template)) {\n            $resource->setTVValue('critical_css_uri', '');\n            break;\n        }\n\n        // Templates with shared CSS\n        if (in_array($resource->get('template'), $sharedTemplates)) {\n            $uri = strtolower($template->get('templatename'));\n            $uri = str_replace(' ', '', $uri);\n        }\n\n        // Store full path to css file in a TV\n        $resource->setTVValue('critical_css_uri', $criticalPath . $uri . '.css');\n\n        $romanesco->generateCriticalCSS(array(\n            'id' => $id,\n            'uri' => $uri,\n            'cssPath' => $cssPath,\n            'distPath' => $distPath,\n        ));\n\n        break;\n\n    case 'OnWebPagePrerender':\n        if ($_SERVER['HTTPS'] === 'on') {\n            $cssFile = $modx->resource->getTVValue('critical_css_uri');\n            $logo = $romanesco->getConfigSetting('logo_path', $context);\n\n            // Create array of objects for the header\n            $linkObjects = array();\n            if ($cssFile && file_exists($basePath . $cssFile)) {\n                $linkObjects[] = \"</{$cssFile}>; as=style; rel=preload;\";\n            }\n            if ($logo) {\n                $linkObjects[] = \"</assets/img/{$logo}>; as=image; rel=preload; nopush\";\n            }\n            $linkObjects[] = \"</{$distPath}/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush\";\n\n            // Set PHP header\n            header('Link: ' . implode(',',$linkObjects));\n        }\n\n        break;\n}"

-----


/**
 * GenerateCriticalCSS
 *
 * Determine which CSS styles are used above the fold and write them to a custom
 * CSS file. This needs NPM and the critical package to be installed.
 *
 * https://github.com/addyosmani/critical
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

$basePath = $modx->getOption('base_path');
$cssPath = $modx->getOption('romanesco.custom_css_path');
$distPath = $modx->getOption('romanesco.semantic_dist_path');
$context = $modx->resource->get('context_key');

// Abort if critical is not enabled for current context
//if (!$romanesco->getConfigSetting('critical_css', $context)) return;

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        $globalTemplates = [8,9,10,11,19,27];
        $excludedTemplates = explode(',', $romanesco->getConfigSetting('critical_exclude_templates', $context));
        $excludedTemplates = array_merge($globalTemplates, $excludedTemplates);
        $sharedTemplates = explode(',', $romanesco->getConfigSetting('critical_shared_templates', $context));

        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));
        $uri = ltrim($resource->get('uri'),'/');
        $uri = rtrim($uri,'/');
        $criticalPath = rtrim($cssPath,'/') . '/critical/';

        // Empty and excluded templates
        if (in_array($resource->get('template'), $excludedTemplates) || !is_object($template)) {
            $resource->setTVValue('critical_css_uri', '');
            break;
        }

        // Templates with shared CSS
        if (in_array($resource->get('template'), $sharedTemplates)) {
            $uri = strtolower($template->get('templatename'));
            $uri = str_replace(' ', '', $uri);
        }

        // Store full path to css file in a TV
        $resource->setTVValue('critical_css_uri', $criticalPath . $uri . '.css');

        $romanesco->generateCriticalCSS(array(
            'id' => $id,
            'uri' => $uri,
            'cssPath' => $cssPath,
            'distPath' => $distPath,
        ));

        break;

    case 'OnWebPagePrerender':
        if ($_SERVER['HTTPS'] === 'on') {
            $cssFile = $modx->resource->getTVValue('critical_css_uri');
            $logo = $romanesco->getConfigSetting('logo_path', $context);

            // Create array of objects for the header
            $linkObjects = array();
            if ($cssFile && file_exists($basePath . $cssFile)) {
                $linkObjects[] = "</{$cssFile}>; as=style; rel=preload;";
            }
            if ($logo) {
                $linkObjects[] = "</assets/img/{$logo}>; as=image; rel=preload; nopush";
            }
            $linkObjects[] = "</{$distPath}/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush";

            // Set PHP header
            header('Link: ' . implode(',',$linkObjects));
        }

        break;
}