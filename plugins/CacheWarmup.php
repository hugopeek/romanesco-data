id: 48
name: CacheWarmup
description: 'Visit all URLs in sitemap to warm up the cache. This plugin creates a scheduler task to run the cacheWarmup snippet in the background.'
category: c_performance
plugincode: "/**\n * CacheWarmup\n *\n * Visit all URLs in sitemap to warm up the cache.\n * This plugin creates a scheduler task to run the cacheWarmup snippet in the\n * background.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse MODX\\Revolution\\modResource;\n\nif (!($modx->resource instanceof modResource)) return;\n\n$context = $modx->resource->get('context_key');\n$sitemapID = $modx->getOption('romanesco.sitemap_id', $scriptProperties, '');\n\nif (!$sitemapID) return;\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         * @var Scheduler $scheduler\n         */\n\n        // Use Scheduler for adding task to queue\n        $schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n        $scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');\n\n        if (!($scheduler instanceof Scheduler)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Scheduler not found. You\\'ll have to do your own warm upping.');\n            break;\n        }\n\n        // Get or create the warmup task\n        $task = $scheduler->getTask('romanesco', 'CacheWarmup');\n\n        if (!($task instanceof sTask)) {\n            $task = $modx->newObject('sSnippetTask');\n            $task->fromArray(array(\n                'class_key' => 'sSnippetTask',\n                'content' => 'cacheWarmup',\n                'namespace' => 'romanesco',\n                'reference' => 'CacheWarmup',\n                'description' => 'Batch process page visits to rebuild cache.'\n            ));\n            if (!$task->save()) {\n                $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Error saving CacheWarmup task!');\n                return;\n            }\n        }\n\n        // No need to run if task is already pending\n        $pendingTasks = $modx->getCollection('sTaskRun', array(\n            'task' => $task->get('id'),\n            'status' => 0,\n            'executedon' => NULL,\n        ));\n        if (count($pendingTasks) > 0) {\n            break;\n        }\n\n        // Schedule a new run\n        $task->schedule('+0 minutes', [\n            'sitemap_url' => $modx->makeUrl($sitemapID, '', '', 'full'),\n        ]);\n        $modx->log(modX::LOG_LEVEL_INFO, '[CacheWarmup] Scheduled new warmup task.');\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * CacheWarmup
 *
 * Visit all URLs in sitemap to warm up the cache.
 * This plugin creates a scheduler task to run the cacheWarmup snippet in the
 * background.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use MODX\Revolution\modResource;

if (!($modx->resource instanceof modResource)) return;

$context = $modx->resource->get('context_key');
$sitemapID = $modx->getOption('romanesco.sitemap_id', $scriptProperties, '');

if (!$sitemapID) return;

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         * @var Scheduler $scheduler
         */

        // Use Scheduler for adding task to queue
        $schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
        $scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');

        if (!($scheduler instanceof Scheduler)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Scheduler not found. You\'ll have to do your own warm upping.');
            break;
        }

        // Get or create the warmup task
        $task = $scheduler->getTask('romanesco', 'CacheWarmup');

        if (!($task instanceof sTask)) {
            $task = $modx->newObject('sSnippetTask');
            $task->fromArray(array(
                'class_key' => 'sSnippetTask',
                'content' => 'cacheWarmup',
                'namespace' => 'romanesco',
                'reference' => 'CacheWarmup',
                'description' => 'Batch process page visits to rebuild cache.'
            ));
            if (!$task->save()) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Error saving CacheWarmup task!');
                return;
            }
        }

        // No need to run if task is already pending
        $pendingTasks = $modx->getCollection('sTaskRun', array(
            'task' => $task->get('id'),
            'status' => 0,
            'executedon' => NULL,
        ));
        if (count($pendingTasks) > 0) {
            break;
        }

        // Schedule a new run
        $task->schedule('+0 minutes', [
            'sitemap_url' => $modx->makeUrl($sitemapID, '', '', 'full'),
        ]);
        $modx->log(modX::LOG_LEVEL_INFO, '[CacheWarmup] Scheduled new warmup task.');

        break;
}