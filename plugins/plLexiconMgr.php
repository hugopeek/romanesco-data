id: 19
name: plLexiconMgr
category: c_global
plugincode: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n# Load custom CSS styles for ContentBlocks\n$url = $modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css';\n$modx->regClientCss($url);"
properties: 'a:0:{}'
content: "$modx->controller->addLexiconTopic('romanescobackyard:manager');\n\n# Load custom CSS styles for ContentBlocks\n$url = $modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css';\n$modx->regClientCss($url);"

-----


$modx->controller->addLexiconTopic('romanescobackyard:manager');

# Load custom CSS styles for ContentBlocks
$url = $modx->getOption('base_url') . 'assets/components/romanescobackyard/css/contentblocks.css';
$modx->regClientCss($url);