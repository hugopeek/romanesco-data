id: 85
name: getRawTVValue
description: 'Get the raw value of a TV. Usually when retrieving a TV value, it gets processed first before being returned. But sometimes you need the unprocessed value instead, e.g. when using @inherit.'
category: f_basic
snippet: "$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the raw content of the TV\nif (is_object($tv)) {\n    $rawValue = $tv->getValue($resourceId);\n\n    return $rawValue;\n}\nelse {\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:37:"romanesco.getrawtvvalue.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:38:"romanesco.getrawtvvalue.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));
$tvName = $modx->getOption('tv', $scriptProperties, '');

// Get the TV
$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));

// Get the raw content of the TV
if (is_object($tv)) {
    $rawValue = $tv->getValue($resourceId);

    return $rawValue;
}
else {
    return '';
}