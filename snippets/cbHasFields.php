id: 104
name: cbHasFields
description: 'This is a copy of the original cbHasField snippet that ships with ContentBlocks. The only difference is that it takes in a comma separate list of IDs, instead of just 1.'
category: f_contentblocks
snippet: "/**\n * Use the cbHasField snippet for conditional logic depending on whether a certain field\n * is in use on a resource or not.\n *\n * For example, this can be useful if you need to initialise a certain javascript library\n * in your site's footer, but only when you have a Gallery on the page.\n *\n * Example usage:\n *\n * [[cbHasField?\n *      &field=`13`\n *      &then=`Has a Gallery!`\n *      &else=`Doesn't have a gallery!`\n * ]]\n *\n * An optional &resource param allows checking for fields on other resources.\n *\n * Note that if the resource does not use ContentBlocks for the content, it will default to the &else value.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n$resource = (isset($scriptProperties['resource']) && $scriptProperties['resource'] != $modx->resource->get('id')) ? $modx->getObject('modResource', $scriptProperties['resource']) : $modx->resource;\n$fld = $modx->getOption('field', $scriptProperties, 0);\n$then = $modx->getOption('then', $scriptProperties, '1');\n$else = $modx->getOption('else', $scriptProperties, '');\n\n// If comma-separated list in $fld, make array of IDs, else $fields = false\n$fields = false;\nif (strpos($fld, ',') !== false) {\n    $fields = array_filter(array_map('trim', explode(',', $fld)));\n    $fld = $fields[0]; // Let's not have $fld be a comma-separated string, in case it breaks something below\n}\nif(!$fld) {\n    $showDebug = true;\n}\n\n// Make sure this is a contentblocks-enabled resource\n$enabled = $resource->getProperty('_isContentBlocks', 'contentblocks');\nif ($enabled !== true) return $else;\n\n// Get the field counts\n$counts = $resource->getProperty('fieldcounts', 'contentblocks');\n\n// Loop through $fields and replace the $fld var with the first matching element\nif (is_array($counts) && is_array($fields)) {\n    foreach ($fields as $f) {\n        if (isset($counts[$f])) {\n            $fld = $f;\n            break;\n        }\n    }\n}\n\n// Otherwise, $fld is the first ID provided and the snippet continues as in previous versions. No harm no foul.\nif (is_array($counts)) {\n    if (isset($counts[$fld])) return $then;\n}\n\nelse {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[ContentBlocks.cbHasField] Resource ' . $resource->get('id') . ' does not contain field count data. This feature was added in ContentBlocks 0.9.2. Any resources not saved since the update to 0.9.2 need to be saved in order for the field counts to be calculated and stored.');\n}\nreturn $else;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:35:"romanesco.cbhasfields.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:36:"romanesco.cbhasfields.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * Use the cbHasField snippet for conditional logic depending on whether a certain field
 * is in use on a resource or not.
 *
 * For example, this can be useful if you need to initialise a certain javascript library
 * in your site's footer, but only when you have a Gallery on the page.
 *
 * Example usage:
 *
 * [[cbHasField?
 *      &field=`13`
 *      &then=`Has a Gallery!`
 *      &else=`Doesn't have a gallery!`
 * ]]
 *
 * An optional &resource param allows checking for fields on other resources.
 *
 * Note that if the resource does not use ContentBlocks for the content, it will default to the &else value.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
$resource = (isset($scriptProperties['resource']) && $scriptProperties['resource'] != $modx->resource->get('id')) ? $modx->getObject('modResource', $scriptProperties['resource']) : $modx->resource;
$fld = $modx->getOption('field', $scriptProperties, 0);
$then = $modx->getOption('then', $scriptProperties, '1');
$else = $modx->getOption('else', $scriptProperties, '');

// If comma-separated list in $fld, make array of IDs, else $fields = false
$fields = false;
if (strpos($fld, ',') !== false) {
    $fields = array_filter(array_map('trim', explode(',', $fld)));
    $fld = $fields[0]; // Let's not have $fld be a comma-separated string, in case it breaks something below
}
if(!$fld) {
    $showDebug = true;
}

// Make sure this is a contentblocks-enabled resource
$enabled = $resource->getProperty('_isContentBlocks', 'contentblocks');
if ($enabled !== true) return $else;

// Get the field counts
$counts = $resource->getProperty('fieldcounts', 'contentblocks');

// Loop through $fields and replace the $fld var with the first matching element
if (is_array($counts) && is_array($fields)) {
    foreach ($fields as $f) {
        if (isset($counts[$f])) {
            $fld = $f;
            break;
        }
    }
}

// Otherwise, $fld is the first ID provided and the snippet continues as in previous versions. No harm no foul.
if (is_array($counts)) {
    if (isset($counts[$fld])) return $then;
}

else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[ContentBlocks.cbHasField] Resource ' . $resource->get('id') . ' does not contain field count data. This feature was added in ContentBlocks 0.9.2. Any resources not saved since the update to 0.9.2 need to be saved in order for the field counts to be calculated and stored.');
}
return $else;