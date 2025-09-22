id: 127
name: jsonImportInputOptions
description: 'Generate input options from a JSON file. These options are written directly into their database table inside the Backyard package.'
category: f_json
snippet: "/**\n * jsonImportInputOptions\n *\n * Generate input options from a JSON file. These options are written directly\n * into their database table inside the Backyard package.\n *\n * The option groups are referenced and compared by key, the options themselves\n * by alias. This means that IDs are assigned by MODX and settings can be mixed\n * with user generated input.\n *\n * Normally, this also means that when you change the key or alias of a\n * field, a new item is created. This is not always desirable. Sometimes fields\n * are referenced by ID, so you want to keep these selections intact when making\n * adjustments to a field.\n *\n * That's why there is a safety net built in. It works like this: if you want to\n * change the key of a group or alias of an option, you can do that. But *only*\n * if you leave the name property alone. The script will perform a second check\n * in the background, and if the names still match it will update the existing\n * element instead of creating a new one.\n *\n * So NEVER change name and key/alias in the same update, unless you don't mind\n * new elements being created. Change one > run script > change the other.\n *\n * And ALWAYS backup first.\n *\n * Usage:\n * [[jsonImportInputOptions?\n *     &json=`/absolute/path/to/file.json`\n *     &updateExisting=`0`\n * ]]\n *\n * Set the updateExisting option to true if you want existing values to be\n * overwritten by the file contents. The default is false: existing options will\n * be left alone; only new options will be added.\n *\n * Tip:\n * If you want to populate the options with only the contents of the file, you\n * can set them all to deleted=1 before updating and then back to 0 if present\n * in the json file.\n *\n * Don't do this if you want to mix options with user generated input.\n * If you need to delete options from the JSON file, just add \"deleted\":1 to\n * their config, run the script once and then delete them from the file.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$json = $modx->getOption('file', $scriptProperties, '');\n$updateExisting = $modx->getOption('updateExisting', $scriptProperties, false);\n\nif (!is_file($json)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[jsonImportInputOptions] Input file not found!');\n    return '';\n}\n$options = file_get_contents($json);\n$optionsArray = json_decode($options, true);\n\n$modx->log(modX::LOG_LEVEL_INFO, 'Importing default set of input options...');\n\nforeach ($optionsArray['groups'] as $group) {\n    $groupID = '';\n\n    // Prevent NULL on NOT NULL field errors\n    if (!isset($group['deleted'])) {\n        $group['deleted'] = 0;\n    }\n\n    // Assume group key is the same as any existing \"old\" key\n    $oldKey = $group['key'];\n\n    // Check if group exists\n    $existingGroup = $modx->getObject('FractalFarming\\Romanesco\\Model\\OptionGroup', array(\n        'key' => $group['key']\n    ));\n\n    // Perform second check on name, to see if user wants to update key for existing group\n    if (!is_object($existingGroup)) {\n        $existingGroup = $modx->getObject('FractalFarming\\Romanesco\\Model\\OptionGroup', array(\n            'name' => $group['name']\n        ));\n\n        // If group key was changed, use previous key to fetch existing options correctly\n        if (is_object($existingGroup)) {\n            $oldKey = $existingGroup->get('key');\n        }\n    }\n\n    // Update existing group with new data\n    if (is_object($existingGroup) && $updateExisting) {\n        $modx->log(modX::LOG_LEVEL_INFO, ' - updating group: ' . $group['name']);\n        $existingGroup->set('name', $group['name']);\n        $existingGroup->set('description', $group['description']);\n        $existingGroup->set('key', $group['key']);\n        $existingGroup->set('position', $group['position']);\n        $existingGroup->set('deleted', $group['deleted']);\n        $existingGroup->save();\n        $groupID = $existingGroup->get('id'); // for connecting options\n    }\n    // If group doesn't exist, create it\n    elseif (!is_object($existingGroup)) {\n        $modx->log(modX::LOG_LEVEL_INFO, ' - creating group: ' . $group['name']);\n        $newGroup = $modx->newObject('FractalFarming\\Romanesco\\Model\\OptionGroup', array(\n            'name' => $group['name'],\n            'description' => $group['description'],\n            'key' => $group['key'],\n            'position' => $group['position'],\n        ));\n        $newGroup->save();\n        $groupID = $newGroup->get('id'); // for connecting options\n    }\n    else {\n        continue;\n    }\n\n    // Same drill for the options\n    foreach ($group['options'] as $option) {\n        // Prevent NULL on NOT NULL field errors\n        if (!isset($option['deleted'])) {\n            $option['deleted'] = 0;\n        }\n\n        // Generate alias if none was set\n        if (!isset($option['alias'])) {\n            $option['alias'] = $modx->filterPathSegment($option['name'], [\n                'friendly_alias_restrict_chars' => 'alphanumeric'\n            ]);\n        }\n\n        // Check if option exists\n        $existingOption = $modx->getObject('FractalFarming\\Romanesco\\Model\\Option', array(\n            'alias' => $option['alias'],\n            'key' => $oldKey,\n        ));\n\n        // Perform second check on name, to see if user wants to update alias for existing option\n        if (!is_object($existingOption)) {\n            $existingOption = $modx->getObject('FractalFarming\\Romanesco\\Model\\Option', array(\n                'name' => $option['name'],\n                'key' => $oldKey,\n            ));\n        }\n\n        // Update existing option with new data\n        if (is_object($existingOption) && $updateExisting) {\n            $existingOption->set('name', $option['name']);\n            $existingOption->set('description', $option['description']);\n            $existingOption->set('alias', $option['alias']);\n            $existingOption->set('key', $group['key']);\n            $existingOption->set('position', $option['position']);\n            $existingOption->set('deleted', $option['deleted']);\n            $existingOption->save();\n        }\n        // Or create new option\n        elseif (!is_object($existingOption)) {\n            $newOption = $modx->newObject('FractalFarming\\Romanesco\\Model\\Option', array(\n                'name' => $option['name'],\n                'description' => $option['description'],\n                'alias' => $option['alias'],\n                'group' => $groupID,\n                'key' => $group['key'],\n                'position' => $option['position'],\n                'deleted' => $option['deleted'],\n            ));\n            $newOption->save();\n        }\n    }\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * jsonImportInputOptions
 *
 * Generate input options from a JSON file. These options are written directly
 * into their database table inside the Backyard package.
 *
 * The option groups are referenced and compared by key, the options themselves
 * by alias. This means that IDs are assigned by MODX and settings can be mixed
 * with user generated input.
 *
 * Normally, this also means that when you change the key or alias of a
 * field, a new item is created. This is not always desirable. Sometimes fields
 * are referenced by ID, so you want to keep these selections intact when making
 * adjustments to a field.
 *
 * That's why there is a safety net built in. It works like this: if you want to
 * change the key of a group or alias of an option, you can do that. But *only*
 * if you leave the name property alone. The script will perform a second check
 * in the background, and if the names still match it will update the existing
 * element instead of creating a new one.
 *
 * So NEVER change name and key/alias in the same update, unless you don't mind
 * new elements being created. Change one > run script > change the other.
 *
 * And ALWAYS backup first.
 *
 * Usage:
 * [[jsonImportInputOptions?
 *     &json=`/absolute/path/to/file.json`
 *     &updateExisting=`0`
 * ]]
 *
 * Set the updateExisting option to true if you want existing values to be
 * overwritten by the file contents. The default is false: existing options will
 * be left alone; only new options will be added.
 *
 * Tip:
 * If you want to populate the options with only the contents of the file, you
 * can set them all to deleted=1 before updating and then back to 0 if present
 * in the json file.
 *
 * Don't do this if you want to mix options with user generated input.
 * If you need to delete options from the JSON file, just add "deleted":1 to
 * their config, run the script once and then delete them from the file.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$json = $modx->getOption('file', $scriptProperties, '');
$updateExisting = $modx->getOption('updateExisting', $scriptProperties, false);

if (!is_file($json)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[jsonImportInputOptions] Input file not found!');
    return '';
}
$options = file_get_contents($json);
$optionsArray = json_decode($options, true);

$modx->log(modX::LOG_LEVEL_INFO, 'Importing default set of input options...');

foreach ($optionsArray['groups'] as $group) {
    $groupID = '';

    // Prevent NULL on NOT NULL field errors
    if (!isset($group['deleted'])) {
        $group['deleted'] = 0;
    }

    // Assume group key is the same as any existing "old" key
    $oldKey = $group['key'];

    // Check if group exists
    $existingGroup = $modx->getObject('FractalFarming\Romanesco\Model\OptionGroup', array(
        'key' => $group['key']
    ));

    // Perform second check on name, to see if user wants to update key for existing group
    if (!is_object($existingGroup)) {
        $existingGroup = $modx->getObject('FractalFarming\Romanesco\Model\OptionGroup', array(
            'name' => $group['name']
        ));

        // If group key was changed, use previous key to fetch existing options correctly
        if (is_object($existingGroup)) {
            $oldKey = $existingGroup->get('key');
        }
    }

    // Update existing group with new data
    if (is_object($existingGroup) && $updateExisting) {
        $modx->log(modX::LOG_LEVEL_INFO, ' - updating group: ' . $group['name']);
        $existingGroup->set('name', $group['name']);
        $existingGroup->set('description', $group['description']);
        $existingGroup->set('key', $group['key']);
        $existingGroup->set('position', $group['position']);
        $existingGroup->set('deleted', $group['deleted']);
        $existingGroup->save();
        $groupID = $existingGroup->get('id'); // for connecting options
    }
    // If group doesn't exist, create it
    elseif (!is_object($existingGroup)) {
        $modx->log(modX::LOG_LEVEL_INFO, ' - creating group: ' . $group['name']);
        $newGroup = $modx->newObject('FractalFarming\Romanesco\Model\OptionGroup', array(
            'name' => $group['name'],
            'description' => $group['description'],
            'key' => $group['key'],
            'position' => $group['position'],
        ));
        $newGroup->save();
        $groupID = $newGroup->get('id'); // for connecting options
    }
    else {
        continue;
    }

    // Same drill for the options
    foreach ($group['options'] as $option) {
        // Prevent NULL on NOT NULL field errors
        if (!isset($option['deleted'])) {
            $option['deleted'] = 0;
        }

        // Generate alias if none was set
        if (!isset($option['alias'])) {
            $option['alias'] = $modx->filterPathSegment($option['name'], [
                'friendly_alias_restrict_chars' => 'alphanumeric'
            ]);
        }

        // Check if option exists
        $existingOption = $modx->getObject('FractalFarming\Romanesco\Model\Option', array(
            'alias' => $option['alias'],
            'key' => $oldKey,
        ));

        // Perform second check on name, to see if user wants to update alias for existing option
        if (!is_object($existingOption)) {
            $existingOption = $modx->getObject('FractalFarming\Romanesco\Model\Option', array(
                'name' => $option['name'],
                'key' => $oldKey,
            ));
        }

        // Update existing option with new data
        if (is_object($existingOption) && $updateExisting) {
            $existingOption->set('name', $option['name']);
            $existingOption->set('description', $option['description']);
            $existingOption->set('alias', $option['alias']);
            $existingOption->set('key', $group['key']);
            $existingOption->set('position', $option['position']);
            $existingOption->set('deleted', $option['deleted']);
            $existingOption->save();
        }
        // Or create new option
        elseif (!is_object($existingOption)) {
            $newOption = $modx->newObject('FractalFarming\Romanesco\Model\Option', array(
                'name' => $option['name'],
                'description' => $option['description'],
                'alias' => $option['alias'],
                'group' => $groupID,
                'key' => $group['key'],
                'position' => $option['position'],
                'deleted' => $option['deleted'],
            ));
            $newOption->save();
        }
    }
}

return '';