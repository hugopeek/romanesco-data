id: 160
name: isEditor
description: 'Check if user is logged in to the manager.'
category: f_user
snippet: "/**\n * isEditor snippet\n *\n * Check if user is logged in to the manager.\n *\n * @author Mark Hamstra\n *\n * @var modX $modx\n */\n\nif ($modx->user instanceof MODX\\Revolution\\modUser) {\n    if ($modx->user->hasSessionContext('mgr')) {\n        return true;\n    }\n}\nreturn false;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * isEditor snippet
 *
 * Check if user is logged in to the manager.
 *
 * @author Mark Hamstra
 *
 * @var modX $modx
 */

if ($modx->user instanceof MODX\Revolution\modUser) {
    if ($modx->user->hasSessionContext('mgr')) {
        return true;
    }
}
return false;