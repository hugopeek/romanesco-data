id: 57
name: fbGetForms
category: f_formblocks
snippet: "$parentID = $modx->getOption('formblocks.container_id');\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]'\n    )\n));\n\nreturn $output;"
properties: 'a:0:{}'
content: "$parentID = $modx->getOption('formblocks.container_id');\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 99,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]'\n    )\n));\n\nreturn $output;"

-----


$parentID = $modx->getOption('formblocks.container_id');

$output = $modx->runSnippet('getResources', (array(
    'parents' => $parentID,
    'limit' => 99,
    'showHidden' => 1,
    'showUnpublished' => 1,
    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]'
    )
));

return $output;