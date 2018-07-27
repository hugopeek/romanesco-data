id: 85
name: getRawTVValue
description: 'Get the raw value of a TV. Usually when retrieving a TV value, it gets processed first before being returned. But sometimes you need the unprocessed value instead, e.g. when using @inherit.'
category: f_templatevars
snippet: "$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the raw content of the TV\nif (is_object($tv)) {\n    $rawValue = $tv->getValue($resourceId);\n\n    return $rawValue;\n}\nelse {\n    return '';\n}"
properties: 'a:0:{}'
content: "$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->get('id'));\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV\n$tv = $modx->getObject('modTemplateVar',array('name'=>$tvName));\n\n// Get the raw content of the TV\nif (is_object($tv)) {\n    $rawValue = $tv->getValue($resourceId);\n\n    return $rawValue;\n}\nelse {\n    return '';\n}"

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