id: 151
name: generateCriticalCSS
description: 'Utility snippet to determine which CSS styles are used above the fold and write them to a custom CSS file. This needs NPM and the critical package to be installed.'
category: f_performance
snippet: "/**\n * generateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * https://github.com/addyosmani/critical\n *\n * It works by using runProcessor to save the given resource, which triggers\n * the GenerateCriticalCSS plugin, which in turn triggers the critical gulp task.\n * This detour is required, because the gulp task needs to know the exact path\n * of the critical CSS file, which is stored in a TV. Without the save action,\n * that TV might still be empty.\n *\n * Usage:\n *\n * - As a utility snippet. Place it in the content somewhere and visit that page\n *   in the browser to generate the file.\n * - As tpl inside a getResources / pdoTools call, to generate CSS for a batch\n *   of resources. Be careful though: will quickly lead to performance issues!\n * - As snippet source for a Scheduler task. This will bypass the processor\n *   part and execute the task directly.\n *\n * Example:\n *\n * [[!generateCriticalCSS? &id=`[[+id]]`]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var Romanesco $romanesco\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse MODX\\Revolution\\modResource;\nuse Psr\\Container\\NotFoundExceptionInterface;\nuse FractalFarming\\Romanesco\\Romanesco;\n\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n    return '';\n}\n\n$resourceID = (int) $modx->getOption('id', $scriptProperties, 0);\n$resourceURL = $modx->getOption('url', $scriptProperties, '');\n$resourceURI = $modx->getOption('uri', $scriptProperties, '');\n$context = $modx->getOption('context', $scriptProperties, '');\n\n// Make sure we have a resource to work with\nif ($resourceID <= 0) return '';\n$resource = $modx->getObject(modResource::class, $resourceID);\nif (!($resource instanceof modResource)) return '';\n\n// Fall back on existing resource values if needed\n$uri = $resourceURI !== '' ? $resourceURI : $resource->get('uri');\n$context = $context !== '' ? $context : $resource->get('context_key');\n\n// If snippet is run as scheduled task, generate CSS directly\nif (isset($task) && is_object($task)) {\n    $romanesco->generateCriticalCSS([\n        'id' => $resourceID,\n        'url' => $resourceURL,\n        'uri' => $uri,\n        'cssPathSemantic' => $romanesco->getContextSetting('romanesco.semantic_css_path', $context),\n        'cssPathCustom' => $romanesco->getContextSetting('romanesco.custom_css_path', $context),\n        'criticalPath' => $romanesco->getContextSetting('romanesco.critical_css_path', $context),\n        'distPath' => $romanesco->getContextSetting('romanesco.semantic_dist_path', $context),\n    ]);\n\n    return \"Critical CSS generated for: $uri ($resourceID)\";\n}\n\n// Run update processor to generate the critical_css_uri TV value\n// NB: some fields are required for the processor to run!\n// NB: sometimes an old alias is retrieved when alias is not forwarded!!\n$resourceFields = [\n    'id' => $resourceID,\n    'pagetitle' => $resource->get('pagetitle'),\n    'alias' => $resource->get('alias'),\n    'context_key' => $resource->get('context_key'),\n    'class_key' => $resource->get('class_key'),\n    'published' => $resource->get('published')\n];\n\n// The update processor will trigger the GenerateCriticalCSS plugin\n$response = $modx->runProcessor('resource/update', $resourceFields);\n\nif (is_object($response) && $response->isError()) {\n    $error = 'Failed to update resource: ' . $resource->get('pagetitle') . '. Errors: ' . implode(', ', $response->getAllErrors());\n    $modx->log(modX::LOG_LEVEL_ERROR, $error, __METHOD__, __LINE__);\n    return $error;\n}\n\nreturn \"Critical CSS will be generated for: $uri ($resourceID)\";"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * generateCriticalCSS
 *
 * Determine which CSS styles are used above the fold and write them to a custom
 * CSS file. This needs NPM and the critical package to be installed.
 *
 * https://github.com/addyosmani/critical
 *
 * It works by using runProcessor to save the given resource, which triggers
 * the GenerateCriticalCSS plugin, which in turn triggers the critical gulp task.
 * This detour is required, because the gulp task needs to know the exact path
 * of the critical CSS file, which is stored in a TV. Without the save action,
 * that TV might still be empty.
 *
 * Usage:
 *
 * - As a utility snippet. Place it in the content somewhere and visit that page
 *   in the browser to generate the file.
 * - As tpl inside a getResources / pdoTools call, to generate CSS for a batch
 *   of resources. Be careful though: will quickly lead to performance issues!
 * - As snippet source for a Scheduler task. This will bypass the processor
 *   part and execute the task directly.
 *
 * Example:
 *
 * [[!generateCriticalCSS? &id=`[[+id]]`]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var object $task
 * @var Romanesco $romanesco
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use MODX\Revolution\modResource;
use Psr\Container\NotFoundExceptionInterface;
use FractalFarming\Romanesco\Romanesco;

try {
    $romanesco = $modx->services->get('romanesco');
} catch (NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
    return '';
}

$resourceID = (int) $modx->getOption('id', $scriptProperties, 0);
$resourceURL = $modx->getOption('url', $scriptProperties, '');
$resourceURI = $modx->getOption('uri', $scriptProperties, '');
$context = $modx->getOption('context', $scriptProperties, '');

// Make sure we have a resource to work with
if ($resourceID <= 0) return '';
$resource = $modx->getObject(modResource::class, $resourceID);
if (!($resource instanceof modResource)) return '';

// Fall back on existing resource values if needed
$uri = $resourceURI !== '' ? $resourceURI : $resource->get('uri');
$context = $context !== '' ? $context : $resource->get('context_key');

// If snippet is run as scheduled task, generate CSS directly
if (isset($task) && is_object($task)) {
    $romanesco->generateCriticalCSS([
        'id' => $resourceID,
        'url' => $resourceURL,
        'uri' => $uri,
        'cssPathSemantic' => $romanesco->getContextSetting('romanesco.semantic_css_path', $context),
        'cssPathCustom' => $romanesco->getContextSetting('romanesco.custom_css_path', $context),
        'criticalPath' => $romanesco->getContextSetting('romanesco.critical_css_path', $context),
        'distPath' => $romanesco->getContextSetting('romanesco.semantic_dist_path', $context),
    ]);

    return "Critical CSS generated for: $uri ($resourceID)";
}

// Run update processor to generate the critical_css_uri TV value
// NB: some fields are required for the processor to run!
// NB: sometimes an old alias is retrieved when alias is not forwarded!!
$resourceFields = [
    'id' => $resourceID,
    'pagetitle' => $resource->get('pagetitle'),
    'alias' => $resource->get('alias'),
    'context_key' => $resource->get('context_key'),
    'class_key' => $resource->get('class_key'),
    'published' => $resource->get('published')
];

// The update processor will trigger the GenerateCriticalCSS plugin
$response = $modx->runProcessor('resource/update', $resourceFields);

if (is_object($response) && $response->isError()) {
    $error = 'Failed to update resource: ' . $resource->get('pagetitle') . '. Errors: ' . implode(', ', $response->getAllErrors());
    $modx->log(modX::LOG_LEVEL_ERROR, $error, __METHOD__, __LINE__);
    return $error;
}

return "Critical CSS will be generated for: $uri ($resourceID)";