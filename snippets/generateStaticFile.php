id: 141
name: generateStaticFile
description: 'Create a physical HTML file of a resource in the designated location. This is a utility snippet. Place it in the content somewhere and visit that page in the browser to generate the file.'
category: f_performance
snippet: "/**\n * generateStaticFile\n *\n * Create a physical HTML file of a resource in the designated location.\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * NB! Won't work if the snippet is on the page you want to export!\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n *\n * @todo: convert to plugin, support multiple files, add ability to change site_url.\n */\n\n$file = $modx->getOption('file', $scriptProperties, '');\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n\n$pathInfo = pathinfo($file);\n$path = $pathInfo['dirname'];\n\nif (!file_exists($path)) {\n    mkdir($path, 0755, true);\n}\n\nif ($file) {\n    // The resource needs to be processed by MODX first.\n    // The easiest way to get the result is through the browser.\n    $content = file_get_contents($modx->makeUrl($resourceID,'','','full')) . PHP_EOL;\n\n    file_put_contents($file, $content);\n}\n\nreturn 'Done.';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:42:"romanesco.generatestaticfile.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:43:"romanesco.generatestaticfile.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * generateStaticFile
 *
 * Create a physical HTML file of a resource in the designated location.
 *
 * Usage: this is a utility snippet. Place it in the content somewhere and visit
 * that page in the browser to generate the file.
 *
 * NB! Won't work if the snippet is on the page you want to export!
 *
 * @var modX $modx
 * @var $scriptProperties array
 *
 * @package romanesco
 *
 * @todo: convert to plugin, support multiple files, add ability to change site_url.
 */

$file = $modx->getOption('file', $scriptProperties, '');
$resourceID = $modx->getOption('id', $scriptProperties, '');

$pathInfo = pathinfo($file);
$path = $pathInfo['dirname'];

if (!file_exists($path)) {
    mkdir($path, 0755, true);
}

if ($file) {
    // The resource needs to be processed by MODX first.
    // The easiest way to get the result is through the browser.
    $content = file_get_contents($modx->makeUrl($resourceID,'','','full')) . PHP_EOL;

    file_put_contents($file, $content);
}

return 'Done.';