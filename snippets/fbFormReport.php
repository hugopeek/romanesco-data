id: 144
name: fbFormReport
description: 'Generates a report from submitted field values. Primarily for emails, but you can also use this snippet to template other kinds of functionality (confirmation pages, multi page forms..).'
category: f_formblocks
snippet: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix.\n * &formID: Resource ID of the form. Can be a comma-separated list also, for\n *  processing multi-page forms.\n */\n\nif (!function_exists('getFields')) {\n    function getFields(&$modx, $data, $prefix, $id) {\n        $result = '';\n\n        foreach($data as $key => $value) {\n            if(!is_array($value)) {\n                continue;\n            }\n\n            if(isset($value['field'])) {\n                $value['settings']['id'] = $id;\n                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n                continue;\n            }\n\n            $result .= getFields($modx, $value, $prefix, $id);\n        }\n\n        return $result;\n    }\n}\n\n$output = array();\n\n$formID = $modx->getOption('formID', $scriptProperties, '');\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');\n\nif (!$formID) return '';\n$forms = explode(',',$formID);\n\nforeach ($forms as $formID) {\n    $resource = $modx->getObject('modResource', $formID);\n    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n    $result = '';\n\n    // Only add header if there are multiple forms and a tpl chunk present\n    if ($forms[1] && $tplSectionHeader) {\n        $result .= $modx->getChunk($tplSectionHeader, array(\"title\" => $resource->get('pagetitle')));\n    }\n\n    $result .= getFields($modx, $cbData, $tplPrefix, $formID);\n\n    $output[] = $result;\n}\n\nreturn implode(array_reverse($output));"
properties: 'a:0:{}'
content: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix.\n * &formID: Resource ID of the form. Can be a comma-separated list also, for\n *  processing multi-page forms.\n */\n\nif (!function_exists('getFields')) {\n    function getFields(&$modx, $data, $prefix, $id) {\n        $result = '';\n\n        foreach($data as $key => $value) {\n            if(!is_array($value)) {\n                continue;\n            }\n\n            if(isset($value['field'])) {\n                $value['settings']['id'] = $id;\n                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n                continue;\n            }\n\n            $result .= getFields($modx, $value, $prefix, $id);\n        }\n\n        return $result;\n    }\n}\n\n$output = array();\n\n$formID = $modx->getOption('formID', $scriptProperties, '');\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');\n\nif (!$formID) return '';\n$forms = explode(',',$formID);\n\nforeach ($forms as $formID) {\n    $resource = $modx->getObject('modResource', $formID);\n    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n    $result = '';\n\n    // Only add header if there are multiple forms and a tpl chunk present\n    if ($forms[1] && $tplSectionHeader) {\n        $result .= $modx->getChunk($tplSectionHeader, array(\"title\" => $resource->get('pagetitle')));\n    }\n\n    $result .= getFields($modx, $cbData, $tplPrefix, $formID);\n\n    $output[] = $result;\n}\n\nreturn implode(array_reverse($output));"

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
 */

if (!function_exists('getFields')) {
    function getFields(&$modx, $data, $prefix, $id) {
        $result = '';

        foreach($data as $key => $value) {
            if(!is_array($value)) {
                continue;
            }

            if(isset($value['field'])) {
                $value['settings']['id'] = $id;
                $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);
                continue;
            }

            $result .= getFields($modx, $value, $prefix, $id);
        }

        return $result;
    }
}

$output = array();

$formID = $modx->getOption('formID', $scriptProperties, '');
$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');
$tplSectionHeader = $modx->getOption('tplSectionHeader', $scriptProperties, '');

if (!$formID) return '';
$forms = explode(',',$formID);

foreach ($forms as $formID) {
    $resource = $modx->getObject('modResource', $formID);
    $cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);
    $result = '';

    // Only add header if there are multiple forms and a tpl chunk present
    if ($forms[1] && $tplSectionHeader) {
        $title = $resource->get('menutitle') ? $resource->get('menutitle') : $resource->get('pagetitle');
        $result .= $modx->getChunk($tplSectionHeader, array("title" => $title));
    }

    $result .= getFields($modx, $cbData, $tplPrefix, $formID);

    $output[] = $result;
}

return implode($output);