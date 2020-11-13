id: 151
name: generateCriticalCSS
description: 'Utility snippet to determine which CSS styles are used above the fold and write them to a custom CSS file. This needs NPM and the critical package to be installed.'
category: f_performance
snippet: "/**\n * generateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n$parallel = $modx->getOption('parallel', $scriptProperties, 0);\n$resource = $modx->getObject('modResource',$resourceID);\n\nif (!($resource instanceof modResource)) return;\n\n$romanesco->generateCriticalCSS(array(\n    'id' => $resourceID,\n    'uri' => $resource->get('uri'),\n    'cssPath' => $romanesco->getContextSetting('romanesco.custom_css_path', $resource->get('context_key')),\n    'distPath' => $romanesco->getContextSetting('romanesco.semantic_dist_path', $resource->get('context_key')),\n    'parallel' => $parallel,\n));\n\nreturn \"Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)\";"
properties: 'a:0:{}'
content: "/**\n * generateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * Usage: this is a utility snippet. Place it in the content somewhere and visit\n * that page in the browser to generate the file.\n *\n * @var modX $modx\n * @var $scriptProperties array\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) return;\n\n$resourceID = $modx->getOption('id', $scriptProperties, '');\n$parallel = $modx->getOption('parallel', $scriptProperties, 0);\n$resource = $modx->getObject('modResource',$resourceID);\n\nif (!($resource instanceof modResource)) return;\n\n$romanesco->generateCriticalCSS(array(\n    'id' => $resourceID,\n    'uri' => $resource->get('uri'),\n    'cssPath' => $romanesco->getContextSetting('romanesco.custom_css_path', $resource->get('context_key')),\n    'distPath' => $romanesco->getContextSetting('romanesco.semantic_dist_path', $resource->get('context_key')),\n    'parallel' => $parallel,\n));\n\nreturn \"Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)\";"

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

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) return;

$resourceID = $modx->getOption('id', $scriptProperties, '');
$parallel = $modx->getOption('parallel', $scriptProperties, 0);
$resource = $modx->getObject('modResource',$resourceID);

if (!($resource instanceof modResource)) return;

// Run update processor to generate the critical_css_uri TV value
// NB: processor won't run without pagetitle and context_key!
// NB: sometimes an old alias is retrieved when alias is not forwarded!!
$resourceFields = array(
    'id' => $resourceID,
    'pagetitle' => $resource->get('pagetitle'),
    'alias' => $resource->get('alias'),
    'context_key' => $resource->get('context_key')
);

$response = $modx->runProcessor('resource/update', $resourceFields);

if ($response->isError()) {
    $error = 'Failed to update resource: ' . $resource->get('pagetitle') . '. Errors: ' . implode(', ', $response->getAllErrors());
    $modx->log(MODX::LOG_LEVEL_ERROR, $error, __METHOD__, __LINE__);
    return $error;
}

// Processor triggers the GenerateCriticalCSS plugin already
// $romanesco->generateCriticalCSS(array(
//     'id' => $resourceID,
//     'uri' => $resource->get('uri'),
//     'cssPath' => $romanesco->getContextSetting('romanesco.custom_css_path', $resource->get('context_key')),
//     'distPath' => $romanesco->getContextSetting('romanesco.semantic_dist_path', $resource->get('context_key')),
//     'parallel' => $parallel,
// ));

return "Critical CSS generated for <strong>{$resource->get('uri')}</strong> ($resourceID)";
