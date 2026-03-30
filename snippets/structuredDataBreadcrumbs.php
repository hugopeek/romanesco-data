id: 184
name: structuredDataBreadcrumbs
description: 'This adds a breadcrumb list to the schema, referencing all parent pages.'
category: f_dat_structured
snippet: "/**\n * renderCtaData\n *\n * WIP.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\n$data = $romanesco->getSchemaOptions();\n$graph = &$romanesco->structuredData;\n\n$crumbs = array($modx->runSnippet('pdoCrumbs', [\n    'from' => 0,\n    'to' => $modx->resource->id,\n    'where' => '{\"alias_visible:!=\":\"0\"}',\n    'return' => 'data'\n]));\n\n$crumbItems = [];\nforeach ($crumbs[0] as $crumb) {\n    $crumbItems[] = Schema::listItem()\n        ->position($crumb['idx'])\n        ->item([\n            '@id' => $data['siteURL'] . $crumb['uri'],\n            'name' => $crumb['menutitle'] ?? $crumb['pagetitle']\n        ])\n    ;\n}\n\n$graph\n    ->breadcrumbList()\n    ->identifier($data['url'] . '#breadcrumb')\n    ->itemListElement($crumbItems)\n;\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderCtaData
 *
 * WIP.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

use MODX\Revolution\modX;
use FractalFarming\Romanesco\Romanesco;
use Spatie\SchemaOrg\Schema;

/** @var Romanesco $romanesco */
try {
    $romanesco = $modx->services->get('romanesco');
} catch (\Psr\Container\NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

if (!$romanesco->getConfigSetting('structured_data')) return;

$data = $romanesco->getSchemaOptions();
$graph = &$romanesco->structuredData;

$crumbs = array($modx->runSnippet('pdoCrumbs', [
    'from' => 0,
    'to' => $modx->resource->id,
    'where' => '{"alias_visible:!=":"0"}',
    'return' => 'data'
]));

$crumbItems = [];
foreach ($crumbs[0] as $crumb) {
    $crumbItems[] = Schema::listItem()
        ->position($crumb['idx'])
        ->item([
            '@id' => $data['siteURL'] . $crumb['uri'],
            'name' => $crumb['menutitle'] ?? $crumb['pagetitle']
        ])
    ;
}

$graph
    ->breadcrumbList()
    ->identifier($data['url'] . '#breadcrumb')
    ->itemListElement($crumbItems)
;

return;