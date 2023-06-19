id: 142
name: setBackground
description: 'Output the necessary class names for the applied Global Background.'
category: f_framework
snippet: "/**\n * setBackground\n *\n * Set the appropriate classes for an applied Global Background.\n * For use in CB layout templates.\n *\n * Backwards compatible with the old, classname-based approach.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n */\n\n$background = $modx->getOption('background', $scriptProperties, $input);\n$cbField = $modx->getOption('romanesco.cb_field_background_id', $scriptProperties, '');\n\n// Convert system default value\nif ($background == 'default') {\n    $background = $modx->getObject('cgSetting', array('key' => 'layout_background_default'))->get('value');\n}\n\n// Numeric value means it's a resource-based global background\nif (is_numeric($background)) {\n    $query = $modx->newQuery('modResource', $background);\n    $query->select('alias');\n    $alias = $modx->getValue($query->prepare());\n\n    // Get inverted value from CB settings\n    $inverted = $modx->runSnippet('cbGetFieldContent',array(\n        'resource' => $background,\n        'field' => $cbField,\n        'fieldSettingFilter' => 'inverted==1',\n        'returnAsJSON' => 1,\n    ));\n\n    // Default output is []\n    if (strlen($inverted) > 2) {\n        $inverted = ' inverted';\n    } else {\n        $inverted = '';\n    }\n\n    $background = $alias . $inverted . ' background';\n}\n\nreturn $background;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.setbackground.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.setbackground.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * setBackground
 *
 * Set the appropriate classes for an applied Global Background.
 * For use in CB layout templates.
 *
 * Backwards compatible with the old, classname-based approach.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 */

$background = $modx->getOption('background', $scriptProperties, $input);
$cbField = $modx->getOption('romanesco.cb_field_background_id', $scriptProperties, '');

// Convert system default value
if ($background == 'default') {
    $background = $modx->getObject('cgSetting', array('key' => 'layout_background_default'))->get('value');
}

// Numeric value means it's a resource-based global background
if (is_numeric($background)) {
    $query = $modx->newQuery('modResource', $background);
    $query->select('alias');
    $alias = $modx->getValue($query->prepare());

    // Get inverted value from CB settings
    $inverted = $modx->runSnippet('cbGetFieldContent',array(
        'resource' => $background,
        'field' => $cbField,
        'fieldSettingFilter' => 'inverted==1',
        'returnAsJSON' => 1,
    ));

    // Default output is []
    if (strlen($inverted) > 2) {
        $inverted = ' inverted';
    } else {
        $inverted = '';
    }

    $background = $alias . $inverted . ' background';
}

return $background;