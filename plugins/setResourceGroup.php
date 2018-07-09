id: 33
name: setResourceGroup
category: c_content
plugincode: "/*\n * setResourceGroup\n *\n * Add resource to a specific group, based on certain conditions or variables.\n */\n\n$resourceGroup = $modx->getObject('modResourceGroup',1);\n$resourceGroupName = $resourceGroup->get('name');\n\nif (!is_object($resourceGroup)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[setResourceGroup] Resource group not found.');\n    return false;\n}\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n\n        // Tickets\n        if ($resource->get('class_key','Ticket')) {\n\n            // If resource is a private ticket, add it to the KB private resource group\n            if ($resource->get('privateweb') && !$resource->isMember($resourceGroupName)) {\n                $resource->joinGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" joined resource group: ' . $resourceGroupName);\n            }\n\n            // If the ticket is not private, remove it from the resource group\n            if (!$resource->get('privateweb') && $resource->isMember($resourceGroup->get('name'))) {\n                $resource->leaveGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" left resource group: ' . $resourceGroupName);\n            }\n        }\n\n        break;\n}"
properties: 'a:0:{}'
content: "/*\n * setResourceGroup\n *\n * Add resource to a specific group, based on certain conditions or variables.\n */\n\n$resourceGroup = $modx->getObject('modResourceGroup',1);\n$resourceGroupName = $resourceGroup->get('name');\n\nif (!is_object($resourceGroup)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[setResourceGroup] Resource group not found.');\n    return false;\n}\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n\n        // Tickets\n        if ($resource->get('class_key','Ticket')) {\n\n            // If resource is a private ticket, add it to the KB private resource group\n            if ($resource->get('privateweb') && !$resource->isMember($resourceGroupName)) {\n                $resource->joinGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" joined resource group: ' . $resourceGroupName);\n            }\n\n            // If the ticket is not private, remove it from the resource group\n            if (!$resource->get('privateweb') && $resource->isMember($resourceGroup->get('name'))) {\n                $resource->leaveGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" left resource group: ' . $resourceGroupName);\n            }\n        }\n\n        break;\n}"

-----


/*
 * setResourceGroup
 *
 * Add resource to a specific group, based on certain conditions or variables.
 */

switch ($modx->event->name) {
    case 'OnDocFormSave':
        $resourceGroup = $modx->getObject('modResourceGroup',1);

        if (!is_object($resourceGroup)) {
            $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource group not found.');
            break;
        } else {
            $resourceGroupName = $resourceGroup->get('name');
        }

        // Tickets
        if ($resource->get('class_key','Ticket')) {

            // If resource is a private ticket, add it to the KB private resource group
            if ($resource->get('privateweb') && !$resource->isMember($resourceGroupName)) {
                $resource->joinGroup(1);
                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource "' . $resource->get('pagetitle') . '" joined resource group: ' . $resourceGroupName);
            }

            // If the ticket is not private, remove it from the resource group
            if (!$resource->get('privateweb') && $resource->isMember($resourceGroup->get('name'))) {
                $resource->leaveGroup(1);
                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource "' . $resource->get('pagetitle') . '" left resource group: ' . $resourceGroupName);
            }
        }

        break;
}