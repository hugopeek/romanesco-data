id: 115
name: responsiveImgSrcset
description: 'Generate a number of srcset properties, for use inside an img tag.'
category: f_presentation
snippet: "/**\n * responsiveImgSrcset\n *\n * Generates a number of srcset properties, for use inside an <img> tag.\n *\n * The dimensions for each srcset image are defined inside the\n * img_breakpoints configuration setting.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$breakpoints = $modx->getOption('breakpoints', $scriptProperties, '');\n$src = $modx->getOption('src', $scriptProperties, '');\n$crop = $modx->getOption('crop', $scriptProperties, '');\n$width = $modx->getOption('width', $scriptProperties, '');\n$quality = $modx->getOption('quality', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'imgResponsiveRowSrcset');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n\n// Output filters are also processed when the input is empty, so check for that.\nif ($breakpoints == '') { return ''; }\n\n$breakpoints = explode(',', $breakpoints);\n$output = array();\n\n// Process each breakpoint individually\nforeach ($breakpoints as $key => $value) {\n    $output[] = $modx->getChunk($tpl, array(\n        'src' => $src,\n        'crop' => $crop,\n        'width' => $width,\n        'breakpoint' => $value,\n        'quality' => $quality\n    ));\n}\n\n$output = implode(\",\\n\", $output);\n\nif ($placeholder) {\n    $modx->toPlaceholder($placeholder, $output, $prefix);\n    return '';\n} else {\n    return $output;\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:43:"romanesco.responsiveimgsrcset.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:44:"romanesco.responsiveimgsrcset.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * responsiveImgSrcset
 *
 * Generates a number of srcset properties, for use inside an <img> tag.
 *
 * The dimensions for each srcset image are defined inside the
 * img_breakpoints configuration setting.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$breakpoints = $modx->getOption('breakpoints', $scriptProperties, '');
$src = $modx->getOption('src', $scriptProperties, '');
$crop = $modx->getOption('crop', $scriptProperties, '');
$width = $modx->getOption('width', $scriptProperties, '');
$quality = $modx->getOption('quality', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'imgResponsiveRowSrcset');
$prefix = $modx->getOption('prefix', $scriptProperties, '');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');

// Output filters are also processed when the input is empty, so check for that.
if ($breakpoints == '') { return ''; }

$breakpoints = explode(',', $breakpoints);
$output = array();

// Process each breakpoint individually
foreach ($breakpoints as $key => $value) {
    $output[] = $modx->getChunk($tpl, array(
        'src' => $src,
        'crop' => $crop,
        'width' => $width,
        'breakpoint' => $value,
        'quality' => $quality
    ));
}

$output = implode(",\n", $output);

if ($placeholder) {
    $modx->toPlaceholder($placeholder, $output, $prefix);
    return '';
} else {
    return $output;
}