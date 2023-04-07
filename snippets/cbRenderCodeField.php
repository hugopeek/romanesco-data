id: 110
name: cbRenderCodeField
description: 'For use in a CB Code field, together with a field setting to control how the :tag modifier is rendered. Useful for dealing with MODX tags inside a Code field, i.e. when writing documentation.'
category: f_contentblocks
snippet: "/**\n * cbRenderCodeField\n *\n * Useful when dealing with MODX tags inside a Code field, i.e. for documentation.\n * Used in conjunction with a field setting to control how the :tag modifier is rendered.\n *\n * Available options:\n *\n * render -> Render :tag modifier(s) anyway and set code_field_raw placeholder\n * respect -> Respect :tag modifier(s) and set code_field_rendered placeholder\n * ignore -> Process everything as usual, without setting any placeholders\n *\n */\n\n$valueRaw = $modx->getOption('valueRaw', $scriptProperties, '');\n$valueRendered = $modx->getOption('valueRendered', $scriptProperties, '');\n$renderTag = $modx->getOption('renderTag', $scriptProperties, '');\n\n$output = '';\n\nswitch($renderTag) {\n    case $renderTag == 'render':\n        $modx->toPlaceholder('code_field_raw', $valueRaw);\n        $output = $valueRendered;\n        break;\n    case $renderTag == 'respect':\n        $modx->toPlaceholder('code_field_rendered', $valueRendered);\n        $output = $valueRaw;\n        break;\n    case $renderTag == 'ignore':\n    default:\n        $output = $valueRaw;\n        break;\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.cbrendercodefield.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.cbrendercodefield.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * cbRenderCodeField
 *
 * Useful when dealing with MODX tags inside a Code field, i.e. for documentation.
 * Used in conjunction with a field setting to control how the :tag modifier is rendered.
 *
 * Available options:
 *
 * render -> Render :tag modifier(s) anyway and set code_field_raw placeholder
 * respect -> Respect :tag modifier(s) and set code_field_rendered placeholder
 * ignore -> Process everything as usual, without setting any placeholders
 *
 */

$valueRaw = $modx->getOption('valueRaw', $scriptProperties, '');
$valueRendered = $modx->getOption('valueRendered', $scriptProperties, '');
$renderTag = $modx->getOption('renderTag', $scriptProperties, '');

$output = '';

switch($renderTag) {
    case $renderTag == 'render':
        $modx->toPlaceholder('code_field_raw', $valueRaw);
        $output = $valueRendered;
        break;
    case $renderTag == 'respect':
        $modx->toPlaceholder('code_field_rendered', $valueRendered);
        $output = $valueRaw;
        break;
    case $renderTag == 'ignore':
    default:
        $output = $valueRaw;
        break;
}

return $output;