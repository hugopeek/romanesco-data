id: 144
name: fbFormReport
description: 'Generates a report from submitted field values. Primarily for emails, but you can also use this snippet to template other kinds of functionality (confirmation pages, multi page forms..).'
category: f_formblocks
snippet: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix.\n * &formID: Resource ID of the form. Can be a comma-separated list also, for\n *  processing multipage forms.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n */\n\n$formID = $modx->getOption('formID', $scriptProperties, '');\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');\n$tplStepCompleted = $modx->getOption('tplStepCompleted', $scriptProperties, 'fbStoreRowStep');\n$allSteps = $modx->getOption('allSteps', $scriptProperties, '');\n$allForms = $modx->getOption('allForms', $scriptProperties, '');\n$reqOnly = $modx->getOption('requiredOnly', $scriptProperties, '');\n$outputReverse = $modx->getOption('outputReverse', $scriptProperties, 0);\n\nif (!function_exists('getFields')) {\n    function getFields(&$modx, $data, $prefix, $id, $uid, $reqOnly): array\n    {\n        $result = [];\n        $idx = 0;\n\n        foreach($data as $value) {\n            if (!is_array($value)) {\n                continue;\n            }\n\n            // Capture all fields, except for nested fieldsets (which contain fields themselves)\n            if (isset($value['field']) && $value['field'] != $modx->getOption('formblocks.cb_nested_fieldset_id')) {\n                $idx++;\n                $value['settings']['id'] = $id;\n                $value['settings']['uid'] = $uid . '_' . $idx;\n\n                // Only return required fields if specified\n                if ($reqOnly) {\n\n                    // Some fields are always required\n                    switch ($value['field']) {\n                        case $modx->getOption('formblocks.cb_input_email_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'email';\n                            break;\n                        case $modx->getOption('formblocks.cb_accept_terms_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'terms';\n                            break;\n                        case $modx->getOption('formblocks.cb_math_question_id'):\n                            // Almost always...\n                            if (!$modx->getOption('formblocks.ajax_mode')) {\n                                $value['settings']['field_required'] = 1;\n                                $value['settings']['field_type'] = 'math';\n                            }\n                            break;\n                    }\n\n                    if ($value['settings']['field_required'] ?? '' != 1) {\n                        continue;\n                    }\n                }\n\n                $result[] = $modx->getChunk($prefix.$value['field'], $value['settings']);\n                continue;\n            }\n\n            // This iterates over nested fields until a field is found\n            // Each iteration receives a new parent (layout) idx\n            $result[] = getFields($modx, $value, $prefix, $id, $uid++, $reqOnly);\n        }\n\n        return $result;\n    }\n}\n\nif (!$formID) return '';\n$forms = explode(',',$formID);\n$output = [];\n\n// Match form IDs to resource IDs\n$allFormSteps = [];\nif ($allSteps && $allForms) {\n    $allSteps = explode(',',$allSteps);\n    $allForms = explode(',',$allForms);\n    $allFormSteps = array_combine(array_filter($allForms), array_filter($allSteps));\n}\n\n// Reverse output to display multistep forms in consecutive order\nif ($outputReverse) {\n    $forms = array_reverse($forms);\n}\n\n// Set UID to help with caching\n// UID format will end up as formID_layoutID_idx\n$uid = $formID . '_0';\n\nforeach ($forms as $formID) {\n    $resource = $modx->getObject('modResource', $formID);\n    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n    $result = [];\n    $uid++;\n\n    // Only add header if there are multiple forms and a tpl chunk present\n    if ($forms[1] && $tplSectionHeader) {\n        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');\n        $result[] = $modx->getChunk($tplSectionHeader, ['title' => $title]);\n    }\n\n    // Add hidden field to indicate this step is completed\n    if ($allFormSteps) {\n        $result[] = $modx->getChunk($tplStepCompleted, ['id' => $allFormSteps[$formID]]);\n    }\n\n    // Get fields\n    $fields = getFields($modx, $cbData, $tplPrefix, $formID, $uid, $reqOnly);\n\n    // Flatten fields array\n    $fields = new RecursiveIteratorIterator(new RecursiveArrayIterator($fields));\n    foreach($fields as $field) {\n        $result[] = $field;\n    }\n\n    $output[] = implode($result);\n}\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.fbformreport.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.fbformreport.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbFormReport Snippet
 *
 * Generates a report from submitted field values. Primarily used in email
 * responders of course, but you can also use this snippet to template other
 * kinds of functionality (confirmation pages, multi page forms..).
 *
 * @author Jsewill
 * @version 1.0
 *
 * &tplPrefix: Template chunk name prefix.
 * &formID: Resource ID of the form. Can be a comma-separated list also, for
 *  processing multipage forms.
 *
 * @var modX $modx
 * @var array $scriptProperties;
 */

$formID = $modx->getOption('formID', $scriptProperties, '');
$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');
$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');
$tplStepCompleted = $modx->getOption('tplStepCompleted', $scriptProperties, 'fbStoreRowStep');
$allSteps = $modx->getOption('allSteps', $scriptProperties, '');
$allForms = $modx->getOption('allForms', $scriptProperties, '');
$reqOnly = $modx->getOption('requiredOnly', $scriptProperties, '');
$outputReverse = $modx->getOption('outputReverse', $scriptProperties, 0);

if (!function_exists('getFields')) {
    function getFields(&$modx, $data, $prefix, $id, $uid, $reqOnly): array
    {
        $result = [];
        $idx = 0;

        foreach($data as $value) {
            if (!is_array($value)) {
                continue;
            }

            // Capture all fields, except for nested fieldsets (which contain fields themselves)
            if (isset($value['field']) && $value['field'] != $modx->getOption('formblocks.cb_nested_fieldset_id')) {
                $idx++;
                $value['settings']['id'] = $id;
                $value['settings']['uid'] = $uid . '_' . $idx;

                // Only return required fields if specified
                if ($reqOnly) {

                    // Some fields are always required
                    switch ($value['field']) {
                        case $modx->getOption('formblocks.cb_input_email_id'):
                            $value['settings']['field_required'] = 1;
                            $value['settings']['field_type'] = 'email';
                            break;
                        case $modx->getOption('formblocks.cb_accept_terms_id'):
                            $value['settings']['field_required'] = 1;
                            $value['settings']['field_type'] = 'terms';
                            break;
                        case $modx->getOption('formblocks.cb_math_question_id'):
                            // Almost always...
                            if (!$modx->getOption('formblocks.ajax_mode')) {
                                $value['settings']['field_required'] = 1;
                                $value['settings']['field_type'] = 'math';
                            }
                            break;
                    }

                    if ($value['settings']['field_required'] ?? '' != 1) {
                        continue;
                    }
                }

                $result[] = $modx->getChunk($prefix.$value['field'], $value['settings']);
                continue;
            }

            // This iterates over nested fields until a field is found
            // Each iteration receives a new parent (layout) idx
            $result[] = getFields($modx, $value, $prefix, $id, $uid++, $reqOnly);
        }

        return $result;
    }
}

if (!$formID) return '';
$forms = explode(',',$formID);
$output = [];

// Match form IDs to resource IDs
$allFormSteps = [];
if ($allSteps && $allForms) {
    $allSteps = explode(',',$allSteps);
    $allForms = explode(',',$allForms);
    $allFormSteps = array_combine(array_filter($allForms), array_filter($allSteps));
}

// Reverse output to display multistep forms in consecutive order
if ($outputReverse) {
    $forms = array_reverse($forms);
}

// Set UID to help with caching
// UID format will end up as formID_layoutID_idx
$uid = $formID . '_0';

foreach ($forms as $formID) {
    $resource = $modx->getObject('modResource', $formID);
    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);
    $result = [];
    $uid++;

    // Only add header if there are multiple forms and a tpl chunk present
    if ($forms[1] && $tplSectionHeader) {
        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');
        $result[] = $modx->getChunk($tplSectionHeader, ['title' => $title]);
    }

    // Add hidden field to indicate this step is completed
    if ($allFormSteps) {
        $result[] = $modx->getChunk($tplStepCompleted, ['id' => $allFormSteps[$formID]]);
    }

    // Get fields
    $fields = getFields($modx, $cbData, $tplPrefix, $formID, $uid, $reqOnly);

    // Flatten fields array
    $fields = new RecursiveIteratorIterator(new RecursiveArrayIterator($fields));
    foreach($fields as $field) {
        $result[] = $field;
    }

    $output[] = implode($result);
}

return implode($output);