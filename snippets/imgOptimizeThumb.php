id: 158
name: imgOptimizeThumb
description: 'Post hook for pThumb, that runs after the thumbnail is generated. It uses the Squoosh library from Google to create a WebP version of the image and optimize the original.'
category: f_performance
snippet: "/**\n * imgOptimizeThumb\n *\n * Output modifier for pThumb, to further optimize the generated thumbnail.\n *\n * It uses the Squoosh library from Google to create a WebP version of the image\n * and optimize the original. You need to install the Squoosh CLI package on\n * your server with NPM: 'npm install -g @squoosh/cli'\n *\n * If the Scheduler extra is installed, the Squoosh command is added there as an\n * individual task. This means it takes a little while for all the images to be\n * generated. Without Scheduler they're created when the page is requested,\n * but the initial request will take a lot longer (the thumbnails are\n * also being generated here).\n *\n * To serve the WebP images in the browser, use Nginx to intercept the image\n * request and redirect it to the WebP version. It will do so by setting a\n * different header with the correct mime type, but only if the WebP\n * image is available (and if the browser supports it). So you don't need to\n * change the image paths in MODX or provide any fallbacks in HTML.\n *\n * This guide perfectly explains this little trick:\n * https://alexey.detr.us/en/posts/2018/2018-08-20-webp-nginx-with-fallback/\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var string $input\n * @var string $options\n */\n\nuse Jcupitt\\Vips;\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n// Get image path from task properties, pThumb properties or input\n$imgPath = $modx->getOption('img_path', $scriptProperties, $input ?? null);\n$imgPathFull = str_replace('//','/', MODX_BASE_PATH . $imgPath);\n$imgName = pathinfo($imgPathFull, PATHINFO_FILENAME);\n$imgType = pathinfo($imgPathFull, PATHINFO_EXTENSION);\n$imgType = strtolower($imgType);\n$outputDir = dirname($imgPathFull);\n\n// Check if path or file exist\nif (!$imgPath || !file_exists($imgPathFull)) {\n    $modx->log(modX::LOG_LEVEL_WARN, '[imgOptimizeThumb] Image not found: ' . $imgPathFull);\n    return $imgPath;\n}\n\n// Look for resource context key\n$context = $modx->getOption('context', $scriptProperties, '');\nif (is_object($modx->resource) && !$context) {\n    $context = $modx->resource->get('context_key');\n}\n\n// Abort if optimization is disabled for this context\nif (!$romanesco->getConfigSetting('img_optimize', $context)) {\n    return $imgPath;\n}\n\n// Abort if file format is not supported\nif ($imgType == 'svg') {\n    return $imgPath;\n}\n\n// And if WebP version is already created\nif (file_exists($outputDir . '/' . $imgName . '.webp')) {\n    return $imgPath;\n}\n\n// Get image quality from output modifier option, task properties or corresponding context setting\n$imgQuality = $options ?? $modx->getOption('img_quality', $scriptProperties);\nif (!$imgQuality) {\n    $imgQuality = $romanesco->getConfigSetting('img_quality', $context);\n}\n$imgQuality = (int) $imgQuality;\n\n$configWebP = [\n    \"Q\" => $imgQuality,\n];\n\n$configJPG = [\n    \"Q\" => $imgQuality,\n];\n\n$configPNG = [\n    \"Q\" => $imgQuality,\n];\n\n// Use Scheduler for adding task to queue (if available)\n/** @var Scheduler $scheduler */\n$schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');\n\n// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed\nif (!($scheduler instanceof Scheduler) || isset($task)) {\n    try {\n        $image = Vips\\Image::newFromFile($imgPathFull);\n    }\n    catch (Vips\\Exception $e) {\n        $modx->log(modX::LOG_LEVEL_ERROR, '[Vips] ' . $e->getMessage());\n        return $imgPath;\n    }\n\n    // Create WebP version\n    $image->webpsave($outputDir . '/' . $imgName . '.webp', $configWebP);\n\n    // Overwrite original with optimized version\n    if ($imgType == 'png') {\n        $image->pngsave($imgPathFull, $configPNG);\n    }\n    if ($imgType == 'jpg' || $imgType == 'jpeg') {\n        $image->jpegsave($imgPathFull, $configJPG);\n    }\n\n    return $imgPath;\n}\n\n// From here on, we're scheduling a task\n$task = $scheduler->getTask('romanesco', 'ImgOptimizeThumb');\n\n// Create task first if it doesn't exist\nif (!($task instanceof sTask)) {\n    $task = $modx->newObject('sSnippetTask');\n    $task->fromArray(array(\n        'class_key' => 'sSnippetTask',\n        'content' => 'imgOptimizeThumb',\n        'namespace' => 'romanesco',\n        'reference' => 'ImgOptimizeThumb',\n        'description' => 'Create WebP version and reduce file size of thumbnail image.'\n    ));\n    if (!$task->save()) {\n        return 'Error saving ImgOptimizeThumb task';\n    }\n}\n\n// Check if task is not already scheduled\n$pendingTasks = $modx->getCollection('sTaskRun', array(\n    'task' => $task->get('id'),\n    'status' => 0,\n    'executedon' => NULL,\n));\nforeach ($pendingTasks as $pendingTask) {\n    $data = $pendingTask->get('data');\n    if (isset($data['img_path']) && $data['img_path'] == $imgPath && isset($data['img_quality']) && $data['img_quality'] == $imgQuality) {\n        return;\n    }\n}\n\n// Schedule a new run\n$task->schedule('+1 minutes', array(\n    'img_path' => $imgPath,\n    'img_quality' => $imgQuality,\n    'context' => $context,\n));\n\nreturn $imgPath;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.imgoptimizethumb.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.imgoptimizethumb.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * imgOptimizeThumb
 *
 * Output modifier for pThumb, to further optimize the generated thumbnail.
 *
 * It uses the Squoosh library from Google to create a WebP version of the image
 * and optimize the original. You need to install the Squoosh CLI package on
 * your server with NPM: 'npm install -g @squoosh/cli'
 *
 * If the Scheduler extra is installed, the Squoosh command is added there as an
 * individual task. This means it takes a little while for all the images to be
 * generated. Without Scheduler they're created when the page is requested,
 * but the initial request will take a lot longer (the thumbnails are
 * also being generated here).
 *
 * To serve the WebP images in the browser, use Nginx to intercept the image
 * request and redirect it to the WebP version. It will do so by setting a
 * different header with the correct mime type, but only if the WebP
 * image is available (and if the browser supports it). So you don't need to
 * change the image paths in MODX or provide any fallbacks in HTML.
 *
 * This guide perfectly explains this little trick:
 * https://alexey.detr.us/en/posts/2018/2018-08-20-webp-nginx-with-fallback/
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var object $task
 * @var string $input
 * @var string $options
 */

use Jcupitt\Vips;

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

// Get image path from task properties, pThumb properties or input
$imgPath = $modx->getOption('img_path', $scriptProperties, $input ?? null);
$imgPathFull = str_replace('//','/', MODX_BASE_PATH . $imgPath);
$imgName = pathinfo($imgPathFull, PATHINFO_FILENAME);
$imgType = pathinfo($imgPathFull, PATHINFO_EXTENSION);
$imgType = strtolower($imgType);
$outputDir = dirname($imgPathFull);

// Check if path or file exist
if (!$imgPath || !file_exists($imgPathFull)) {
    $modx->log(modX::LOG_LEVEL_WARN, '[imgOptimizeThumb] Image not found: ' . $imgPathFull);
    return $imgPath;
}

// Look for resource context key
$context = $modx->getOption('context', $scriptProperties, '');
if (is_object($modx->resource) && !$context) {
    $context = $modx->resource->get('context_key');
}

// Abort if optimization is disabled for this context
if (!$romanesco->getConfigSetting('img_optimize', $context)) {
    return $imgPath;
}

// Abort if file format is not supported
if ($imgType == 'svg') {
    return $imgPath;
}

// And if WebP version is already created
if (file_exists($outputDir . '/' . $imgName . '.webp')) {
    return $imgPath;
}

// Get image quality from output modifier option, task properties or corresponding context setting
$imgQuality = $options ?? $modx->getOption('img_quality', $scriptProperties);
if (!$imgQuality) {
    $imgQuality = $romanesco->getConfigSetting('img_quality', $context);
}
$imgQuality = (int) $imgQuality;

$configWebP = [
    "Q" => $imgQuality,
];

$configJPG = [
    "Q" => $imgQuality,
];

$configPNG = [
    "Q" => $imgQuality,
];

// Use Scheduler for adding task to queue (if available)
/** @var Scheduler $scheduler */
$schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
$scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');

// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed
if (!($scheduler instanceof Scheduler) || isset($task)) {
    try {
        $image = Vips\Image::newFromFile($imgPathFull);
    }
    catch (Vips\Exception $e) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[Vips] ' . $e->getMessage());
        return $imgPath;
    }

    // Create WebP version
    $image->webpsave($outputDir . '/' . $imgName . '.webp', $configWebP);

    // Overwrite original with optimized version
    if ($imgType == 'png') {
        $image->pngsave($imgPathFull, $configPNG);
    }
    if ($imgType == 'jpg' || $imgType == 'jpeg') {
        $image->jpegsave($imgPathFull, $configJPG);
    }

    return $imgPath;
}

// From here on, we're scheduling a task
$task = $scheduler->getTask('romanesco', 'ImgOptimizeThumb');

// Create task first if it doesn't exist
if (!($task instanceof sTask)) {
    $task = $modx->newObject('sSnippetTask');
    $task->fromArray(array(
        'class_key' => 'sSnippetTask',
        'content' => 'imgOptimizeThumb',
        'namespace' => 'romanesco',
        'reference' => 'ImgOptimizeThumb',
        'description' => 'Create WebP version and reduce file size of thumbnail image.'
    ));
    if (!$task->save()) {
        return 'Error saving ImgOptimizeThumb task';
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
    if (isset($data['img_path']) && $data['img_path'] == $imgPath && isset($data['img_quality']) && $data['img_quality'] == $imgQuality) {
        return;
    }
}

// Schedule a new run
$task->schedule('+1 minutes', array(
    'img_path' => $imgPath,
    'img_quality' => $imgQuality,
    'context' => $context,
));

return $imgPath;