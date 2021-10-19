id: 158
name: imgOptimizeThumb
description: 'Post hook for pThumb, that runs after the thumbnail is generated. It uses the Squoosh library from Google to create a WebP version of the image and optimize the original.'
category: f_performance
snippet: "/**\n * imgOptimizeThumb\n *\n * Output modifier for pThumb, to further optimize the generated thumbnail.\n *\n * It uses the Squoosh library from Google to create a WebP version of the image\n * and optimize the original. You need to install the Squoosh CLI package on\n * your server with NPM: 'npm install -g @squoosh/cli'\n *\n * If the Scheduler extra is installed, the Squoosh command is added there as an\n * individual task. This means it takes a little while for all the images to be\n * generated. Without Scheduler they're created when the page is requested,\n * but the initial request will take a lot longer (the thumbnails are\n * also being generated here).\n *\n * To serve the WebP images in the browser, use Nginx to intercept the image\n * request and redirect it to the WebP version. It will do so by setting a\n * different header with the correct mime type, but only if the WebP\n * image is available (and if the browser supports it). So you don't need to\n * change the image paths in MODX or provide any fallbacks in HTML.\n *\n * This guide perfectly explains this little trick:\n * https://alexey.detr.us/en/posts/2018/2018-08-20-webp-nginx-with-fallback/\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var string $input\n * @var string $options\n */\n\n$basePath = $modx->getOption('base_path', $scriptProperties, '');\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n// Get image path from task properties, pThumb properties or input\n$imgPath = $modx->getOption('img_path', $scriptProperties, $input);\n$imgPathFull = str_replace('//','/', MODX_BASE_PATH . $imgPath);\n$imgName = pathinfo($imgPathFull, PATHINFO_FILENAME);\n$imgType = pathinfo($imgPathFull, PATHINFO_EXTENSION);\n$outputDir = dirname($imgPathFull);\n\n// Look for context key\n$context = $modx->getOption('context', $scriptProperties, '');\nif (!$context) {\n    $context = $modx->resource->get('context_key');\n}\n\n// Abort if optimization is disabled for this context\nif (!$romanesco->getConfigSetting('img_optimize', $context)) {\n    return $imgPath;\n}\n\n// Also abort if WebP version is already created\nif (file_exists($outputDir . '/' . $imgName . '.webp')) {\n    return $imgPath;\n}\n\n// Get image quality from task properties, output modifier option or corresponding context setting\n$imgQuality = $scriptProperties['img_quality'] ?? $options;\nif (!$imgQuality) {\n    $imgQuality = $romanesco->getConfigSetting('img_quality', $context);\n}\n$imgQuality = (int) $imgQuality;\n\n$configWebP = json_encode([\n    \"quality\" => $imgQuality,\n    \"target_size\" => 0,\n    \"target_PSNR\" => 0,\n    \"method\" => 4,\n    \"sns_strength\" => 50,\n    \"filter_strength\" => 60,\n    \"filter_sharpness\" => 0,\n    \"filter_type\" => 1,\n    \"partitions\" => 0,\n    \"segments\" => 4,\n    \"pass\" => 1,\n    \"show_compressed\" => 0,\n    \"preprocessing\" => 0,\n    \"autofilter\" => 0,\n    \"partition_limit\" => 0,\n    \"alpha_compression\" => 1,\n    \"alpha_filtering\" => 1,\n    \"alpha_quality\" => 100,\n    \"lossless\" => 0,\n    \"exact\" => 0,\n    \"image_hint\" => 0,\n    \"emulate_jpeg_size\" => 0,\n    \"thread_level\" => 0,\n    \"low_memory\" => 0,\n    \"near_lossless\" => 100,\n    \"use_delta_palette\" => 0,\n    \"use_sharp_yuv\" => 0\n]);\n\n$configJPG = json_encode([\n    \"quality\" => $imgQuality,\n    \"baseline\" => false,\n    \"arithmetic\" => false,\n    \"progressive\" => true,\n    \"optimize_coding\" => true,\n    \"smoothing\" => 0,\n    \"color_space\" => 3,\n    \"quant_table\" => 3,\n    \"trellis_multipass\" => false,\n    \"trellis_opt_zero\" => false,\n    \"trellis_opt_table\" => false,\n    \"trellis_loops\" => 1,\n    \"auto_subsample\" => true,\n    \"chroma_subsample\" => 2,\n    \"separate_chroma_quality\" => false,\n    \"chroma_quality\" => 75\n]);\n\n$configPNG = json_encode([\n    \"level\" => 2,\n    \"interlace\" => false\n]);\n\n// Use Scheduler for adding task to queue (if available)\n/** @var Scheduler $scheduler */\n$schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');\n\n// Use different compression engine for JPG and PNG\nif (strtolower($imgType) == 'png') {\n    $squooshOptions = ' --oxipng ' . escapeshellarg($configPNG);\n} else {\n    $squooshOptions = ' --mozjpeg ' . escapeshellarg($configJPG);\n}\n\n// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed\nif (!($scheduler instanceof Scheduler) || is_object($task)) {\n    $output = array();\n\n    exec('\"$HOME\"/.nvm/nvm-exec squoosh-cli' .\n        $squooshOptions .\n        ' --webp ' . escapeshellarg($configWebP) .\n        ' --output-dir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($imgPathFull) .\n        ' 2>&1',\n        $output,\n        $return_img\n    );\n\n    // Write output to file and error log\n    $logFile = MODX_CORE_PATH . 'cache/logs/img.log';\n    $date = new DateTime();\n    $output = implode(\"\\n\",$output) . \"\\n\";\n\n    file_put_contents($logFile, \"[\" . $date->format(\"Y-m-d H:i:s\") . \"] \" . $output, FILE_APPEND);\n    $modx->log(modX::LOG_LEVEL_INFO, \"\\n\" . $output);\n\n    return;\n}\n\n// From here on, we're scheduling a task\n$task = $scheduler->getTask('romanesco', 'ImgOptimizeThumb');\n\n// Create task first if it doesn't exist\nif (!($task instanceof sTask)) {\n    $task = $modx->newObject('sSnippetTask');\n    $task->fromArray(array(\n        'class_key' => 'sSnippetTask',\n        'content' => 'imgOptimizeThumb',\n        'namespace' => 'romanesco',\n        'reference' => 'ImgOptimizeThumb',\n        'description' => 'Create WebP version and reduce file size of thumbnail image.'\n    ));\n    if (!$task->save()) {\n        return 'Error saving ImgOptimizeThumb task';\n    }\n}\n\n// Check if task is not already scheduled\n$pendingTasks = $modx->getCollection('sTaskRun', array(\n    'task' => $task->get('id'),\n    'status' => 0,\n    'executedon' => NULL,\n));\nforeach ($pendingTasks as $pendingTask) {\n    $data = $pendingTask->get('data');\n    if ($data['img_path'] == $imgPath && $data['img_quality'] == $imgQuality) {\n        return;\n    }\n}\n\n// Schedule a new run\n$task->schedule('+1 minutes', array(\n    'img_path' => $imgPath,\n    'img_quality' => $imgQuality,\n    'context' => $context,\n));\n\nreturn $imgPath;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.imgoptimizethumb.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.imgoptimizethumb.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * imgOptimizeThumb\n *\n * Output modifier for pThumb, to further optimize the generated thumbnail.\n *\n * It uses the Squoosh library from Google to create a WebP version of the image\n * and optimize the original. You need to install the Squoosh CLI package on\n * your server with NPM: 'npm install -g @squoosh/cli'\n *\n * If the Scheduler extra is installed, the Squoosh command is added there as an\n * individual task. This means it takes a little while for all the images to be\n * generated. Without Scheduler they're created when the page is requested,\n * but the initial request will take a lot longer (the thumbnails are\n * also being generated here).\n *\n * To serve the WebP images in the browser, use Nginx to intercept the image\n * request and redirect it to the WebP version. It will do so by setting a\n * different header with the correct mime type, but only if the WebP\n * image is available (and if the browser supports it). So you don't need to\n * change the image paths in MODX or provide any fallbacks in HTML.\n *\n * This guide perfectly explains this little trick:\n * https://alexey.detr.us/en/posts/2018/2018-08-20-webp-nginx-with-fallback/\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var object $task\n * @var string $input\n * @var string $options\n */\n\n$basePath = $modx->getOption('base_path', $scriptProperties, '');\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\n\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n// Get image path from task properties, pThumb properties or input\n$imgPath = $modx->getOption('img_path', $scriptProperties, $input);\n$imgPathFull = str_replace('//','/', MODX_BASE_PATH . $imgPath);\n$imgName = pathinfo($imgPathFull, PATHINFO_FILENAME);\n$imgType = pathinfo($imgPathFull, PATHINFO_EXTENSION);\n$outputDir = dirname($imgPathFull);\n\n// Look for context key\n$context = $modx->getOption('context', $scriptProperties, '');\nif (!$context) {\n    $context = $modx->resource->get('context_key');\n}\n\n// Abort if optimization is disabled for this context\nif (!$romanesco->getConfigSetting('img_optimize', $context)) {\n    return $imgPath;\n}\n\n// Also abort if WebP version is already created\nif (file_exists($outputDir . '/' . $imgName . '.webp')) {\n    return $imgPath;\n}\n\n// Get image quality from task properties, output modifier option or corresponding context setting\n$imgQuality = $scriptProperties['img_quality'] ?? $options;\nif (!$imgQuality) {\n    $imgQuality = $romanesco->getConfigSetting('img_quality', $context);\n}\n$imgQuality = (int) $imgQuality;\n\n$configWebP = json_encode([\n    \"quality\" => $imgQuality,\n    \"target_size\" => 0,\n    \"target_PSNR\" => 0,\n    \"method\" => 4,\n    \"sns_strength\" => 50,\n    \"filter_strength\" => 60,\n    \"filter_sharpness\" => 0,\n    \"filter_type\" => 1,\n    \"partitions\" => 0,\n    \"segments\" => 4,\n    \"pass\" => 1,\n    \"show_compressed\" => 0,\n    \"preprocessing\" => 0,\n    \"autofilter\" => 0,\n    \"partition_limit\" => 0,\n    \"alpha_compression\" => 1,\n    \"alpha_filtering\" => 1,\n    \"alpha_quality\" => 100,\n    \"lossless\" => 0,\n    \"exact\" => 0,\n    \"image_hint\" => 0,\n    \"emulate_jpeg_size\" => 0,\n    \"thread_level\" => 0,\n    \"low_memory\" => 0,\n    \"near_lossless\" => 100,\n    \"use_delta_palette\" => 0,\n    \"use_sharp_yuv\" => 0\n]);\n\n$configJPG = json_encode([\n    \"quality\" => $imgQuality,\n    \"baseline\" => false,\n    \"arithmetic\" => false,\n    \"progressive\" => true,\n    \"optimize_coding\" => true,\n    \"smoothing\" => 0,\n    \"color_space\" => 3,\n    \"quant_table\" => 3,\n    \"trellis_multipass\" => false,\n    \"trellis_opt_zero\" => false,\n    \"trellis_opt_table\" => false,\n    \"trellis_loops\" => 1,\n    \"auto_subsample\" => true,\n    \"chroma_subsample\" => 2,\n    \"separate_chroma_quality\" => false,\n    \"chroma_quality\" => 75\n]);\n\n$configPNG = json_encode([\n    \"level\" => 2,\n    \"interlace\" => false\n]);\n\n// Use Scheduler for adding task to queue (if available)\n/** @var Scheduler $scheduler */\n$schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');\n$scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');\n\n// Use different compression engine for JPG and PNG\nif (strtolower($imgType) == 'png') {\n    $squooshOptions = ' --oxipng ' . escapeshellarg($configPNG);\n} else {\n    $squooshOptions = ' --mozjpeg ' . escapeshellarg($configJPG);\n}\n\n// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed\nif (!($scheduler instanceof Scheduler) || is_object($task)) {\n    $output = array();\n\n    exec('\"$HOME\"/.nvm/nvm-exec squoosh-cli' .\n        $squooshOptions .\n        ' --webp ' . escapeshellarg($configWebP) .\n        ' --output-dir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($imgPathFull) .\n        ' 2>&1',\n        $output,\n        $return_img\n    );\n\n    // Write output to file and error log\n    $logFile = MODX_CORE_PATH . 'cache/logs/img.log';\n    $date = new DateTime();\n    $output = implode(\"\\n\",$output) . \"\\n\";\n\n    file_put_contents($logFile, \"[\" . $date->format(\"Y-m-d H:i:s\") . \"] \" . $output, FILE_APPEND);\n    $modx->log(modX::LOG_LEVEL_INFO, \"\\n\" . $output);\n\n    return;\n}\n\n// From here on, we're scheduling a task\n$task = $scheduler->getTask('romanesco', 'ImgOptimizeThumb');\n\n// Create task first if it doesn't exist\nif (!($task instanceof sTask)) {\n    $task = $modx->newObject('sSnippetTask');\n    $task->fromArray(array(\n        'class_key' => 'sSnippetTask',\n        'content' => 'imgOptimizeThumb',\n        'namespace' => 'romanesco',\n        'reference' => 'ImgOptimizeThumb',\n        'description' => 'Create WebP version and reduce file size of thumbnail image.'\n    ));\n    if (!$task->save()) {\n        return 'Error saving ImgOptimizeThumb task';\n    }\n}\n\n// Check if task is not already scheduled\n$pendingTasks = $modx->getCollection('sTaskRun', array(\n    'task' => $task->get('id'),\n    'status' => 0,\n    'executedon' => NULL,\n));\nforeach ($pendingTasks as $pendingTask) {\n    $data = $pendingTask->get('data');\n    if ($data['img_path'] == $imgPath && $data['img_quality'] == $imgQuality) {\n        return;\n    }\n}\n\n// Schedule a new run\n$task->schedule('+1 minutes', array(\n    'img_path' => $imgPath,\n    'img_quality' => $imgQuality,\n    'context' => $context,\n));\n\nreturn $imgPath;"

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

$basePath = $modx->getOption('base_path', $scriptProperties, '');
$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));

if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
}

// Get image path from task properties, pThumb properties or input
$imgPath = $modx->getOption('img_path', $scriptProperties, $input);
$imgPathFull = str_replace('//','/', MODX_BASE_PATH . $imgPath);
$imgName = pathinfo($imgPathFull, PATHINFO_FILENAME);
$imgType = pathinfo($imgPathFull, PATHINFO_EXTENSION);
$outputDir = dirname($imgPathFull);

// Look for context key
$context = $modx->getOption('context', $scriptProperties, '');
if (!$context) {
    $context = $modx->resource->get('context_key');
}

// Abort if optimization is disabled for this context
if (!$romanesco->getConfigSetting('img_optimize', $context)) {
    return $imgPath;
}

// Also abort if WebP version is already created
if (file_exists($outputDir . '/' . $imgName . '.webp')) {
    return $imgPath;
}

// Get image quality from task properties, output modifier option or corresponding context setting
$imgQuality = $scriptProperties['img_quality'] ?? $options;
if (!$imgQuality) {
    $imgQuality = $romanesco->getConfigSetting('img_quality', $context);
}
$imgQuality = (int) $imgQuality;

$configWebP = json_encode([
    "quality" => $imgQuality,
    "target_size" => 0,
    "target_PSNR" => 0,
    "method" => 4,
    "sns_strength" => 50,
    "filter_strength" => 60,
    "filter_sharpness" => 0,
    "filter_type" => 1,
    "partitions" => 0,
    "segments" => 4,
    "pass" => 1,
    "show_compressed" => 0,
    "preprocessing" => 0,
    "autofilter" => 0,
    "partition_limit" => 0,
    "alpha_compression" => 1,
    "alpha_filtering" => 1,
    "alpha_quality" => 100,
    "lossless" => 0,
    "exact" => 0,
    "image_hint" => 0,
    "emulate_jpeg_size" => 0,
    "thread_level" => 0,
    "low_memory" => 0,
    "near_lossless" => 100,
    "use_delta_palette" => 0,
    "use_sharp_yuv" => 0
]);

$configJPG = json_encode([
    "quality" => $imgQuality,
    "baseline" => false,
    "arithmetic" => false,
    "progressive" => true,
    "optimize_coding" => true,
    "smoothing" => 0,
    "color_space" => 3,
    "quant_table" => 3,
    "trellis_multipass" => false,
    "trellis_opt_zero" => false,
    "trellis_opt_table" => false,
    "trellis_loops" => 1,
    "auto_subsample" => true,
    "chroma_subsample" => 2,
    "separate_chroma_quality" => false,
    "chroma_quality" => 75
]);

$configPNG = json_encode([
    "level" => 2,
    "interlace" => false
]);

// Use Scheduler for adding task to queue (if available)
/** @var Scheduler $scheduler */
$schedulerPath = $modx->getOption('scheduler.core_path', null, $modx->getOption('core_path') . 'components/scheduler/');
$scheduler = $modx->getService('scheduler', 'Scheduler', $schedulerPath . 'model/scheduler/');

// Use different compression engine for JPG and PNG
if (strtolower($imgType) == 'png') {
    $squooshOptions = ' --oxipng ' . escapeshellarg($configPNG);
} else {
    $squooshOptions = ' --mozjpeg ' . escapeshellarg($configJPG);
}

// Generate CSS directly if snippet is run as scheduled task, or if Scheduler is not installed
if (!($scheduler instanceof Scheduler) || is_object($task)) {
    $output = array();

    exec('"$HOME"/.nvm/nvm-exec squoosh-cli' .
        $squooshOptions .
        ' --webp ' . escapeshellarg($configWebP) .
        ' --output-dir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($imgPathFull) .
        ' 2>&1',
        $output,
        $return_img
    );

    // Write output to file and error log
    $logFile = MODX_CORE_PATH . 'cache/logs/img.log';
    $date = new DateTime();
    $output = implode("\n",$output) . "\n";

    file_put_contents($logFile, "[" . $date->format("Y-m-d H:i:s") . "] " . $output, FILE_APPEND);
    $modx->log(modX::LOG_LEVEL_INFO, "\n" . $output);

    return;
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
    if ($data['img_path'] == $imgPath && $data['img_quality'] == $imgQuality) {
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