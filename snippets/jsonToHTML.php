id: 102
name: jsonToHTML
description: 'Turn a JSON object into an HTML table. For documentation purposes.'
category: f_json
snippet: "/**\n * jsonToHTML\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n// @todo: write documentation and use chunks for the HTML templating\n\n$json = $modx->getOption('json', $scriptProperties, '');\n//$filterKeys = $modx->getOption('filterKeys', $scriptProperties, 'template,process_tags,field_is_exposed');\n//$filterKeys = $modx->getOption('filterKeys', $scriptProperties, '\"template\",\"process_tags\",\"field_is_exposed\"');\n\nif (!$json) return '';\n\n$jsonArray = json_decode($json, true);\n//$filterArray = explode(',', $filterKeys);\n\nif (!function_exists('jsonToHTML')) {\n    function jsonToHTML($array = array(), $tdClass = 'top level') {\n        $output = '<table class=\"ui compact very basic table\"><tbody>';\n\n        $filterKeys = array(\"templates\",\"process_tags\",\"field_is_exposed\");\n\n        foreach ($array as $key => $value) {\n            if ($value == 'icon_class') break;\n            if ($value == 'divider_icon_class') break;\n\n            // Exclude unwanted keys and keys with an empty value from result\n            // When not set to 'true', the first item in the array will always be excluded\n            if (in_array($key, $filterKeys, true) == false && $value != false) {\n                $output .= \"<tr class='top aligned'>\";\n                $output .= \"<td class='$tdClass'><strong>$key</strong></td>\";\n                $output .= \"<td>\";\n                if (is_array($value)) {\n                    $output .= jsonToHTML($value, 'five wide');\n                } else {\n                    $output .= $value;\n                }\n                $output .= \"</td></tr>\";\n            }\n        }\n\n        $output .= \"</tbody></table>\";\n\n        return $output;\n    }\n}\n\nreturn (jsonToHTML($jsonArray));"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.jsontohtml.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.jsontohtml.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * jsonToHTML
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

// @todo: write documentation and use chunks for the HTML templating

$json = $modx->getOption('json', $scriptProperties, '');
//$filterKeys = $modx->getOption('filterKeys', $scriptProperties, 'template,process_tags,field_is_exposed');
//$filterKeys = $modx->getOption('filterKeys', $scriptProperties, '"template","process_tags","field_is_exposed"');

if (!$json) return '';

$jsonArray = json_decode($json, true);
//$filterArray = explode(',', $filterKeys);

if (!function_exists('jsonToHTML')) {
    function jsonToHTML($array = array(), $tdClass = 'top level') {
        $output = '<table class="ui compact very basic table"><tbody>';

        $filterKeys = array("templates","process_tags","field_is_exposed");

        foreach ($array as $key => $value) {
            if ($value == 'icon_class') break;
            if ($value == 'divider_icon_class') break;

            // Exclude unwanted keys and keys with an empty value from result
            // When not set to 'true', the first item in the array will always be excluded
            if (in_array($key, $filterKeys, true) == false && $value != false) {
                $output .= "<tr class='top aligned'>";
                $output .= "<td class='$tdClass'><strong>$key</strong></td>";
                $output .= "<td>";
                if (is_array($value)) {
                    $output .= jsonToHTML($value, 'five wide');
                } else {
                    $output .= $value;
                }
                $output .= "</td></tr>";
            }
        }

        $output .= "</tbody></table>";

        return $output;
    }
}

return (jsonToHTML($jsonArray));