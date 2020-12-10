id: 88
name: statusGridLoadAssets
description: 'Load CSS and JS dependencies for status grid.'
category: f_presentation
snippet: "/**\n * statusGridLoadAssets\n *\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min.css');\n$modx->regClientCSS($assetsPathDist . '/components/modal.min.css');\n\n// Footer\n$modx->regClientScript($assetsPathDist . '/components/modal.min.js');\n$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.js');\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.statusgridloadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.statusgridloadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
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