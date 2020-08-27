id: 141
name: generateStaticFile
description: 'Create a physical HTML file of a resource in the designated location. This is a utility snippet. Place it in the content somewhere and visit that page in the browser to generate the file.'
category: f_utility
snippet: "/**\n * generateStaticFile\n *\n * Create a physical HTML file of a resource in the designated location.\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * NB! Won't work if the snippet is on the page you want to export!\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n *\n * @todo: convert to plugin, support multiple files, add ability to change site_url.\n */\n\n$file = $modx->getOption('file', $scriptProperties, '');\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n\n$pathInfo = pathinfo($file);\n$path = $pathInfo['dirname'];\n\nif (!file_exists($path)) {\n    mkdir($path, 0755, true);\n}\n\nif ($file) {\n    // The resource needs to be processed by MODX first.\n    // The easiest way to get the result is through the browser.\n    $content = file_get_contents($modx->makeUrl($resourceID,'','','full')) . PHP_EOL;\n\n    file_put_contents($file, $content);\n}\n\nreturn 'Done.';"
properties: 'a:0:{}'
content: "/**\n * generateStaticFile\n *\n * Create a physical HTML file of a resource in the designated location.\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * NB! Won't work if the snippet is on the page you want to export!\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n *\n * @todo: convert to plugin, support multiple files, add ability to change site_url.\n */\n\n$file = $modx->getOption('file', $scriptProperties, '');\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n\n$pathInfo = pathinfo($file);\n$path = $pathInfo['dirname'];\n\nif (!file_exists($path)) {\n    mkdir($path, 0755, true);\n}\n\nif ($file) {\n    // The resource needs to be processed by MODX first.\n    // The easiest way to get the result is through the browser.\n    $content = file_get_contents($modx->makeUrl($resourceID,'','','full')) . PHP_EOL;\n\n    file_put_contents($file, $content);\n}\n\nreturn 'Done.';"

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