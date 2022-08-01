id: 164
name: minifyCSS
description: 'Generate minified version of given CSS file. To avoid increased saving times, execution of the Gulp process will be added to a task queue if Scheduler is installed.'
category: f_performance
snippet: "/**\n * MinifyCSS snippet\n *\n * Generate minified version of given CSS file.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var string $input\n * @var Scheduler $scheduler\n *\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));\n$corePath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $corePath . 'model/scheduler/');\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n// Get CSS path from task properties, snippet properties or input\n$cssPath = $modx->getOption('css_path', $scriptProperties, $input);\n\n// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed\nif (!($scheduler instanceof Scheduler) || is_object($task)) {\n    $romanesco->minifyCSS($cssPath);\n    return;\n}\n\n// From here on, we're scheduling a task\n$task = $scheduler->getTask('romanesco', 'MinifyCSS');\n\n// Create task first if it doesn't exist\nif (!($task instanceof sTask)) {\n    $task = $modx->newObject('sSnippetTask');\n    $task->fromArray([\n        'class_key' => 'sSnippetTask',\n        'content' => 'minifyCSS',\n        'namespace' => 'romanesco',\n        'reference' => 'MinifyCSS',\n        'description' => 'Generate minified version of given CSS file.'\n    ]);\n    if (!$task->save()) {\n        return 'Error saving MinifyCSS task';\n    }\n}\n\n// Check if task is not already scheduled\n$pendingTasks = $modx->getCollection('sTaskRun', [\n    'task' => $task->get('id'),\n    'status' => 0,\n    'executedon' => NULL,\n]);\nforeach ($pendingTasks as $pendingTask) {\n    $data = $pendingTask->get('data');\n    if ($data['css_path'] == $cssPath) {\n        return;\n    }\n}\n\n// Schedule a new run\n$task->schedule('+1 minutes', [\n    'css_path' => $cssPath\n]);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:33:"romanesco.minifycss.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:34:"romanesco.minifycss.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * MinifyCSS snippet\n *\n * Generate minified version of given CSS file.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var string $input\n * @var Scheduler $scheduler\n *\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));\n$corePath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $corePath . 'model/scheduler/');\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n// Get CSS path from task properties, snippet properties or input\n$cssPath = $modx->getOption('css_path', $scriptProperties, $input);\n\n// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed\nif (!($scheduler instanceof Scheduler) || is_object($task)) {\n    $romanesco->minifyCSS($cssPath);\n    return;\n}\n\n// From here on, we're scheduling a task\n$task = $scheduler->getTask('romanesco', 'MinifyCSS');\n\n// Create task first if it doesn't exist\nif (!($task instanceof sTask)) {\n    $task = $modx->newObject('sSnippetTask');\n    $task->fromArray([\n        'class_key' => 'sSnippetTask',\n        'content' => 'minifyCSS',\n        'namespace' => 'romanesco',\n        'reference' => 'MinifyCSS',\n        'description' => 'Generate minified version of given CSS file.'\n    ]);\n    if (!$task->save()) {\n        return 'Error saving MinifyCSS task';\n    }\n}\n\n// Check if task is not already scheduled\n$pendingTasks = $modx->getCollection('sTaskRun', [\n    'task' => $task->get('id'),\n    'status' => 0,\n    'executedon' => NULL,\n]);\nforeach ($pendingTasks as $pendingTask) {\n    $data = $pendingTask->get('data');\n    if ($data['css_path'] == $cssPath) {\n        return;\n    }\n}\n\n// Schedule a new run\n$task->schedule('+1 minutes', [\n    'css_path' => $cssPath\n]);"

-----


/**
 * MinifyCSS snippet
 *
 * Generate minified version of given CSS file.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var object $task
 * @var string $input
 * @var Scheduler $scheduler
 *
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/', array('core_path' => $corePath));
$corePath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
$scheduler = $modx->getService('scheduler', 'Scheduler', $corePath . 'model/scheduler/');

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

// Get CSS path from task properties, snippet properties or input
$cssPath = $modx->getOption('css_path', $scriptProperties, $input);

// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed
if (!($scheduler instanceof Scheduler) || is_object($task)) {
    $romanesco->minifyCSS($cssPath);
    return;
}

// From here on, we're scheduling a task
$task = $scheduler->getTask('romanesco', 'MinifyCSS');

// Create task first if it doesn't exist
if (!($task instanceof sTask)) {
    $task = $modx->newObject('sSnippetTask');
    $task->fromArray([
        'class_key' => 'sSnippetTask',
        'content' => 'minifyCSS',
        'namespace' => 'romanesco',
        'reference' => 'MinifyCSS',
        'description' => 'Generate minified version of given CSS file.'
    ]);
    if (!$task->save()) {
        return 'Error saving MinifyCSS task';
    }
}

// Check if task is not already scheduled
$pendingTasks = $modx->getCollection('sTaskRun', [
    'task' => $task->get('id'),
    'status' => 0,
    'executedon' => NULL,
]);
foreach ($pendingTasks as $pendingTask) {
    $data = $pendingTask->get('data');
    if ($data['css_path'] == $cssPath) {
        return;
    }
}

// Schedule a new run
$task->schedule('+1 minutes', [
    'css_path' => $cssPath
]);