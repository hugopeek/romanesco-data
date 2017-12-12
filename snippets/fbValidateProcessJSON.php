id: 60
name: fbValidateProcessJSON
category: f_formblocks
snippet: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n */\n\n$input = $modx->getOption('json', $scriptProperties);\n$array = $modx->fromJSON($input);\n$id = $modx->resource->get('id');\n$prefix = !empty($prefix) ? $prefix: 'fb' . $id . '-';\n$emailField = $modx->getOption('emailField', $scriptProperties);\n\n//$jsonString = $modx->getOption('json', $scriptProperties);\n//$array = json_decode($jsonString, true);\n\n// Function to search for required fields in JSON array\nif (!function_exists('search')) {\n    function search($array, $key, $value) {\n        $results = array();\n\n        if (is_array($array)) {\n            if (isset($array[$key]) && $array[$key] == $value) {\n                $results[] = $array;\n            }\n            foreach ($array as $subarray) {\n                $results = array_merge($results, search($subarray, $key, $value));\n            }\n        }\n\n        return $results;\n    }\n}\n\n// Function to strip required field names correctly\n// @todo: Replace this part with modx->runSnippet('fbStripAsAlias');\nif (!function_exists('stripResults')) {\n\n    function stripResults($row) {\n        $row = strip_tags($row); // strip HTML\n        $row = strtolower($row); // convert to lowercase\n        $row = preg_replace('/[^\\A-Za-z0-9 _-]/', '', $row); // strip non-alphanumeric characters\n        $row = preg_replace('/\\s+/', '-', $row); // convert white-space to dash\n        $row = preg_replace('/-+/', '-', $row);  // convert multiple dashes to one\n        $row = trim($row, '-'); // trim excess\n\n        return $row;\n    }\n}\n\n// Go through JSON array and collect all required fields\n$results = search($array, 'field_required', '1');\n\n// Create new array from all required results\n$names = array();\n\n// Generate FormIt validation string for each result\nforeach ($results as $result) {\n    if ($result['field_name'] == $emailField) {\n        $names[] = $emailField . \":email:required,\"; // Untested...\n    } else {\n        $names[] = $prefix . stripResults($result['field_name']) . \":required,\";\n    }\n}\n\nreturn implode('', $names);"
properties: 'a:0:{}'
content: "/**\n * fbValidateProcessJSON\n *\n * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.\n *\n * @author Hugo Peek\n */\n\n$input = $modx->getOption('json', $scriptProperties);\n$array = $modx->fromJSON($input);\n$id = $modx->resource->get('id');\n$prefix = !empty($prefix) ? $prefix: 'fb' . $id . '-';\n$emailField = $modx->getOption('emailField', $scriptProperties);\n\n//$jsonString = $modx->getOption('json', $scriptProperties);\n//$array = json_decode($jsonString, true);\n\n// Function to search for required fields in JSON array\nif (!function_exists('search')) {\n    function search($array, $key, $value) {\n        $results = array();\n\n        if (is_array($array)) {\n            if (isset($array[$key]) && $array[$key] == $value) {\n                $results[] = $array;\n            }\n            foreach ($array as $subarray) {\n                $results = array_merge($results, search($subarray, $key, $value));\n            }\n        }\n\n        return $results;\n    }\n}\n\n// Function to strip required field names correctly\n// @todo: Replace this part with modx->runSnippet('fbStripAsAlias');\nif (!function_exists('stripResults')) {\n\n    function stripResults($row) {\n        $row = strip_tags($row); // strip HTML\n        $row = strtolower($row); // convert to lowercase\n        $row = preg_replace('/[^\\A-Za-z0-9 _-]/', '', $row); // strip non-alphanumeric characters\n        $row = preg_replace('/\\s+/', '-', $row); // convert white-space to dash\n        $row = preg_replace('/-+/', '-', $row);  // convert multiple dashes to one\n        $row = trim($row, '-'); // trim excess\n\n        return $row;\n    }\n}\n\n// Go through JSON array and collect all required fields\n$results = search($array, 'field_required', '1');\n\n// Create new array from all required results\n$names = array();\n\n// Generate FormIt validation string for each result\nforeach ($results as $result) {\n    if ($result['field_name'] == $emailField) {\n        $names[] = $emailField . \":email:required,\"; // Untested...\n    } else {\n        $names[] = $prefix . stripResults($result['field_name']) . \":required,\";\n    }\n}\n\nreturn implode('', $names);"

-----


/**
 * fbValidateProcessJSON
 *
 * A snippet for FormBlocks that generates the correct strings for the FormIt &validate property.
 *
 * @author Hugo Peek
 */

$input = $modx->getOption('json', $scriptProperties);
$array = $modx->fromJSON($input);
$id = $modx->resource->get('id');
$prefix = !empty($prefix) ? $prefix: 'fb' . $id . '-';
$emailField = $modx->getOption('emailField', $scriptProperties);

//$jsonString = $modx->getOption('json', $scriptProperties);
//$array = json_decode($jsonString, true);

// Function to search for required fields in JSON array
if (!function_exists('search')) {
    function search($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, search($subarray, $key, $value));
            }
        }

        return $results;
    }
}

// Function to strip required field names correctly
// @todo: Replace this part with modx->runSnippet('fbStripAsAlias');
if (!function_exists('stripResults')) {

    function stripResults($row) {
        $row = strip_tags($row); // strip HTML
        $row = strtolower($row); // convert to lowercase
        $row = preg_replace('/[^\A-Za-z0-9 _-]/', '', $row); // strip non-alphanumeric characters
        $row = preg_replace('/\s+/', '-', $row); // convert white-space to dash
        $row = preg_replace('/-+/', '-', $row);  // convert multiple dashes to one
        $row = trim($row, '-'); // trim excess

        return $row;
    }
}

// Go through JSON array and collect all required fields
$results = search($array, 'field_required', '1');

// Create new array from all required results
$names = array();

// Generate FormIt validation string for each result
foreach ($results as $result) {
    if ($result['field_name'] == $emailField) {
        $names[] = $emailField . ":email:required,"; // Untested...
    } else {
        $names[] = $prefix . stripResults($result['field_name']) . ":required,";
    }
}

return implode('', $names);