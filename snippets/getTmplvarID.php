id: 103
name: getTmplvarID
description: 'Get the ID of a TV, in case you only know its name. Created for the front-end library, to help with listing included TVs.'
category: f_basic
snippet: "$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the ID of the TV\nif (is_object($tv)) {\n    $id = $tv->get('id');\n\n    return $id;\n}\nelse {\n    return '';\n}"
properties: 'a:0:{}'
content: "$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the ID of the TV\nif (is_object($tv)) {\n    $id = $tv->get('id');\n\n    return $id;\n}\nelse {\n    return '';\n}"

-----


$tvName = $modx->getOption('tv', $scriptProperties, '');

// Get the TV by name
$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));

// Get the ID of the TV
if (is_object($tv)) {
    $id = $tv->get('id');

    return $id;
}
else {
    return '';
}