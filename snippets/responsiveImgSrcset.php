id: 115
name: responsiveImgSrcset
description: 'Generate a number of srcset properties, for use inside an img tag.'
category: f_presentation
snippet: "/**\n * responsiveImgSrcset\n *\n * Generates a number of srcset properties, for use inside an <img> tag.\n *\n * The dimensions for each srcset image are defined inside the\n * responsive_img_breakpoints configuration setting.\n *\n * @author: Hugo Peek\n * @license: MIT\n */\n\n$breakpoints = $modx->getOption('breakpoints', $scriptProperties, '');\n$src = $modx->getOption('src', $scriptProperties, '');\n$crop = $modx->getOption('crop', $scriptProperties, '');\n$width = $modx->getOption('width', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'imgResponsiveRowSrcset');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n\n// Output filters are also processed when the input is empty, so check for that.\nif ($breakpoints == '') { return ''; }\n$breakpoints = explode(',', $breakpoints);\n\n// Process each breakpoint individually\nforeach ($breakpoints as $key => $value) {\n    $output[] = $modx->getChunk($tpl, array(\n        'src' => $src,\n        'crop' => $crop,\n        'width' => $width,\n        'breakpoint' => $value,\n    ));\n}\n\nif ($placeholder) {\n    $modx->toPlaceholder($placeholder, implode(\",\\n\", $output));\n    return '';\n} else {\n    return implode(\",\\n\", $output);\n}"
properties: 'a:0:{}'
content: "/**\n * responsiveImgSrcset\n *\n * Generates a number of srcset properties, for use inside an <img> tag.\n *\n * The dimensions for each srcset image are defined inside the\n * responsive_img_breakpoints configuration setting.\n *\n * @author: Hugo Peek\n * @license: MIT\n */\n\n$breakpoints = $modx->getOption('breakpoints', $scriptProperties, '');\n$src = $modx->getOption('src', $scriptProperties, '');\n$crop = $modx->getOption('crop', $scriptProperties, '');\n$width = $modx->getOption('width', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'imgResponsiveRowSrcset');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n\n// Output filters are also processed when the input is empty, so check for that.\nif ($breakpoints == '') { return ''; }\n$breakpoints = explode(',', $breakpoints);\n\n// Process each breakpoint individually\nforeach ($breakpoints as $key => $value) {\n    $output[] = $modx->getChunk($tpl, array(\n        'src' => $src,\n        'crop' => $crop,\n        'width' => $width,\n        'breakpoint' => $value,\n    ));\n}\n\nif ($placeholder) {\n    $modx->toPlaceholder($placeholder, implode(\",\\n\", $output));\n    return '';\n} else {\n    return implode(\",\\n\", $output);\n}"

-----


/**
 * responsiveImgSrcset
 *
 * Generates a number of srcset properties, for use inside an <img> tag.
 *
 * The dimensions for each srcset image are defined inside the
 * responsive_img_breakpoints configuration setting.
 *
 * @author: Hugo Peek
 * @license: MIT
 */

$breakpoints = $modx->getOption('breakpoints', $scriptProperties, '');
$src = $modx->getOption('src', $scriptProperties, '');
$crop = $modx->getOption('crop', $scriptProperties, '');
$width = $modx->getOption('width', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'imgResponsiveRowSrcset');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');

// Output filters are also processed when the input is empty, so check for that.
if ($breakpoints == '') { return ''; }
$breakpoints = explode(',', $breakpoints);

// Process each breakpoint individually
foreach ($breakpoints as $key => $value) {
    $output[] = $modx->getChunk($tpl, array(
        'src' => $src,
        'crop' => $crop,
        'width' => $width,
        'breakpoint' => $value,
    ));
}

if ($placeholder) {
    $modx->toPlaceholder($placeholder, implode(",\n", $output));
    return '';
} else {
    return implode(",\n", $output);
}