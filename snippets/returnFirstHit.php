id: 149
name: returnFirstHit
description: "Feed it a bunch of properties, and it spits out the first one that's not empty. Property names are irrelevant. Sort order is all that matters."
category: f_basic
snippet: "/**\n * returnFirstHit snippet\n *\n * Feed it a bunch of properties, and it spits out the first one that's not empty.\n * Property names are irrelevant. Sort order is all that matters.\n *\n * [[!returnFirstHit?\n *     &1=`[[+redirect_id]]`\n *     &2=`[[+next_step]]`\n *     &3=`[[*fb_redirect_dynamic]]`\n *     &4=`[[*fb_redirect_id]]`\n *     &default=`Nothing there!`\n * ]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n// Avoid hitting snippet properties\nunset($scriptProperties['toPlaceholder']);\nunset($scriptProperties['elementExample']);\nunset($scriptProperties['elementStatus']);\n\n$output = '';\nforeach ($scriptProperties as $key => $value) {\n    if ($value) {\n        $output = $value;\n        break;\n    }\n}\nif ($placeholder) {\n    $modx->setPlaceholder($placeholder, $output);\n    return '';\n}\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * returnFirstHit snippet
 *
 * Feed it a bunch of properties, and it spits out the first one that's not empty.
 * Property names are irrelevant. Sort order is all that matters.
 *
 * [[!returnFirstHit?
 *     &1=`[[+redirect_id]]`
 *     &2=`[[+next_step]]`
 *     &3=`[[*fb_redirect_dynamic]]`
 *     &4=`[[*fb_redirect_id]]`
 *     &default=`Nothing there!`
 * ]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Avoid hitting snippet properties
unset($scriptProperties['toPlaceholder']);
unset($scriptProperties['elementExample']);
unset($scriptProperties['elementStatus']);

$output = '';
foreach ($scriptProperties as $key => $value) {
    if ($value) {
        $output = $value;
        break;
    }
}
if ($placeholder) {
    $modx->setPlaceholder($placeholder, $output);
    return '';
}
return $output;