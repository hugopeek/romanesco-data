id: 29
name: ManagerAugmentations
description: 'Small tweaks to the MODX backend, to enhance the Romanesco experience.'
category: c_global
plugincode: "/**\n * ManagerAugmentations\n *\n * Small tweaks to the MODX backend, to enhance the Romanesco experience.\n *\n * @var modX $modx\n * @var modManagerController $controller\n *\n * @package romanesco\n */\n\n$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n$versionCSS = $modx->getOption('romanesco.assets_version_css');\n$versionJS = $modx->getOption('romanesco.assets_version_js');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        /**\n         * @var modResource $resource\n         */\n\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css?v=' . $versionCSS);\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/step.css?v=' . $versionCSS); # for CB chunk previews\n\n        // Load custom CSS for Global Backgrounds\n        if ($resource->get('template') == 27) {\n            $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/backgrounds.css?v=' . $versionCSS);\n        }\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css?v=' . $versionCSS);\n\n        // Load JS and additional dependencies\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/jquery.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/semantic/dist/themes/romanesco/assets/vendor/arrive/arrive.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/manager.js?v=' . $versionJS . '\"></script>');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLines = array(\n            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',\n        );\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLines, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.manageraugmentations.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * ManagerAugmentations\n *\n * Small tweaks to the MODX backend, to enhance the Romanesco experience.\n *\n * @var modX $modx\n * @var modManagerController $controller\n *\n * @package romanesco\n */\n\n$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n$versionCSS = $modx->getOption('romanesco.assets_version_css');\n$versionJS = $modx->getOption('romanesco.assets_version_js');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        /**\n         * @var modResource $resource\n         */\n\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css?v=' . $versionCSS);\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/step.css?v=' . $versionCSS); # for CB chunk previews\n\n        // Load custom CSS for Global Backgrounds\n        if ($resource->get('template') == 27) {\n            $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/backgrounds.css?v=' . $versionCSS);\n        }\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css?v=' . $versionCSS);\n\n        // Load JS and additional dependencies\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/jquery.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/semantic/dist/themes/romanesco/assets/vendor/arrive/arrive.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/manager.js?v=' . $versionJS . '\"></script>');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLines = array(\n            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',\n        );\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLines, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"

-----


/**
 * ManagerAugmentations
 *
 * Small tweaks to the MODX backend, to enhance the Romanesco experience.
 *
 * @var modX $modx
 * @var modManagerController $controller
 *
 * @package romanesco
 */

$modx->controller->addLexiconTopic('romanescobackyard:manager');

$versionCSS = $modx->getOption('romanesco.assets_version_css');
$versionJS = $modx->getOption('romanesco.assets_version_js');

switch ($modx->event->name) {
    case 'OnDocFormRender':
        /**
         * @var modResource $resource
         */

        // Load custom CSS styles
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css?v=' . $versionCSS);
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/step.css?v=' . $versionCSS); # for CB chunk previews

        // Load custom CSS for Global Backgrounds
        if ($resource->get('template') == 27) {
            $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/backgrounds.css?v=' . $versionCSS);
        }
        break;

    case 'OnManagerPageBeforeRender':
        // Load CSS for manager on different event
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css?v=' . $versionCSS);

        // Load JS and additional dependencies
        $controller->addHtml('<script src="/assets/components/romanescobackyard/js/jquery.min.js"></script>');
        $controller->addHtml('<script src="/assets/semantic/dist/themes/romanesco/assets/vendor/arrive/arrive.min.js"></script>');
        $controller->addHtml('<script src="/assets/components/romanescobackyard/js/manager.js?v=' . $versionJS . '"></script>');
        break;

    case 'OnManagerPageAfterRender':
        // Mute rogue output lines from certain packages
        $removeLines = array(
            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',
            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',
            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',
        );

        $managerContent = $modx->controller->content;
        $managerContent = str_replace($removeLines, '', $managerContent);

        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)
        $managerContent = str_replace('class="x-tab" title="_', 'class="x-tab" title="', $managerContent);

        $controller->content = $managerContent;
        break;
}
return;