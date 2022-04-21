id: 30
name: LexiconWeb
description: 'Load default lexicon in web context.'
category: c_global
plugincode: "/**\n * LexiconWeb\n *\n * Load default lexicon in web context.\n *\n * @var modX $modx\n * @package romanesco\n */\n\nif ($modx->event->name == 'OnHandleRequest') {\n    $modx->lexicon->load('romanescobackyard:default');\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.lexiconweb.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * LexiconWeb\n *\n * Load default lexicon in web context.\n *\n * @var modX $modx\n * @package romanesco\n */\n\nif ($modx->event->name == 'OnHandleRequest') {\n    $modx->lexicon->load('romanescobackyard:default');\n}"

-----


/**
 * LexiconWeb
 *
 * Load default lexicon in web context.
 *
 * @var modX $modx
 * @package romanesco
 */

if ($modx->event->name == 'OnHandleRequest') {
    $modx->lexicon->load('romanescobackyard:default');
}