id: 154
name: getVimeoData
description: 'Retrieve the largest existing thumbnail image available. You can choose between JPG and webP extension. Can be used as output modifier as well.'
category: f_presentation
snippet: "/**\n * getVimeoData\n *\n * Retrieve thumbnail and video ID through oEmbed. Outputs them as placeholder.\n *\n * You need to make this request because video ID and thumbnail ID are not\n * always the same, depending on the Vimeo privacy settings.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$videoURL = $modx->getOption('videoURL', $scriptProperties, '');\n$imgSize = $modx->getOption('imgSize', $scriptProperties, '720');\n$imgType = $modx->getOption('imgType', $scriptProperties, 'webp');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$cacheKey = parse_url($videoURL, PHP_URL_PATH);\n$cacheManager = $modx->getCacheManager();\n$cacheLifetime = (int)$modx->getOption('cacheLifetime', $scriptProperties, 7 * 24 * 60 * 60, true);\n$cacheOptions = [\n    xPDO::OPT_CACHE_KEY => 'video/vimeo',\n];\n$fromCache = true;\n$data = $cacheManager->get($cacheKey, $cacheOptions);\n\n// Use pThumb cache location if activated\nif ($modx->getOption('pthumb.use_ptcache', null, true) ) {\n    $cachePath = $modx->getOption('pthumb.ptcache_location', null, 'assets/cache/img', true);\n} else {\n    $cachePath = $modx->getOption('phpthumbof.cache_path', null, \"assets/components/phpthumbof/cache\", true);\n}\n$cachePath = rtrim($cachePath, '/') . '/video/vimeo/';\n$cachePathFull = MODX_BASE_PATH . $cachePath;\n\n// Invalidate cache if URL changed\nif (is_array($data) && array_diff($data['properties'], $scriptProperties)) {\n    $data = '';\n}\n\n// Invalidate cache if thumbnail can't be found\nif (is_array($data) && !file_exists(MODX_BASE_PATH . $data['thumbPath'])) {\n    $data = '';\n}\n\n// Make the request and fetch thumbnail\nif (!is_array($data)) {\n    $fromCache = false;\n    $curl = curl_init();\n\n    curl_setopt_array($curl, array(\n        CURLOPT_URL => \"https://vimeo.com/api/oembed.json?url=\" . $videoURL,\n        CURLOPT_AUTOREFERER => true,\n        CURLOPT_RETURNTRANSFER => true,\n        CURLOPT_ENCODING => \"\",\n        CURLOPT_MAXREDIRS => 3,\n        CURLOPT_TIMEOUT => 10,\n        CURLOPT_CUSTOMREQUEST => \"GET\",\n        CURLOPT_POSTFIELDS => \"\",\n    ));\n\n    $response = curl_exec($curl);\n    $error = curl_error($curl);\n    curl_close($curl);\n\n    if ($error) {\n        $modx->log(modX::LOG_LEVEL_ERROR, $error);\n        return;\n    }\n\n    // Response is in JSON\n    $response = json_decode($response, 1);\n\n    // Construct proper thumb URL\n    $thumbURL = $response['thumbnail_url'];\n    $videoID = $response['video_id'];\n\n    $thumbSplit = explode('_', $thumbURL);\n    $thumbID = str_replace('https://i.vimeocdn.com/video/', '', $thumbSplit[0]);\n    $thumbURL = 'https://i.vimeocdn.com/video/' . $thumbID . '_' . $imgSize . '.' . $imgType;\n\n    // Write image file to assets cache folder\n    $thumbFile = file_get_contents($thumbURL);\n    $thumbFileName = $videoID . '.' . $imgSize . '.' . $imgType;\n    $thumbPath = $cachePath . $thumbFileName;\n\n    if (!@is_dir($cachePathFull)) {\n        if (!@mkdir($cachePathFull, 0755, 1)) {\n            $modx->log(xPDO::LOG_LEVEL_ERROR, '[getVimeoData] Could not create cache path.', '', 'Romanesco');\n        }\n    }\n    if (!@file_put_contents(MODX_BASE_PATH . $thumbPath, $thumbFile)) {\n        $modx->log(xPDO::LOG_LEVEL_ERROR, '[getVimeoData] Could not create thumbnail file @ ' . $thumbPath, '', 'Romanesco');\n    }\n\n    // Create array of data to be cached\n    $data = [\n        'properties' => $scriptProperties,\n        'response' => $response,\n        'thumbURL' => $thumbURL,\n        'thumbPath' => $thumbPath,\n    ];\n\n    $cacheManager->set($cacheKey, $data, $cacheLifetime, $cacheOptions);\n}\n\nif (!is_array($data)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getVimeoData] Could not find requested data');\n    return '';\n}\n\n// Load data from cache\n$modx->toPlaceholder('vimeoID', $data['response']['video_id'], $prefix);\n$modx->toPlaceholder('vimeoThumb', $data['thumbPath'], $prefix);\n\n//return '<p>From cache: ' . ($fromCache ? 'Yes' : 'No') . '</p>';\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.getvimeodata.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.getvimeodata.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getVimeoData
 *
 * Retrieve thumbnail and video ID through oEmbed. Outputs them as placeholder.
 *
 * You need to make this request because video ID and thumbnail ID are not
 * always the same, depending on the Vimeo privacy settings.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$videoURL = $modx->getOption('videoURL', $scriptProperties, '');
$imgSize = $modx->getOption('imgSize', $scriptProperties, '720');
$imgType = $modx->getOption('imgType', $scriptProperties, 'webp');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

$cacheKey = parse_url($videoURL, PHP_URL_PATH);
$cacheManager = $modx->getCacheManager();
$cacheLifetime = (int)$modx->getOption('cacheLifetime', $scriptProperties, 7 * 24 * 60 * 60, true);
$cacheOptions = [
    xPDO::OPT_CACHE_KEY => 'video/vimeo',
];
$fromCache = true;
$data = $cacheManager->get($cacheKey, $cacheOptions);

// Use pThumb cache location if activated
if ($modx->getOption('pthumb.use_ptcache', null, true) ) {
    $cachePath = $modx->getOption('pthumb.ptcache_location', null, 'assets/cache/img', true);
} else {
    $cachePath = $modx->getOption('phpthumbof.cache_path', null, "assets/components/phpthumbof/cache", true);
}
$cachePath = rtrim($cachePath, '/') . '/video/vimeo/';
$cachePathFull = MODX_BASE_PATH . $cachePath;

// Invalidate cache if URL changed
if (is_array($data) && array_diff($data['properties'], $scriptProperties)) {
    $data = '';
}

// Invalidate cache if thumbnail can't be found
if (is_array($data) && !file_exists(MODX_BASE_PATH . $data['thumbPath'])) {
    $data = '';
}

// Make the request and fetch thumbnail
if (!is_array($data)) {
    $fromCache = false;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://vimeo.com/api/oembed.json?url=" . $videoURL,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        $modx->log(modX::LOG_LEVEL_ERROR, $error);
        return;
    }

    // Response is in JSON
    $response = json_decode($response, 1);

    // Construct proper thumb URL
    $thumbURL = $response['thumbnail_url'];
    $videoID = $response['video_id'];

    $thumbSplit = explode('_', $thumbURL);
    $thumbID = str_replace('https://i.vimeocdn.com/video/', '', $thumbSplit[0]);
    $thumbURL = 'https://i.vimeocdn.com/video/' . $thumbID . '_' . $imgSize . '.' . $imgType;

    // Write image file to assets cache folder
    $thumbFile = file_get_contents($thumbURL);
    $thumbFileName = $videoID . '.' . $imgSize . '.' . $imgType;
    $thumbPath = $cachePath . $thumbFileName;

    if (!@is_dir($cachePathFull)) {
        if (!@mkdir($cachePathFull, 0755, 1)) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, '[getVimeoData] Could not create cache path.', '', 'Romanesco');
        }
    }
    if (!@file_put_contents(MODX_BASE_PATH . $thumbPath, $thumbFile)) {
        $modx->log(xPDO::LOG_LEVEL_ERROR, '[getVimeoData] Could not create thumbnail file @ ' . $thumbPath, '', 'Romanesco');
    }

    // Create array of data to be cached
    $data = [
        'properties' => $scriptProperties,
        'response' => $response,
        'thumbURL' => $thumbURL,
        'thumbPath' => $thumbPath,
    ];

    $cacheManager->set($cacheKey, $data, $cacheLifetime, $cacheOptions);
}

if (!is_array($data)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getVimeoData] Could not find requested data');
    return '';
}

// Load data from cache
$modx->toPlaceholder('vimeoID', $data['response']['video_id'], $prefix);
$modx->toPlaceholder('vimeoThumb', $data['thumbPath'], $prefix);

//return '<p>From cache: ' . ($fromCache ? 'Yes' : 'No') . '</p>';

return '';