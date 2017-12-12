id: 99
name: includedTVs
category: f_hub
snippet: "/**\n * includedTVs\n *\n * This snippet is intended to list the TVs that are being used\n * inside a given chunk. It needs the raw content of the chunk as input.\n *\n * See includedChunks for more detailed instructions.\n *\n * @author Hugo Peek\n */\n\n$string = $input;\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Find possible TVs by looking at placeholders with a leading + character\n// @todo: this should also consider other prefixes, such as &rowTpl or *.\n$regex = '/(?<!\\w)\\+\\w+/';\n\n// Set idx start value to something high, to prevent overlap\n$idx = 2000;\n\n// Define output array\n$output = array();\n\nif (preg_match_all($regex, $string, $matches)) {\n    // Remove + from all matches\n    foreach ($matches as $match) {\n        $match = str_replace('+', '', $match);\n    }\n\n    // Remove duplicates\n    $result = array_unique($match);\n\n    // Create a comma separated list of possible TVs\n    foreach ($result as $key => $value) {\n        $query = $modx->newQuery('modTemplateVar', array(\n            'name' => $value\n        ));\n\n        $query->select('id');\n        $possibleTVs[] = $modx->getValue($query->prepare());\n    }\n\n    // Filter results that returned false because TV name doesn't exist\n    $tvList = array_filter($possibleTVs, function($value) {\n        return ($value !== null && $value !== false && $value !== '');\n    });\n\n    // We have a list of positive IDs now (literally), so we can create a list\n    // of links to their corresponding PL locations.\n    foreach ($tvList as $value) {\n        $tv = $modx->getObject('modTemplateVar', $value);\n        $name = $tv->get('name');\n        $category = $tv->get('category');\n\n        // The actual TV categories often contain spaces and hyphens and they\n        // don't accurately represent the file structure of the library.\n        // That's why we get the parent category instead.\n        $query = $modx->newQuery('modCategory', array(\n            'id' => $category\n        ));\n        $query->select('parent');\n        $parent = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'category' => $parent,\n            'idx' => $idx\n        ));\n    }\n}\n\nreturn implode($output);"
properties: 'a:0:{}'
content: "/**\n * includedTVs\n *\n * This snippet is intended to list the TVs that are being used\n * inside a given chunk. It needs the raw content of the chunk as input.\n *\n * See includedChunks for more detailed instructions.\n *\n * @author Hugo Peek\n */\n\n$string = $input;\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Find possible TVs by looking at placeholders with a leading + character\n// @todo: this should also consider other prefixes, such as &rowTpl or *.\n$regex = '/(?<!\\w)\\+\\w+/';\n\n// Set idx start value to something high, to prevent overlap\n$idx = 2000;\n\n// Define output array\n$output = array();\n\nif (preg_match_all($regex, $string, $matches)) {\n    // Remove + from all matches\n    foreach ($matches as $match) {\n        $match = str_replace('+', '', $match);\n    }\n\n    // Remove duplicates\n    $result = array_unique($match);\n\n    // Create a comma separated list of possible TVs\n    foreach ($result as $key => $value) {\n        $query = $modx->newQuery('modTemplateVar', array(\n            'name' => $value\n        ));\n\n        $query->select('id');\n        $possibleTVs[] = $modx->getValue($query->prepare());\n    }\n\n    // Filter results that returned false because TV name doesn't exist\n    $tvList = array_filter($possibleTVs, function($value) {\n        return ($value !== null && $value !== false && $value !== '');\n    });\n\n    // We have a list of positive IDs now (literally), so we can create a list\n    // of links to their corresponding PL locations.\n    foreach ($tvList as $value) {\n        $tv = $modx->getObject('modTemplateVar', $value);\n        $name = $tv->get('name');\n        $category = $tv->get('category');\n\n        // The actual TV categories often contain spaces and hyphens and they\n        // don't accurately represent the file structure of the library.\n        // That's why we get the parent category instead.\n        $query = $modx->newQuery('modCategory', array(\n            'id' => $category\n        ));\n        $query->select('parent');\n        $parent = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'category' => $parent,\n            'idx' => $idx\n        ));\n    }\n}\n\nreturn implode($output);"

-----


/**
 * includedTVs
 *
 * This snippet is intended to list the TVs that are being used
 * inside a given chunk. It needs the raw content of the chunk as input.
 *
 * See includedChunks for more detailed instructions.
 *
 * @author Hugo Peek
 */

$string = $input;
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');

// Find possible TVs by looking at placeholders with a leading + character
// @todo: this should also consider other prefixes, such as &rowTpl or *.
$regex = '/(?<!\w)\+\w+/';

// Set idx start value to something high, to prevent overlap
$idx = 2000;

// Define output array
$output = array();

if (preg_match_all($regex, $string, $matches)) {
    // Remove + from all matches
    foreach ($matches as $match) {
        $match = str_replace('+', '', $match);
    }

    // Remove duplicates
    $result = array_unique($match);

    // Create a comma separated list of possible TVs
    foreach ($result as $key => $value) {
        $query = $modx->newQuery('modTemplateVar', array(
            'name' => $value
        ));

        $query->select('id');
        $possibleTVs[] = $modx->getValue($query->prepare());
    }

    // Filter results that returned false because TV name doesn't exist
    $tvList = array_filter($possibleTVs, function($value) {
        return ($value !== null && $value !== false && $value !== '');
    });

    // We have a list of positive IDs now (literally), so we can create a list
    // of links to their corresponding PL locations.
    foreach ($tvList as $value) {
        $tv = $modx->getObject('modTemplateVar', $value);
        $name = $tv->get('name');
        $category = $tv->get('category');

        // The actual TV categories often contain spaces and hyphens and they
        // don't accurately represent the file structure of the library.
        // That's why we get the parent category instead.
        $query = $modx->newQuery('modCategory', array(
            'id' => $category
        ));
        $query->select('parent');
        $parent = $modx->getValue($query->prepare());

        // Up idx value by 1, so a unique placeholder can be created
        $idx++;

        // Output to a chunk that contains the link generator
        $output[] = $modx->getChunk($tpl, array(
            'name' => $name,
            'category' => $parent,
            'idx' => $idx
        ));
    }
}

return implode($output);