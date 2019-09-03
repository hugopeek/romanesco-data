id: 29
name: ManagerAugmentations
category: c_global
plugincode: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css'); # for CB chunk previews\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLines = array(\n            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',\n        );\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLines, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"
properties: 'a:0:{}'
content: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css'); # for CB chunk previews\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLines = array(\n            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',\n        );\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLines, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"

-----


$modx->controller->addLexiconTopic('romanescobackyard:manager');

switch ($modx->event->name) {
    case 'OnDocFormRender':
        // Load custom CSS styles
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css'); # for CB chunk previews
        break;

    case 'OnManagerPageBeforeRender':
        // Load CSS for manager on different event
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');
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