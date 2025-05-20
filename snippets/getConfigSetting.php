id: 166
name: getConfigSetting
description: 'Context aware retrieval of a Configuration (ClientConfig) setting.'
category: f_basic
snippet: "/**\n * getConfigSetting\n *\n * Context aware retrieval of a Configuration (ClientConfig) setting.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\nuse FractalFarming\\Romanesco\\Romanesco;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n$context = $modx->getOption('context', $scriptProperties);\n$setting = $modx->getOption('setting', $scriptProperties);\n\nreturn $romanesco->getConfigSetting($setting, $context);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getConfigSetting
 *
 * Context aware retrieval of a Configuration (ClientConfig) setting.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use FractalFarming\Romanesco\Romanesco;

/** @var Romanesco $romanesco */
try {
    $romanesco = $modx->services->get('romanesco');
} catch (\Psr\Container\NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

$context = $modx->getOption('context', $scriptProperties);
$setting = $modx->getOption('setting', $scriptProperties);

return $romanesco->getConfigSetting($setting, $context);