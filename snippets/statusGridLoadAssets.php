id: 88
name: statusGridLoadAssets
description: 'Load CSS and JS dependencies for status grid.'
category: f_presentation
snippet: "/**\n * statusGridLoadAssets\n *\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min.css');\n$modx->regClientCSS($assetsPathDist . '/components/modal.min.css');\n\n// Footer\n$modx->regClientScript($assetsPathDist . '/components/modal.min.js');\n$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.js');\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * statusGridLoadAssets\n *\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min.css');\n$modx->regClientCSS($assetsPathDist . '/components/modal.min.css');\n\n// Footer\n$modx->regClientScript($assetsPathDist . '/components/modal.min.js');\n$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.js');\n\nreturn '';"

-----


/**
 * statusGridLoadAssets
 *
 */

$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');

// Header
$modx->regClientCSS($assetsPathDist . '/components/step.min.css');
$modx->regClientCSS($assetsPathDist . '/components/modal.min.css');

// Footer
$modx->regClientScript($assetsPathDist . '/components/modal.min.js');
$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.js');

return '';