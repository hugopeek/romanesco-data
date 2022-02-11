id: 144
name: fbFormReport
description: 'Generates a report from submitted field values. Primarily for emails, but you can also use this snippet to template other kinds of functionality (confirmation pages, multi page forms..).'
category: f_formblocks
snippet: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix.\n * &formID: Resource ID of the form. Can be a comma-separated list also, for\n *  processing multi-page forms.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n */\n\n$formID = $modx->getOption('formID', $scriptProperties, '');\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');\n$reqOnly = $modx->getOption('requiredOnly', $scriptProperties, '');\n\nif (!function_exists('getFields')) {\n    function getFields(&$modx, $data, $prefix, $id, $reqOnly) {\n        $result = '';\n\n        foreach($data as $value) {\n            if (!is_array($value)) {\n                continue;\n            }\n\n            if (isset($value['field'])) {\n                $value['settings']['id'] = $id;\n\n                // Only return required fields if specified\n                if ($reqOnly) {\n\n                    // Some fields are always required\n                    switch ($value['field']) {\n                        case $modx->getOption('formblocks.cb_input_email_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'email';\n                            break;\n                        case $modx->getOption('formblocks.cb_accept_terms_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'terms';\n                            break;\n                        case $modx->getOption('formblocks.cb_math_question_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'math';\n                            break;\n                    }\n\n                    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($value['settings'],1));\n\n                    if ($value['settings']['field_required'] != 1) {\n                        continue;\n                    }\n                }\n\n                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n                continue;\n            }\n\n            $result .= getFields($modx, $value, $prefix, $id, $reqOnly);\n        }\n\n        return $result;\n    }\n}\n\nif (!$formID) return '';\n$forms = explode(',',$formID);\n$output = array();\n\nforeach ($forms as $formID) {\n    $resource = $modx->getObject('modResource', $formID);\n    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n    $result = '';\n\n    // Only add header if there are multiple forms and a tpl chunk present\n    if ($forms[1] && $tplSectionHeader) {\n        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');\n        $result .= $modx->getChunk($tplSectionHeader, array(\"title\" => $title));\n    }\n\n    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($cbData,1));\n\n    $result .= getFields($modx, $cbData, $tplPrefix, $formID, $reqOnly);\n\n    $output[] = $result;\n}\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.fbformreport.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.fbformreport.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix.\n * &formID: Resource ID of the form. Can be a comma-separated list also, for\n *  processing multi-page forms.\n *\n * @var modX $modx\n * @var array $scriptProperties;\n */\n\n$formID = $modx->getOption('formID', $scriptProperties, '');\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');\n$reqOnly = $modx->getOption('requiredOnly', $scriptProperties, '');\n\nif (!function_exists('getFields')) {\n    function getFields(&$modx, $data, $prefix, $id, $reqOnly) {\n        $result = '';\n\n        foreach($data as $value) {\n            if (!is_array($value)) {\n                continue;\n            }\n\n            if (isset($value['field'])) {\n                $value['settings']['id'] = $id;\n\n                // Only return required fields if specified\n                if ($reqOnly) {\n\n                    // Some fields are always required\n                    switch ($value['field']) {\n                        case $modx->getOption('formblocks.cb_input_email_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'email';\n                            break;\n                        case $modx->getOption('formblocks.cb_accept_terms_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'terms';\n                            break;\n                        case $modx->getOption('formblocks.cb_math_question_id'):\n                            $value['settings']['field_required'] = 1;\n                            $value['settings']['field_type'] = 'math';\n                            break;\n                    }\n\n                    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($value['settings'],1));\n\n                    if ($value['settings']['field_required'] != 1) {\n                        continue;\n                    }\n                }\n\n                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n                continue;\n            }\n\n            $result .= getFields($modx, $value, $prefix, $id, $reqOnly);\n        }\n\n        return $result;\n    }\n}\n\nif (!$formID) return '';\n$forms = explode(',',$formID);\n$output = array();\n\nforeach ($forms as $formID) {\n    $resource = $modx->getObject('modResource', $formID);\n    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n    $result = '';\n\n    // Only add header if there are multiple forms and a tpl chunk present\n    if ($forms[1] && $tplSectionHeader) {\n        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');\n        $result .= $modx->getChunk($tplSectionHeader, array(\"title\" => $title));\n    }\n\n    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($cbData,1));\n\n    $result .= getFields($modx, $cbData, $tplPrefix, $formID, $reqOnly);\n\n    $output[] = $result;\n}\n\nreturn implode($output);"

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
 *  processing multi-page forms.
 *
 * @var modX $modx
 * @var array $scriptProperties;
 */

$formID = $modx->getOption('formID', $scriptProperties, '');
$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');
$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');
$reqOnly = $modx->getOption('requiredOnly', $scriptProperties, '');

if (!function_exists('getFields')) {
    function getFields(&$modx, $data, $prefix, $id, $reqOnly) {
        $result = '';

        foreach($data as $value) {
            if (!is_array($value)) {
                continue;
            }

            if (isset($value['field'])) {
                $value['settings']['id'] = $id;

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
                            $value['settings']['field_required'] = 1;
                            $value['settings']['field_type'] = 'math';
                            break;
                    }

                    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($value['settings'],1));

                    if ($value['settings']['field_required'] != 1) {
                        continue;
                    }
                }

                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);
                continue;
            }

            $result .= getFields($modx, $value, $prefix, $id, $reqOnly);
        }

        return $result;
    }
}

if (!$formID) return '';
$forms = explode(',',$formID);
$output = array();

foreach ($forms as $formID) {
    $resource = $modx->getObject('modResource', $formID);
    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);
    $result = '';

    // Only add header if there are multiple forms and a tpl chunk present
    if ($forms[1] && $tplSectionHeader) {
        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');
        $result .= $modx->getChunk($tplSectionHeader, array("title" => $title));
    }

    //$modx->log(modX::LOG_LEVEL_ERROR, print_r($cbData,1));

    $result .= getFields($modx, $cbData, $tplPrefix, $formID, $reqOnly);

    $output[] = $result;
}

return implode($output);