id: 166
name: getConfigSetting
description: 'Context aware retrieval of a Configuration (ClientConfig) setting.'
category: f_basic
snippet: "/**\n * getConfigSetting\n *\n * Context aware retrieval of a Configuration (ClientConfig) setting.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$context = $modx->getOption('context', $scriptProperties);\n$setting = $modx->getOption('setting', $scriptProperties);\n\nreturn $romanesco->getConfigSetting($setting, $context);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.getconfigsetting.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.getconfigsetting.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getConfigSetting
 *
 * Context aware retrieval of a Configuration (ClientConfig) setting.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) return;

$context = $modx->getOption('context', $scriptProperties);
$setting = $modx->getOption('setting', $scriptProperties);

return $romanesco->getConfigSetting($setting, $context);