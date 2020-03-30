id: 58
name: fbLoadAssets
category: f_formblocks
snippet: "$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load Semantic UI form component separately\n$modx->regClientCSS($assetsPathDist . '/components/form.min.css');\n$modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathDist . '/components/form.min.js');\n$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load Semantic UI form component separately\n$modx->regClientCSS($assetsPathDist . '/components/form.min.css');\n$modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathDist . '/components/form.min.js');\n$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\nreturn '';"

-----


$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');
$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);

// Load Semantic UI form component separately
$modx->regClientCSS($assetsPathDist . '/components/form.min.css');
$modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');

// Load FormBlocks JS in footer
$modx->regClientScript($assetsPathDist . '/components/form.min.js');
$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');
$modx->regClientScript($assetsPathJS . '/formblocks.js');

// Load additional assets for file upload field, if present
if ($uploadFile) {
    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');
    $modx->regClientScript($assetsPathJS . '/fileupload.js');
}

return '';