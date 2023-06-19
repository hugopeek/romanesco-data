id: 103
name: getTmplvarID
description: 'Get the ID of a TV, in case you only know its name. Created for the front-end library, to help with listing included TVs.'
category: f_basic
snippet: "/**\n * getTmplvarID\n *\n * Get the ID of a TV, in case you only know its name.\n * Created for the front-end library, to help with listing included TVs.\",\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the ID of the TV\nif (is_object($tv)) {\n    $id = $tv->get('id');\n    return $id;\n} else {\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:36:"romanesco.gettmplvarid.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:37:"romanesco.gettmplvarid.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * getTmplvarID
 *
 * Get the ID of a TV, in case you only know its name.
 * Created for the front-end library, to help with listing included TVs.",
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$tvName = $modx->getOption('tv', $scriptProperties, '');

// Get the TV by name
$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));

// Get the ID of the TV
if (is_object($tv)) {
    $id = $tv->get('id');
    return $id;
} else {
    return '';
}