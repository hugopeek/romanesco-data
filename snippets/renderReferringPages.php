id: 167
name: renderReferringPages
description: 'Takes an ID as input and returns a list of pages in which this resource is used. Intended as snippet renderer for Collections, to show where Forms, CTAs and Backgrounds are being used.'
category: f_resource
snippet: "/**\n * renderReferringPages\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$id = $modx->getOption('id', $scriptProperties, $scriptProperties['row']['id'] ?? '');\n$column = $modx->getOption('column', $scriptProperties);\n\n//$modx->log(modX::LOG_LEVEL_ERROR, print_r($scriptProperties,1));\n//$modx->log(modX::LOG_LEVEL_ERROR, $column);\n\n$where = '';\nswitch ($column) {\n    case 'referring_pages_form':\n        $where = '[{ \"properties:LIKE\":\"%\\\"form_id\\\":\\\"' . $id . '\\\"%\" }]';\n        break;\n    case 'referring_pages_cta':\n        $where = '[{ \"properties:LIKE\":\"%\\\"cta_id\\\":\\\"' . $id . '\\\"%\" }]';\n        break;\n    case 'referring_pages_background':\n        $where = '[{ \"properties:LIKE\":\"%background_____' . $id . '__,%\" }]';\n        break;\n}\n\nif (!$where) return;\n\n$output = $modx->runSnippet('pdoMenu', (array(\n    'parents' => '',\n    'context' => 'web,global,hub,notes',\n    'limit' => 0,\n    'depth' => 0,\n    'showHidden' => 1,\n    'showUnpublished' => 1,\n    'tplOuter' => '@INLINE <ul>[[+wrapper]]</ul>',\n    'tpl' => '@INLINE <li><a href=\"[[~[[+id]]]]\" target=\"_blank\">[[+pagetitle]]</a> ([[+id]])</li>',\n    'sortby' => 'menuindex',\n    'sortdir' => 'ASC',\n    'where' => $where,\n)));\n\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.renderreferringpages.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:45:"romanesco.renderreferringpages.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderReferringPages
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$id = $modx->getOption('id', $scriptProperties, $scriptProperties['row']['id'] ?? '');
$column = $modx->getOption('column', $scriptProperties);

//$modx->log(modX::LOG_LEVEL_ERROR, print_r($scriptProperties,1));
//$modx->log(modX::LOG_LEVEL_ERROR, $column);

$where = '';
switch ($column) {
    case 'referring_pages_form':
        $where = '[{ "properties:LIKE":"%\"form_id\":\"' . $id . '\"%" }]';
        break;
    case 'referring_pages_cta':
        $where = '[{ "properties:LIKE":"%\"cta_id\":\"' . $id . '\"%" }]';
        break;
    case 'referring_pages_background':
        $where = '[{ "properties:LIKE":"%background_____' . $id . '__,%" }]';
        break;
}

if (!$where) return;

$output = $modx->runSnippet('pdoMenu', (array(
    'parents' => '',
    'context' => 'web,global,hub,notes',
    'limit' => 0,
    'depth' => 0,
    'showHidden' => 1,
    'showUnpublished' => 1,
    'tplOuter' => '@INLINE <ul>[[+wrapper]]</ul>',
    'tpl' => '@INLINE <li><a href="[[~[[+id]]]]" target="_blank">[[+pagetitle]]</a> ([[+id]])</li>',
    'sortby' => 'menuindex',
    'sortdir' => 'ASC',
    'where' => $where,
)));


return $output;