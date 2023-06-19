id: 127
name: jsonImportInputOptions
description: 'Generate input options from a JSON file. These options are written directly into their database table inside the Backyard package.'
category: f_json
snippet: "/**\n * jsonImportInputOptions\n *\n * Generate input options from a JSON file. These options are written directly\n * into their database table inside the Backyard package.\n *\n * The option groups are referenced and compared by key, the options themselves\n * by alias. This means that IDs are assigned by MODX and settings can be mixed\n * with user generated input.\n *\n * Normally, this also means that when you change the key or alias of a\n * field, a new item is created. This is not always desirable. Sometimes, fields\n * are referenced by ID so you want to keep these selections intact when making\n * adjustments to a field.\n *\n * That's why there is a safety net built in. It works like this: if you want to\n * change the key of a group or alias of an option, you can do that. But *only*\n * if you leave the name property alone. The script will perform a second check\n * in the background, and if the names still match it will update the existing\n * element instead of creating a new one.\n *\n * So NEVER change name and key/alias in the same update, unless you don't mind\n * new elements being created. Change one > run script > change the other.\n *\n * Usage:\n * [[jsonImportInputOptions? &json=`/absolute/path/to/file.json`]]\n *\n * Tip:\n * If you want to populate the options with only the contents of the file, you\n * can set them all to deleted=1 before updating and then back to 0 if present\n * in the json file.\n *\n * Don't do this if you want to mix options with user generated input (obviously).\n * If you need to delete options from the JSON file, just add \"deleted\":1 to\n * their config, run the script once and then delete them from the file.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$backyard = $modx->addPackage('romanescobackyard', $corePath . 'model/');\n$json = $modx->getOption('file', $scriptProperties, '');\n\nif (!is_file($json)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[jsonImportInputOptions] input file not found!');\n    return '';\n}\n$options = file_get_contents($json);\n$optionsArray = json_decode($options, true);\n\nforeach ($optionsArray['groups'] as $group) {\n    $groupID = '';\n\n    // Prevent NULL on NOT NULL field errors\n    if (!isset($group['deleted'])) {\n        $group['deleted'] = 0;\n    }\n\n    // Assume group key is the same as any existing \"old\" key\n    $oldKey = $group['key'];\n\n    // Check if group exists\n    $existingGroup = $modx->getObject('rmOptionGroup', array(\n        'key' => $group['key']\n    ));\n\n    // Perform second check on name, to see if user wants to update key for existing group\n    if (!is_object($existingGroup)) {\n        $existingGroup = $modx->getObject('rmOptionGroup', array(\n            'name' => $group['name']\n        ));\n\n        // If group key was changed, use previous key to fetch existing options correctly\n        if (is_object($existingGroup)) {\n            $oldKey = $existingGroup->get('key');\n        }\n    }\n\n    // Update existing group with new data\n    if (is_object($existingGroup)) {\n        $existingGroup->set('name', $group['name']);\n        $existingGroup->set('description', $group['description']);\n        $existingGroup->set('key', $group['key']);\n        $existingGroup->set('position', $group['position']);\n        $existingGroup->set('deleted', $group['deleted']);\n        $existingGroup->save();\n        $groupID = $existingGroup->get('id'); // for connecting options\n    }\n    // If group doesn't exist, create it\n    else {\n        $newGroup = $modx->newObject('rmOptionGroup', array(\n            'name' => $group['name'],\n            'description' => $group['description'],\n            'key' => $group['key'],\n            'position' => $group['position'],\n        ));\n        $newGroup->save();\n        $groupID = $newGroup->get('id'); // for connecting options\n    }\n\n    // Same drill for the options\n    foreach ($group['options'] as $option) {\n        // Prevent NULL on NOT NULL field errors\n        if (!isset($option['deleted'])) {\n            $option['deleted'] = 0;\n        }\n\n        // Generate alias if none was set\n        if (!isset($option['alias'])) {\n            $option['alias'] = $modx->runSnippet('stripAsAlias', array('input' => $option['name']));\n        }\n\n        // Check if option exists\n        $existingOption = $modx->getObject('rmOption', array(\n            'alias' => $option['alias'],\n            'key' => $oldKey,\n        ));\n\n        // Perform second check on name, to see if user wants to update alias for existing option\n        if (!is_object($existingOption)) {\n            $existingOption = $modx->getObject('rmOption', array(\n                'name' => $option['name'],\n                'key' => $oldKey,\n            ));\n        }\n\n        // Update existing option with new data\n        if (is_object($existingOption)) {\n            $existingOption->set('name', $option['name']);\n            $existingOption->set('description', $option['description']);\n            $existingOption->set('alias', $option['alias']);\n            $existingOption->set('key', $group['key']);\n            $existingOption->set('position', $option['position']);\n            $existingOption->set('deleted', $option['deleted']);\n            $existingOption->save();\n        }\n        // Or create new option\n        else {\n            $newOption = $modx->newObject('rmOption', array(\n                'name' => $option['name'],\n                'description' => $option['description'],\n                'alias' => $option['alias'],\n                'group' => $groupID,\n                'key' => $group['key'],\n                'position' => $option['position'],\n                'deleted' => $option['deleted'],\n            ));\n            $newOption->save();\n        }\n    }\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:46:"romanesco.jsonimportinputoptions.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:47:"romanesco.jsonimportinputoptions.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

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
 * field, a new item is created. This is not always desirable. Sometimes, fields
 * are referenced by ID so you want to keep these selections intact when making
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
 * Usage:
 * [[jsonImportInputOptions? &json=`/absolute/path/to/file.json`]]
 *
 * Tip:
 * If you want to populate the options with only the contents of the file, you
 * can set them all to deleted=1 before updating and then back to 0 if present
 * in the json file.
 *
 * Don't do this if you want to mix options with user generated input (obviously).
 * If you need to delete options from the JSON file, just add "deleted":1 to
 * their config, run the script once and then delete them from the file.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$backyard = $modx->addPackage('romanescobackyard', $corePath . 'model/');
$json = $modx->getOption('file', $scriptProperties, '');

if (!is_file($json)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[jsonImportInputOptions] input file not found!');
    return '';
}
$options = file_get_contents($json);
$optionsArray = json_decode($options, true);

foreach ($optionsArray['groups'] as $group) {
    $groupID = '';

    // Prevent NULL on NOT NULL field errors
    if (!isset($group['deleted'])) {
        $group['deleted'] = 0;
    }

    // Assume group key is the same as any existing "old" key
    $oldKey = $group['key'];

    // Check if group exists
    $existingGroup = $modx->getObject('rmOptionGroup', array(
        'key' => $group['key']
    ));

    // Perform second check on name, to see if user wants to update key for existing group
    if (!is_object($existingGroup)) {
        $existingGroup = $modx->getObject('rmOptionGroup', array(
            'name' => $group['name']
        ));

        // If group key was changed, use previous key to fetch existing options correctly
        if (is_object($existingGroup)) {
            $oldKey = $existingGroup->get('key');
        }
    }

    // Update existing group with new data
    if (is_object($existingGroup)) {
        $existingGroup->set('name', $group['name']);
        $existingGroup->set('description', $group['description']);
        $existingGroup->set('key', $group['key']);
        $existingGroup->set('position', $group['position']);
        $existingGroup->set('deleted', $group['deleted']);
        $existingGroup->save();
        $groupID = $existingGroup->get('id'); // for connecting options
    }
    // If group doesn't exist, create it
    else {
        $newGroup = $modx->newObject('rmOptionGroup', array(
            'name' => $group['name'],
            'description' => $group['description'],
            'key' => $group['key'],
            'position' => $group['position'],
        ));
        $newGroup->save();
        $groupID = $newGroup->get('id'); // for connecting options
    }

    // Same drill for the options
    foreach ($group['options'] as $option) {
        // Prevent NULL on NOT NULL field errors
        if (!isset($option['deleted'])) {
            $option['deleted'] = 0;
        }

        // Generate alias if none was set
        if (!isset($option['alias'])) {
            $option['alias'] = $modx->runSnippet('stripAsAlias', array('input' => $option['name']));
        }

        // Check if option exists
        $existingOption = $modx->getObject('rmOption', array(
            'alias' => $option['alias'],
            'key' => $oldKey,
        ));

        // Perform second check on name, to see if user wants to update alias for existing option
        if (!is_object($existingOption)) {
            $existingOption = $modx->getObject('rmOption', array(
                'name' => $option['name'],
                'key' => $oldKey,
            ));
        }

        // Update existing option with new data
        if (is_object($existingOption)) {
            $existingOption->set('name', $option['name']);
            $existingOption->set('description', $option['description']);
            $existingOption->set('alias', $option['alias']);
            $existingOption->set('key', $group['key']);
            $existingOption->set('position', $option['position']);
            $existingOption->set('deleted', $option['deleted']);
            $existingOption->save();
        }
        // Or create new option
        else {
            $newOption = $modx->newObject('rmOption', array(
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