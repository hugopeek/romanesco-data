id: 41
name: GenerateCriticalCSS
description: 'Determine which CSS styles are used above the fold and write them to a custom CSS file. This needs NPM and the critical package to be installed.'
category: c_performance
plugincode: "/**\n * GenerateCriticalCSS\n *\n * Determine which CSS styles are used above the fold and write them to a custom\n * CSS file. This needs NPM and the critical package to be installed.\n *\n * Because generating these stylesheets is very resource intensive, it is\n * inadvisable to run this in parallel (which is what would happen if you\n * trigger a save action on a batch of resources).\n *\n * To prevent performance issues, you should also install the Scheduler extra.\n * The plugin will then add each task to the Scheduler work queue, so they can\n * be executed serially (or in small batches).\n *\n * https://github.com/addyosmani/critical\n * https://github.com/modmore/Scheduler\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$basePath = $modx->getOption('base_path');\n$context = $modx->resource->get('context_key');\n\n// Abort if critical is not enabled for current context\nif (!$romanesco->getConfigSetting('critical_css', $context)) return;\n\n$cssPath = $romanesco->getContextSetting('romanesco.custom_css_path', $context);\n$distPath = $romanesco->getContextSetting('romanesco.semantic_dist_path', $context);\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $globalTemplates = [0,8,9,10,11,19,20,27]; // 0 = empty template\n        $excludedTemplates = explode(',', $romanesco->getConfigSetting('critical_excluded_templates', $context));\n        $excludedTemplates = array_merge($globalTemplates, $excludedTemplates);\n        $sharedTemplates = explode(',', $romanesco->getConfigSetting('critical_shared_templates', $context));\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n        $url = $modx->makeUrl($id,'','','full');\n        $uri = ltrim($resource->get('uri'),'/');\n        $uri = rtrim($uri,'/');\n        $uri = str_replace('.html','',$uri);\n        $criticalPath = rtrim($cssPath,'/') . '/critical/';\n\n        // Empty and excluded templates\n        if (in_array($resource->get('template'), $excludedTemplates) || !is_object($template)) {\n            $resource->setTVValue('critical_css_uri', '');\n            break;\n        }\n\n        // Templates with shared CSS\n        if (in_array($resource->get('template'), $sharedTemplates)) {\n            $uri = strtolower($template->get('templatename'));\n            $uri = str_replace(' ', '', $uri);\n        }\n\n        // Store full path to css file in a TV\n        $resource->setTVValue('critical_css_uri', $criticalPath . $uri . '.css');\n\n        // Use Scheduler for adding task to queue (if available)\n        /** @var Scheduler $scheduler */\n        $schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n        $scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');\n\n        if (!($scheduler instanceof Scheduler)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[GenerateCriticalCSS] Scheduler not found. Generating CSS directly.');\n\n            // NB: this will delay the save action significantly!\n            $romanesco->generateCriticalCSS(array(\n                'id' => $id,\n                'uri' => $uri,\n                'cssPath' => $cssPath,\n                'distPath' => $distPath,\n            ));\n\n            break;\n        }\n\n        $task = $scheduler->getTask('romanesco', 'GenerateCriticalCSS');\n\n        // Create task first if it doesn't exist\n        if (!($task instanceof sTask)) {\n            $task = $modx->newObject('sSnippetTask');\n            $task->fromArray(array(\n                'class_key' => 'sSnippetTask',\n                'content' => 'generateCriticalCSS',\n                'namespace' => 'romanesco',\n                'reference' => 'GenerateCriticalCSS',\n                'description' => 'Extract above-the-fold styling into custom CSS file.'\n            ));\n            if (!$task->save()) {\n                return 'Error saving GenerateCriticalCSS task';\n            }\n        }\n\n        // Check if task is not already scheduled\n        $pendingTasks = $modx->getCollection('sTaskRun', array(\n            'task' => $task->get('id'),\n            'status' => 0,\n            'executedon' => NULL,\n        ));\n        foreach ($pendingTasks as $pendingTask) {\n            $data = $pendingTask->get('data');\n            if ($data['id'] == $id && $data['url'] == $url) {\n                return true;\n            }\n        }\n\n        // Schedule a new run\n        $task->schedule('+1 minutes', array(\n            'id' => $id,\n            'url' => $url,\n            'uri' => $uri,\n        ));\n\n        break;\n\n//    case 'OnWebPagePrerender':\n//        if ($_SERVER['HTTPS'] === 'on') {\n//            $cssFile = $modx->resource->getTVValue('critical_css_uri');\n//\n//            // Create array of objects for the header\n//            $linkObjects = array();\n//            if ($cssFile && file_exists($basePath . $cssFile)) {\n//                $linkObjects[] = \"</{$cssFile}>; as=style; rel=preload;\";\n//            }\n//\n//            // Set PHP header\n//            header('Link: ' . implode(',',$linkObjects));\n//        }\n//\n//        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:43:"romanesco.generatecriticalcss.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * GenerateCriticalCSS
 *
 * Determine which CSS styles are used above the fold and write them to a custom
 * CSS file. This needs NPM and the critical package to be installed.
 *
 * Because generating these stylesheets is very resource intensive, it is
 * inadvisable to run this in parallel (which is what would happen if you
 * trigger a save action on a batch of resources).
 *
 * To prevent performance issues, you should also install the Scheduler extra.
 * The plugin will then add each task to the Scheduler work queue, so they can
 * be executed serially (or in small batches).
 *
 * https://github.com/addyosmani/critical
 * https://github.com/modmore/Scheduler
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

$basePath = $modx->getOption('base_path');
$context = $modx->resource->get('context_key');

// Abort if critical is not enabled for current context
if (!$romanesco->getConfigSetting('critical_css', $context)) return;

$cssPath = $romanesco->getContextSetting('romanesco.custom_css_path', $context);
$distPath = $romanesco->getContextSetting('romanesco.semantic_dist_path', $context);

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        $globalTemplates = [0,8,9,10,11,19,20,27]; // 0 = empty template
        $excludedTemplates = explode(',', $romanesco->getConfigSetting('critical_excluded_templates', $context));
        $excludedTemplates = array_merge($globalTemplates, $excludedTemplates);
        $sharedTemplates = explode(',', $romanesco->getConfigSetting('critical_shared_templates', $context));

        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));
        $url = $modx->makeUrl($id,'','','full');
        $uri = ltrim($resource->get('uri'),'/');
        $uri = rtrim($uri,'/');
        $uri = str_replace('.html','',$uri);
        $criticalPath = rtrim($cssPath,'/') . '/critical/';

        // Empty and excluded templates
        if (in_array($resource->get('template'), $excludedTemplates) || !is_object($template)) {
            $resource->setTVValue('critical_css_uri', '');
            break;
        }

        // Templates with shared CSS
        if (in_array($resource->get('template'), $sharedTemplates)) {
            $uri = strtolower($template->get('templatename'));
            $uri = str_replace(' ', '', $uri);
        }

        // Store full path to css file in a TV
        $resource->setTVValue('critical_css_uri', $criticalPath . $uri . '.css');

        // Use Scheduler for adding task to queue (if available)
        /** @var Scheduler $scheduler */
        $schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
        $scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');

        if (!($scheduler instanceof Scheduler)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[GenerateCriticalCSS] Scheduler not found. Generating CSS directly.');

            // NB: this will delay the save action significantly!
            $romanesco->generateCriticalCSS(array(
                'id' => $id,
                'uri' => $uri,
                'cssPath' => $cssPath,
                'distPath' => $distPath,
            ));

            break;
        }

        $task = $scheduler->getTask('romanesco', 'GenerateCriticalCSS');

        // Create task first if it doesn't exist
        if (!($task instanceof sTask)) {
            $task = $modx->newObject('sSnippetTask');
            $task->fromArray(array(
                'class_key' => 'sSnippetTask',
                'content' => 'generateCriticalCSS',
                'namespace' => 'romanesco',
                'reference' => 'GenerateCriticalCSS',
                'description' => 'Extract above-the-fold styling into custom CSS file.'
            ));
            if (!$task->save()) {
                return 'Error saving GenerateCriticalCSS task';
            }
        }

        // Check if task is not already scheduled
        $pendingTasks = $modx->getCollection('sTaskRun', array(
            'task' => $task->get('id'),
            'status' => 0,
            'executedon' => NULL,
        ));
        foreach ($pendingTasks as $pendingTask) {
            $data = $pendingTask->get('data');
            if ($data['id'] == $id && $data['url'] == $url) {
                return true;
            }
        }

        // Schedule a new run
        $task->schedule('+1 minutes', array(
            'id' => $id,
            'url' => $url,
            'uri' => $uri,
        ));

        break;

//    case 'OnWebPagePrerender':
//        if ($_SERVER['HTTPS'] === 'on') {
//            $cssFile = $modx->resource->getTVValue('critical_css_uri');
//
//            // Create array of objects for the header
//            $linkObjects = array();
//            if ($cssFile && file_exists($basePath . $cssFile)) {
//                $linkObjects[] = "</{$cssFile}>; as=style; rel=preload;";
//            }
//
//            // Set PHP header
//            header('Link: ' . implode(',',$linkObjects));
//        }
//
//        break;
}