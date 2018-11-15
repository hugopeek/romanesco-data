id: 131
name: migxSaveRepurposeDest
description: 'After save hook for MIGXdb. Sets current resource ID as re-purpose source.'
category: f_toolshed
snippet: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as source\n$object->set('source', $properties['resource_id']);\n$object->save();\n\nreturn '';"
properties: 'a:0:{}'
content: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as source\n$object->set('source', $properties['resource_id']);\n$object->save();\n\nreturn '';"

-----


$properties = $modx->getOption('scriptProperties', $scriptProperties, array());

// Set current resource as source
$object->set('source', $properties['resource_id']);
$object->save();

return '';