id: 144
name: fbFormReport
description: 'Generates a report from submitted field values. Primarily for emails, but you can also use this snippet to template other kinds of functionality (confirmation pages, multi page forms..).'
category: f_formblocks
snippet: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix\n * &id: Resource ID of the resource where the form is being used\n */\n\nfunction getFields(&$modx, $data, $prefix) {\n    $result = '';\n\n    foreach($data as $key => $value) {\n        if(!is_array($value)) {\n            continue;\n        }\n\n        if(isset($value['field'])) {\n            $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n            continue;\n        }\n\n        $result .= getFields($modx, $value, $prefix);\n    }\n\n    return $result;\n}\n\n$output = '';\n\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));\n$resource = $modx->getObject('modResource', $id);\n\n$cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n\n$output .= getFields($modx, $cbData, $tplPrefix);\n\nreturn $output;"
properties: 'a:0:{}'
content: "/**\n * fbFormReport Snippet\n *\n * Generates a report from submitted field values. Primarily used in email\n * responders of course, but you can also use this snippet to template other\n * kinds of functionality (confirmation pages, multi page forms..).\n *\n * @author Jsewill\n * @version 1.0\n *\n * &tplPrefix: Template chunk name prefix\n * &id: Resource ID of the resource where the form is being used\n */\n\nfunction getFields(&$modx, $data, $prefix) {\n    $result = '';\n\n    foreach($data as $key => $value) {\n        if(!is_array($value)) {\n            continue;\n        }\n\n        if(isset($value['field'])) {\n            $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n            continue;\n        }\n\n        $result .= getFields($modx, $value, $prefix);\n    }\n\n    return $result;\n}\n\n$output = '';\n\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));\n$resource = $modx->getObject('modResource', $id);\n\n$cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);\n\n$output .= getFields($modx, $cbData, $tplPrefix);\n\nreturn $output;"

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
 * &tplPrefix: Template chunk name prefix
 * &id: Resource ID of the resource where the form is being used
 */

function getFields(&$modx, $data, $prefix) {
    $result = '';

    foreach($data as $key => $value) {
        if(!is_array($value)) {
            continue;
        }

        if(isset($value['field'])) {
            $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);
            continue;
        }

        $result .= getFields($modx, $value, $prefix);
    }

    return $result;
}

$output = '';

$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');
$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));
$resource = $modx->getObject('modResource', $id);

$cbData = json_decode($resource->getProperty('content', 'contentblocks'), true);

$output .= getFields($modx, $cbData, $tplPrefix);

return $output;