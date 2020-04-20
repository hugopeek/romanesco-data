id: 148
name: getYoutubeThumb
description: 'Retrieve the largest existing thumbnail image available. You can choose between JPG and webP extension. Can be used as output modifier as well.'
category: f_presentation
snippet: "/**\n * getYoutubeThumb\n *\n * Retrieve the largest existing thumbnail image available. You can choose\n * between JPG and webP extension. Can be used as output modifier as well.\n */\n\n$videoID = $modx->getOption('videoID', $scriptProperties, $input);\n$imgType = $modx->getOption('imgType', $scriptProperties, $options);\n\nif (!$imgType) $imgType = 'jpg';\n$vi = ($imgType == 'webp') ? 'vi_webp' : 'vi';\n$imgURL = \"https://img.youtube.com/$vi/$videoID/0.$imgType\";\n\n$resolutions = array('maxresdefault', 'hqdefault', 'mqdefault');\n\nforeach($resolutions as $res) {\n    $imgUrl = \"https://img.youtube.com/$vi/$videoID/$res.$imgType\";\n    if(@getimagesize(($imgUrl))) {\n        return $imgUrl;\n    }\n}\n\nreturn $imgUrl;"
properties: 'a:0:{}'
content: "/**\n * getYoutubeThumb\n *\n * Retrieve the largest existing thumbnail image available. You can choose\n * between JPG and webP extension. Can be used as output modifier as well.\n */\n\n$videoID = $modx->getOption('videoID', $scriptProperties, $input);\n$imgType = $modx->getOption('imgType', $scriptProperties, $options);\n\nif (!$imgType) $imgType = 'jpg';\n$vi = ($imgType == 'webp') ? 'vi_webp' : 'vi';\n$imgURL = \"https://img.youtube.com/$vi/$videoID/0.$imgType\";\n\n$resolutions = array('maxresdefault', 'hqdefault', 'mqdefault');\n\nforeach($resolutions as $res) {\n    $imgUrl = \"https://img.youtube.com/$vi/$videoID/$res.$imgType\";\n    if(@getimagesize(($imgUrl))) {\n        return $imgUrl;\n    }\n}\n\nreturn $imgUrl;"

-----


/**
 * getYoutubeThumb
 *
 * Retrieve the largest existing thumbnail image available. You can choose
 * between JPG and webP extension. Can be used as output modifier as well.
 */

$videoID = $modx->getOption('videoID', $scriptProperties, $input);
$imgType = $modx->getOption('imgType', $scriptProperties, $options);

if (!$imgType) $imgType = 'jpg';
$vi = ($imgType == 'webp') ? 'vi_webp' : 'vi';
$imgURL = "https://img.youtube.com/$vi/$videoID/0.$imgType";

$resolutions = array('maxresdefault', 'hqdefault', 'mqdefault');

foreach($resolutions as $res) {
    $imgUrl = "https://img.youtube.com/$vi/$videoID/$res.$imgType";
    if(@getimagesize(($imgUrl))) {
        return $imgUrl;
    }
}

return $imgUrl;