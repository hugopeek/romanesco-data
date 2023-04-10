id: 29
name: ManagerAugmentations
description: 'Small tweaks to the MODX backend, to enhance the Romanesco experience.'
category: c_global
plugincode: "/**\n * ManagerAugmentations\n *\n * Small tweaks to the MODX backend, to enhance the Romanesco experience.\n *\n * @var modX $modx\n * @var modManagerController $controller\n *\n * @package romanesco\n */\n\n$versionCSS = $modx->getOption('romanesco.assets_version_css');\n$versionJS = $modx->getOption('romanesco.assets_version_js');\n\nswitch ($modx->event->name) {\n    case 'OnDocFormRender':\n        /**\n         * @var modResource $resource\n         */\n\n        // Load custom CSS styles\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css?v=' . $versionCSS);\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/step.css?v=' . $versionCSS); # for CB chunk previews\n\n        // Load custom CSS for Global Backgrounds\n        if ($resource->get('template') == 27) {\n            $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/backgrounds.css?v=' . $versionCSS);\n        }\n        break;\n\n    case 'OnManagerPageBeforeRender':\n        $modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n        // Load CSS for manager on different event\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews\n        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css?v=' . $versionCSS);\n\n        // Load JS and additional dependencies\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/jquery.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/semantic/dist/themes/romanesco/assets/vendor/arrive/arrive.min.js\"></script>');\n        $controller->addHtml('<script src=\"/assets/components/romanescobackyard/js/manager.js?v=' . $versionJS . '\"></script>');\n\n        // Hide advanced ContentBlocks features\n        $settings = $modx->getOption('romanesco.cb_hide_settings');\n        $fields = $modx->getOption('romanesco.cb_hide_fields');\n        //$sudo = $modx->user->get('sudo');\n\n        if ($settings || $fields) {\n            $controller->addHtml(\"\n                <script>\n                // Wait for ContentBlocks to finish loading.\n                // Depends on arrive.js library (included in Romanesco theme).\n                $(document).arrive('#contentblocks-modal', function() {\n                    $(document).arrive('.contentblocks-modal-content', function() {\n                        let settings = '$settings';\n                        let fields = '$fields';\n\n                        // Hide settings\n                        if (settings) {\n                            settings = settings.split(',');\n                            for (const name of settings) {\n                                $('.contentblocks-modal-field [data-name=' + name + ']').parent().hide();\n                            }\n                        }\n\n                        // Hide CB fields\n                        if (fields) {\n                            fields = fields.split(',');\n                            for (const id of fields) {\n                                $('li.tooltip a[data-id=' + id + ']').closest('li').hide();\n                            }\n                        }\n                    });\n                });\n                </script>\n            \");\n        }\n\n        // Load Ybug widget for collecting manager feedback\n        if ($modx->getOption('romanesco.manager_feedback') == 1) {\n            if ($modx->user->hasSessionContext('mgr')) {\n                $userName = $modx->user->get('username');\n                $userEmail = $modx->user->getOne('Profile')->get('email');\n            }\n            $controller->addHtml($modx->getChunk('feedbackWidgetJS', [\n                'project_id' => $modx->getOption('romanesco.ybug_project_id'),\n                'username' => $userName ?? '',\n                'email' => $userEmail ?? '',\n                'button_text' => $modx->lexicon('romanesco.feedback.button_text'),\n                'position' => 'bottom-right',\n            ]));\n        }\n        break;\n\n    case 'OnManagerPageAfterRender':\n        // Mute rogue output lines from certain packages\n        $removeLines = array(\n            $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/colorpicker/elements/tv/output/',\n            $modx->getOption('core_path') . 'components/redactor/elements/tvs/output/',\n        );\n\n        $managerContent = $modx->controller->content;\n        $managerContent = str_replace($removeLines, '', $managerContent);\n\n        // Remove underscore prefix in TV category tabs (so custom categories can have same name as core categories)\n        $managerContent = str_replace('class=\"x-tab\" title=\"_', 'class=\"x-tab\" title=\"', $managerContent);\n\n        $controller->content = $managerContent;\n        break;\n}\nreturn;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.manageraugmentations.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

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
        $modx->controller->addLexiconTopic('romanescobackyard:manager');

        // Load CSS for manager on different event
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/semantic.css?v=' . $versionCSS); # for CB chunk previews
        $modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/manager.css?v=' . $versionCSS);

        // Load JS and additional dependencies
        $controller->addHtml('<script src="/assets/components/romanescobackyard/js/jquery.min.js"></script>');
        $controller->addHtml('<script src="/assets/semantic/dist/themes/romanesco/assets/vendor/arrive/arrive.min.js"></script>');
        $controller->addHtml('<script src="/assets/components/romanescobackyard/js/manager.js?v=' . $versionJS . '"></script>');

        // Hide advanced ContentBlocks features
        $settings = $modx->getOption('romanesco.cb_hide_settings');
        $fields = $modx->getOption('romanesco.cb_hide_fields');
        //$sudo = $modx->user->get('sudo');

        if ($settings || $fields) {
            $controller->addHtml("
                <script>
                // Wait for ContentBlocks to finish loading.
                // Depends on arrive.js library (included in Romanesco theme).
                $(document).arrive('#contentblocks-modal', function() {
                    $(document).arrive('.contentblocks-modal-content', function() {
                        let settings = '$settings';
                        let fields = '$fields';

                        // Hide settings
                        if (settings) {
                            settings = settings.split(',');
                            for (const name of settings) {
                                $('.contentblocks-modal-field [data-name=' + name + ']').parent().hide();
                            }
                        }

                        // Hide CB fields
                        if (fields) {
                            fields = fields.split(',');
                            for (const id of fields) {
                                $('li.tooltip a[data-id=' + id + ']').closest('li').hide();
                            }
                        }
                    });
                });
                </script>
            ");
        }

        // Load Ybug widget for collecting manager feedback
        if ($modx->getOption('romanesco.manager_feedback') == 1) {
            if ($modx->user->hasSessionContext('mgr')) {
                $userName = $modx->user->get('username');
                $userEmail = $modx->user->getOne('Profile')->get('email');
            }
            $controller->addHtml($modx->getChunk('feedbackWidgetJS', [
                'project_id' => $modx->getOption('romanesco.ybug_project_id'),
                'username' => $userName ?? '',
                'email' => $userEmail ?? '',
                'button_text' => $modx->lexicon('romanesco.feedback.button_text'),
                'position' => 'bottom-right',
            ]));
        }
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