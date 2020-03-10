id: 40
name: GenerateStaticCSS
category: c_global
plugincode: "/**\n * generateStaticCSS\n *\n * Creates static CSS files for each context, with their own global backgrounds.\n *\n * If you want a context to have its own set of backgrounds, you need to create\n * a child page under the Global Backgrounds container for it. Make sure the\n * template is GlobalBackgrounds too and that the alias matches the context_key!\n *\n * A default stylesheet (site.css) is also generated, containing only the\n * backgrounds at root level of the Global Backgrounds container.\n *\n * CSS files are regenerated each time a GlobalBackgrounds resource is saved.\n *\n * NB! The plugin priority should be set to something higher than 0. Otherwise,\n * users will need to save the resource twice to see their changes reflected.\n */\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n\n        // Abort if resource template is not GlobalBackground(s)\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            return true;\n        }\n\n        // Get all background containers\n        $bgContainers = $modx->getCollection('modResource', array(\n            'parent' => $modx->getOption('romanesco.global_backgrounds_id'),\n            'template' => 8\n        ));\n\n        // Get chunk with CSS template\n        if ($modx->getObject('modChunk', array('name' => 'cssTheme'))) {\n            $cssChunk = 'cssTheme';\n        } else {\n            $cssChunk = 'css';\n        }\n\n        // Each container represents a context\n        foreach ($bgContainers as $container) {\n            $context = $container->get('alias');\n\n            // Generate CSS for each context\n            $css = $modx->getChunk($cssChunk, array(\n                'context' => $context,\n            ));\n\n            if ($context) {\n                $staticFile = $modx->getOption('base_path') . 'assets/css/' . $context . '.css';\n\n                if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n                }\n            }\n        }\n\n        // Also generate a default CSS file\n        $staticFile = $modx->getOption('base_path') . 'assets/css/site.css';\n        $css = $modx->getChunk($cssChunk);\n\n        if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n        }\n\n        // Minify CSS\n        if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n            exec(\n                'NODE_VERSION=12 \"$HOME/.nvm/nvm-exec\"' .\n                ' gulp build-custom' .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .\n                ' 2>&1 &',\n                $output,\n                $return_css\n            );\n        }\n\n        // Bump CSS version number to force refresh\n        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n        if ($versionCSS) {\n            $versionCSS->set('value', $versionCSS->get('value') + 0.01);\n            $versionCSS->save();\n        } else {\n            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n        }\n\n        // Clear cache\n        $modx->cacheManager->refresh();\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n * generateStaticCSS\n *\n * Creates static CSS files for each context, with their own global backgrounds.\n *\n * If you want a context to have its own set of backgrounds, you need to create\n * a child page under the Global Backgrounds container for it. Make sure the\n * template is GlobalBackgrounds too and that the alias matches the context_key!\n *\n * A default stylesheet (site.css) is also generated, containing only the\n * backgrounds at root level of the Global Backgrounds container.\n *\n * CSS files are regenerated each time a GlobalBackgrounds resource is saved.\n *\n * NB! The plugin priority should be set to something higher than 0. Otherwise,\n * users will need to save the resource twice to see their changes reflected.\n */\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n\n        // Abort if resource template is not GlobalBackground(s)\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            return true;\n        }\n\n        // Get all background containers\n        $bgContainers = $modx->getCollection('modResource', array(\n            'parent' => $modx->getOption('romanesco.global_backgrounds_id'),\n            'template' => 8\n        ));\n\n        // Get chunk with CSS template\n        if ($modx->getObject('modChunk', array('name' => 'cssTheme'))) {\n            $cssChunk = 'cssTheme';\n        } else {\n            $cssChunk = 'css';\n        }\n\n        // Each container represents a context\n        foreach ($bgContainers as $container) {\n            $context = $container->get('alias');\n\n            // Generate CSS for each context\n            $css = $modx->getChunk($cssChunk, array(\n                'context' => $context,\n            ));\n\n            if ($context) {\n                $staticFile = $modx->getOption('base_path') . 'assets/css/' . $context . '.css';\n\n                if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n                    $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n                }\n            }\n        }\n\n        // Also generate a default CSS file\n        $staticFile = $modx->getOption('base_path') . 'assets/css/site.css';\n        $css = $modx->getChunk($cssChunk);\n\n        if (!$modx->cacheManager->writeFile($staticFile, $css)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, \"Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}\", '', __FUNCTION__, __FILE__, __LINE__);\n        }\n\n        // Minify CSS\n        if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n            exec(\n                'NODE_VERSION=12 \"$HOME/.nvm/nvm-exec\"' .\n                ' gulp build-custom' .\n                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .\n                ' 2>&1 &',\n                $output,\n                $return_css\n            );\n        }\n\n        // Bump CSS version number to force refresh\n        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));\n        if ($versionCSS) {\n            $versionCSS->set('value', $versionCSS->get('value') + 0.01);\n            $versionCSS->save();\n        } else {\n            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');\n        }\n\n        // Clear cache\n        $modx->cacheManager->refresh();\n\n        break;\n}"

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
 */

switch ($modx->event->name) {
    case 'OnDocFormSave':

        // Abort if resource template is not GlobalBackground(s)
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            return true;
        }

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

        // Each container represents a context
        foreach ($bgContainers as $container) {
            $context = $container->get('alias');

            // Generate CSS for each context
            $css = $modx->getChunk($cssChunk, array(
                'context' => $context,
            ));

            if ($context) {
                $staticFile = $modx->getOption('base_path') . 'assets/css/' . $context . '.css';

                if (!$modx->cacheManager->writeFile($staticFile, $css)) {
                    $modx->log(modX::LOG_LEVEL_ERROR, "Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}", '', __FUNCTION__, __FILE__, __LINE__);
                }
            }
        }

        // Also generate a default CSS file
        $staticFile = $modx->getOption('base_path') . 'assets/css/site.css';
        $css = $modx->getChunk($cssChunk);

        if (!$modx->cacheManager->writeFile($staticFile, $css)) {
            $modx->log(modX::LOG_LEVEL_ERROR, "Error caching output from Resource {$modx->resource->get('id')} to static file {$staticFile}", '', __FUNCTION__, __FILE__, __LINE__);
        }

        // Minify CSS
        if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {
            exec(
                'NODE_VERSION=12 "$HOME/.nvm/nvm-exec"' .
                ' gulp build-custom' .
                ' --gulpfile ' . escapeshellcmd($modx->getOption('assets_path')) . 'semantic/gulpfile.js' .
                ' 2>&1 &',
                $output,
                $return_css
            );
        }

        // Bump CSS version number to force refresh
        $versionCSS = $modx->getObject('modSystemSetting', array('key' => 'romanesco.assets_version_css'));
        if ($versionCSS) {
            $versionCSS->set('value', $versionCSS->get('value') + 0.01);
            $versionCSS->save();
        } else {
            $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find romanesco.assets_version_css setting');
        }

        // Clear cache
        $modx->cacheManager->refresh();

        break;
}