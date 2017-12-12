id: 58
name: fbLoadAssets
category: f_formblocks
snippet: "$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\nreturn '';"

-----


$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');
$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);

// Load FormBlocks JS in footer
$modx->regClientScript($assetsPathJS . '/formblocks.js');

// Load additional assets for file upload field, if present
if ($uploadFile) {
    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');
    $modx->regClientScript($assetsPathJS . '/fileupload.js');
}

return '';