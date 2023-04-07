id: 107
name: assignedTVs
category: f_hub
snippet: "$templateName = $modx->getOption('template', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Get template as object\n$template = $modx->getObject('modTemplate', array('templatename'=>$templateName));\n$templateID = '';\n\n// Get the ID of the template\nif ($template) {\n    $templateID = $template->get('id');\n} else {\n    $modx->log(modX::LOG_LEVEL_WARN, '[assignedTVs] ' . $templateName . ' could not be processed');\n}\n\n// Look in the tmplvar_templates table to find attached TVs\n$assignedTVs = $modx->getCollection('modTemplateVarTemplate', array('templateid' => $templateID));\n\n// Create the list\n$tvList = [];\nforeach ($assignedTVs as $tv) {\n    $tvList[] = $tv->get('tmplvarid');\n}\n$tvList = array_filter($tvList);\n\n// Sort list\n// @todo: sort array alphabetically\nsort($tvList);\n\n// Set idx start value\n$idx = 3000;\n\n// Define output array\n$output = array();\n\n// Create a list of links to their corresponding PL locations\nforeach ($tvList as $value) {\n    $tv = $modx->getObject('modTemplateVar', $value);\n\n    if (is_object($tv)) {\n        $name = $tv->get('name');\n        $category = $tv->get('category');\n\n        // The actual TV categories often contain spaces and hyphens and they\n        // don't accurately represent the file structure of the library.\n        // That's why we get the parent category instead.\n        $query = $modx->newQuery('modCategory', array(\n            'id' => $category\n        ));\n        $query->select('parent');\n        $parent = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        // Filter all TVs under the Status tab, since that's not relevant info\n        if (strpos($name, 'status_') === false) {\n            $output[] = $modx->getChunk($tpl, array(\n                'name' => $name,\n                'category' => $parent,\n                'idx' => $idx\n            ));\n        }\n    }\n}\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:35:"romanesco.assignedtvs.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:36:"romanesco.assignedtvs.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


$templateName = $modx->getOption('template', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');

// Get template as object
$template = $modx->getObject('modTemplate', array('templatename'=>$templateName));
$templateID = '';

// Get the ID of the template
if ($template) {
    $templateID = $template->get('id');
} else {
    $modx->log(modX::LOG_LEVEL_WARN, '[assignedTVs] ' . $templateName . ' could not be processed');
}

// Look in the tmplvar_templates table to find attached TVs
$assignedTVs = $modx->getCollection('modTemplateVarTemplate', array('templateid' => $templateID));

// Create the list
$tvList = [];
foreach ($assignedTVs as $tv) {
    $tvList[] = $tv->get('tmplvarid');
}
$tvList = array_filter($tvList);

// Sort list
// @todo: sort array alphabetically
sort($tvList);

// Set idx start value
$idx = 3000;

// Define output array
$output = array();

// Create a list of links to their corresponding PL locations
foreach ($tvList as $value) {
    $tv = $modx->getObject('modTemplateVar', $value);

    if (is_object($tv)) {
        $name = $tv->get('name');
        $category = $tv->get('category');

        // The actual TV categories often contain spaces and hyphens and they
        // don't accurately represent the file structure of the library.
        // That's why we get the parent category instead.
        $query = $modx->newQuery('modCategory', array(
            'id' => $category
        ));
        $query->select('parent');
        $parent = $modx->getValue($query->prepare());

        // Up idx value by 1, so a unique placeholder can be created
        $idx++;

        // Output to a chunk that contains the link generator
        // Filter all TVs under the Status tab, since that's not relevant info
        if (strpos($name, 'status_') === false) {
            $output[] = $modx->getChunk($tpl, array(
                'name' => $name,
                'category' => $parent,
                'idx' => $idx
            ));
        }
    }
}

return implode($output);