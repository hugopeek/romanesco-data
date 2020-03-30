id: 147
name: stepsLoadAssets
description: 'Load CSS styles for Steps component. This is not included in semantic.css by default, to keep its file size down.'
category: f_presentation
snippet: "/**\n * stepsLoadAssets\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min.css');\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * stepsLoadAssets\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min.css');\n\nreturn '';"

-----


/**
 * stepsLoadAssets
 */

$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');

// Header
$modx->regClientCSS($assetsPathDist . '/components/step.min.css');

return '';