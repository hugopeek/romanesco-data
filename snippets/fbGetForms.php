id: 57
name: fbGetForms
category: f_formblocks
snippet: "/**\n * fbGetForms snippet\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nif (!($modx->resource instanceof modResource)) return;\n\n$context = $modx->getContext($modx->resource->get('context_key'));\n$contextKey = $context->get('key');\n$contextName = $context->get('name');\n$parentID = $context->getOption('formblocks.container_id') ?? $modx->getOption('formblocks.container_id');\n$sortBy = $modx->getOption('formblocks.manager_sortby', $scriptProperties, 'menuindex');\n\n$output = $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 0,\n    'depth' => 2,\n    'showHidden' => 0,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE ['.$contextName.'] [[+pagetitle]]=[[+id]]',\n    'sortby' => $sortBy,\n    'sortdir' => 'ASC',\n    'where' => '[{\"template:IN\":[10,19]},{\"uri:LIKE\":\"%/'.$contextKey.'/%\"}]',\n)));\nif ($output) {\n    $output .= \"\\n\";\n}\n$output .= $modx->runSnippet('getResources', (array(\n    'parents' => $parentID,\n    'limit' => 0,\n    'depth' => 0,\n    'showHidden' => 0,\n    'showUnpublished' => 1,\n    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]',\n    'sortby' => $sortBy,\n    'sortdir' => 'ASC',\n    'where' => '{\"template:IN\":[10,19]}',\n)));\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbGetForms snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

if (!($modx->resource instanceof modResource)) return;

$context = $modx->getContext($modx->resource->get('context_key'));
$contextKey = $context->get('key');
$contextName = $context->get('name');
$parentID = $context->getOption('formblocks.container_id') ?? $modx->getOption('formblocks.container_id');
$sortBy = $modx->getOption('formblocks.manager_sortby', $scriptProperties, 'menuindex');

$output = $modx->runSnippet('getResources', (array(
    'parents' => $parentID,
    'limit' => 0,
    'depth' => 2,
    'showHidden' => 0,
    'showUnpublished' => 1,
    'tpl' => '@INLINE ['.$contextName.'] [[+pagetitle]]=[[+id]]',
    'sortby' => $sortBy,
    'sortdir' => 'ASC',
    'where' => '[{"template:IN":[10,19]},{"uri:LIKE":"%/'.$contextKey.'/%"}]',
)));
if ($output) {
    $output .= "\n";
}
$output .= $modx->runSnippet('getResources', (array(
    'parents' => $parentID,
    'limit' => 0,
    'depth' => 0,
    'showHidden' => 0,
    'showUnpublished' => 1,
    'tpl' => '@INLINE [[+pagetitle]]=[[+id]]',
    'sortby' => $sortBy,
    'sortdir' => 'ASC',
    'where' => '{"template:IN":[10,19]}',
)));

return $output;