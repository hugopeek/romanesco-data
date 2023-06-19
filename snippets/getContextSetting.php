id: 91
name: getContextSetting
description: 'Retrieve a specific setting from a context of choice. Useful if you want to "borrow" a setting from another context, e.g. the correct site_url for assets only available in that context.'
category: f_basic
snippet: "/**\n * getContextSetting\n *\n * Useful for retrieving settings from a different context.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$context = $modx->getOption('context', $scriptProperties);\n$setting = $modx->getOption('setting', $scriptProperties);\n\nreturn $romanesco->getContextSetting($setting, $context);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.getcontextsetting.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.getcontextsetting.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getContextSetting
 *
 * Useful for retrieving settings from a different context.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) return;

$context = $modx->getOption('context', $scriptProperties);
$setting = $modx->getOption('setting', $scriptProperties);

return $romanesco->getContextSetting($setting, $context);