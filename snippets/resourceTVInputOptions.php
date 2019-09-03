id: 70
name: resourceTVInputOptions
description: 'Generate input option values that can be used in TVs, for creating resource selectors.'
category: f_resources
snippet: "/**\n * resourceTVInputOptions\n *\n * @todo: Is this snippet used anywhere?\n *\n * Grab list of resources from given parent. Parent ID can be provided directly\n * (with &parents) or via a context or system setting (&key).\n *\n * For use in ContentBlocks settings, add &tplMode=`cb`.\n */\n\n$key = $modx->getOption('key',$scriptProperties,null);\n$context = $modx->getContext($modx->resource->get('context_key'));\n$parents = $context->getOption($key) ?? $modx->getOption('parents',$scriptProperties,$key);\n\n$tpl = $modx->getOption('tpl',$scriptProperties,'');\n$tplMode = $modx->getOption('tplMode',$scriptProperties,'tv');\n$separator = $modx->getOption('outputSeparator',$scriptProperties,null);\n\n// Set appropriate template if no custom tpl is defined\nif (!$tpl && $tplMode == 'cb') {\n    $tpl = '@INLINE [[+pagetitle]]=[[+id]]';\n}\nif (!$tpl && $tplMode == 'tv') {\n    $tpl = '@INLINE [[+pagetitle]]==[[+id]]';\n    $separator = '||';\n}\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parents,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => $tpl,\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n    'outputSeparator' => $separator,\n)));\n\nreturn $output;"
properties: 'a:0:{}'
content: "/**\n * resourceTVInputOptions\n *\n * @todo: Is this snippet used anywhere?\n *\n * Grab list of resources from given parent. Parent ID can be provided directly\n * (with &parents) or via a context or system setting (&key).\n *\n * For use in ContentBlocks settings, add &tplMode=`cb`.\n */\n\n$key = $modx->getOption('key',$scriptProperties,null);\n$context = $modx->getContext($modx->resource->get('context_key'));\n$parents = $context->getOption($key) ?? $modx->getOption('parents',$scriptProperties,$key);\n\n$tpl = $modx->getOption('tpl',$scriptProperties,'');\n$tplMode = $modx->getOption('tplMode',$scriptProperties,'tv');\n$separator = $modx->getOption('outputSeparator',$scriptProperties,null);\n\n// Set appropriate template if no custom tpl is defined\nif (!$tpl && $tplMode == 'cb') {\n    $tpl = '@INLINE [[+pagetitle]]=[[+id]]';\n}\nif (!$tpl && $tplMode == 'tv') {\n    $tpl = '@INLINE [[+pagetitle]]==[[+id]]';\n    $separator = '||';\n}\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parents,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => $tpl,\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n    'outputSeparator' => $separator,\n)));\n\nreturn $output;"

-----


/**
 * resourceTVInputOptions
 *
 * @todo: Is this snippet used anywhere?
 *
 * Grab list of resources from given parent. Parent ID can be provided directly
 * (with &parents) or via a context or system setting (&key).
 *
 * For use in ContentBlocks settings, add &tplMode=`cb`.
 */

$key = $modx->getOption('key',$scriptProperties,null);
$context = $modx->getContext($modx->resource->get('context_key'));
$parents = $context->getOption($key) ?? $modx->getOption('parents',$scriptProperties,$key);

$tpl = $modx->getOption('tpl',$scriptProperties,'');
$tplMode = $modx->getOption('tplMode',$scriptProperties,'tv');
$separator = $modx->getOption('outputSeparator',$scriptProperties,null);

// Set appropriate template if no custom tpl is defined
if (!$tpl && $tplMode == 'cb') {
    $tpl = '@INLINE [[+pagetitle]]=[[+id]]';
}
if (!$tpl && $tplMode == 'tv') {
    $tpl = '@INLINE [[+pagetitle]]==[[+id]]';
    $separator = '||';
}

$output = $modx->runSnippet('getResources', (array(
    'parents' => $parents,
    'limit' => 99,
    'showHidden' => 1,
    'showUnpublished' => 1,
    'tpl' => $tpl,
    'sortby' => 'menuindex',
    'sortdir' => 'ASC',
    'outputSeparator' => $separator,
)));

return $output;