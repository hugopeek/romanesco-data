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

switch ($modx->event->name) {
    case 'OnDocFormSave':
        $exit = '';

        // Exit if resource template is not GlobalBackground(s)
        /** @var modResource $resource */
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            $exit = 1;
        }

        // But continue if a header background is being set
        if ($resource->getTVValue('header_background_img')) {
            $exit = 0;
        }

        // Leave the EU?
        if ($exit) return true;

        // Get all background containers
        $bgContainers = $modx->getCollection('modResource', array(
            'parent' => $modx->getOption('romanesco.global_backgrounds_id'),
            'template' => 8
        ));

        // Get chunk with CSS template
        if ($modx->getObject('modChunk', array('name' => 'cssTheme'))) {
            $cssChunk = 'cssTheme';
        } else {
            $cssChunk = 'css';
        }

        // Get default CSS path
        $cssPathSystem = $modx->getObject('modSystemSetting', array('key' => 'romanesco.custom_css_path'));
        if ($cssPathSystem) {
            $cssPathDefault = $modx->getOption('base_path') . $cssPathSystem->get('value');
        } else {
            $cssPathDefault = $modx->getOption('base_path') . 'assets/css';
        }

        // Generate default CSS file
        $css = $modx->getChunk($cssChunk);
        $staticFile = $cssPathDefault . '/site.css';

        if (!$modx->cacheManager->writeFile($staticFile, $css)) {
            $modx->log(modX::LOG_LEVEL_ERROR, "Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}", '', __FUNCTION__, __FILE__, __LINE__);
        }

        // Start collecting CSS paths for minification down the road
        $minifyCSS[] = $cssPathDefault;

        // Each container represents a context
        foreach ($bgContainers as $container) {
            $context = $container->get('alias');

            // Prepare CSS for this context
            $css = $modx->getChunk($cssChunk, array(
                'context' => $context,
            ));

            // Find correct file path for this context
            $cssPathContext = $modx->getObject('modContextSetting', array(
                'context_key' => $context,
                'key' => 'romanesco.custom_css_path'
            ));
            if ($cssPathContext) {
                $cssPath = $modx->getOption('base_path') . $cssPathContext->get('value');
            } else {
                $cssPath = $cssPathDefault . '/' . $context;
            }

            // Generate static file
            if ($context) {
                $staticFile = $cssPath . '/site.css';

                if (!$modx->cacheManager->writeFile($staticFile, $css)) {
                    $modx->log(modX::LOG_LEVEL_ERROR, "Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}", '', __FUNCTION__, __FILE__, __LINE__);
                }
            }

            // Sign up for minification
            $minifyCSS[] = $cssPath;
        }

        // Minify CSS
        foreach ($minifyCSS as $path) {
            exec(
                '"$HOME/.nvm/nvm-exec"' .
                ' gulp minify-css --path ' . $path .
                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'components/romanescobackyard/js/gulp/minify-css.js' .
                ' > ' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify.log' .
                ' 2>' . escapeshellcmd($modx->getOption('core_path')) . 'cache/logs/minify-error.log &',
                $output,
                $return_css
            );
        }

        // Bump CSS version number to force refresh
        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));
        if ($versionCSS)
        {
            // Only update minor version number (1.0.1<--)
            $versionArray = explode('.', $versionCSS->get('value'));
            $versionMinor = array_pop($versionArray);
            $versionArray[] = $versionMinor + 1;

            $versionCSS->set('value', implode('.', $versionArray));
            $versionCSS->save();
        } else {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');
        }

        // Clear cache
        $modx->cacheManager->refresh();

        break;
}