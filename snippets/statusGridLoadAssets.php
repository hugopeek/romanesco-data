id: 88
name: statusGridLoadAssets
description: 'Load CSS and JS dependencies for status grid.'
category: f_presentation
snippet: "/**\n * statusGridLoadAssets\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');\n$modx->regClientCSS($assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css');\n\n// Footer\n$modx->regClientScript($assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js');\n$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js');\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.statusgridloadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:10:"deprecated";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.statusgridloadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * statusGridLoadAssets\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');\n$modx->regClientCSS($assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css');\n\n// Footer\n$modx->regClientScript($assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js');\n$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js');\n\nreturn '';"

-----


/**
 * statusGridLoadAssets
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

// Load strings to insert in asset paths when cache busting is enabled
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');
$cacheBusterJS = $romanesco->getCacheBustingString('JS');

// Header
$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');
$modx->regClientCSS($assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css');

// Footer
$modx->regClientScript($assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js');
$modx->regClientScript($assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js');

return '';