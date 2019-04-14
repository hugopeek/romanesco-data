id: 29
name: ManagerAugmentations
category: c_global
plugincode: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css'); # for CB chunk previews\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);\n        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"
properties: 'a:0:{}'
content: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css'); # for CB chunk previews\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);\n        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"

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
        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';
        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';

        $managerContent = $modx->controller->content;
        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);
        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);

        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)
        $managerContent = str_replace('class="x-tab" title="_', 'class="x-tab" title="', $managerContent);

        $controller->content = $managerContent;
        break;
}
return;