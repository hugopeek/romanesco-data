id: 152
name: renderResourceName
description: 'Takes an ID as input and returns the pagetitle. Mainly intended as snippet renderer for Collections, but can be used independently or as output modifier too.'
category: f_resource
snippet: "$id = $modx->getOption('id', $scriptProperties, $input);\n$resource = $modx->getObject('modResource', $id);\n\nif (is_object($resource)) {\n    return $resource->get('pagetitle') . \" ($id)\";\n}\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:42:"romanesco.renderresourcename.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:43:"romanesco.renderresourcename.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


$id = $modx->getOption('id', $scriptProperties, $input);
$resource = $modx->getObject('modResource', $id);

if (is_object($resource)) {
    return $resource->get('pagetitle') . " ($id)";
}

return;