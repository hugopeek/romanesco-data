id: 54
name: cbGetFieldContent
description: 'Get the content of a particular ContentBlocks field. (Part of ContentBlocks)'
category: ContentBlocks
snippet: "/**\n * Use the cbGetFieldContent snippet to get the content of a particular field.\n *\n * For example, this can be useful if you need to get a bit of content\n * in a getResources call\n *\n * Example usage:\n *\n * [[cbGetFieldContent?\n *      &field=`13`\n *      &tpl=`fieldTpl`\n * ]]\n *\n * [[cbGetFieldContent?\n *      &field=`13`\n *      &fieldSettingFilter=`class==keyImage`\n *      &tpl=`fieldTpl`\n * ]]\n *\n * An optional &resource param allows checking for fields on other resources.\n * An option &fieldSettingFilter allows filtering by == or != of a field setting. Only items matching the filter will be returned.\n * An optional &limit param allows limiting the number of matched fields\n * An optional &offset param allows skipping the first n matched fields\n * An optional &tpl param is a chunk name defining a template to use for your field. If not set,\n *   the ContentBlocks template for the field will be used.\n * An optional &wrapTpl param is a chunk name defining a template to use for your field wrapper.\n *   If not set, the ContentBlocks wrapper template for the field will be used. Applies only to\n *   multi-value inputs (galleries, files, etc.)\n * An optional &returnAsJSON parameter will return all values of the selected field as JSON.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n\n// Use the current resource if it's available\n$resource = isset($modx->resource) ? $modx->resource : false;\n\n// If we have a requested resource...\nif ($scriptProperties['resource']) {\n    // ... check if it is the same one as the current, and only load the requested resource if it isn't\n    if ($resource instanceof modResource) {\n        if ($scriptProperties['resource'] != $resource->get('id')) {\n            $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);\n        }\n    }\n    // ... or grab the requested resource anyway\n    else {\n        $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);\n    }\n}\n\n// Make sure we have a resource or end here\nif (!$resource) {\n    return '';\n}\n\n$fld = $modx->getOption('field', $scriptProperties, 0, true);\n$fieldSettingFilter = $modx->getOption('fieldSettingFilter', $scriptProperties, false, true);\n$limit = $modx->getOption('limit', $scriptProperties, 0, true);\n$offset = $modx->getOption('offset', $scriptProperties, 0, true);\n$innerLimit = $modx->getOption('innerLimit', $scriptProperties, 0, true);\n$innerOffset = $modx->getOption('innerOffset', $scriptProperties, 0, true);\n$tpl = $modx->getOption('tpl', $scriptProperties, false, true);\n$wrapTpl = $modx->getOption('wrapTpl', $scriptProperties, false, true);\n$showDebug = $modx->getOption('showDebug', $scriptProperties, false, true);\n$returnAsJSON = $modx->getOption('returnAsJSON', $scriptProperties, false, true);\n\n/** @var array $debug */\n$debug = array('scriptProperties' => $scriptProperties);\n$output = '';\n\nif(!$fld) {\n    $showDebug = true;\n}\n\nelse {\n    // Make sure this is a contentblocks-enabled resource\n    $enabled = $resource->getProperty('_isContentBlocks', 'contentblocks');\n    $debug['enabled'] = (bool)$enabled;\n    if ($enabled !== true) return;\n\n    // Get the field counts\n    $counts = $resource->getProperty('fieldcounts', 'contentblocks');\n    $debug['counts'] = $counts;\n    if (is_array($counts) && isset($counts[$fld])) {\n        $fieldsData = $resource->getProperty('linear', 'contentblocks');\n        $fieldsTypeData = array();\n        $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n        $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n        $ContentBlocks->loadInputs();\n        $field = $modx->getObject('cbField', $fld);\n\n        if(!($field instanceof cbField)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[cbGetFieldContent] Error loading field ' . $fld);\n            return;\n        }\n\n        if($tpl) {\n            $chunk = $modx->getObject('modChunk', array('name' => $tpl));\n            if ($chunk instanceof modChunk) {\n                $field->set('template', $chunk->getContent());\n            }\n        }\n\n        if($wrapTpl) {\n            $chunk = $modx->getObject('modChunk', array('name' => $wrapTpl));\n            if ($chunk instanceof modChunk) {\n                $field->set('wrapper_template', $chunk->getContent());\n            }\n        }\n\n        $debug['fieldsData'] = $fieldsData;\n\n        foreach($fieldsData as $fieldData) {\n            if($fieldData['field'] == $fld) {\n                $fieldsTypeData[] = $fieldData;\n            }\n        }\n\n        $debug['fieldsTypeData'] = $fieldsTypeData;\n\n        if($fieldSettingFilter) {\n            $operators = array(\n                '!=' => '!=',\n                '==' => '==',\n            );\n            $filters = explode(',', $fieldSettingFilter);\n            $debug['filters'] = array('original' => $filters);\n            foreach($filters as $i => $filter) {\n                foreach($operators as $op => $symbol) {\n                    if (strpos($filter, $op, 1) !== false) {\n                        $operator = $op;\n                        break;\n                    }\n                }\n\n                $filter = explode($operator, $filter);\n                $debug[$i]['filter'] = $filter;\n                $setting = array_shift($filter);\n                $value = array_shift($filter);\n                $debug[$i]['setting'] = $setting;\n                $debug[$i]['value'] = $value;\n                $debug[$i]['operator'] = $operator;\n\n                foreach($fieldsTypeData as $idx => $fieldData) {\n                    if($fieldData['settings'] && array_key_exists($setting, $fieldData['settings'])) {\n                        switch($operator) {\n                            case '==' :\n                                if($fieldData['settings'][$setting] != $value) {\n                                    unset($fieldsTypeData[$idx]);\n                                }\n                                break;\n                            case '!=' :\n                                if($fieldData['settings'][$setting] == $value) {\n                                    unset($fieldsTypeData[$idx]);\n                                }\n                                break;\n                        }\n                    }\n                }\n            }\n        }\n\n        if($offset) {\n            $fieldsTypeData = array_splice($fieldsTypeData, (int)$offset);\n            $debug['offset'] = $offset;\n        }\n        if($limit) {\n            $fieldsTypeData = array_splice($fieldsTypeData, 0, $limit);\n            $debug['limit'] = $limit;\n        }\n\n        $debug['result'] = $fieldsTypeData;\n\n        if ($innerLimit || $innerOffset) {\n            switch ($field->get('input')) {\n                case 'repeater':\n                    $keyname = 'rows';\n                    break;\n                case 'gallery':\n                    $keyname = 'images';\n                    break;\n                default:\n                    $keyname = '';\n            }\n            if (!empty($keyname)) {\n                $debug['innerLimit'] = $innerLimit;\n                $debug['innerOffset'] = $innerOffset;\n\n                foreach ($fieldsTypeData as &$fieldsTypeInner) {\n                    if ($innerOffset) {\n                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], (int)$innerOffset);\n                    }\n                    if ($innerLimit) {\n                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], 0, $innerLimit);\n                    }\n                }\n            }\n        }\n\n        if(!$returnAsJSON && count($fieldsTypeData)) {\n            $i = 0;\n            foreach($fieldsTypeData as $fieldData) {\n                $i++;\n                $output .= $ContentBlocks->generateFieldHtml($fieldData, $field, $i);\n            }\n        }\n\n        if($returnAsJSON) {\n            if($showDebug) {\n                $output = $modx->toJSON(array('output' => $fieldsTypeData, 'debug' => $debug));\n            }\n            else {\n                $output = $modx->toJSON($fieldsTypeData);\n            }\n        }\n    }\n}\n\nif(!$returnAsJSON && $showDebug) {\n    $output .= '<pre>' . print_r($debug, true) . '</pre>';\n}\n\n\nreturn $output;"
properties: 'a:0:{}'
content: "/**\n * Use the cbGetFieldContent snippet to get the content of a particular field.\n *\n * For example, this can be useful if you need to get a bit of content\n * in a getResources call\n *\n * Example usage:\n *\n * [[cbGetFieldContent?\n *      &field=`13`\n *      &tpl=`fieldTpl`\n * ]]\n *\n * [[cbGetFieldContent?\n *      &field=`13`\n *      &fieldSettingFilter=`class==keyImage`\n *      &tpl=`fieldTpl`\n * ]]\n *\n * An optional &resource param allows checking for fields on other resources.\n * An option &fieldSettingFilter allows filtering by == or != of a field setting. Only items matching the filter will be returned.\n * An optional &limit param allows limiting the number of matched fields\n * An optional &offset param allows skipping the first n matched fields\n * An optional &tpl param is a chunk name defining a template to use for your field. If not set,\n *   the ContentBlocks template for the field will be used.\n * An optional &wrapTpl param is a chunk name defining a template to use for your field wrapper.\n *   If not set, the ContentBlocks wrapper template for the field will be used. Applies only to\n *   multi-value inputs (galleries, files, etc.)\n * An optional &returnAsJSON parameter will return all values of the selected field as JSON.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n\n// Use the current resource if it's available\n$resource = isset($modx->resource) ? $modx->resource : false;\n\n// If we have a requested resource...\nif ($scriptProperties['resource']) {\n    // ... check if it is the same one as the current, and only load the requested resource if it isn't\n    if ($resource instanceof modResource) {\n        if ($scriptProperties['resource'] != $resource->get('id')) {\n            $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);\n        }\n    }\n    // ... or grab the requested resource anyway\n    else {\n        $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);\n    }\n}\n\n// Make sure we have a resource or end here\nif (!$resource) {\n    return '';\n}\n\n$fld = $modx->getOption('field', $scriptProperties, 0, true);\n$fieldSettingFilter = $modx->getOption('fieldSettingFilter', $scriptProperties, false, true);\n$limit = $modx->getOption('limit', $scriptProperties, 0, true);\n$offset = $modx->getOption('offset', $scriptProperties, 0, true);\n$innerLimit = $modx->getOption('innerLimit', $scriptProperties, 0, true);\n$innerOffset = $modx->getOption('innerOffset', $scriptProperties, 0, true);\n$tpl = $modx->getOption('tpl', $scriptProperties, false, true);\n$wrapTpl = $modx->getOption('wrapTpl', $scriptProperties, false, true);\n$showDebug = $modx->getOption('showDebug', $scriptProperties, false, true);\n$returnAsJSON = $modx->getOption('returnAsJSON', $scriptProperties, false, true);\n\n/** @var array $debug */\n$debug = array('scriptProperties' => $scriptProperties);\n$output = '';\n\nif(!$fld) {\n    $showDebug = true;\n}\n\nelse {\n    // Make sure this is a contentblocks-enabled resource\n    $enabled = $resource->getProperty('_isContentBlocks', 'contentblocks');\n    $debug['enabled'] = (bool)$enabled;\n    if ($enabled !== true) return;\n\n    // Get the field counts\n    $counts = $resource->getProperty('fieldcounts', 'contentblocks');\n    $debug['counts'] = $counts;\n    if (is_array($counts) && isset($counts[$fld])) {\n        $fieldsData = $resource->getProperty('linear', 'contentblocks');\n        $fieldsTypeData = array();\n        $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n        $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n        $ContentBlocks->loadInputs();\n        $field = $modx->getObject('cbField', $fld);\n\n        if(!($field instanceof cbField)) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[cbGetFieldContent] Error loading field ' . $fld);\n            return;\n        }\n\n        if($tpl) {\n            $chunk = $modx->getObject('modChunk', array('name' => $tpl));\n            if ($chunk instanceof modChunk) {\n                $field->set('template', $chunk->getContent());\n            }\n        }\n\n        if($wrapTpl) {\n            $chunk = $modx->getObject('modChunk', array('name' => $wrapTpl));\n            if ($chunk instanceof modChunk) {\n                $field->set('wrapper_template', $chunk->getContent());\n            }\n        }\n\n        $debug['fieldsData'] = $fieldsData;\n\n        foreach($fieldsData as $fieldData) {\n            if($fieldData['field'] == $fld) {\n                $fieldsTypeData[] = $fieldData;\n            }\n        }\n\n        $debug['fieldsTypeData'] = $fieldsTypeData;\n\n        if($fieldSettingFilter) {\n            $operators = array(\n                '!=' => '!=',\n                '==' => '==',\n            );\n            $filters = explode(',', $fieldSettingFilter);\n            $debug['filters'] = array('original' => $filters);\n            foreach($filters as $i => $filter) {\n                foreach($operators as $op => $symbol) {\n                    if (strpos($filter, $op, 1) !== false) {\n                        $operator = $op;\n                        break;\n                    }\n                }\n\n                $filter = explode($operator, $filter);\n                $debug[$i]['filter'] = $filter;\n                $setting = array_shift($filter);\n                $value = array_shift($filter);\n                $debug[$i]['setting'] = $setting;\n                $debug[$i]['value'] = $value;\n                $debug[$i]['operator'] = $operator;\n\n                foreach($fieldsTypeData as $idx => $fieldData) {\n                    if($fieldData['settings'] && array_key_exists($setting, $fieldData['settings'])) {\n                        switch($operator) {\n                            case '==' :\n                                if($fieldData['settings'][$setting] != $value) {\n                                    unset($fieldsTypeData[$idx]);\n                                }\n                                break;\n                            case '!=' :\n                                if($fieldData['settings'][$setting] == $value) {\n                                    unset($fieldsTypeData[$idx]);\n                                }\n                                break;\n                        }\n                    }\n                }\n            }\n        }\n\n        if($offset) {\n            $fieldsTypeData = array_splice($fieldsTypeData, (int)$offset);\n            $debug['offset'] = $offset;\n        }\n        if($limit) {\n            $fieldsTypeData = array_splice($fieldsTypeData, 0, $limit);\n            $debug['limit'] = $limit;\n        }\n\n        $debug['result'] = $fieldsTypeData;\n\n        if ($innerLimit || $innerOffset) {\n            switch ($field->get('input')) {\n                case 'repeater':\n                    $keyname = 'rows';\n                    break;\n                case 'gallery':\n                    $keyname = 'images';\n                    break;\n                default:\n                    $keyname = '';\n            }\n            if (!empty($keyname)) {\n                $debug['innerLimit'] = $innerLimit;\n                $debug['innerOffset'] = $innerOffset;\n\n                foreach ($fieldsTypeData as &$fieldsTypeInner) {\n                    if ($innerOffset) {\n                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], (int)$innerOffset);\n                    }\n                    if ($innerLimit) {\n                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], 0, $innerLimit);\n                    }\n                }\n            }\n        }\n\n        if(!$returnAsJSON && count($fieldsTypeData)) {\n            $i = 0;\n            foreach($fieldsTypeData as $fieldData) {\n                $i++;\n                $output .= $ContentBlocks->generateFieldHtml($fieldData, $field, $i);\n            }\n        }\n\n        if($returnAsJSON) {\n            if($showDebug) {\n                $output = $modx->toJSON(array('output' => $fieldsTypeData, 'debug' => $debug));\n            }\n            else {\n                $output = $modx->toJSON($fieldsTypeData);\n            }\n        }\n    }\n}\n\nif(!$returnAsJSON && $showDebug) {\n    $output .= '<pre>' . print_r($debug, true) . '</pre>';\n}\n\n\nreturn $output;"

-----


/**
 * Use the cbGetFieldContent snippet to get the content of a particular field.
 *
 * For example, this can be useful if you need to get a bit of content
 * in a getResources call
 *
 * Example usage:
 *
 * [[cbGetFieldContent?
 *      &field=`13`
 *      &tpl=`fieldTpl`
 * ]]
 *
 * [[cbGetFieldContent?
 *      &field=`13`
 *      &fieldSettingFilter=`class==keyImage`
 *      &tpl=`fieldTpl`
 * ]]
 *
 * An optional &resource param allows checking for fields on other resources.
 * An option &fieldSettingFilter allows filtering by == or != of a field setting. Only items matching the filter will be returned.
 * An optional &limit param allows limiting the number of matched fields
 * An optional &offset param allows skipping the first n matched fields
 * An optional &tpl param is a chunk name defining a template to use for your field. If not set,
 *   the ContentBlocks template for the field will be used.
 * An optional &wrapTpl param is a chunk name defining a template to use for your field wrapper.
 *   If not set, the ContentBlocks wrapper template for the field will be used. Applies only to
 *   multi-value inputs (galleries, files, etc.)
 * An optional &returnAsJSON parameter will return all values of the selected field as JSON.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */


// Use the current resource if it's available
$resource = isset($modx->resource) ? $modx->resource : false;

// If we have a requested resource...
if ($scriptProperties['resource']) {
    // ... check if it is the same one as the current, and only load the requested resource if it isn't
    if ($resource instanceof modResource) {
        if ($scriptProperties['resource'] != $resource->get('id')) {
            $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);
        }
    }
    // ... or grab the requested resource anyway
    else {
        $resource = $modx->getObject('modResource', (int)$scriptProperties['resource']);
    }
}

// Make sure we have a resource or end here
if (!$resource) {
    return '';
}

$fld = $modx->getOption('field', $scriptProperties, 0, true);
$fieldSettingFilter = $modx->getOption('fieldSettingFilter', $scriptProperties, false, true);
$limit = $modx->getOption('limit', $scriptProperties, 0, true);
$offset = $modx->getOption('offset', $scriptProperties, 0, true);
$innerLimit = $modx->getOption('innerLimit', $scriptProperties, 0, true);
$innerOffset = $modx->getOption('innerOffset', $scriptProperties, 0, true);
$tpl = $modx->getOption('tpl', $scriptProperties, false, true);
$wrapTpl = $modx->getOption('wrapTpl', $scriptProperties, false, true);
$showDebug = $modx->getOption('showDebug', $scriptProperties, false, true);
$returnAsJSON = $modx->getOption('returnAsJSON', $scriptProperties, false, true);

/** @var array $debug */
$debug = array('scriptProperties' => $scriptProperties);
$output = '';

if(!$fld) {
    $showDebug = true;
}

else {
    // Make sure this is a contentblocks-enabled resource
    $enabled = $resource->getProperty('_isContentBlocks', 'contentblocks');
    $debug['enabled'] = (bool)$enabled;
    if ($enabled !== true) return;

    // Get the field counts
    $counts = $resource->getProperty('fieldcounts', 'contentblocks');
    $debug['counts'] = $counts;
    if (is_array($counts) && isset($counts[$fld])) {
        $fieldsData = $resource->getProperty('linear', 'contentblocks');
        $fieldsTypeData = array();
        $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
        $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');
        $ContentBlocks->loadInputs();
        $field = $modx->getObject('cbField', $fld);

        if(!($field instanceof cbField)) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[cbGetFieldContent] Error loading field ' . $fld);
            return;
        }

        if($tpl) {
            $chunk = $modx->getObject('modChunk', array('name' => $tpl));
            if ($chunk instanceof modChunk) {
                $field->set('template', $chunk->getContent());
            }
        }

        if($wrapTpl) {
            $chunk = $modx->getObject('modChunk', array('name' => $wrapTpl));
            if ($chunk instanceof modChunk) {
                $field->set('wrapper_template', $chunk->getContent());
            }
        }

        $debug['fieldsData'] = $fieldsData;

        foreach($fieldsData as $fieldData) {
            if($fieldData['field'] == $fld) {
                $fieldsTypeData[] = $fieldData;
            }
        }

        $debug['fieldsTypeData'] = $fieldsTypeData;

        if($fieldSettingFilter) {
            $operators = array(
                '!=' => '!=',
                '==' => '==',
            );
            $filters = explode(',', $fieldSettingFilter);
            $debug['filters'] = array('original' => $filters);
            foreach($filters as $i => $filter) {
                foreach($operators as $op => $symbol) {
                    if (strpos($filter, $op, 1) !== false) {
                        $operator = $op;
                        break;
                    }
                }

                $filter = explode($operator, $filter);
                $debug[$i]['filter'] = $filter;
                $setting = array_shift($filter);
                $value = array_shift($filter);
                $debug[$i]['setting'] = $setting;
                $debug[$i]['value'] = $value;
                $debug[$i]['operator'] = $operator;

                foreach($fieldsTypeData as $idx => $fieldData) {
                    if($fieldData['settings'] && array_key_exists($setting, $fieldData['settings'])) {
                        switch($operator) {
                            case '==' :
                                if($fieldData['settings'][$setting] != $value) {
                                    unset($fieldsTypeData[$idx]);
                                }
                                break;
                            case '!=' :
                                if($fieldData['settings'][$setting] == $value) {
                                    unset($fieldsTypeData[$idx]);
                                }
                                break;
                        }
                    }
                }
            }
        }

        if($offset) {
            $fieldsTypeData = array_splice($fieldsTypeData, (int)$offset);
            $debug['offset'] = $offset;
        }
        if($limit) {
            $fieldsTypeData = array_splice($fieldsTypeData, 0, $limit);
            $debug['limit'] = $limit;
        }

        $debug['result'] = $fieldsTypeData;

        if ($innerLimit || $innerOffset) {
            switch ($field->get('input')) {
                case 'repeater':
                    $keyname = 'rows';
                    break;
                case 'gallery':
                    $keyname = 'images';
                    break;
                default:
                    $keyname = '';
            }
            if (!empty($keyname)) {
                $debug['innerLimit'] = $innerLimit;
                $debug['innerOffset'] = $innerOffset;

                foreach ($fieldsTypeData as &$fieldsTypeInner) {
                    if ($innerOffset) {
                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], (int)$innerOffset);
                    }
                    if ($innerLimit) {
                        $fieldsTypeInner[$keyname] = array_splice($fieldsTypeInner[$keyname], 0, $innerLimit);
                    }
                }
            }
        }

        if(!$returnAsJSON && count($fieldsTypeData)) {
            $i = 0;
            foreach($fieldsTypeData as $fieldData) {
                $i++;
                $output .= $ContentBlocks->generateFieldHtml($fieldData, $field, $i);
            }
        }

        if($returnAsJSON) {
            if($showDebug) {
                $output = $modx->toJSON(array('output' => $fieldsTypeData, 'debug' => $debug));
            }
            else {
                $output = $modx->toJSON($fieldsTypeData);
            }
        }
    }
}

if(!$returnAsJSON && $showDebug) {
    $output .= '<pre>' . print_r($debug, true) . '</pre>';
}


return $output;