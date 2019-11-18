id: 142
name: setBackground
description: 'Output the necessary class names for the applied Global Background.'
category: f_framework
snippet: "/**\n * setBackground\n *\n * Set the appropriate classes for an applied Global Background.\n * For use in CB layout templates.\n *\n * Backwards compatible with the old, classname-based approach.\n */\n\n$background = $modx->getOption('background', $scriptProperties, $input);\n$cbField = $modx->getOption('romanesco.cb_field_background_id', $scriptProperties, '');\n\n// Numeric value means it's a resource-based background\nif (is_numeric($background)) {\n    $query = $modx->newQuery('modResource', $background);\n    $query->select('alias');\n    $alias = $modx->getValue($query->prepare());\n\n    // Get inverted value from CB settings\n    $inverted = $modx->runSnippet('cbGetFieldContent',array(\n        'resource' => $background,\n        'field' => $cbField,\n        'fieldSettingFilter' => 'inverted==1',\n        'returnAsJSON' => 1,\n    ));\n\n    // Default output is []\n    if (strlen($inverted) > 2) {\n        $inverted = ' inverted';\n    } else {\n        $inverted = '';\n    }\n\n    return $alias . $inverted . ' background';\n}\n\n// For backwards compatibility, return the old classes by default\nreturn $background;"
properties: 'a:0:{}'
content: "/**\n * setBackground\n *\n * Set the appropriate classes for an applied Global Background.\n * For use in CB layout templates.\n *\n * Backwards compatible with the old, classname-based approach.\n */\n\n$background = $modx->getOption('background', $scriptProperties, $input);\n$cbField = $modx->getOption('romanesco.cb_field_background_id', $scriptProperties, '');\n\n// Numeric value means it's a resource-based background\nif (is_numeric($background)) {\n    $query = $modx->newQuery('modResource', $background);\n    $query->select('alias');\n    $alias = $modx->getValue($query->prepare());\n\n    // Get inverted value from CB settings\n    $inverted = $modx->runSnippet('cbGetFieldContent',array(\n        'resource' => $background,\n        'field' => $cbField,\n        'fieldSettingFilter' => 'inverted==1',\n        'returnAsJSON' => 1,\n    ));\n\n    // Default output is []\n    if (strlen($inverted) > 2) {\n        $inverted = ' inverted';\n    } else {\n        $inverted = '';\n    }\n\n    return $alias . $inverted . ' background';\n}\n\n// For backwards compatibility, return the old classes by default\nreturn $background;"

-----


/**
 * setBackground
 *
 * Set the appropriate classes for an applied Global Background.
 * For use in CB layout templates.
 *
 * Backwards compatible with the old, classname-based approach.
 */

$background = $modx->getOption('background', $scriptProperties, $input);
$cbField = $modx->getOption('romanesco.cb_field_background_id', $scriptProperties, '');

// Numeric value means it's a resource-based background
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

    return $alias . $inverted . ' background';
}

// For backwards compatibility, return the old classes by default
return $background;