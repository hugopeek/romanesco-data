id: 135
name: renderInputOption
description: 'Fetch option name from the romanesco_options table (when used as output modifier). You can also get other available fields with a regular snippet call and tpl chunk.'
category: f_toolshed
snippet: "/**\n * renderInputOption\n *\n * Fetch option name from the romanesco_options table (when used as output\n * modifier). You can also get other available fields with a regular snippet\n * call and tpl chunk.\n *\n * Use as output modifier:\n *\n * [[+status_progress:renderInputOption]]\n *\n * Choose whether to match by something other than the default ID:\n *\n * [[+status_progress:renderInputOption=`alias`]]\n *\n * Use as regular snippet, with tpl and key to restrict search results by:\n *\n * [[renderInputOption?\n *     &value=`[[+status_progress]]`\n *     &match=`alias`\n *     &key=`status_progress`\n *     &tpl=`tagItemTooltip`\n * ]]\n *\n * Available fields in tpl:\n *\n * [[+id]]\n * [[+name]]\n * [[+alias]]\n * [[+description]]\n * [[+parent]]\n * [[+key]]\n * [[+position]]\n */\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$backyard = $modx->addPackage('romanescobackyard',$corePath . 'model/');\n\n$value = $modx->getOption('value', $scriptProperties, $input);\n$match = $modx->getOption('match', $scriptProperties, $options);\n$key = $modx->getOption('key', $scriptProperties, '');\n$select = $modx->getOption('select', $scriptProperties, 'name');\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');\n\nif (!function_exists('getInputOption')) {\n    function getInputOption($value,$match,$key,$select,$tpl){\n        global $modx;\n\n        $inputOption = $modx->getObject('rmOption', array(\n            $match => $value,\n            'key' => $key,\n        ));\n        $outputFields = array(\n            'id' => $inputOption->get('id'),\n            'name' => $inputOption->get('name'),\n            'title' => $inputOption->get('name'),\n            'tag' => $inputOption->get('name'),\n            'alias' => $inputOption->get('alias'),\n            'description' => $inputOption->get('description'),\n            'parent' => $inputOption->get('parent'),\n            'group' => $inputOption->get('parent'),\n            'key' => $inputOption->get('key'),\n            'position' => $inputOption->get('position'),\n        );\n\n        if ($tpl) {\n            $output = $modx->getChunk($tpl, $outputFields);\n        } else {\n            $output = $inputOption->get($select);\n        }\n\n        return $output;\n    }\n}\n\nif ($value == '') { return ''; }\n\n// Find matching ID by default\nif (!$match) { $match = 'id'; }\n\n// Don't fetch entire object if it's being used as output modifier\nif ($input) {\n    $query = $modx->newQuery('rmOption');\n    $query->where(array(\n        $match => $value,\n    ));\n    $query->select($select);\n\n    return $modx->getValue($query->prepare());\n}\n\n// Value can be an array as well\nelse if (strpos($value,',')) {\n    $values = explode(',',$value);\n\n    foreach ($values as $value) {\n        $output[] = getInputOption($value,$match,$key,$select,$tpl);\n    }\n    return implode($outputSeparator,$output);\n}\n\nelse {\n    return getInputOption($value,$match,$key,$select,$tpl);\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.renderinputoption.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.renderinputoption.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * renderInputOption\n *\n * Fetch option name from the romanesco_options table (when used as output\n * modifier). You can also get other available fields with a regular snippet\n * call and tpl chunk.\n *\n * Use as output modifier:\n *\n * [[+status_progress:renderInputOption]]\n *\n * Choose whether to match by something other than the default ID:\n *\n * [[+status_progress:renderInputOption=`alias`]]\n *\n * Use as regular snippet, with tpl and key to restrict search results by:\n *\n * [[renderInputOption?\n *     &value=`[[+status_progress]]`\n *     &match=`alias`\n *     &key=`status_progress`\n *     &tpl=`tagItemTooltip`\n * ]]\n *\n * Available fields in tpl:\n *\n * [[+id]]\n * [[+name]]\n * [[+alias]]\n * [[+description]]\n * [[+parent]]\n * [[+key]]\n * [[+position]]\n */\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$backyard = $modx->addPackage('romanescobackyard',$corePath . 'model/');\n\n$value = $modx->getOption('value', $scriptProperties, $input);\n$match = $modx->getOption('match', $scriptProperties, $options);\n$key = $modx->getOption('key', $scriptProperties, '');\n$select = $modx->getOption('select', $scriptProperties, 'name');\n$tpl = $modx->getOption('tpl', $scriptProperties, '');\n$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');\n\nif (!function_exists('getInputOption')) {\n    function getInputOption($value,$match,$key,$select,$tpl){\n        global $modx;\n\n        $inputOption = $modx->getObject('rmOption', array(\n            $match => $value,\n            'key' => $key,\n        ));\n        $outputFields = array(\n            'id' => $inputOption->get('id'),\n            'name' => $inputOption->get('name'),\n            'title' => $inputOption->get('name'),\n            'tag' => $inputOption->get('name'),\n            'alias' => $inputOption->get('alias'),\n            'description' => $inputOption->get('description'),\n            'parent' => $inputOption->get('parent'),\n            'group' => $inputOption->get('parent'),\n            'key' => $inputOption->get('key'),\n            'position' => $inputOption->get('position'),\n        );\n\n        if ($tpl) {\n            $output = $modx->getChunk($tpl, $outputFields);\n        } else {\n            $output = $inputOption->get($select);\n        }\n\n        return $output;\n    }\n}\n\nif ($value == '') { return ''; }\n\n// Find matching ID by default\nif (!$match) { $match = 'id'; }\n\n// Don't fetch entire object if it's being used as output modifier\nif ($input) {\n    $query = $modx->newQuery('rmOption');\n    $query->where(array(\n        $match => $value,\n    ));\n    $query->select($select);\n\n    return $modx->getValue($query->prepare());\n}\n\n// Value can be an array as well\nelse if (strpos($value,',')) {\n    $values = explode(',',$value);\n\n    foreach ($values as $value) {\n        $output[] = getInputOption($value,$match,$key,$select,$tpl);\n    }\n    return implode($outputSeparator,$output);\n}\n\nelse {\n    return getInputOption($value,$match,$key,$select,$tpl);\n}"

-----


/**
 * renderInputOption
 *
 * Fetch option name from the romanesco_options table (when used as output
 * modifier). You can also get other available fields with a regular snippet
 * call and tpl chunk.
 *
 * Use as output modifier:
 *
 * [[+status_progress:renderInputOption]]
 *
 * Choose whether to match by something other than the default ID:
 *
 * [[+status_progress:renderInputOption=`alias`]]
 *
 * Use as regular snippet, with tpl and key to restrict search results by:
 *
 * [[renderInputOption?
 *     &value=`[[+status_progress]]`
 *     &match=`alias`
 *     &key=`status_progress`
 *     &tpl=`tagItemTooltip`
 * ]]
 *
 * Available fields in tpl:
 *
 * [[+id]]
 * [[+name]]
 * [[+alias]]
 * [[+description]]
 * [[+parent]]
 * [[+key]]
 * [[+position]]
 */
$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$backyard = $modx->addPackage('romanescobackyard',$corePath . 'model/');

$value = $modx->getOption('value', $scriptProperties, $input);
$match = $modx->getOption('match', $scriptProperties, $options);
$key = $modx->getOption('key', $scriptProperties, '');
$select = $modx->getOption('select', $scriptProperties, 'name');
$tpl = $modx->getOption('tpl', $scriptProperties, '');
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');

if (!function_exists('getInputOption')) {
    function getInputOption($value,$match,$key,$select,$tpl){
        global $modx;

        $inputOption = $modx->getObject('rmOption', array(
            $match => $value,
            'key' => $key,
        ));
        $outputFields = array(
            'id' => $inputOption->get('id'),
            'name' => $inputOption->get('name'),
            'title' => $inputOption->get('name'),
            'tag' => $inputOption->get('name'),
            'alias' => $inputOption->get('alias'),
            'description' => $inputOption->get('description'),
            'parent' => $inputOption->get('parent'),
            'group' => $inputOption->get('parent'),
            'key' => $inputOption->get('key'),
            'position' => $inputOption->get('position'),
        );

        if ($tpl) {
            $output = $modx->getChunk($tpl, $outputFields);
        } else {
            $output = $inputOption->get($select);
        }

        return $output;
    }
}

if ($value == '') { return ''; }

// Find matching ID by default
if (!$match) { $match = 'id'; }

// Don't fetch entire object if it's being used as output modifier
if ($input) {
    $query = $modx->newQuery('rmOption');
    $query->where(array(
        $match => $value,
    ));
    $query->select($select);

    return $modx->getValue($query->prepare());
}

// Value can be an array as well
else if (strpos($value,',')) {
    $values = explode(',',$value);

    foreach ($values as $value) {
        $output[] = getInputOption($value,$match,$key,$select,$tpl);
    }
    return implode($outputSeparator,$output);
}

else {
    return getInputOption($value,$match,$key,$select,$tpl);
}