id: 40
name: GenerateStaticCSS
description: 'Creates static CSS files for each context, with their own global backgrounds. If you want a context to have its own set of backgrounds, you need to add a child page under Global Backgrounds.'
category: c_performance
plugincode: "/**\n * generateStaticCSS\n *\n * Creates static CSS files for each context, with their own global backgrounds.\n *\n * If you want a context to have its own set of backgrounds, you need to create\n * a child page under the Global Backgrounds container for it. Make sure the\n * template is GlobalBackgrounds too and that the alias matches the context_key!\n *\n * A default stylesheet (site.css) is also generated, containing only the\n * backgrounds at root level of the Global Backgrounds container.\n *\n * CSS files are regenerated each time a GlobalBackgrounds resource is saved.\n *\n * NB! The plugin priority should be set to something higher than 0. Otherwise,\n * users will need to save the resource twice to see their changes reflected.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        $exit = '';\n\n        // Exit if resource template is not GlobalBackground(s)\n        /** @var modResource $resource */\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            $exit = 1;\n        }\n\n        // But continue if a header background is being set\n        if ($resource->getTVValue('header_background_img')) {\n            $exit = 0;\n        }\n\n        // Leave the EU?\n        if ($exit) return true;\n\n        // Get all background containers\n        $bgContainers = $modx->getCollection('modResource', array(\n            'parent' => $modx->getOption('romanesco.global_backgrounds_id'),\n            'template' => 8\n        ));\n\n        // Get chunk with CSS template\n        if ($modx->getObject('modChunk', array('name' => 'cssTheme'))) {\n            $cssChunk = 'cssTheme';\n        } else {\n            $cssChunk = 'css';\n        }\n\n        // Get default CSS path\n        $cssPathSystem = $modx->getObject('modSystemSetting', array('key' => 'romanesco.custom_css_path'));\n        if ($cssPathSystem) {\n            $cssPathDefault = $modx->getOption('base_path') . $cssPathSystem->get('value');\n        } else {\n            $cssPathDefault = $modx->getOption('base_path') . 'assets/css';\n        }\n\n        // Generate default CSS file\n        $css = $modx->getChunk($cssChunk);\n        $staticFile = $cssPathDefault . '/site.css';\n\n        if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n        }\n\n        // Start collecting CSS paths for minification down the road\n        $minifyCSS[] = $cssPathDefault;\n\n        // Each container represents a context\n        foreach ($bgContainers as $container) {\n            $context = $container->get('alias');\n\n            // Prepare CSS for this context\n            $css = $modx->getChunk($cssChunk, array(\n                'context' => $context,\n            ));\n\n            // Find correct file path for this context\n            $cssPathContext = $modx->getObject('modContextSetting', array(\n                'context_key' => $context,\n                'key' => 'romanesco.custom_css_path'\n            ));\n            if ($cssPathContext) {\n                $cssPath = $modx->getOption('base_path') . $cssPathContext->get('value');\n            } else {\n                $cssPath = $cssPathDefault . '/' . $context;\n            }\n\n            // Generate static file\n            if ($context) {\n                $staticFile = $cssPath . '/site.css';\n\n                if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n                }\n            }\n\n            // Sign up for minification\n            $minifyCSS[] = $cssPath;\n        }\n\n        // Minify CSS\n        foreach ($minifyCSS as $path) {\n            exec(\n                '\"$HOME/.nvm/nvm-exec\"' .\n                ' gulp minify-css --path ' . $path .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/minify-css.js' .\n                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify.log' .\n                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify-error.log &',\n                $output,\n                $return_css\n            );\n        }\n\n        // Bump CSS version number to force refresh\n        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n        if ($versionCSS)\n        {\n            // Only update minor version number (1.0.1<--)\n            $versionArray = explode('.', $versionCSS->get('value'));\n            $versionMinor = array_pop($versionArray);\n            $versionArray[] = $versionMinor + 1;\n\n            $versionCSS->set('value', implode('.', $versionArray));\n            $versionCSS->save();\n        } else {\n            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n        }\n\n        // Clear cache\n        $modx->cacheManager->refresh();\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.generatestaticcss.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * generateStaticCSS\n *\n * Creates static CSS files for each context, with their own global backgrounds.\n *\n * If you want a context to have its own set of backgrounds, you need to create\n * a child page under the Global Backgrounds container for it. Make sure the\n * template is GlobalBackgrounds too and that the alias matches the context_key!\n *\n * A default stylesheet (site.css) is also generated, containing only the\n * backgrounds at root level of the Global Backgrounds container.\n *\n * CSS files are regenerated each time a GlobalBackgrounds resource is saved.\n *\n * NB! The plugin priority should be set to something higher than 0. Otherwise,\n * users will need to save the resource twice to see their changes reflected.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        $exit = '';\n\n        // Exit if resource template is not GlobalBackground(s)\n        /** @var modResource $resource */\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            $exit = 1;\n        }\n\n        // But continue if a header background is being set\n        if ($resource->getTVValue('header_background_img')) {\n            $exit = 0;\n        }\n\n        // Leave the EU?\n        if ($exit) return true;\n\n        // Get all background containers\n        $bgContainers = $modx->getCollection('modResource', array(\n            'parent' => $modx->getOption('romanesco.global_backgrounds_id'),\n            'template' => 8\n        ));\n\n        // Get chunk with CSS template\n        if ($modx->getObject('modChunk', array('name' => 'cssTheme'))) {\n            $cssChunk = 'cssTheme';\n        } else {\n            $cssChunk = 'css';\n        }\n\n        // Get default CSS path\n        $cssPathSystem = $modx->getObject('modSystemSetting', array('key' => 'romanesco.custom_css_path'));\n        if ($cssPathSystem) {\n            $cssPathDefault = $modx->getOption('base_path') . $cssPathSystem->get('value');\n        } else {\n            $cssPathDefault = $modx->getOption('base_path') . 'assets/css';\n        }\n\n        // Generate default CSS file\n        $css = $modx->getChunk($cssChunk);\n        $staticFile = $cssPathDefault . '/site.css';\n\n        if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n        }\n\n        // Start collecting CSS paths for minification down the road\n        $minifyCSS[] = $cssPathDefault;\n\n        // Each container represents a context\n        foreach ($bgContainers as $container) {\n            $context = $container->get('alias');\n\n            // Prepare CSS for this context\n            $css = $modx->getChunk($cssChunk, array(\n                'context' => $context,\n            ));\n\n            // Find correct file path for this context\n            $cssPathContext = $modx->getObject('modContextSetting', array(\n                'context_key' => $context,\n                'key' => 'romanesco.custom_css_path'\n            ));\n            if ($cssPathContext) {\n                $cssPath = $modx->getOption('base_path') . $cssPathContext->get('value');\n            } else {\n                $cssPath = $cssPathDefault . '/' . $context;\n            }\n\n            // Generate static file\n            if ($context) {\n                $staticFile = $cssPath . '/site.css';\n\n                if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n                }\n            }\n\n            // Sign up for minification\n            $minifyCSS[] = $cssPath;\n        }\n\n        // Minify CSS\n        foreach ($minifyCSS as $path) {\n            exec(\n                '\"$HOME/.nvm/nvm-exec\"' .\n                ' gulp minify-css --path ' . $path .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/minify-css.js' .\n                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify.log' .\n                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify-error.log &',\n                $output,\n                $return_css\n            );\n        }\n\n        // Bump CSS version number to force refresh\n        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n        if ($versionCSS)\n        {\n            // Only update minor version number (1.0.1<--)\n            $versionArray = explode('.', $versionCSS->get('value'));\n            $versionMinor = array_pop($versionArray);\n            $versionArray[] = $versionMinor + 1;\n\n            $versionCSS->set('value', implode('.', $versionArray));\n            $versionCSS->save();\n        } else {\n            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n        }\n\n        // Clear cache\n        $modx->cacheManager->refresh();\n\n        break;\n}"

-----


/**
 * generateStaticCSS
 *
 * Creates static CSS files for each context, with their own global backgrounds.
 *
 * If you want a context to have its own set of backgrounds, you need to create
 * a child page under the Global Backgrounds container for it. Make sure the
 * template is GlobalBackgrounds too and that the alias matches the context_key!
 *
 * A default stylesheet (site.css) is also generated, containing only the
 * backgrounds at root level of the Global Backgrounds container.
 *
 * CSS files are regenerated each time a GlobalBackgrounds resource is saved.
 *
 * NB! The plugin priority should be set to something higher than 0. Otherwise,
 * users will need to save the resource twice to see their changes reflected.
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

// Css validator should be loaded through Romanesco
if (!class_exists(CssLint\Linter::class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[CssLint] Class not found!');
    return;
}

use CssLint\Linter;

switch ($modx->event->name) {
    case 'OnBeforeDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        // Exit if resource template is not GlobalBackground(s)
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            break;
        }

        // Clear event output to avoid rogue messages popping up again
        $modx->event->_output = '';

        // Init CSS linter
        $cssLinter = new Linter();

        // Validate the CSS gradient field
        if ($templateID == 27)
        {
            // Prepare an array with submitted ContentBlocks data
            $cbData = $resource->get('contentblocks');
            $cbData = json_decode($cbData, true);

            // It's probably just 1 background field, but let's not assume anything
            $fields = $cbData[0]['content']['main'];
            foreach ($fields as $field) {
                if ($field['field'] != 109) continue;
                $i = 0;

                foreach ($field['rows'] as $row) {
                    $i++;

                    $image = 'url(' . $row['image']['url'] . ')';
                    $position = $row['position']['value'] ? : 'center center';
                    $size = $row['size']['value'] ? : 'cover';
                    $repeat = $row['repeat']['value'] ? : 'no-repeat';
                    $attachment = $row['attachment']['value'] ? : 'scroll';
                    $gradient = $row['gradient']['value'];
                    $background = $row['image']['url'] ? $image : $gradient;
                    $css = "
.background::before {
    background:
        $background
        $position /
        $size
        $repeat
        $attachment
        !important
    ;
}";

                    // Validate CSS
                    if ($cssLinter->lintString($css) !== true) {
                        $errors = implode("\n", $cssLinter->getErrors());
                        $modx->log(modX::LOG_LEVEL_ERROR, "CSS for background $id is not valid:" . $css . "\n" . $errors);
                        $modx->event->output("The CSS in layer $i is not valid! Please check the error log for details.<br>");
                    }
                }
            }
        }

        break;

    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        $exit = false;

        // Exit if resource template is not GlobalBackground(s)
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            $exit = true;
        }

        // ...but continue if a header background is (being) set
        if ($resource->getTVValue('header_background_img')) {
            $exit = false;
        }

        // Leave the EU?
        if ($exit) break;

        // Generate CSS
        $romanesco->generateBackgroundCSS();

        // Bump CSS version number to force refresh
        $romanesco->bumpVersionNumber();

        // Clear cache
        $modx->cacheManager->refresh();

        break;
}