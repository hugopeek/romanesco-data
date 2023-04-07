id: 116
name: fbInputRepeater
description: 'Parse the HTML output of repeating form fields and remember the submitted values of each field, in case the page is reloaded.'
category: f_formblocks
snippet: "/**\n * fbInputRepeater snippet\n *\n * Repeating inputs are form fields (either single fields, or a combination) that\n * can be added dynamically below each existing row. You can repeat each row as many\n * times as you like, and even use multiple repeaters in the same form.\n *\n * This snippet parses the HTML code in the form and remembers the submitted values if the\n * page is reloaded. It's an adoption of Goldsky's ingenious fiDynamicFields snippet.\n *\n * Usage example:\n *\n * [[!fbInputRepeater?\n *    &itemTpl=`fbInputProductRow`\n *    &wrapperTpl=`fbInputProduct`\n *    &identifier=`product`\n * ]]\n *\n * @link http://www.virtudraft.com/blog/dynamic-fields-in-multiple-pages-for-formit.html\n * @author goldsky <goldsky@virtudraft.com>\n * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3\n */\n\n/**\n * &itemTpl=`yourRowTpl`\n * Chunk to be used as template for each row.\n */\n$itemTpl = $modx->getOption('itemTpl', $scriptProperties);\n\n/**\n * (optional)\n * &wrapperTpl=`yourWrapperTpl`\n * Chunk to wrap rendered items and place the javascript code.\n * You can leave this empty/unused if the javascript code is added directly in the template.\n */\n$wrapperTpl = $modx->getOption('wrapperTpl', $scriptProperties);\n\nif (empty($itemTpl)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[fbInputRepeater] is missing &itemTpl');\n    return;\n}\n\n/**\n * To prevent collisions when using multiple repeater fields in a single form,\n * use a unique identifier in each field name and list it in the snippet call.\n *\n * In HTML, that would be something like this (using a custom 'product' identifier):\n *\n * <input name=\"product-amount[]\" type=\"number\" value=\"[[!+fb_repeater.product-amount]]\">\n *\n * You can also use the default 'repeating' identifier in your field names if you're\n * only using a single repeater field in your form.\n *\n * And in any case: make sure to include the square brackets[] at the end of each\n * field name. This tells the snippet to expect an array.\n */\n$identifier = $modx->getOption('identifier', $scriptProperties, 'repeating');\n\n/**\n * If you still have issues after that, changing the prefix is your second line of defence.\n */\n$prefix = $modx->getOption('prefix', $scriptProperties, 'fb_repeater.');\n\nif (!isset($_POST)) {\n    return;\n}\n\n$reverting = array();\nforeach ($_POST as $k => $v) {\n    if (!is_array($v)) {\n        continue;\n    }\n    if (stripos($k, $identifier) !== false) {\n        $i = 0;\n        foreach ($v as $key => $val) {\n            $reverting[$key][$prefix.$identifier.'-idx'] = $i;\n            $reverting[$key][$prefix.$k] = $val;\n            $i++;\n        }\n    }\n}\n\n$items = array();\nforeach ($reverting as $phs) {\n    $items[] = $modx->getChunk($itemTpl, $phs);\n}\n\nif (empty($wrapperTpl)) {\n    $output = implode(\"\\n\", $items);\n} else {\n    $wrapper = array(\n        $prefix.'items' => implode(\"\\n\", $items),\n    );\n    $output = $modx->getChunk($wrapperTpl, $wrapper);\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.fbinputrepeater.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:40:"romanesco.fbinputrepeater.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbInputRepeater snippet
 *
 * Repeating inputs are form fields (either single fields, or a combination) that
 * can be added dynamically below each existing row. You can repeat each row as many
 * times as you like, and even use multiple repeaters in the same form.
 *
 * This snippet parses the HTML code in the form and remembers the submitted values if the
 * page is reloaded. It's an adoption of Goldsky's ingenious fiDynamicFields snippet.
 *
 * Usage example:
 *
 * [[!fbInputRepeater?
 *    &itemTpl=`fbInputProductRow`
 *    &wrapperTpl=`fbInputProduct`
 *    &identifier=`product`
 * ]]
 *
 * @link http://www.virtudraft.com/blog/dynamic-fields-in-multiple-pages-for-formit.html
 * @author goldsky <goldsky@virtudraft.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

/**
 * &itemTpl=`yourRowTpl`
 * Chunk to be used as template for each row.
 */
$itemTpl = $modx->getOption('itemTpl', $scriptProperties);

/**
 * (optional)
 * &wrapperTpl=`yourWrapperTpl`
 * Chunk to wrap rendered items and place the javascript code.
 * You can leave this empty/unused if the javascript code is added directly in the template.
 */
$wrapperTpl = $modx->getOption('wrapperTpl', $scriptProperties);

if (empty($itemTpl)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[fbInputRepeater] is missing &itemTpl');
    return;
}

/**
 * To prevent collisions when using multiple repeater fields in a single form,
 * use a unique identifier in each field name and list it in the snippet call.
 *
 * In HTML, that would be something like this (using a custom 'product' identifier):
 *
 * <input name="product-amount[]" type="number" value="[[!+fb_repeater.product-amount]]">
 *
 * You can also use the default 'repeating' identifier in your field names if you're
 * only using a single repeater field in your form.
 *
 * And in any case: make sure to include the square brackets[] at the end of each
 * field name. This tells the snippet to expect an array.
 */
$identifier = $modx->getOption('identifier', $scriptProperties, 'repeating');

/**
 * If you still have issues after that, changing the prefix is your second line of defence.
 */
$prefix = $modx->getOption('prefix', $scriptProperties, 'fb_repeater.');

if (!isset($_POST)) {
    return;
}

$reverting = array();
foreach ($_POST as $k => $v) {
    if (!is_array($v)) {
        continue;
    }
    if (stripos($k, $identifier) !== false) {
        $i = 0;
        foreach ($v as $key => $val) {
            $reverting[$key][$prefix.$identifier.'-idx'] = $i;
            $reverting[$key][$prefix.$k] = $val;
            $i++;
        }
    }
}

$items = array();
foreach ($reverting as $phs) {
    $items[] = $modx->getChunk($itemTpl, $phs);
}

if (empty($wrapperTpl)) {
    $output = implode("\n", $items);
} else {
    $wrapper = array(
        $prefix.'items' => implode("\n", $items),
    );
    $output = $modx->getChunk($wrapperTpl, $wrapper);
}

return $output;