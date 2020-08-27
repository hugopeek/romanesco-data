id: 151
name: generateCriticalCSS
description: 'Utility snippet to determine which CSS styles are used above the fold and write them to a custom CSS file. This needs NPM and the critical package to be installed.'
category: f_utility
snippet: "/**\n * generateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n */\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n$resource = $modx->getObject('modResource',$resourceID);\n\nif (!($resource instanceof modResource)) return;\n\n$romanesco->generateCriticalCSS($resourceID, $resource->get('uri'), $romanesco->getCssPath($resource->get('context_key')));\n\nreturn \"Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)\";"
properties: 'a:0:{}'
content: "/**\n * generateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n */\n\n$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n$resource = $modx->getObject('modResource',$resourceID);\n\nif (!($resource instanceof modResource)) return;\n\n$romanesco->generateCriticalCSS($resourceID, $resource->get('uri'), $romanesco->getCssPath($resource->get('context_key')));\n\nreturn \"Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)\";"

-----


/**
 * generateCriticalCSS
 *
 * Determine which CSS styles are used above the fold and write them to a custom
 * CSS file. This needs NPM and the critical package to be installed.
 *
 * https://github.com/addyosmani/critical
 *
 * Usage: this is a utility snippet. Place it in the content somewhere and visit
 * that page in the browser to generate the file.
 *
 * @var modX $modx
 * @var $scriptProperties array
 *
 * @package romanesco
 */

$rmCorePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$rmCorePath . 'model/romanescobackyard/',array('core_path' => $rmCorePath));

if (!($romanesco instanceof Romanesco)) return;

$resourceID = $modx->getOption('id', $scriptProperties, '');
$resource = $modx->getObject('modResource',$resourceID);

if (!($resource instanceof modResource)) return;

$romanesco->generateCriticalCSS($resourceID, $resource->get('uri'), $romanesco->getCssPath($resource->get('context_key')));

return "Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)";