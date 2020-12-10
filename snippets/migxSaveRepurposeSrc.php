id: 130
name: migxSaveRepurposeSrc
description: 'After save hook for MIGXdb. Sets current resource ID as re-purpose destination.'
category: f_toolshed
snippet: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as destination\n$object->set('destination', $properties['resource_id']);\n$object->save();\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.migxsaverepurposesrc.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.migxsaverepurposesrc.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as destination\n$object->set('destination', $properties['resource_id']);\n$object->save();\n\nreturn '';"

-----


$properties = $modx->getOption('scriptProperties', $scriptProperties, array());

// Set current resource as destination
$object->set('destination', $properties['resource_id']);
$object->save();

return '';