id: 114
name: tvToJSON
description: 'Output the properties of given TV to a JSON object. The output could be used by jsonToHTML to generate an HTML table.'
category: f_json
snippet: "/**\n * tvToJSON\n *\n * Output the properties of given TV to a JSON object.\n * The output could be used by jsonToHTML.\n *\n * Initially intended for use in the front-end library. TV settings can now be\n * loaded automatically, instead of copy/pasting the JSON from the GPM config by hand.\n *\n * Usage example:\n * [[tvToJSON? &tv=`[[+pattern_name]]`]]\n *\n */\n\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));\n\nif ($tv) {\n    // Render category name for clarity\n    $query = $modx->newQuery('modCategory', array(\n        'id' => $tv->get('category')\n    ));\n    $query->select('category');\n    $catName = $modx->getValue($query->prepare());\n\n    // Render media source name for clarity\n    $sourceID = $tv->get('source');\n    if ($sourceID != false) {\n        $query = $modx->newQuery('modMediaSource', array(\n            'id' => $sourceID\n        ));\n        $query->select('name');\n        $sourceName = $modx->getValue($query->prepare());\n    }\n\n    // Create a new object with altered elements\n    // The new key names mimic the properties used by GPM\n    $tvAltered = array(\n        'caption' => $tv->get('caption'),\n        'description' => $tv->get('description'),\n        //'name' => $tv->get('name'),\n        'type' => $tv->get('type'),\n        'category' => $catName,\n        'sortOrder' => $tv->get('rank'),\n        'inputOptionValues' => str_replace('||', '<br>', $tv->get('elements')),\n        'defaultValue' => $tv->get('default_text'),\n        'inputProperties' => $tv->get('input_properties'),\n        'outputProperties' => $tv->get('output_properties'),\n        'mediaSource' => $sourceName // Not a GPM property, but good to know anyway\n    );\n\n    // Output as JSON object\n    return json_encode($tvAltered);\n}"
properties: 'a:0:{}'
content: "/**\n * tvToJSON\n *\n * Output the properties of given TV to a JSON object.\n * The output could be used by jsonToHTML.\n *\n * Initially intended for use in the front-end library. TV settings can now be\n * loaded automatically, instead of copy/pasting the JSON from the GPM config by hand.\n *\n * Usage example:\n * [[tvToJSON? &tv=`[[+pattern_name]]`]]\n *\n */\n\n$tvName = $modx->getOption('tv', $scriptProperties, '');\n\n// Get the TV by name\n$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));\n\nif ($tv) {\n    // Render category name for clarity\n    $query = $modx->newQuery('modCategory', array(\n        'id' => $tv->get('category')\n    ));\n    $query->select('category');\n    $catName = $modx->getValue($query->prepare());\n\n    // Render media source name for clarity\n    $sourceID = $tv->get('source');\n    if ($sourceID != false) {\n        $query = $modx->newQuery('modMediaSource', array(\n            'id' => $sourceID\n        ));\n        $query->select('name');\n        $sourceName = $modx->getValue($query->prepare());\n    }\n\n    // Create a new object with altered elements\n    // The new key names mimic the properties used by GPM\n    $tvAltered = array(\n        'caption' => $tv->get('caption'),\n        'description' => $tv->get('description'),\n        //'name' => $tv->get('name'),\n        'type' => $tv->get('type'),\n        'category' => $catName,\n        'sortOrder' => $tv->get('rank'),\n        'inputOptionValues' => str_replace('||', '<br>', $tv->get('elements')),\n        'defaultValue' => $tv->get('default_text'),\n        'inputProperties' => $tv->get('input_properties'),\n        'outputProperties' => $tv->get('output_properties'),\n        'mediaSource' => $sourceName // Not a GPM property, but good to know anyway\n    );\n\n    // Output as JSON object\n    return json_encode($tvAltered);\n}"

-----


/**
 * tvToJSON
 *
 * Output the properties of given TV to a JSON object.
 * The output could be used by jsonToHTML.
 *
 * Initially intended for use in the front-end library. TV settings can now be
 * loaded automatically, instead of copy/pasting the JSON from the GPM config by hand.
 *
 * Usage example:
 * [[tvToJSON? &tv=`[[+pattern_name]]`]]
 *
 */

$tvName = $modx->getOption('tv', $scriptProperties, '');

// Get the TV by name
$tv = $modx->getObject('modTemplateVar', array('name'=>$tvName));

if ($tv) {
    // Render category name for clarity
    $query = $modx->newQuery('modCategory', array(
        'id' => $tv->get('category')
    ));
    $query->select('category');
    $catName = $modx->getValue($query->prepare());

    // Render media source name for clarity
    $sourceID = $tv->get('source');
    if ($sourceID != false) {
        $query = $modx->newQuery('modMediaSource', array(
            'id' => $sourceID
        ));
        $query->select('name');
        $sourceName = $modx->getValue($query->prepare());
    }

    // Create a new object with altered elements
    // The new key names mimic the properties used by GPM
    $tvAltered = array(
        'caption' => $tv->get('caption'),
        'description' => $tv->get('description'),
        //'name' => $tv->get('name'),
        'type' => $tv->get('type'),
        'category' => $catName,
        'sortOrder' => $tv->get('rank'),
        'inputOptionValues' => str_replace('||', '<br>', $tv->get('elements')),
        'defaultValue' => $tv->get('default_text'),
        'inputProperties' => $tv->get('input_properties'),
        'outputProperties' => $tv->get('output_properties'),
        'mediaSource' => $sourceName // Not a GPM property, but good to know anyway
    );

    // Output as JSON object
    return json_encode($tvAltered);
}