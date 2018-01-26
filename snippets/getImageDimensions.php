id: 122
name: getImageDimensions
description: 'Retrieve width and height from physical image files. Auto detects SVGs.'
category: f_presentation
snippet: "/**\n * getImageDimensions\n *\n * Retrieve width and height from physical image files. Auto detects SVGs.\n */\n\n$imgPath = $modx->getOption('image', $scriptProperties, '');\n$imgFile = end(explode('/', $imgPath));\n$imgType = end(explode('.', $imgFile));\n\n$phWidth = $modx->getOption('phWidth', $scriptProperties, 'img_width');\n$phHeight = $modx->getOption('phHeight', $scriptProperties, 'img_height');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n// Check if image is SVG\nif (strtolower($imgType) == 'svg') {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Image is SVG');\n\n    $xml = file_get_contents($imgPath);\n    $attributes = simplexml_load_string($xml)->attributes();\n\n    // Primarily relying on viewbox, since width and height values are not\n    // required and also kind of meaningless given the scalable nature of SVG.\n    $viewbox = (string) $attributes->viewBox;\n\n    if ($viewbox) {\n        $viewbox = explode(' ', $viewbox);\n\n        $width = round($viewbox[2], 5);\n        $height = round($viewbox[3], 5);\n    }\n\n    // Fall back on width and height attributes if viewbox is empty\n    else {\n        $width = (string) $attributes->width;\n        $width = preg_filter('/[a-zA-Z]+/', '', $width);\n        $width = round($width, 2);\n\n        $height = (string) $attributes->height;\n        $height = preg_filter('/[a-zA-Z]+/', '', $height);\n        $height = round($height, 2);\n    }\n}\n\n// Validate image file and get dimensions\nelse if (substr(mime_content_type($imgPath), 0, 5) === 'image') {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Logo is valid image file');\n\n    $img = getimagesize($imgPath);\n\n    $width = $img[0];\n    $height = $img[1];\n}\n\nelse {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getImageDimensions] Image file could not be found');\n    return '';\n}\n\n$modx->toPlaceholder($phWidth, $width, $prefix);\n$modx->toPlaceholder($phHeight, $height, $prefix);\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * getImageDimensions\n *\n * Retrieve width and height from physical image files. Auto detects SVGs.\n */\n\n$imgPath = $modx->getOption('image', $scriptProperties, '');\n$imgFile = end(explode('/', $imgPath));\n$imgType = end(explode('.', $imgFile));\n\n$phWidth = $modx->getOption('phWidth', $scriptProperties, 'img_width');\n$phHeight = $modx->getOption('phHeight', $scriptProperties, 'img_height');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n// Check if image is SVG\nif (strtolower($imgType) == 'svg') {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Image is SVG');\n\n    $xml = file_get_contents($imgPath);\n    $attributes = simplexml_load_string($xml)->attributes();\n\n    // Primarily relying on viewbox, since width and height values are not\n    // required and also kind of meaningless given the scalable nature of SVG.\n    $viewbox = (string) $attributes->viewBox;\n\n    if ($viewbox) {\n        $viewbox = explode(' ', $viewbox);\n\n        $width = round($viewbox[2], 5);\n        $height = round($viewbox[3], 5);\n    }\n\n    // Fall back on width and height attributes if viewbox is empty\n    else {\n        $width = (string) $attributes->width;\n        $width = preg_filter('/[a-zA-Z]+/', '', $width);\n        $width = round($width, 2);\n\n        $height = (string) $attributes->height;\n        $height = preg_filter('/[a-zA-Z]+/', '', $height);\n        $height = round($height, 2);\n    }\n}\n\n// Validate image file and get dimensions\nelse if (substr(mime_content_type($imgPath), 0, 5) === 'image') {\n    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Logo is valid image file');\n\n    $img = getimagesize($imgPath);\n\n    $width = $img[0];\n    $height = $img[1];\n}\n\nelse {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getImageDimensions] Image file could not be found');\n    return '';\n}\n\n$modx->toPlaceholder($phWidth, $width, $prefix);\n$modx->toPlaceholder($phHeight, $height, $prefix);\n\nreturn '';"

-----


/**
 * getImageDimensions
 *
 * Retrieve width and height from physical image files. Auto detects SVGs.
 */

$imgPath = $modx->getOption('image', $scriptProperties, '');
$imgFile = end(explode('/', $imgPath));
$imgType = end(explode('.', $imgFile));

$phWidth = $modx->getOption('phWidth', $scriptProperties, 'img_width');
$phHeight = $modx->getOption('phHeight', $scriptProperties, 'img_height');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

// Check if image is SVG
if (strtolower($imgType) == 'svg') {
    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Image is SVG');

    $xml = file_get_contents($imgPath);
    $attributes = simplexml_load_string($xml)->attributes();

    // Primarily relying on viewbox, since width and height values are not
    // required and also kind of meaningless given the scalable nature of SVG.
    $viewbox = (string) $attributes->viewBox;

    if ($viewbox) {
        $viewbox = explode(' ', $viewbox);

        $width = round($viewbox[2], 5);
        $height = round($viewbox[3], 5);
    }

    // Fall back on width and height attributes if viewbox is empty
    else {
        $width = (string) $attributes->width;
        $width = preg_filter('/[a-zA-Z]+/', '', $width);
        $width = round($width, 2);

        $height = (string) $attributes->height;
        $height = preg_filter('/[a-zA-Z]+/', '', $height);
        $height = round($height, 2);
    }
}

// Validate image file and get dimensions
else if (substr(mime_content_type($imgPath), 0, 5) === 'image') {
    $modx->log(modX::LOG_LEVEL_INFO, '[getImageDimensions] Logo is valid image file');

    $img = getimagesize($imgPath);

    $width = $img[0];
    $height = $img[1];
}

else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getImageDimensions] Image file could not be found');
    return '';
}

$modx->toPlaceholder($phWidth, $width, $prefix);
$modx->toPlaceholder($phHeight, $height, $prefix);

return '';