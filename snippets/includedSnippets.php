id: 98
name: includedSnippets
category: f_hub
snippet: "/**\n * includedSnippets\n *\n * This snippet is intended to list the snippets that are being called\n * inside a given chunk. It needs the raw content of the chunk as input.\n *\n * See includedChunks for more detailed instructions.\n *\n * @author Hugo Peek\n */\n\n$string = $input;\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Create a list with all available snippets\n$snippetList = $modx->runSnippet('Rowboat', (array(\n    'table' => 'modx_site_snippets',\n    'tpl' => 'rawName',\n    'limit' => '0',\n    'columns' => '{ \"name\":\"\" }',\n    'outputSeparator' => '|'\n)\n));\n\n// Find included snippets by comparing them to the list\n$regex = '\"(' . $snippetList . ')\"';\n\n// Set idx start value to something high, to prevent overlap\n$idx = 1000;\n\n// Define output array\n$output = array();\n\nif (preg_match_all($regex, $string, $matches)) {\n    foreach ($matches as $snippet) {\n        $match = $snippet;\n    }\n\n    // Remove duplicates\n    $result = array_unique($match);\n\n    // Process matches individually\n    foreach ($result as $name) {\n        // Also fetch category, to help ensure the correct resource is being linked\n        $query = $modx->newQuery('modSnippet', array(\n            'name' => $name\n        ));\n        $query->select('category');\n        $category = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'category' => $category,\n            'idx' => $idx\n        ));\n    }\n}\n\nsort($output);\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:40:"romanesco.includedsnippets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:41:"romanesco.includedsnippets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * includedSnippets\n *\n * This snippet is intended to list the snippets that are being called\n * inside a given chunk. It needs the raw content of the chunk as input.\n *\n * See includedChunks for more detailed instructions.\n *\n * @author Hugo Peek\n */\n\n$string = $input;\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Create a list with all available snippets\n$snippetList = $modx->runSnippet('Rowboat', (array(\n    'table' => 'modx_site_snippets',\n    'tpl' => 'rawName',\n    'limit' => '0',\n    'columns' => '{ \"name\":\"\" }',\n    'outputSeparator' => '|'\n)\n));\n\n// Find included snippets by comparing them to the list\n$regex = '\"(' . $snippetList . ')\"';\n\n// Set idx start value to something high, to prevent overlap\n$idx = 1000;\n\n// Define output array\n$output = array();\n\nif (preg_match_all($regex, $string, $matches)) {\n    foreach ($matches as $snippet) {\n        $match = $snippet;\n    }\n\n    // Remove duplicates\n    $result = array_unique($match);\n\n    // Process matches individually\n    foreach ($result as $name) {\n        // Also fetch category, to help ensure the correct resource is being linked\n        $query = $modx->newQuery('modSnippet', array(\n            'name' => $name\n        ));\n        $query->select('category');\n        $category = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'category' => $category,\n            'idx' => $idx\n        ));\n    }\n}\n\nsort($output);\n\nreturn implode($output);"

-----


/**
 * includedSnippets
 *
 * This snippet is intended to list the snippets that are being called
 * inside a given chunk. It needs the raw content of the chunk as input.
 *
 * See includedChunks for more detailed instructions.
 *
 * @author Hugo Peek
 */

$string = $input;
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');

// Create a list with all available snippets
$snippetList = $modx->runSnippet('Rowboat', (array(
    'table' => 'modx_site_snippets',
    'tpl' => 'rawName',
    'limit' => '0',
    'columns' => '{ "name":"" }',
    'outputSeparator' => '|'
)
));

// Find included snippets by comparing them to the list
$regex = '"(' . $snippetList . ')"';

// Set idx start value to something high, to prevent overlap
$idx = 1000;

// Define output array
$output = array();

if (preg_match_all($regex, $string, $matches)) {
    foreach ($matches as $snippet) {
        $match = $snippet;
    }

    // Remove duplicates
    $result = array_unique($match);

    // Process matches individually
    foreach ($result as $name) {
        // Also fetch category, to help ensure the correct resource is being linked
        $query = $modx->newQuery('modSnippet', array(
            'name' => $name
        ));
        $query->select('category');
        $category = $modx->getValue($query->prepare());

        // Up idx value by 1, so a unique placeholder can be created
        $idx++;

        // Output to a chunk that contains the link generator
        $output[] = $modx->getChunk($tpl, array(
            'name' => $name,
            'category' => $category,
            'idx' => $idx
        ));
    }
}

sort($output);

return implode($output);