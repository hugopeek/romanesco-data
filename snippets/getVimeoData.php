id: 154
name: getVimeoData
description: 'Retrieve the largest existing thumbnail image available. You can choose between JPG and webP extension. Can be used as output modifier as well.'
category: f_presentation
snippet: "/**\n * getVimeoData\n *\n * Retrieve thumbnail and video ID through oEmbed. Outputs them as placeholder.\n *\n * You need to make this request because video ID and thumbnail ID are not\n * always the same, depending on the Vimeo privacy settings.\n */\n\n$videoURL = $modx->getOption('videoURL', $scriptProperties, $input);\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$curl = curl_init();\n\ncurl_setopt_array($curl, array(\n    CURLOPT_URL => \"https://vimeo.com/api/oembed.json?url=\" . $videoURL,\n    CURLOPT_RETURNTRANSFER => true,\n    CURLOPT_ENCODING => \"\",\n    CURLOPT_MAXREDIRS => 10,\n    CURLOPT_TIMEOUT => 30,\n    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,\n    CURLOPT_CUSTOMREQUEST => \"GET\",\n    CURLOPT_POSTFIELDS => \"\",\n    CURLOPT_HTTPHEADER => array(\n        \"Referer: \" . $_SERVER['HTTP_REFERER'] . \"\"\n    ),\n));\n\n$response = curl_exec($curl);\n$err = curl_error($curl);\n\n// this returns JSON so decode it into an object\n$response = json_decode($response);\ncurl_close($curl);\n\n$thumbURL = $response->thumbnail_url;\n$videoID = $response->video_id;\n\n$thumbSplit = explode('_', $thumbURL);\n$thumbID = str_replace('https://i.vimeocdn.com/video/', '', $thumbSplit[0]);\n\n$modx->toPlaceholder('vimeoID', $videoID, $prefix);\n$modx->toPlaceholder('vimeoThumbID', $thumbID, $prefix);\n\nreturn '';"
properties: 'a:2:{s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.getvimeothumb.elementExample";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.getvimeothumb.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * getVimeoData\n *\n * Retrieve thumbnail and video ID through oEmbed. Outputs them as placeholder.\n *\n * You need to make this request because video ID and thumbnail ID are not\n * always the same, depending on the Vimeo privacy settings.\n */\n\n$videoURL = $modx->getOption('videoURL', $scriptProperties, $input);\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$curl = curl_init();\n\ncurl_setopt_array($curl, array(\n    CURLOPT_URL => \"https://vimeo.com/api/oembed.json?url=\" . $videoURL,\n    CURLOPT_RETURNTRANSFER => true,\n    CURLOPT_ENCODING => \"\",\n    CURLOPT_MAXREDIRS => 10,\n    CURLOPT_TIMEOUT => 30,\n    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,\n    CURLOPT_CUSTOMREQUEST => \"GET\",\n    CURLOPT_POSTFIELDS => \"\",\n    CURLOPT_HTTPHEADER => array(\n        \"Referer: \" . $_SERVER['HTTP_REFERER'] . \"\"\n    ),\n));\n\n$response = curl_exec($curl);\n$err = curl_error($curl);\n\n// this returns JSON so decode it into an object\n$response = json_decode($response);\ncurl_close($curl);\n\n$thumbURL = $response->thumbnail_url;\n$videoID = $response->video_id;\n\n$thumbSplit = explode('_', $thumbURL);\n$thumbID = str_replace('https://i.vimeocdn.com/video/', '', $thumbSplit[0]);\n\n$modx->toPlaceholder('vimeoID', $videoID, $prefix);\n$modx->toPlaceholder('vimeoThumbID', $thumbID, $prefix);\n\nreturn '';"

-----


/**
 * getVimeoData
 *
 * Retrieve thumbnail and video ID through oEmbed. Outputs them as placeholder.
 *
 * You need to make this request because video ID and thumbnail ID are not
 * always the same, depending on the Vimeo privacy settings.
 */

$videoURL = $modx->getOption('videoURL', $scriptProperties, $input);
$prefix = $modx->getOption('prefix', $scriptProperties, '');

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://vimeo.com/api/oembed.json?url=" . $videoURL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
        "Referer: " . $_SERVER['HTTP_REFERER'] . ""
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

// this returns JSON so decode it into an object
$response = json_decode($response);
curl_close($curl);

$thumbURL = $response->thumbnail_url;
$videoID = $response->video_id;

$thumbSplit = explode('_', $thumbURL);
$thumbID = str_replace('https://i.vimeocdn.com/video/', '', $thumbSplit[0]);

$modx->toPlaceholder('vimeoID', $videoID, $prefix);
$modx->toPlaceholder('vimeoThumbID', $thumbID, $prefix);

return '';