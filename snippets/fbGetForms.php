id: 57
name: fbGetForms
category: f_formblocks
snippet: "$context = $modx->getContext($modx->resource->get('context_key'));\n$parentID = $context->getOption('formblocks.container_id') ?? $modx->getOption('formblocks.container_id');\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]',\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n)));\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.fbgetforms.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.fbgetforms.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "$context = $modx->getContext($modx->resource->get('context_key'));\n$parentID = $context->getOption('formblocks.container_id') ?? $modx->getOption('formblocks.container_id');\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]',\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n)));\n\nreturn $output;"

-----


$context = $modx->getContext($modx->resource->get('context_key'));
$parentID = $context->getOption('formblocks.container_id') ?? $modx->getOption('formblocks.container_id');

$output = $modx->runSnippet('getResources', (array(
    'parents' => $parentID,
    'limit' => 99,
    'showHidden' => 1,
    'showUnpublished' => 1,
    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]',
    'sortby' => 'menuindex',
    'sortdir' => 'ASC',
)));

return $output;