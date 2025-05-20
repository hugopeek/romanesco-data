id: 33
name: setResourceGroup
description: 'Add resource to a specific group, based on certain conditions or variables.'
category: c_content
plugincode: "/**\n * setResourceGroup\n *\n * Add resource to a specific group, based on certain conditions or variables.\n *\n * @var modX $modx\n * @package romanesco\n */\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         */\n\n        $resourceGroup = $modx->getObject('modResourceGroup',1);\n\n        if (!is_object($resourceGroup)) {\n            $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] No resource group was found.');\n            break;\n        } else {\n            $resourceGroupName = $resourceGroup->get('name');\n        }\n\n        // Tickets\n        if ($resource->get('class_key','Ticket')) {\n\n            // If resource is a private ticket, add it to the KB private resource group\n            if ($resource->get('privateweb') && !$resource->isMember($resourceGroupName)) {\n                $resource->joinGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" joined resource group: ' . $resourceGroupName);\n            }\n\n            // If the ticket is not private, remove it from the resource group\n            if (!$resource->get('privateweb') && $resource->isMember($resourceGroup->get('name'))) {\n                $resource->leaveGroup(1);\n                $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] Resource \"' . $resource->get('pagetitle') . '\" left resource group: ' . $resourceGroupName);\n            }\n        }\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
disabled: 1

-----


/**
 * setResourceGroup
 *
 * Add resource to a specific group, based on certain conditions or variables.
 *
 * @var modX $modx
 * @package romanesco
 */

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         */

        $resourceGroup = $modx->getObject('modResourceGroup',1);

        if (!is_object($resourceGroup)) {
            $modx->log(modX::LOG_LEVEL_INFO, '[setResourceGroup] No resource group was found.');
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