id: 68
name: getChildCount
description: 'Return the amount of child pages a resource has. Now you can make one of those shiny little badges inside a menu button, telling the user upfront how much treasure is inside.'
category: f_resource
snippet: "/**\n * getChildCount\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $parent\n */\n\n$count = 0;\n$parent = isset($parent) ? (integer) $parent : 0;\nif ($parent > 0) {\n    $criteria = array(\n        'parent' => $parent,\n        'deleted' => false,\n        'published' => true,\n    );\n    $count = $modx->getCount('modResource', $criteria);\n}\nreturn (string) $count;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.getchildcount.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.getchildcount.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getChildCount
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $parent
 */

$count = 0;
$parent = isset($parent) ? (integer) $parent : 0;
if ($parent > 0) {
    $criteria = array(
        'parent' => $parent,
        'deleted' => false,
        'published' => true,
    );
    $count = $modx->getCount('modResource', $criteria);
}
return (string) $count;