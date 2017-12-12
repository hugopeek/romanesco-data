id: 23
name: hideEmptyTVCategories
category: c_global
plugincode: "switch ($modx->event->name) {\n    case 'OnManagerPageInit':\n        $jsFile = '/assets/js/hide-empty-tv-categories.js';\n        $modx->regClientStartupScript($jsFile);\n        break;\n}\nreturn;"
properties: 'a:0:{}'
content: "switch ($modx->event->name) {\n    case 'OnManagerPageInit':\n        $jsFile = '/assets/js/hide-empty-tv-categories.js';\n        $modx->regClientStartupScript($jsFile);\n        break;\n}\nreturn;"

-----


switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $jsFile = '/assets/js/hide-empty-tv-categories.js';
        $modx->regClientStartupScript($jsFile);
        break;
}
return;