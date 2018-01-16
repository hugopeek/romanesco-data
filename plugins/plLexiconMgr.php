id: 19
name: plLexiconMgr
category: c_global
plugincode: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n// Load custom CSS styles for ContentBlocks\n$modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n\n// Mute strange and elusive ImagePlus output line\nif ($modx->event->name == 'OnManagerPageAfterRender') {\n    $outputToRemove = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n    $controller->content = str_replace($outputToRemove, '', $modx->controller->content);\n}"
properties: 'a:0:{}'
content: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n// Load custom CSS styles for ContentBlocks\n$modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');\n\n// Mute strange and elusive ImagePlus output line\nif ($modx->event->name == 'OnManagerPageAfterRender') {\n    $outputToRemove = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';\n    $controller->content = str_replace($outputToRemove, '', $modx->controller->content);\n}"

-----


$modx->controller->addLexiconTopic('romanescobackyard:manager');

// Load custom CSS styles for ContentBlocks
$modx->regClientCss($modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css');

// Mute strange and elusive ImagePlus output line
if ($modx->event->name == 'OnManagerPageAfterRender') {
    $outputToRemove = $modx->getOption('core_path') . 'components/imageplus/elements/tv/output/';
    $controller->content = str_replace($outputToRemove, '', $modx->controller->content);
}