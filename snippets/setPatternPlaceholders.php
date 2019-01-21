id: 109
name: setPatternPlaceholders
category: f_hub
snippet: "$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n//$ContentBlocks->loadInputs();\n\n$cbField = $modx->getOption('cbField', $scriptProperties, '');\n$cbLayout = $modx->getOption('cbLayout', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\nif ($cbField) {\n    $field = $modx->getObject('cbField', array(\n        'name' => $cbField\n    ));\n\n    if ($field) {\n        // Create an array with all internal fields\n        $array = $field->toArray();\n\n        // Set all fields as placeholders\n        // Use a prefix to prevent collisions\n        $modx->toPlaceholders($array, $prefix);\n\n        // Set placeholder with all field settings parsed in an HTML table\n        //$settingsTable = $modx->runSnippet('jsonToHTML', array(\n        //    'json' => $field->get('settings')\n        //));\n        //$modx->toPlaceholder('settings_table', $settingsTable, $prefix);\n\n        // Above option doesn't work somehow, so just output raw json to placeholder\n        $modx->toPlaceholder('settings_json', $field->get('settings'), $prefix);\n\n        // Set placeholder with wrapper template, if present inside properties field\n        $properties = $field->get('properties');\n        if (strpos($properties, 'wrapper_template') !== false) {\n            // Get the wrapper_template value from its JSON container\n            $wrapperTemplate = $modx->runSnippet('jsonGetValue', array(\n                'json' => $properties,\n                'key' => 'wrapper_template',\n                'tpl' => 'displayRawTemplate'\n            ));\n        }\n        $modx->toPlaceholder('wrapper_template', $wrapperTemplate, $prefix);\n\n        // Set separate placeholder with prefix, for easier retrieval of the other placeholders\n        // Usage example: [[+[[+cb]].placeholder]]\n        $modx->toPlaceholder('cf', $prefix);\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbField . ' could not be processed');\n    }\n}\n\nif ($cbLayout) {\n    $layout = $modx->getObject('cbLayout', array(\n        'name' => $cbLayout\n    ));\n\n    if ($layout) {\n        // Create an array with all internal fields\n        $array = $layout->toArray();\n\n        // Set all fields as placeholders\n        // Use a prefix to prevent collisions\n        $modx->toPlaceholders($array, $prefix);\n\n        // Set placeholder with raw json output from the settings column\n        $modx->toPlaceholder('settings_json', $layout->get('settings'), $prefix);\n\n        // Set separate placeholder with prefix, for easier retrieval of the other placeholders\n        // Usage example: [[+[[+cl]].placeholder]]\n        $modx->toPlaceholder('cl', $prefix);\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbLayout . ' could not be processed');\n    }\n}\n\nreturn '';"
properties: 'a:0:{}'
content: "$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n//$ContentBlocks->loadInputs();\n\n$cbField = $modx->getOption('cbField', $scriptProperties, '');\n$cbLayout = $modx->getOption('cbLayout', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\nif ($cbField) {\n    $field = $modx->getObject('cbField', array(\n        'name' => $cbField\n    ));\n\n    if ($field) {\n        // Create an array with all internal fields\n        $array = $field->toArray();\n\n        // Set all fields as placeholders\n        // Use a prefix to prevent collisions\n        $modx->toPlaceholders($array, $prefix);\n\n        // Set placeholder with all field settings parsed in an HTML table\n        //$settingsTable = $modx->runSnippet('jsonToHTML', array(\n        //    'json' => $field->get('settings')\n        //));\n        //$modx->toPlaceholder('settings_table', $settingsTable, $prefix);\n\n        // Above option doesn't work somehow, so just output raw json to placeholder\n        $modx->toPlaceholder('settings_json', $field->get('settings'), $prefix);\n\n        // Set placeholder with wrapper template, if present inside properties field\n        $properties = $field->get('properties');\n        if (strpos($properties, 'wrapper_template') !== false) {\n            // Get the wrapper_template value from its JSON container\n            $wrapperTemplate = $modx->runSnippet('jsonGetValue', array(\n                'json' => $properties,\n                'key' => 'wrapper_template',\n                'tpl' => 'displayRawTemplate'\n            ));\n        }\n        $modx->toPlaceholder('wrapper_template', $wrapperTemplate, $prefix);\n\n        // Set separate placeholder with prefix, for easier retrieval of the other placeholders\n        // Usage example: [[+[[+cb]].placeholder]]\n        $modx->toPlaceholder('cf', $prefix);\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbField . ' could not be processed');\n    }\n}\n\nif ($cbLayout) {\n    $layout = $modx->getObject('cbLayout', array(\n        'name' => $cbLayout\n    ));\n\n    if ($layout) {\n        // Create an array with all internal fields\n        $array = $layout->toArray();\n\n        // Set all fields as placeholders\n        // Use a prefix to prevent collisions\n        $modx->toPlaceholders($array, $prefix);\n\n        // Set placeholder with raw json output from the settings column\n        $modx->toPlaceholder('settings_json', $layout->get('settings'), $prefix);\n\n        // Set separate placeholder with prefix, for easier retrieval of the other placeholders\n        // Usage example: [[+[[+cl]].placeholder]]\n        $modx->toPlaceholder('cl', $prefix);\n    }\n    else {\n        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbLayout . ' could not be processed');\n    }\n}\n\nreturn '';"

-----


$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');
//$ContentBlocks->loadInputs();

$cbField = $modx->getOption('cbField', $scriptProperties, '');
$cbLayout = $modx->getOption('cbLayout', $scriptProperties, '');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

if ($cbField) {
    $field = $modx->getObject('cbField', array(
        'name' => $cbField
    ));

    if ($field) {
        // Create an array with all internal fields
        $array = $field->toArray();

        // Set all fields as placeholders
        // Use a prefix to prevent collisions
        $modx->toPlaceholders($array, $prefix);

        // Set placeholder with all field settings parsed in an HTML table
        //$settingsTable = $modx->runSnippet('jsonToHTML', array(
        //    'json' => $field->get('settings')
        //));
        //$modx->toPlaceholder('settings_table', $settingsTable, $prefix);

        // Above option doesn't work somehow, so just output raw json to placeholder
        $modx->toPlaceholder('settings_json', $field->get('settings'), $prefix);

        // Set placeholder with wrapper template, if present inside properties field
        $properties = $field->get('properties');
        if (strpos($properties, 'wrapper_template') !== false) {
            // Get the wrapper_template value from its JSON container
            $wrapperTemplate = $modx->runSnippet('jsonGetValue', array(
                'json' => $properties,
                'key' => 'wrapper_template',
                'tpl' => 'displayRawTemplate'
            ));
        }
        $modx->toPlaceholder('wrapper_template', $wrapperTemplate, $prefix);

        // Set separate placeholder with prefix, for easier retrieval of the other placeholders
        // Usage example: [[+[[+cb]].placeholder]]
        $modx->toPlaceholder('cf', $prefix);
    }
    else {
        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbField . ' could not be processed');
    }
}

if ($cbLayout) {
    $layout = $modx->getObject('cbLayout', array(
        'name' => $cbLayout
    ));

    if ($layout) {
        // Create an array with all internal fields
        $array = $layout->toArray();

        // Set all fields as placeholders
        // Use a prefix to prevent collisions
        $modx->toPlaceholders($array, $prefix);

        // Set placeholder with raw json output from the settings column
        $modx->toPlaceholder('settings_json', $layout->get('settings'), $prefix);

        // Set separate placeholder with prefix, for easier retrieval of the other placeholders
        // Usage example: [[+[[+cl]].placeholder]]
        $modx->toPlaceholder('cl', $prefix);
    }
    else {
        $modx->log(modX::LOG_LEVEL_WARN, '[setPatternPlaceholders] ' . $cbLayout . ' could not be processed');
    }
}

return '';