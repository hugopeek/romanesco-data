id: 147
name: stepsLoadAssets
description: 'Load CSS styles for Steps component. This is not included in semantic.css by default, to keep its file size down.'
category: f_presentation
snippet: "/**\n * stepsLoadAssets\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.stepsloadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:10:"deprecated";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.stepsloadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * stepsLoadAssets\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n\n// Header\n$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');\n\nreturn '';"

-----


/**
 * stepsLoadAssets
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

$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');

// Header
$modx->regClientCSS($assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css');

return '';