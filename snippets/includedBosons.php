id: 111
name: includedBosons
category: f_hub
snippet: "/**\n * includedBosons\n *\n * List all ContentBlocks fields being used in a given layout, or if no layout\n * is specified, on a given page.\n *\n * @author Hugo Peek\n */\n\n$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));\n$layoutIdx = $modx->getOption('layout', $scriptProperties, '');\n$filterFields = $modx->getOption('filterFields', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Function to turn result into a link to its corresponding resource\n// @todo: This function is also being used in the referringBosons snippet.. Could this somehow be loaded from 1 file?\nif (!function_exists('createLink')) {\n    function createLink($catID, $uriExtension) {\n        global $modx;\n\n        // Since we have an ID, let's go hunt for the category name\n        $category = $modx->getObject('cbCategory', array(\n            'id' => $catID\n        ));\n\n        if ($category) {\n            $catName = strtolower($category->get('name'));\n        } else {\n            $modx->log(modX::LOG_LEVEL_WARN, '[includedBosons] Link could not be generated due to missing category ID');\n        }\n\n        // Use bosons as parent name, because we don't know if this is a layout or field\n        $parentName = 'bosons';\n\n        // Get the resource with an alias that matches both category and parent name\n        $query = $modx->newQuery('modResource');\n        $query->where(array(\n            'uri:LIKE' => '%' . $parentName . '%',\n            'AND:uri:LIKE' => '%' . $catName . $uriExtension\n        ));\n        $query->select('uri');\n        $link = $modx->getValue($query->prepare());\n\n        return $link;\n    }\n}\n\n// Function to look for a key in a multi level array\nif (!function_exists('recursive_array_search')) {\n    function recursive_array_search(array $array, $needle) {\n        $result = array();\n        $iterator  = new RecursiveArrayIterator($array);\n        $recursive = new RecursiveIteratorIterator(\n            $iterator,\n            RecursiveIteratorIterator::SELF_FIRST\n        );\n        foreach ($recursive as $key => $value) {\n            if ($key === $needle) {\n                $result[] = $value;\n            }\n        }\n        return $result;\n    }\n}\n\n// Get the properties of the current resource first\n$query = $modx->newQuery('modResource', array(\n    'id' => $resourceID\n));\n$query->select('properties');\n$properties = $modx->getValue($query->prepare());\n\n// Prepare an array with just the content part\n$propertiesArray = json_decode($properties, true);\n$propertiesArray = json_decode($propertiesArray['contentblocks']['content'], true);\n\n// If a layout idx is set, pick the corresponding layout from the array\nif ($layoutIdx != '') {\n    $result = $propertiesArray[$layoutIdx];\n} else {\n    $result = $propertiesArray; // And if not, just get all the fields\n}\n\n// Great! Now let's retrieve all field IDs from the array\nif (is_array($result)) {\n    $result = recursive_array_search($result, 'field');\n    $result = array_unique($result);\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[includedBosons] Result is not a valid array. Is the layout idx correct?');\n    return '';\n}\n\n// User specified CB fields need to be excluded from result\n$arrayFilter = explode(',', $filterFields);\n\n// Turn each match into a list item with a link\nforeach ($result as $id) {\n    if (!in_array($id, $arrayFilter)) {\n        $boson = $modx->getObject('cbField', array(\n            'id' => $id\n        ));\n    }\n    if ($boson) {\n        $name = $boson->get('name');\n        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link,\n            'label_classes' => ''\n        ));\n    }\n}\n\nreturn implode(array_unique($output));"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.includedbosons.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.includedbosons.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * includedBosons\n *\n * List all ContentBlocks fields being used in a given layout, or if no layout\n * is specified, on a given page.\n *\n * @author Hugo Peek\n */\n\n$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n$resourceID = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));\n$layoutIdx = $modx->getOption('layout', $scriptProperties, '');\n$filterFields = $modx->getOption('filterFields', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Function to turn result into a link to its corresponding resource\n// @todo: This function is also being used in the referringBosons snippet.. Could this somehow be loaded from 1 file?\nif (!function_exists('createLink')) {\n    function createLink($catID, $uriExtension) {\n        global $modx;\n\n        // Since we have an ID, let's go hunt for the category name\n        $category = $modx->getObject('cbCategory', array(\n            'id' => $catID\n        ));\n\n        if ($category) {\n            $catName = strtolower($category->get('name'));\n        } else {\n            $modx->log(modX::LOG_LEVEL_WARN, '[includedBosons] Link could not be generated due to missing category ID');\n        }\n\n        // Use bosons as parent name, because we don't know if this is a layout or field\n        $parentName = 'bosons';\n\n        // Get the resource with an alias that matches both category and parent name\n        $query = $modx->newQuery('modResource');\n        $query->where(array(\n            'uri:LIKE' => '%' . $parentName . '%',\n            'AND:uri:LIKE' => '%' . $catName . $uriExtension\n        ));\n        $query->select('uri');\n        $link = $modx->getValue($query->prepare());\n\n        return $link;\n    }\n}\n\n// Function to look for a key in a multi level array\nif (!function_exists('recursive_array_search')) {\n    function recursive_array_search(array $array, $needle) {\n        $result = array();\n        $iterator  = new RecursiveArrayIterator($array);\n        $recursive = new RecursiveIteratorIterator(\n            $iterator,\n            RecursiveIteratorIterator::SELF_FIRST\n        );\n        foreach ($recursive as $key => $value) {\n            if ($key === $needle) {\n                $result[] = $value;\n            }\n        }\n        return $result;\n    }\n}\n\n// Get the properties of the current resource first\n$query = $modx->newQuery('modResource', array(\n    'id' => $resourceID\n));\n$query->select('properties');\n$properties = $modx->getValue($query->prepare());\n\n// Prepare an array with just the content part\n$propertiesArray = json_decode($properties, true);\n$propertiesArray = json_decode($propertiesArray['contentblocks']['content'], true);\n\n// If a layout idx is set, pick the corresponding layout from the array\nif ($layoutIdx != '') {\n    $result = $propertiesArray[$layoutIdx];\n} else {\n    $result = $propertiesArray; // And if not, just get all the fields\n}\n\n// Great! Now let's retrieve all field IDs from the array\nif (is_array($result)) {\n    $result = recursive_array_search($result, 'field');\n    $result = array_unique($result);\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[includedBosons] Result is not a valid array. Is the layout idx correct?');\n    return '';\n}\n\n// User specified CB fields need to be excluded from result\n$arrayFilter = explode(',', $filterFields);\n\n// Turn each match into a list item with a link\nforeach ($result as $id) {\n    if (!in_array($id, $arrayFilter)) {\n        $boson = $modx->getObject('cbField', array(\n            'id' => $id\n        ));\n    }\n    if ($boson) {\n        $name = $boson->get('name');\n        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link,\n            'label_classes' => ''\n        ));\n    }\n}\n\nreturn implode(array_unique($output));"

-----


/**
 * includedBosons
 *
 * List all ContentBlocks fields being used in a given layout, or if no layout
 * is specified, on a given page.
 *
 * @author Hugo Peek
 */

$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');

$resourceID = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));
$layoutIdx = $modx->getOption('layout', $scriptProperties, '');
$filterFields = $modx->getOption('filterFields', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');

$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));

// Function to turn result into a link to its corresponding resource
// @todo: This function is also being used in the referringBosons snippet.. Could this somehow be loaded from 1 file?
if (!function_exists('createLink')) {
    function createLink($catID, $uriExtension) {
        global $modx;

        // Since we have an ID, let's go hunt for the category name
        $category = $modx->getObject('cbCategory', array(
            'id' => $catID
        ));

        if ($category) {
            $catName = strtolower($category->get('name'));
        } else {
            $modx->log(modX::LOG_LEVEL_WARN, '[includedBosons] Link could not be generated due to missing category ID');
        }

        // Use bosons as parent name, because we don't know if this is a layout or field
        $parentName = 'bosons';

        // Get the resource with an alias that matches both category and parent name
        $query = $modx->newQuery('modResource');
        $query->where(array(
            'uri:LIKE' => '%' . $parentName . '%',
            'AND:uri:LIKE' => '%' . $catName . $uriExtension
        ));
        $query->select('uri');
        $link = $modx->getValue($query->prepare());

        return $link;
    }
}

// Function to look for a key in a multi level array
if (!function_exists('recursive_array_search')) {
    function recursive_array_search(array $array, $needle) {
        $result = array();
        $iterator  = new RecursiveArrayIterator($array);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($recursive as $key => $value) {
            if ($key === $needle) {
                $result[] = $value;
            }
        }
        return $result;
    }
}

// Get the properties of the current resource first
$query = $modx->newQuery('modResource', array(
    'id' => $resourceID
));
$query->select('properties');
$properties = $modx->getValue($query->prepare());

// Prepare an array with just the content part
$propertiesArray = json_decode($properties, true);
$propertiesArray = json_decode($propertiesArray['contentblocks']['content'], true);

// If a layout idx is set, pick the corresponding layout from the array
if ($layoutIdx != '') {
    $result = $propertiesArray[$layoutIdx];
} else {
    $result = $propertiesArray; // And if not, just get all the fields
}

// Great! Now let's retrieve all field IDs from the array
if (is_array($result)) {
    $result = recursive_array_search($result, 'field');
    $result = array_unique($result);
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[includedBosons] Result is not a valid array. Is the layout idx correct?');
    return '';
}

// User specified CB fields need to be excluded from result
$arrayFilter = explode(',', $filterFields);

// Turn each match into a list item with a link
foreach ($result as $id) {
    if (!in_array($id, $arrayFilter)) {
        $boson = $modx->getObject('cbField', array(
            'id' => $id
        ));
    }
    if ($boson) {
        $name = $boson->get('name');
        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));

        $output[] = $modx->getChunk($tpl, array(
            'name' => $name,
            'link' => $link,
            'label_classes' => ''
        ));
    }
}

return implode(array_unique($output));