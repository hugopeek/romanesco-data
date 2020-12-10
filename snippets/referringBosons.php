id: 108
name: referringBosons
category: f_hub
snippet: "$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n$pattern = $modx->getOption('pattern', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Function to turn result into a link to its corresponding resource\nif (!function_exists('createLink')) {\n    function createLink($catID, $uriExtension) {\n        global $modx;\n\n        // Since we have an ID, let's go hunt for the category name\n        $category = $modx->getObject('cbCategory', array(\n            'id' => $catID\n        ));\n\n        if ($category) {\n            $catName = strtolower($category->get('name'));\n        } else {\n            $modx->log(modX::LOG_LEVEL_WARN, '[referringBosons] Link could not be generated due to missing category ID');\n        }\n\n        // Use bosons as parent name, because we don't know if this is a layout or field\n        $parentName = 'bosons';\n\n        // Get the resource with an alias that matches both category and parent name\n        $query = $modx->newQuery('modResource');\n        $query->where(array(\n            'uri:LIKE' => '%' . $parentName . '%',\n            'AND:uri:LIKE' => '%' . $catName . $uriExtension\n        ));\n        $query->select('uri');\n        $link = $modx->getValue($query->prepare());\n\n        return $link;\n    }\n}\n\n// First, we need to know which CB elements contain the pattern name\n// Let's start searching inside fields first, since they're the most common\n$result = $modx->getCollection('cbField', array(\n    'template:LIKE' => '%' . $pattern . '%',\n    'OR:properties:LIKE' => '%' . $pattern . '%',\n    'OR:settings:LIKE' => '%' . $pattern . '%'\n));\n\n// Maybe the field type is Chunk, meaning it is referenced by ID instead of name\nif (!$result) {\n    $query = $modx->newQuery('modChunk');\n    $query->where(array(\n        'name' => $pattern\n    ));\n    $query->select('id');\n    $patternID = $modx->getValue($query->prepare());\n\n    $result = $modx->getObject('cbField', array(\n        'properties:LIKE' => '%\"chunk\":\"' . $patternID . '\"%'\n    ));\n\n    if ($result) {\n        $name = $result->get('name');\n        $link = createLink($result->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link\n        ));\n\n        return $output;\n    }\n}\n\n// If no fields where found, try the layouts table instead\nif (!$result) {\n    $result = $modx->getCollection('cbLayout', array(\n        'template:LIKE' => '%' . $pattern . '%',\n        'OR:settings:LIKE' => '%' . $pattern . '%'\n    ));\n}\n\n// Proceed if any matches are present\nif ($result) {\n    // Turn each match into a list item with a link\n    foreach ($result as $boson) {\n        $name = $boson->get('name');\n        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link,\n            'label_classes' => 'blue'\n        ));\n    }\n\n    return implode($output);\n\n    //if ($placeholder) {\n    //    $modx->toPlaceholder($placeholder, $output);\n    //} else {\n    //    return $output;\n    //}\n} else {\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.referringbosons.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.referringbosons.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n$pattern = $modx->getOption('pattern', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Function to turn result into a link to its corresponding resource\nif (!function_exists('createLink')) {\n    function createLink($catID, $uriExtension) {\n        global $modx;\n\n        // Since we have an ID, let's go hunt for the category name\n        $category = $modx->getObject('cbCategory', array(\n            'id' => $catID\n        ));\n\n        if ($category) {\n            $catName = strtolower($category->get('name'));\n        } else {\n            $modx->log(modX::LOG_LEVEL_WARN, '[referringBosons] Link could not be generated due to missing category ID');\n        }\n\n        // Use bosons as parent name, because we don't know if this is a layout or field\n        $parentName = 'bosons';\n\n        // Get the resource with an alias that matches both category and parent name\n        $query = $modx->newQuery('modResource');\n        $query->where(array(\n            'uri:LIKE' => '%' . $parentName . '%',\n            'AND:uri:LIKE' => '%' . $catName . $uriExtension\n        ));\n        $query->select('uri');\n        $link = $modx->getValue($query->prepare());\n\n        return $link;\n    }\n}\n\n// First, we need to know which CB elements contain the pattern name\n// Let's start searching inside fields first, since they're the most common\n$result = $modx->getCollection('cbField', array(\n    'template:LIKE' => '%' . $pattern . '%',\n    'OR:properties:LIKE' => '%' . $pattern . '%',\n    'OR:settings:LIKE' => '%' . $pattern . '%'\n));\n\n// Maybe the field type is Chunk, meaning it is referenced by ID instead of name\nif (!$result) {\n    $query = $modx->newQuery('modChunk');\n    $query->where(array(\n        'name' => $pattern\n    ));\n    $query->select('id');\n    $patternID = $modx->getValue($query->prepare());\n\n    $result = $modx->getObject('cbField', array(\n        'properties:LIKE' => '%\"chunk\":\"' . $patternID . '\"%'\n    ));\n\n    if ($result) {\n        $name = $result->get('name');\n        $link = createLink($result->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link\n        ));\n\n        return $output;\n    }\n}\n\n// If no fields where found, try the layouts table instead\nif (!$result) {\n    $result = $modx->getCollection('cbLayout', array(\n        'template:LIKE' => '%' . $pattern . '%',\n        'OR:settings:LIKE' => '%' . $pattern . '%'\n    ));\n}\n\n// Proceed if any matches are present\nif ($result) {\n    // Turn each match into a list item with a link\n    foreach ($result as $boson) {\n        $name = $boson->get('name');\n        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));\n\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'link' => $link,\n            'label_classes' => 'blue'\n        ));\n    }\n\n    return implode($output);\n\n    //if ($placeholder) {\n    //    $modx->toPlaceholder($placeholder, $output);\n    //} else {\n    //    return $output;\n    //}\n} else {\n    return '';\n}"

-----


$cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
$ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');

$pattern = $modx->getOption('pattern', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedContentBlocksRow');

$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));

// Function to turn result into a link to its corresponding resource
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
            $modx->log(modX::LOG_LEVEL_WARN, '[referringBosons] Link could not be generated due to missing category ID');
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

// First, we need to know which CB elements contain the pattern name
// Let's start searching inside fields first, since they're the most common
$result = $modx->getCollection('cbField', array(
    'template:LIKE' => '%' . $pattern . '%',
    'OR:properties:LIKE' => '%' . $pattern . '%',
    'OR:settings:LIKE' => '%' . $pattern . '%'
));

// Maybe the field type is Chunk, meaning it is referenced by ID instead of name
if (!$result) {
    $query = $modx->newQuery('modChunk');
    $query->where(array(
        'name' => $pattern
    ));
    $query->select('id');
    $patternID = $modx->getValue($query->prepare());

    $result = $modx->getObject('cbField', array(
        'properties:LIKE' => '%"chunk":"' . $patternID . '"%'
    ));

    if ($result) {
        $name = $result->get('name');
        $link = createLink($result->get('category'), $htmlContentType->get('file_extensions'));

        $output = $modx->getChunk($tpl, array(
            'name' => $name,
            'link' => $link
        ));

        return $output;
    }
}

// If no fields where found, try the layouts table instead
if (!$result) {
    $result = $modx->getCollection('cbLayout', array(
        'template:LIKE' => '%' . $pattern . '%',
        'OR:settings:LIKE' => '%' . $pattern . '%'
    ));
}

// Proceed if any matches are present
if ($result) {
    // Turn each match into a list item with a link
    foreach ($result as $boson) {
        $name = $boson->get('name');
        $link = createLink($boson->get('category'), $htmlContentType->get('file_extensions'));

        $output[] = $modx->getChunk($tpl, array(
            'name' => $name,
            'link' => $link,
            'label_classes' => 'blue'
        ));
    }

    return implode($output);

    //if ($placeholder) {
    //    $modx->toPlaceholder($placeholder, $output);
    //} else {
    //    return $output;
    //}
} else {
    return '';
}