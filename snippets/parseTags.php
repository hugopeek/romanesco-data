id: 90
name: parseTags
description: 'Take in a comma separated string and turn each value into a separate tag. Sometimes you just need that :)'
category: f_modifiers
snippet: "/**\n * parseTags output filter\n * by Mark Hamstra (http://www.markhamstra.nl)\n * free to use / modify / distribute to your will\n */\n\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tagItemBasic');\n$iconClass = $modx->getOption('iconClass', $scriptProperties, 'info');\n\nif ($input == '') { return ''; } // Output filters are also processed when the input is empty, so check for that.\n$tags = explode(',',$input); // Based on a delimiter of comma-space.\n\n// Process them individually\nforeach ($tags as $key => $value) {\n    if (stripos($tpl,'flag') === false) {\n        $value = ucfirst($value);\n    }\n    $output[] = $modx->getChunk($tpl,array(\n        'tag' => $value,\n        'icon_class' => $iconClass,\n    ));\n}\n\nreturn implode('', $output);"
properties: 'a:0:{}'
content: "/**\n * parseTags output filter\n * by Mark Hamstra (http://www.markhamstra.nl)\n * free to use / modify / distribute to your will\n */\n\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tagItemBasic');\n$iconClass = $modx->getOption('iconClass', $scriptProperties, 'info');\n\nif ($input == '') { return ''; } // Output filters are also processed when the input is empty, so check for that.\n$tags = explode(',',$input); // Based on a delimiter of comma-space.\n\n// Process them individually\nforeach ($tags as $key => $value) {\n    if (stripos($tpl,'flag') === false) {\n        $value = ucfirst($value);\n    }\n    $output[] = $modx->getChunk($tpl,array(\n        'tag' => $value,\n        'icon_class' => $iconClass,\n    ));\n}\n\nreturn implode('', $output);"

-----


/**
 * parseTags output filter
 * by Mark Hamstra (http://www.markhamstra.nl)
 * free to use / modify / distribute to your will
 */

$tpl = $modx->getOption('tpl', $scriptProperties, 'tagItemBasic');
$iconClass = $modx->getOption('iconClass', $scriptProperties, 'info');

if ($input == '') { return ''; } // Output filters are also processed when the input is empty, so check for that.
$tags = explode(',',$input); // Based on a delimiter of comma-space.

// Process them individually
foreach ($tags as $key => $value) {
    if (stripos($tpl,'flag') === false) {
        $value = ucfirst($value);
    }
    $output[] = $modx->getChunk($tpl,array(
        'tag' => $value,
        'icon_class' => $iconClass,
    ));
}

return implode('', $output);