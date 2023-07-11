id: 97
name: includedChunks
category: f_hub
snippet: "/**\n * includedChunks\n *\n * This snippet is intended to list the chunks that are being used\n * inside another chunk. It needs the raw content of the chunk as input.\n * A regular chunk call won't work, since the referenced chunks have\n * already been parsed there.\n *\n * You can get the raw input by looking directly in the database table\n * of the chunk, using Rowboat for example:\n *\n * [[!Rowboat:toPlaceholder=`raw_chunk`?\n *     &table=`modx_site_htmlsnippets`\n *     &tpl=`displayRawElement`\n *     &where=`{\"name\":\"overviewRowBasic\"}`\n * ]]\n *\n * Then scan the raw input for included chunks like this:\n *\n * [[!includedChunks? &input=`[[+raw_chunk]]`]]\n *\n * If you want to see which chunks have references to a specific chunk\n * (the reverse thing, basically), you can use Rowboat again:\n *\n * [[Rowboat?\n *     &table=`modx_site_htmlsnippets`\n *     &tpl=`includedPatternsRow`\n *     &sortBy=`name`\n *     &where=`{ \"snippet:LIKE\":\"%$buttonHrefOverview%\" }`\n * ]]\n *\n * This is not entirely accurate though, since a reference to a chunk\n * called something like 'buttonHrefOverviewBasic' will also be listed\n * in the results.\n *\n * @author Hugo Peek\n */\n\n$string = $modx->getOption('input', $scriptProperties, '');\n$patternID = $modx->getOption('id', $scriptProperties, '');\n$patternName = $modx->getOption('name', $scriptProperties, '');\n$patternType = $modx->getOption('type', $scriptProperties, '');\n$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');\n\n// Finding chunks inside snippets only result in a lot of false positives, so let's disable that for now\n// @todo: Create a different pattern for finding chunks inside snippets\nif (stripos($patternType, 'formula')) {\n    return '';\n}\n\n// Find chunk names by their leading $ character or '&tpl' string\n$regex = '/((?<!\\w)\\&amp;tpl=&#96;\\w+|(?<!\\w)\\$\\w+)/';\n\n// Set idx start value\n$idx = 0;\n\n// Define output array\n$output = array();\n\nif (preg_match_all($regex, $string, $matches)) {\n    // Remove prefix from all matches\n    foreach ($matches as $match) {\n        $match = str_replace('$', '', $match);\n        $match = str_replace('&amp;tpl=&#96;', '', $match);\n    }\n\n    //print_r($match);\n\n    // Remove duplicates\n    $result = array_unique($match);\n\n    // Process matches individually\n    foreach ($result as $name) {\n        // Also fetch category, to help ensure the correct resource is being linked\n        $query = $modx->newQuery('modChunk', array(\n            'name' => $name\n        ));\n        $query->select('category');\n        $category = $modx->getValue($query->prepare());\n\n        // Up idx value by 1, so a unique placeholder can be created\n        $idx++;\n\n        // Output to a chunk that contains the link generator\n        $output[] = $modx->getChunk($tpl, array(\n            'name' => $name,\n            'category' => $category,\n            'idx' => $idx\n        ));\n    }\n}\n\n// If this pattern is a CB field with input type Chunk, then let's find that chunk\nif (stripos($patternType, 'bosonfield') && $patternID) {\n    $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n    $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n\n    // First, let's check if this field contains a chunk ID\n    $result = $modx->getObject('cbField', array(\n        'id' => $patternID,\n        'properties:LIKE' => '%\"chunk\":\"%'\n    ));\n\n    // Do we have a winner?\n    if ($result) {\n        $properties = $result->get('properties');\n        $array = json_decode($properties, true);\n\n        $chunkID = $array['chunk'] ?? '';\n\n        $chunk = $modx->getObject('modChunk', array(\n            'id' => $chunkID\n        ));\n\n        $idx++;\n\n        if ($chunk) {\n            $output[] = $modx->getChunk($tpl, array(\n                'name' => $chunk->get('name'),\n                'category' => $chunk->get('category'),\n                'label_classes' => 'blue',\n                'assigned' => 1,\n                'idx' => $idx\n            ));\n        }\n    }\n\n    // No? Then maybe it's a chunk selector\n    if (!$result) {\n        $result = $modx->getObject('cbField', array(\n            'id' => $patternID,\n            'properties:LIKE' => '%\"available_chunks\":\"%'\n        ));\n\n        if (is_object($result)) {\n            $properties = $result->get('properties');\n            $array = json_decode($properties, true);\n\n            $chunks = $array['available_chunks'] ?? '';\n            $result = explode(',', $chunks);\n\n            foreach ($result as $name) {\n                // Also fetch category, to help ensure the correct resource is being linked\n                $query = $modx->newQuery('modChunk', array(\n                    'name' => $name\n                ));\n                $query->select('category');\n                $category = $modx->getValue($query->prepare());\n\n                $idx++;\n\n                $output[] = $modx->getChunk($tpl, array(\n                    'name' => $name,\n                    'category' => $category,\n                    'label_classes' => 'blue',\n                    'assigned' => 1,\n                    'idx' => $idx\n                ));\n            }\n        }\n    }\n}\n\n// No idea how it sorts the result, but seems better than the default\nsort($output);\n\nreturn implode($output);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.includedchunks.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.includedchunks.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * includedChunks
 *
 * This snippet is intended to list the chunks that are being used
 * inside another chunk. It needs the raw content of the chunk as input.
 * A regular chunk call won't work, since the referenced chunks have
 * already been parsed there.
 *
 * You can get the raw input by looking directly in the database table
 * of the chunk, using Rowboat for example:
 *
 * [[!Rowboat:toPlaceholder=`raw_chunk`?
 *     &table=`modx_site_htmlsnippets`
 *     &tpl=`displayRawElement`
 *     &where=`{"name":"overviewRowBasic"}`
 * ]]
 *
 * Then scan the raw input for included chunks like this:
 *
 * [[!includedChunks? &input=`[[+raw_chunk]]`]]
 *
 * If you want to see which chunks have references to a specific chunk
 * (the reverse thing, basically), you can use Rowboat again:
 *
 * [[Rowboat?
 *     &table=`modx_site_htmlsnippets`
 *     &tpl=`includedPatternsRow`
 *     &sortBy=`name`
 *     &where=`{ "snippet:LIKE":"%$buttonHrefOverview%" }`
 * ]]
 *
 * This is not entirely accurate though, since a reference to a chunk
 * called something like 'buttonHrefOverviewBasic' will also be listed
 * in the results.
 *
 * @author Hugo Peek
 */

$string = $modx->getOption('input', $scriptProperties, '');
$patternID = $modx->getOption('id', $scriptProperties, '');
$patternName = $modx->getOption('name', $scriptProperties, '');
$patternType = $modx->getOption('type', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'includedPatternsRow');

// Finding chunks inside snippets only result in a lot of false positives, so let's disable that for now
// @todo: Create a different pattern for finding chunks inside snippets
if (stripos($patternType, 'formula')) {
    return '';
}

// Find chunk names by their leading $ character or '&tpl' string
$regex = '/((?<!\w)\&amp;tpl=&#96;\w+|(?<!\w)\$\w+)/';

// Set idx start value
$idx = 0;

// Define output array
$output = array();

if (preg_match_all($regex, $string, $matches)) {
    // Remove prefix from all matches
    foreach ($matches as $match) {
        $match = str_replace('$', '', $match);
        $match = str_replace('&amp;tpl=&#96;', '', $match);
    }

    //print_r($match);

    // Remove duplicates
    $result = array_unique($match);

    // Process matches individually
    foreach ($result as $name) {
        // Also fetch category, to help ensure the correct resource is being linked
        $query = $modx->newQuery('modChunk', array(
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

// If this pattern is a CB field with input type Chunk, then let's find that chunk
if (stripos($patternType, 'bosonfield') && $patternID) {
    $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
    $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');

    // First, let's check if this field contains a chunk ID
    $result = $modx->getObject('cbField', array(
        'id' => $patternID,
        'properties:LIKE' => '%"chunk":"%'
    ));

    // Do we have a winner?
    if ($result) {
        $properties = $result->get('properties');
        $array = json_decode($properties, true);

        $chunkID = $array['chunk'] ?? '';

        $chunk = $modx->getObject('modChunk', array(
            'id' => $chunkID
        ));

        $idx++;

        if ($chunk) {
            $output[] = $modx->getChunk($tpl, array(
                'name' => $chunk->get('name'),
                'category' => $chunk->get('category'),
                'label_classes' => 'blue',
                'assigned' => 1,
                'idx' => $idx
            ));
        }
    }

    // No? Then maybe it's a chunk selector
    if (!$result) {
        $result = $modx->getObject('cbField', array(
            'id' => $patternID,
            'properties:LIKE' => '%"available_chunks":"%'
        ));

        if (is_object($result)) {
            $properties = $result->get('properties');
            $array = json_decode($properties, true);

            $chunks = $array['available_chunks'] ?? '';
            $result = explode(',', $chunks);

            foreach ($result as $name) {
                // Also fetch category, to help ensure the correct resource is being linked
                $query = $modx->newQuery('modChunk', array(
                    'name' => $name
                ));
                $query->select('category');
                $category = $modx->getValue($query->prepare());

                $idx++;

                $output[] = $modx->getChunk($tpl, array(
                    'name' => $name,
                    'category' => $category,
                    'label_classes' => 'blue',
                    'assigned' => 1,
                    'idx' => $idx
                ));
            }
        }
    }
}

// No idea how it sorts the result, but seems better than the default
sort($output);

return implode($output);