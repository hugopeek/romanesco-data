id: 58
name: fbLoadAssets
category: f_formblocks
snippet: "/**\n * fbLoadAssets snippet\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load Semantic UI form component separately (and async if critical CSS is enabled)\nif (!$romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $modx->regClientCSS($assetsPathDist . '/components/form.min.css');\n    $modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');\n} else {\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"assets/semantic/dist/components/form.min.css\" type=\"text/css\" media=\"print\" onload=\"this.media=\\'all\\'\">');\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"assets/semantic/dist/components/calendar.min.css\" type=\"text/css\" media=\"print\" onload=\"this.media=\\'all\\'\">');\n}\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathDist . '/components/form.min.js');\n$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\n// Load custom assets, if present\nif (is_file('assets/js/formblocks.min.js')) {\n    $modx->regClientScript('assets/js/formblocks.min.js');\n} elseif (is_file('assets/js/formblocks.js')) {\n    $modx->regClientScript('assets/js/formblocks.js');\n}\nif (is_file('assets/js/form-validation.min.js')) {\n    $modx->regClientScript('assets/js/form-validation.min.js');\n} elseif (is_file('assets/js/form-validation.js')) {\n    $modx->regClientScript('assets/js/form-validation.js');\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.fbloadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.fbloadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * fbLoadAssets snippet\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n\n// Load Semantic UI form component separately (and async if critical CSS is enabled)\nif (!$romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $modx->regClientCSS($assetsPathDist . '/components/form.min.css');\n    $modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');\n} else {\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"assets/semantic/dist/components/form.min.css\" type=\"text/css\" media=\"print\" onload=\"this.media=\\'all\\'\">');\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"assets/semantic/dist/components/calendar.min.css\" type=\"text/css\" media=\"print\" onload=\"this.media=\\'all\\'\">');\n}\n\n// Load FormBlocks JS in footer\n$modx->regClientScript($assetsPathDist . '/components/form.min.js');\n$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');\n$modx->regClientScript($assetsPathJS . '/formblocks.js');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');\n    $modx->regClientScript($assetsPathJS . '/fileupload.js');\n}\n\n// Load custom assets, if present\nif (is_file('assets/js/formblocks.min.js')) {\n    $modx->regClientScript('assets/js/formblocks.min.js');\n} elseif (is_file('assets/js/formblocks.js')) {\n    $modx->regClientScript('assets/js/formblocks.js');\n}\nif (is_file('assets/js/form-validation.min.js')) {\n    $modx->regClientScript('assets/js/form-validation.min.js');\n} elseif (is_file('assets/js/form-validation.js')) {\n    $modx->regClientScript('assets/js/form-validation.js');\n}\n\nreturn '';"

-----


/**
 * fbLoadAssets snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));
if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');
$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);

// Load Semantic UI form component separately (and async if critical CSS is enabled)
if (!$romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $modx->regClientCSS($assetsPathDist . '/components/form.min.css');
    $modx->regClientCSS($assetsPathDist . '/components/calendar.min.css');
} else {
    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="assets/semantic/dist/components/form.min.css" type="text/css" media="print" onload="this.media=\'all\'">');
    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="assets/semantic/dist/components/calendar.min.css" type="text/css" media="print" onload="this.media=\'all\'">');
}

// Load FormBlocks JS in footer
$modx->regClientScript($assetsPathDist . '/components/form.min.js');
$modx->regClientScript($assetsPathDist . '/components/calendar.min.js');
$modx->regClientScript($assetsPathJS . '/formblocks.js');

// Load additional assets for file upload field, if present
if ($uploadFile) {
    $modx->regClientScript($assetsPathVendor . '/arrive/arrive.min.js');
    $modx->regClientScript($assetsPathJS . '/fileupload.js');
}

// Load custom assets, if present
if (is_file('assets/js/formblocks.min.js')) {
    $modx->regClientScript('assets/js/formblocks.min.js');
} elseif (is_file('assets/js/formblocks.js')) {
    $modx->regClientScript('assets/js/formblocks.js');
}
if (is_file('assets/js/form-validation.min.js')) {
    $modx->regClientScript('assets/js/form-validation.min.js');
} elseif (is_file('assets/js/form-validation.js')) {
    $modx->regClientScript('assets/js/form-validation.js');
}

return '';