id: 41
name: GenerateCriticalCSS
category: c_global
plugincode: "/**\n * GenerateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n// Abort if critical CSS generation is not enabled under Configuration settings\nif ($modx->getObject('cgSetting', array('key' => 'generate_critical_css'))->get('value') != 1) {\n    return true;\n}\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Class not found!');\n    return true;\n}\n\n$basePath = $modx->getOption('base_path');\n$cssPath = $romanesco->getCssPath($modx->resource->get('context_key'));\n$distPath = $modx->getOption('romanesco.semantic_dist_path');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $romanesco->generateCriticalCSS($id, $resource->get('uri'), $cssPath);\n\n        break;\n\n    case 'OnWebPagePrerender':\n        if ($_SERVER['HTTPS'] === 'on') {\n            $uri = ltrim($modx->resource->get('uri'),'/');\n            $uri = rtrim($modx->resource->get('uri'),'/');\n            $cssFile = rtrim($cssPath,'/') . \"/critical/$uri.css\";\n            $logo = $modx->getObject('cgSetting', array('key' => 'logo_path'));\n\n            // Create array with objects for the header\n            $linkObjects = array();\n            if (file_exists(\"$basePath$cssFile\")) {\n                $linkObjects[] = \"</$cssFile>; as=style; rel=preload;\";\n            }\n            if ($logo) {\n                $linkObjects[] = \"</assets/img/{$logo->get('value')}>; as=image; rel=preload; nopush\";\n            }\n            $linkObjects[] = \"</$distPath/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush\";\n\n            // Set PHP header\n            header('Link: ' . implode(',',$linkObjects));\n        }\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n * GenerateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n// Abort if critical CSS generation is not enabled under Configuration settings\nif ($modx->getObject('cgSetting', array('key' => 'generate_critical_css'))->get('value') != 1) {\n    return true;\n}\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Class not found!');\n    return true;\n}\n\n$basePath = $modx->getOption('base_path');\n$cssPath = $romanesco->getCssPath($modx->resource->get('context_key'));\n$distPath = $modx->getOption('romanesco.semantic_dist_path');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $romanesco->generateCriticalCSS($id, $resource->get('uri'), $cssPath);\n\n        break;\n\n    case 'OnWebPagePrerender':\n        if ($_SERVER['HTTPS'] === 'on') {\n            $uri = ltrim($modx->resource->get('uri'),'/');\n            $uri = rtrim($modx->resource->get('uri'),'/');\n            $cssFile = rtrim($cssPath,'/') . \"/critical/$uri.css\";\n            $logo = $modx->getObject('cgSetting', array('key' => 'logo_path'));\n\n            // Create array with objects for the header\n            $linkObjects = array();\n            if (file_exists(\"$basePath$cssFile\")) {\n                $linkObjects[] = \"</$cssFile>; as=style; rel=preload;\";\n            }\n            if ($logo) {\n                $linkObjects[] = \"</assets/img/{$logo->get('value')}>; as=image; rel=preload; nopush\";\n            }\n            $linkObjects[] = \"</$distPath/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush\";\n\n            // Set PHP header\n            header('Link: ' . implode(',',$linkObjects));\n        }\n\n        break;\n}"

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
if (!$romanesco->getConfigSetting('generate_critical_css', $context)) return;

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        $romanesco->generateCriticalCSS(array(
            'id' => $id,
            'uri' => $resource->get('uri'),
            'cssPath' => $cssPath,
            'distPath' => $distPath,
        ));

        break;

    case 'OnWebPagePrerender':
        if ($_SERVER['HTTPS'] === 'on') {
            $uri = ltrim($modx->resource->get('uri'),'/');
            $uri = rtrim($modx->resource->get('uri'),'/');
            $cssFile = rtrim($cssPath,'/') . "/critical/$uri.css";
            $logo = $romanesco->getConfigSetting('logo_path', $context);

            // Create array of objects for the header
            $linkObjects = array();
            if (file_exists("$basePath$cssFile")) {
                $linkObjects[] = "</$cssFile>; as=style; rel=preload;";
            }
            if ($logo) {
                $linkObjects[] = "</assets/img/{$logo}>; as=image; rel=preload; nopush";
            }
            $linkObjects[] = "</$distPath/themes/default/assets/fonts/icons.woff2>; as=font; rel=preload; crossorigin; nopush";

            // Set PHP header
            header('Link: ' . implode(',',$linkObjects));
        }

        break;
}