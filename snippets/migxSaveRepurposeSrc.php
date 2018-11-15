id: 130
name: migxSaveRepurposeSrc
description: 'After save hook for MIGXdb. Sets current resource ID as re-purpose destination.'
category: f_toolshed
snippet: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as destination\n$object->set('destination', $properties['resource_id']);\n$object->save();\n\nreturn '';"
properties: 'a:0:{}'
content: "$properties = $modx->getOption('scriptProperties', $scriptProperties, array());\n\n// Set current resource as destination\n$object->set('destination', $properties['resource_id']);\n$object->save();\n\nreturn '';"

-----


$properties = $modx->getOption('scriptProperties', $scriptProperties, array());

// Set current resource as destination
$object->set('destination', $properties['resource_id']);
$object->save();

return '';