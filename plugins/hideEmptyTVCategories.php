id: 23
name: hideEmptyTVCategories
category: c_global
plugincode: "/**\n * DEPRECATED\n *\n * Empty TV categories are now automatically hidden in MODX 2.6 and higher.\n */\nswitch ($modx->event->name) {\n    case 'OnManagerPageInit':\n        $jsFile = '/assets/js/hide-empty-tv-categories.js';\n        $modx->regClientStartupScript($jsFile);\n        break;\n}\nreturn;"
properties: 'a:0:{}'
disabled: 1
content: "/**\n * DEPRECATED\n *\n * Empty TV categories are now automatically hidden in MODX 2.6 and higher.\n */\nswitch ($modx->event->name) {\n    case 'OnManagerPageInit':\n        $jsFile = '/assets/js/hide-empty-tv-categories.js';\n        $modx->regClientStartupScript($jsFile);\n        break;\n}\nreturn;"

-----


/**
 * DEPRECATED
 *
 * Empty TV categories are now automatically hidden in MODX 2.6 and higher.
 */
switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $jsFile = '/assets/js/hide-empty-tv-categories.js';
        $modx->regClientStartupScript($jsFile);
        break;
}
return;