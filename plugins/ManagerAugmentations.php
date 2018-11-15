id: 29
name: ManagerAugmentations
category: c_global
plugincode: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    // Load custom CSS styles\n    case 'OnDocFormRender':\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        break;\n\n    // Load CSS for manager on different event\n    case 'OnManagerPageBeforeRender':\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    // Mute rogue output lines from certain packages\n    case 'OnManagerPageAfterRender':\n        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);\n        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"
properties: 'a:0:{}'
content: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\nswitch ($modx->event->name) {\n    // Load custom CSS styles\n    case 'OnDocFormRender':\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n        break;\n\n    // Load CSS for manager on different event\n    case 'OnManagerPageBeforeRender':\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');\n        break;\n\n    // Mute rogue output lines from certain packages\n    case 'OnManagerPageAfterRender':\n        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);\n        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"

-----


$modx->controller->addLexiconTopic('romanescobackyard:manager');

switch ($modx->event->name) {
    // Load custom CSS styles
    case 'OnDocFormRender':
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');
        break;

    // Load CSS for manager on different event
    case 'OnManagerPageBeforeRender':
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css');
        break;

    // Mute rogue output lines from certain packages
    case 'OnManagerPageAfterRender':
        $removeLineImagePlus = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';
        $removeLineColorPicker = $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/';

        $managerContent = $modx->controller->content;
        $managerContent = str_replace($removeLineImagePlus, '', $managerContent);
        $managerContent = str_replace($removeLineColorPicker, '', $managerContent);

        $controller->content = $managerContent;
        break;
}
return;