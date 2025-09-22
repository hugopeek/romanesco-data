id: 167
name: renderReferringPages
description: 'Takes an ID as input and returns a list of pages in which this resource is used. Intended as snippet renderer for Collections, to show where Forms, CTAs and Backgrounds are being used.'
category: f_resource
snippet: "/**\n * renderReferringPages\n *\n * Takes an ID as input and returns a list of pages in which this resource is\n * referenced. Intended as snippet renderer for Collections, to show where Forms,\n * CTAs and Backgrounds are being used.\n *\n * Scans content and TVs. Note that for TVs, inherited values are not evaluated.\n *\n * If you want to limit the list to only include pages from certain contexts,\n * you may do so by creating the system referring_pages_contexts in the\n * Romanesco namespace.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$id = $modx->getOption('id', $scriptProperties, $scriptProperties['row']['id'] ?? '');\n$contexts = $modx->getOption('contexts', $scriptProperties, $modx->getOption('romanesco.referring_pages_contexts') ?? '');\n$column = $modx->getOption('column', $scriptProperties);\n\n$where = '';\n\n// Content\nswitch ($column) {\n    case 'referring_pages_form':\n        $where = '{ \"properties:LIKE\":\"%\\\"form_id\\\":\\\"' . $id . '\\\"%\" }';\n        break;\n    case 'referring_pages_cta':\n        $where = '{ \"properties:LIKE\":\"%\\\"cta_id\\\":\\\"' . $id . '\\\"%\" }';\n        break;\n    case 'referring_pages_background':\n        $where = '{ \"properties:LIKE\":\"%background_____' . $id . '__,%\" }';\n        break;\n}\n\nif (!$where) return;\n\n// TVs\n$tvValues = [];\n$tvValuesHead = $modx->getCollection('modTemplateVarResource', [\n    'tmplvarid' => 3, // header_cta\n    'value' => $id\n]);\n$tvValuesFooter = $modx->getCollection('modTemplateVarResource', [\n    'tmplvarid' => 104, // footer_cta\n    'value' => $id\n]);\n$tvValuesSidebar = $modx->getCollection('modTemplateVarResource', [\n    'tmplvarid' => 148, // sidebar_cta\n    'value' => $id\n]);\n\nforeach ($tvValuesHead as $value) {\n    $tvValues[] = $value->get('contentid');\n}\nforeach ($tvValuesFooter as $value) {\n    $tvValues[] = $value->get('contentid');\n}\nforeach ($tvValuesSidebar as $value) {\n    $tvValues[] = $value->get('contentid');\n}\n\nif ($tvValues) {\n    $where .= ',{ \"OR:id:IN\": [' . implode(',', $tvValues) . '] }';\n}\n\n$output = $modx->runSnippet('pdoMenu', (array(\n    'parents' => '',\n    'context' => $contexts,\n    'limit' => 0,\n    'depth' => 0,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tplOuter' => '@INLINE <ul class=\"referring-pages\">[[+wrapper]]</ul>',\n    'tpl' => '@INLINE <li><a href=\"[[~[[+id]]]]\" target=\"_blank\">[[+pagetitle]]</a> (<a title=\"Edit\" href=\"[[++site_url]]manager/?a=resource/update&id=[[+id]]\" target=\"_blank\">[[+id]]</a>)</li>',\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n    'where' => \"[$where]\",\n)));\n\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderReferringPages
 *
 * Takes an ID as input and returns a list of pages in which this resource is
 * referenced. Intended as snippet renderer for Collections, to show where Forms,
 * CTAs and Backgrounds are being used.
 *
 * Scans content and TVs. Note that for TVs, inherited values are not evaluated.
 *
 * If you want to limit the list to only include pages from certain contexts,
 * you may do so by creating the system referring_pages_contexts in the
 * Romanesco namespace.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$id = $modx->getOption('id', $scriptProperties, $scriptProperties['row']['id'] ?? '');
$contexts = $modx->getOption('contexts', $scriptProperties, $modx->getOption('romanesco.referring_pages_contexts') ?? '');
$column = $modx->getOption('column', $scriptProperties);

$where = '';

// Content
switch ($column) {
    case 'referring_pages_form':
        $where = '{ "properties:LIKE":"%\"form_id\":\"' . $id . '\"%" }';
        break;
    case 'referring_pages_cta':
        $where = '{ "properties:LIKE":"%\"cta_id\":\"' . $id . '\"%" }';
        break;
    case 'referring_pages_background':
        $where = '{ "properties:LIKE":"%background_____' . $id . '__,%" }';
        break;
}

if (!$where) return;

// TVs
$tvValues = [];
$tvValuesHead = $modx->getCollection('modTemplateVarResource', [
    'tmplvarid' => 3, // header_cta
    'value' => $id
]);
$tvValuesFooter = $modx->getCollection('modTemplateVarResource', [
    'tmplvarid' => 104, // footer_cta
    'value' => $id
]);
$tvValuesSidebar = $modx->getCollection('modTemplateVarResource', [
    'tmplvarid' => 148, // sidebar_cta
    'value' => $id
]);

foreach ($tvValuesHead as $value) {
    $tvValues[] = $value->get('contentid');
}
foreach ($tvValuesFooter as $value) {
    $tvValues[] = $value->get('contentid');
}
foreach ($tvValuesSidebar as $value) {
    $tvValues[] = $value->get('contentid');
}

if ($tvValues) {
    $where .= ',{ "OR:id:IN": [' . implode(',', $tvValues) . '] }';
}

$output = $modx->runSnippet('pdoMenu', (array(
    'parents' => '',
    'context' => $contexts,
    'limit' => 0,
    'depth' => 0,
    'showHidden' => 1,
    'showUnpublished' => 1,
    'tplOuter' => '@INLINE <ul class="referring-pages">[[+wrapper]]</ul>',
    'tpl' => '@INLINE <li><a href="[[~[[+id]]]]" target="_blank">[[+pagetitle]]</a> (<a title="Edit" href="[[++site_url]]manager/?a=resource/update&id=[[+id]]" target="_blank">[[+id]]</a>)</li>',
    'sortby' => 'menuindex',
    'sortdir' => 'ASC',
    'where' => "[$where]",
)));


return $output;