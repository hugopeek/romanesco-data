id: 144
name: fbFormReport
category: f_formblocks
snippet: "/**\n * fbFormReport snippet\n *\n * Generates a report email for FormBlocks forms.\n *\n * @author Jsewill\n * @version 0.1\n *\n * &tplPrefix   Template chunk name prefix\n * &id          Resource ID of the resource where the form is being used\n *\n */\n\nfunction getFields(&$modx, $data, $prefix) {\n    $result = '';\n\n    foreach($data as $key => $value) {\n        if(!is_array($value)) {\n            continue;\n        }\n\n        if(isset($value['field'])) {\n            $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n            continue;\n        }\n\n        $result .= getFields($modx, $value, $prefix);\n    }\n\n    return $result;\n}\n\n$output = '';\n\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));\n$resource = $modx->getObject('modResource', $id);\n\n$cbdata = json_decode($resource->getProperty('content', 'contentblocks'), true);\n$cbsettings = $modx->getIterator('modSystemSetting', array('namespace'=>'romanesco', 'key:LIKE'=>'formblocks.cb_%'));\n\n$output .= getFields($modx, $cbdata, $tplPrefix);\n\nreturn $output;"
properties: 'a:0:{}'
content: "/**\n * fbFormReport snippet\n *\n * Generates a report email for FormBlocks forms.\n *\n * @author Jsewill\n * @version 0.1\n *\n * &tplPrefix   Template chunk name prefix\n * &id          Resource ID of the resource where the form is being used\n *\n */\n\nfunction getFields(&$modx, $data, $prefix) {\n    $result = '';\n\n    foreach($data as $key => $value) {\n        if(!is_array($value)) {\n            continue;\n        }\n\n        if(isset($value['field'])) {\n            $result .= $modx->getChunk($prefix.$value['field'], $value['settings']);\n            continue;\n        }\n\n        $result .= getFields($modx, $value, $prefix);\n    }\n\n    return $result;\n}\n\n$output = '';\n\n$tplPrefix = $modx->getOption('tplPrefix', $scriptProperties, 'fbEmailRow_');\n$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id'));\n$resource = $modx->getObject('modResource', $id);\n\n$cbdata = json_decode($resource->getProperty('content', 'contentblocks'), true);\n$cbsettings = $modx->getIterator('modSystemSetting', array('namespace'=>'romanesco', 'key:LIKE'=>'formblocks.cb_%'));\n\n$output .= getFields($modx, $cbdata, $tplPrefix);\n\nreturn $output;"

-----


/**
 * fbFormReport snippet
 *
 * Generates a report email for FormBlocks forms.
 *
 * @author Jsewill
 * @version 0.1
 *
 * &tplPrefix   Template chunk name prefix
 * &id          Resource ID of the resource where the form is being used
 *
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

$cbdata = json_decode($resource->getProperty('content', 'contentblocks'), true);
$cbsettings = $modx->getIterator('modSystemSetting', array('namespace'=>'romanesco', 'key:LIKE'=>'formblocks.cb_%'));

$output .= getFields($modx, $cbdata, $tplPrefix);

return $output;