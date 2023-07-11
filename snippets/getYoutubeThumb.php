id: 148
name: getYoutubeThumb
description: 'Retrieve the largest existing thumbnail image available. You can choose between JPG and webP extension. Can be used as output modifier as well.'
category: f_presentation
snippet: "/**\n * getYoutubeThumb\n *\n * Retrieve the largest existing thumbnail image available. You can choose\n * between JPG and WebP extension.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$videoID = $modx->getOption('videoID', $scriptProperties, '');\n$imgSize = $modx->getOption('imgSize', $scriptProperties, '720');\n$imgType = $modx->getOption('imgType', $scriptProperties, 'webp');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$cacheKey = $videoID;\n$cacheManager = $modx->getCacheManager();\n$cacheLifetime = (int)$modx->getOption('cacheLifetime', $scriptProperties, 7 * 24 * 60 * 60, true);\n$cacheOptions = [\n    xPDO::OPT_CACHE_KEY => 'video/youtube',\n];\n$fromCache = true;\n$data = $cacheManager->get($cacheKey, $cacheOptions);\n\n// Use pThumb cache location if activated\nif ($modx->getOption('pthumb.use_ptcache', null, true) ) {\n    $cachePath = $modx->getOption('pthumb.ptcache_location', null, 'assets/cache/img', true);\n} else {\n    $cachePath = $modx->getOption('phpthumbof.cache_path', null, \"assets/components/phpthumbof/cache\", true);\n}\n$cachePath = rtrim($cachePath, '/') . '/video/youtube/';\n$cachePathFull = MODX_BASE_PATH . $cachePath;\n\n// Invalidate cache if ID changed\nif (isset($data['properties']) && array_diff($data['properties'], $scriptProperties)) {\n    $data = '';\n}\n\n// Invalidate cache if thumbnail can't be found\nif (isset($data['thumbPath']) && !file_exists(MODX_BASE_PATH . $data['thumbPath'])) {\n    $data = '';\n}\n\n// Make the request and fetch thumbnail\nif (!is_array($data)) {\n    $fromCache = false;\n    if (!$imgType) $imgType = 'jpg';\n    $vi = ($imgType == 'webp') ? 'vi_webp' : 'vi';\n    $imgURL = \"https://img.youtube.com/$vi/$videoID/0.$imgType\";\n\n    $resolutions = ['maxresdefault', 'hqdefault', 'mqdefault'];\n\n    // Loop through resolutions until a match is found\n    foreach($resolutions as $resolution) {\n        $maxResURL = \"https://img.youtube.com/$vi/$videoID/$resolution.$imgType\";\n        if(@getimagesize($maxResURL)) {\n            $imgURL = $maxResURL;\n            break;\n        }\n    }\n\n    // Write image file to assets cache folder\n    $thumbFile = file_get_contents($imgURL);\n    $thumbFileName = $videoID . '.' . $imgType;\n    $thumbPath = $cachePath . $thumbFileName;\n\n    if (!@is_dir($cachePathFull)) {\n        if (!@mkdir($cachePathFull, 0755, 1)) {\n            $modx->log(xPDO::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not create cache path.', '', 'Romanesco');\n        }\n    }\n    if (!@file_put_contents(MODX_BASE_PATH . $thumbPath, $thumbFile)) {\n        $modx->log(xPDO::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not create thumbnail file @ ' . $thumbPath, '', 'Romanesco');\n    }\n\n    // Create array of data to be cached\n    $data = [\n        'properties' => $scriptProperties,\n        'thumbURL' => $imgURL,\n        'thumbPath' => $thumbPath,\n    ];\n\n    $cacheManager->set($cacheKey, $data, $cacheLifetime, $cacheOptions);\n}\n\nif (!is_array($data)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not find requested data');\n    return '';\n}\n\n// Load data from cache\n$modx->toPlaceholder('youtubeThumb', $data['thumbPath'], $prefix);\n\n//return '<p>From cache: ' . ($fromCache ? 'Yes' : 'No') . '</p>';\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.getyoutubethumb.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.getyoutubethumb.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getYoutubeThumb
 *
 * Retrieve the largest existing thumbnail image available. You can choose
 * between JPG and WebP extension.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$videoID = $modx->getOption('videoID', $scriptProperties, '');
$imgSize = $modx->getOption('imgSize', $scriptProperties, '720');
$imgType = $modx->getOption('imgType', $scriptProperties, 'webp');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

$cacheKey = $videoID;
$cacheManager = $modx->getCacheManager();
$cacheLifetime = (int)$modx->getOption('cacheLifetime', $scriptProperties, 7 * 24 * 60 * 60, true);
$cacheOptions = [
    xPDO::OPT_CACHE_KEY => 'video/youtube',
];
$fromCache = true;
$data = $cacheManager->get($cacheKey, $cacheOptions);

// Use pThumb cache location if activated
if ($modx->getOption('pthumb.use_ptcache', null, true) ) {
    $cachePath = $modx->getOption('pthumb.ptcache_location', null, 'assets/cache/img', true);
} else {
    $cachePath = $modx->getOption('phpthumbof.cache_path', null, "assets/components/phpthumbof/cache", true);
}
$cachePath = rtrim($cachePath, '/') . '/video/youtube/';
$cachePathFull = MODX_BASE_PATH . $cachePath;

// Invalidate cache if ID changed
if (isset($data['properties']) && array_diff($data['properties'], $scriptProperties)) {
    $data = '';
}

// Invalidate cache if thumbnail can't be found
if (isset($data['thumbPath']) && !file_exists(MODX_BASE_PATH . $data['thumbPath'])) {
    $data = '';
}

// Make the request and fetch thumbnail
if (!is_array($data)) {
    $fromCache = false;
    if (!$imgType) $imgType = 'jpg';
    $vi = ($imgType == 'webp') ? 'vi_webp' : 'vi';
    $imgURL = "https://img.youtube.com/$vi/$videoID/0.$imgType";

    $resolutions = ['maxresdefault', 'hqdefault', 'mqdefault'];

    // Loop through resolutions until a match is found
    foreach($resolutions as $resolution) {
        $maxResURL = "https://img.youtube.com/$vi/$videoID/$resolution.$imgType";
        if(@getimagesize($maxResURL)) {
            $imgURL = $maxResURL;
            break;
        }
    }

    // Write image file to assets cache folder
    $thumbFile = file_get_contents($imgURL);
    $thumbFileName = $videoID . '.' . $imgType;
    $thumbPath = $cachePath . $thumbFileName;

    if (!@is_dir($cachePathFull)) {
        if (!@mkdir($cachePathFull, 0755, 1)) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not create cache path.', '', 'Romanesco');
        }
    }
    if (!@file_put_contents(MODX_BASE_PATH . $thumbPath, $thumbFile)) {
        $modx->log(xPDO::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not create thumbnail file @ ' . $thumbPath, '', 'Romanesco');
    }

    // Create array of data to be cached
    $data = [
        'properties' => $scriptProperties,
        'thumbURL' => $imgURL,
        'thumbPath' => $thumbPath,
    ];

    $cacheManager->set($cacheKey, $data, $cacheLifetime, $cacheOptions);
}

if (!is_array($data)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getYoutubeThumb] Could not find requested data');
    return '';
}

// Load data from cache
$modx->toPlaceholder('youtubeThumb', $data['thumbPath'], $prefix);

//return '<p>From cache: ' . ($fromCache ? 'Yes' : 'No') . '</p>';

return;