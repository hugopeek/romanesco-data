id: 100
name: includedPatternsLink
category: f_hub
snippet: "$catID = $modx->getOption('input', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Get category name and parent ID\n$category = $modx->getObject('modCategory', array(\n    'id' => $catID\n));\n\nif ($category) {\n    $catName = $category->get('category');\n    $parentID = $category->get('parent');\n}\n\n// If category or parent is empty, don't generate any link.\n// All Romanesco elements are nested at least 1 level deep, so if a category\n// has no parent, we can allow ourselves to assume it's part of a MODX extra.\nif (!$category && $parentID == 0) {\n    $modx->toPlaceholder('pl', $prefix);\n    return;\n}\n\n// Get parent name as well, to avoid issues with multiple matches\n$query = $modx->newQuery('modCategory', array(\n    'id' => $parentID\n));\n$query->select('category');\n$parentName = $modx->getValue($query->prepare());\n\n// Grab only the last part of the category name\n$catName = preg_match('([^_]+$)', $catName, $matchCat);\n$parent = preg_match('([^_]+$)', $parentName, $matchParent);\n$matchCat = strtolower($matchCat[0]);\n$matchParent = strtolower($matchParent[0]);\n\n// If category and parent are the same, squash them\nif ($matchCat === $matchParent) {\n    $match = $matchCat;\n} else {\n    $match = $matchParent . \"/\" . $matchCat;\n}\n\n// Get the resource with an alias that matches the category name\n$query = $modx->newQuery('modResource');\n$query->where(array(\n    'uri:LIKE' => '%' . $match . $htmlContentType->get('file_extensions')\n));\n$query->select('uri');\n$link = $modx->getValue($query->prepare());\n\n// Output to placeholder if one is set\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $link, $prefix);\n} else {\n    return $link;\n}"
properties: 'a:0:{}'
content: "$catID = $modx->getOption('input', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n\n$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));\n\n// Get category name and parent ID\n$category = $modx->getObject('modCategory', array(\n    'id' => $catID\n));\n\nif ($category) {\n    $catName = $category->get('category');\n    $parentID = $category->get('parent');\n}\n\n// If category or parent is empty, don't generate any link.\n// All Romanesco elements are nested at least 1 level deep, so if a category\n// has no parent, we can allow ourselves to assume it's part of a MODX extra.\nif (!$category && $parentID == 0) {\n    $modx->toPlaceholder('pl', $prefix);\n    return;\n}\n\n// Get parent name as well, to avoid issues with multiple matches\n$query = $modx->newQuery('modCategory', array(\n    'id' => $parentID\n));\n$query->select('category');\n$parentName = $modx->getValue($query->prepare());\n\n// Grab only the last part of the category name\n$catName = preg_match('([^_]+$)', $catName, $matchCat);\n$parent = preg_match('([^_]+$)', $parentName, $matchParent);\n$matchCat = strtolower($matchCat[0]);\n$matchParent = strtolower($matchParent[0]);\n\n// If category and parent are the same, squash them\nif ($matchCat === $matchParent) {\n    $match = $matchCat;\n} else {\n    $match = $matchParent . \"/\" . $matchCat;\n}\n\n// Get the resource with an alias that matches the category name\n$query = $modx->newQuery('modResource');\n$query->where(array(\n    'uri:LIKE' => '%' . $match . $htmlContentType->get('file_extensions')\n));\n$query->select('uri');\n$link = $modx->getValue($query->prepare());\n\n// Output to placeholder if one is set\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $link, $prefix);\n} else {\n    return $link;\n}"

-----


$catID = $modx->getOption('input', $scriptProperties, '');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, '');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

$htmlContentType = $modx->getObject('modContentType', array('name' => 'HTML'));

// Get category name and parent ID
$category = $modx->getObject('modCategory', array(
    'id' => $catID
));

if ($category) {
    $catName = $category->get('category');
    $parentID = $category->get('parent');
}

// If category or parent is empty, don't generate any link.
// All Romanesco elements are nested at least 1 level deep, so if a category
// has no parent, we can allow ourselves to assume it's part of a MODX extra.
if (!$category && $parentID == 0) {
    $modx->toPlaceholder('pl', $prefix);
    return;
}

// Get parent name as well, to avoid issues with multiple matches
$query = $modx->newQuery('modCategory', array(
    'id' => $parentID
));
$query->select('category');
$parentName = $modx->getValue($query->prepare());

// Grab only the last part of the category name
$catName = preg_match('([^_]+$)', $catName, $matchCat);
$parent = preg_match('([^_]+$)', $parentName, $matchParent);
$matchCat = strtolower($matchCat[0]);
$matchParent = strtolower($matchParent[0]);

// If category and parent are the same, squash them
if ($matchCat === $matchParent) {
    $match = $matchCat;
} else {
    $match = $matchParent . "/" . $matchCat;
}

// Get the resource with an alias that matches the category name
$query = $modx->newQuery('modResource');
$query->where(array(
    'uri:LIKE' => '%' . $match . $htmlContentType->get('file_extensions')
));
$query->select('uri');
$link = $modx->getValue($query->prepare());

// Output to placeholder if one is set
if ($placeholder) {
    $modx->toPlaceholder('pl', $prefix);
    $modx->toPlaceholder($placeholder, $link, $prefix);
} else {
    return $link;
}