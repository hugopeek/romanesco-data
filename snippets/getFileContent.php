id: 173
name: getFileContent
description: 'Load content of provided file. Only accepts file paths within project directory.'
category: f_basic
snippet: "/**\n * getFileContent snippet\n *\n * Load content of provided file. Only accepts file paths relative to project\n * root directory.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$path = MODX_BASE_PATH . $modx->getOption('path', $scriptProperties, $input);\nif (!file_exists($path)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getFileContent] Path not found: ' . $path);\n    return '';\n}\nif (!str_contains(realpath($path), MODX_BASE_PATH)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getFileContent] Funky file request: ' . $path);\n    return '';\n}\nreturn file_get_contents($path);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.getfilecontent.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.getfilecontent.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getFileContent snippet
 *
 * Load content of provided file. Only accepts file paths relative to project
 * root directory.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$path = MODX_BASE_PATH . $modx->getOption('path', $scriptProperties, $input);
if (!file_exists($path)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getFileContent] Path not found: ' . $path);
    return '';
}
if (!str_contains(realpath($path), MODX_BASE_PATH)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getFileContent] Funky file request: ' . $path);
    return '';
}
return file_get_contents($path);