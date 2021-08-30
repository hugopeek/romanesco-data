id: 100
name: includedPatternsLink
category: f_hub
snippet: "$categoryID = $modx->getOption('input', $scriptProperties, '');\n$pattern = $modx->getOption('pattern', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Get category object\n$category = $modx->getObject('modCategory', array(\n    'id' => $categoryID\n));\n\nif (!is_object($category)) return;\n\n// Grab only the last part of the category name\n$categoryName = preg_match('([^_]+$)', $category->get('category'), $matchCategory);\n\n// Get parent as well\n$parent = $modx->getObject('modCategory', array(\n    'id' => $category->get('parent')\n));\n\n// If parent is empty, don't generate any link.\n// All Romanesco elements are nested at least 1 level deep, so if a category\n// has no parent, we can assume it's part of a MODX extra.\nif (!is_object($parent)) {\n    $modx->toPlaceholder('pl', $prefix);\n    return;\n}\n\n// Grab last part of parent category name\n$parentName = preg_match('([^_]+$)', $parent->get('category'), $matchParent);\n\n// Grab parent categories one level deeper\n$query = $modx->newQuery('modCategory', array(\n    'id' => $parent->get('parent')\n));\n$query->select('category');\n$parentParent = $modx->getValue($query->prepare());\n$parentParentName = preg_match('([^_]+$)', $parentParent, $matchParentParent);\n\n// Collect matches\n$matchCategory = strtolower($matchCategory[0]);\n$matchParent = strtolower($matchParent[0]);\n$matchParentParent = strtolower($matchParentParent[0]);\n\n// Find resource with an alias that matches any of the collected category names\n$query = $modx->newQuery('modResource');\n$query->where(array(\n    'published' => 1,\n    array(\n        'uri:LIKE' => '%patterns%' . $matchCategory . $htmlContentType->get('file_extensions'),\n    ),\n    array(\n        'OR:uri:LIKE' => '%patterns%' . $matchParent . $htmlContentType->get('file_extensions'),\n    ),\n    array(\n        'OR:uri:LIKE' => '%patterns%' . $matchParentParent . $htmlContentType->get('file_extensions'),\n    )\n));\n$query->select('uri');\n$link = $modx->getValue($query->prepare());\n\n//$modx->toPlaceholder('category', $category->get('category'), $prefix);\n//$modx->toPlaceholder('parent', $parent->get('category'), $prefix);\n//$modx->toPlaceholder('parent2', $parentParent, $prefix);\n\n// Add anchor already, if pattern name is defined\nif ($pattern) {\n    $link = $link . '#' . strtolower($pattern);\n}\n\n// Output to placeholder if one is set\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $link, $prefix);\n    return '';\n} else {\n    return $link;\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.includedpatternslink.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.includedpatternslink.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$categoryID = $modx->getOption('input', $scriptProperties, '');\n$pattern = $modx->getOption('pattern', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Get category object\n$category = $modx->getObject('modCategory', array(\n    'id' => $categoryID\n));\n\nif (!is_object($category)) return;\n\n// Grab only the last part of the category name\n$categoryName = preg_match('([^_]+$)', $category->get('category'), $matchCategory);\n\n// Get parent as well\n$parent = $modx->getObject('modCategory', array(\n    'id' => $category->get('parent')\n));\n\n// If parent is empty, don't generate any link.\n// All Romanesco elements are nested at least 1 level deep, so if a category\n// has no parent, we can assume it's part of a MODX extra.\nif (!is_object($parent)) {\n    $modx->toPlaceholder('pl', $prefix);\n    return;\n}\n\n// Grab last part of parent category name\n$parentName = preg_match('([^_]+$)', $parent->get('category'), $matchParent);\n\n// Grab parent categories one level deeper\n$query = $modx->newQuery('modCategory', array(\n    'id' => $parent->get('parent')\n));\n$query->select('category');\n$parentParent = $modx->getValue($query->prepare());\n$parentParentName = preg_match('([^_]+$)', $parentParent, $matchParentParent);\n\n// Collect matches\n$matchCategory = strtolower($matchCategory[0]);\n$matchParent = strtolower($matchParent[0]);\n$matchParentParent = strtolower($matchParentParent[0]);\n\n// Find resource with an alias that matches any of the collected category names\n$query = $modx->newQuery('modResource');\n$query->where(array(\n    'published' => 1,\n    array(\n        'uri:LIKE' => '%patterns%' . $matchCategory . $htmlContentType->get('file_extensions'),\n    ),\n    array(\n        'OR:uri:LIKE' => '%patterns%' . $matchParent . $htmlContentType->get('file_extensions'),\n    ),\n    array(\n        'OR:uri:LIKE' => '%patterns%' . $matchParentParent . $htmlContentType->get('file_extensions'),\n    )\n));\n$query->select('uri');\n$link = $modx->getValue($query->prepare());\n\n//$modx->toPlaceholder('category', $category->get('category'), $prefix);\n//$modx->toPlaceholder('parent', $parent->get('category'), $prefix);\n//$modx->toPlaceholder('parent2', $parentParent, $prefix);\n\n// Add anchor already, if pattern name is defined\nif ($pattern) {\n    $link = $link . '#' . strtolower($pattern);\n}\n\n// Output to placeholder if one is set\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $link, $prefix);\n    return '';\n} else {\n    return $link;\n}"

-----


$categoryID = $modx->getOption('input', $scriptProperties, '');
$pattern = $modx->getOption('pattern', $scriptProperties, '');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));

// Get category object
$category = $modx->getObject('modCategory', array(
    'id' => $categoryID
));

if (!is_object($category)) return;

// Grab only the last part of the category name
$categoryName = preg_match('([^_]+$)', $category->get('category'), $matchCategory);

// Get parent as well
$parent = $modx->getObject('modCategory', array(
    'id' => $category->get('parent')
));

// If parent is empty, don't generate any link.
// All Romanesco elements are nested at least 1 level deep, so if a category
// has no parent, we can assume it's part of a MODX extra.
if (!is_object($parent)) {
    $modx->toPlaceholder('pl', $prefix);
    return;
}

// Grab last part of parent category name
$parentName = preg_match('([^_]+$)', $parent->get('category'), $matchParent);

// Grab parent categories one level deeper
$query = $modx->newQuery('modCategory', array(
    'id' => $parent->get('parent')
));
$query->select('category');
$parentParent = $modx->getValue($query->prepare());
$parentParentName = preg_match('([^_]+$)', $parentParent, $matchParentParent);

// Collect matches
$matchCategory = strtolower($matchCategory[0]);
$matchParent = strtolower($matchParent[0]);
$matchParentParent = strtolower($matchParentParent[0]);

// Find resource with an alias that matches any of the collected category names
$query = $modx->newQuery('modResource');
$query->where(array(
    'published' => 1,
    array(
        'uri:LIKE' => '%patterns%' . $matchCategory . $htmlContentType->get('file_extensions'),
    ),
    array(
        'OR:uri:LIKE' => '%patterns%' . $matchParent . $htmlContentType->get('file_extensions'),
    ),
    array(
        'OR:uri:LIKE' => '%patterns%' . $matchParentParent . $htmlContentType->get('file_extensions'),
    )
));
$query->select('uri');
$link = $modx->getValue($query->prepare());

//$modx->toPlaceholder('category', $category->get('category'), $prefix);
//$modx->toPlaceholder('parent', $parent->get('category'), $prefix);
//$modx->toPlaceholder('parent2', $parentParent, $prefix);

// Add anchor already, if pattern name is defined
if ($pattern) {
    $link = $link . '#' . strtolower($pattern);
}

// Output to placeholder if one is set
if ($placeholder) {
    $modx->toPlaceholder('pl', $prefix);
    $modx->toPlaceholder($placeholder, $link, $prefix);
    return '';
} else {
    return $link;
}