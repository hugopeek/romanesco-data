id: 182
name: renderStructuredData
description: 'Add schema.org JSON-LD data in head. You can add / overwrite properties from any snippet that references the global structuredData graph, or by creating a renderStructuredDataTheme snippet.'
category: f_data
snippet: "/**\n * renderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central $graph object, which is initiated in the\n * Romanesco class. You can add / overwrite properties from any snippet that\n * references the graph, or by creating a renderStructuredDataTheme snippet.\n * This snippet will be run here and receives an array with all available data.\n *\n * The final JSON graph object is forwarded to the structured_data placeholder.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n// Use the object initialized within the Romanesco class, to allow overwriting\n$graph = &$romanesco->structuredData;\n\n// Assorted array of relevant data\n$data = [\n    // System / context\n    'siteName' => $modx->getOption('site_name', $scriptProperties),\n    'siteURL' => $modx->getOption('site_url', $scriptProperties),\n    'httpHost' => $modx->getOption('http_host', $scriptProperties),\n    'context' => $modx->getOption('context_key', $scriptProperties),\n\n    // ClientConfig\n    'clientType' => $modx->getOption('client_type', $scriptProperties, $romanesco->getConfigSetting('client_type')),\n    'clientPhone' => $modx->getOption('client_phone', $scriptProperties, $romanesco->getConfigSetting('client_phone')),\n    'clientEmail' => $modx->getOption('client_email', $scriptProperties, $romanesco->getConfigSetting('client_email')),\n    'clientAddressStreet' => $modx->getOption('client_address_street', $scriptProperties, $romanesco->getConfigSetting('client_address_street')),\n    'clientAddressLocality' => $modx->getOption('client_address_locality', $scriptProperties, $romanesco->getConfigSetting('client_address_locality')),\n    'clientAddressRegion' => $modx->getOption('client_address_region', $scriptProperties, $romanesco->getConfigSetting('client_address_region')),\n    'clientAddressCountry' => $modx->getOption('client_address_country', $scriptProperties, $romanesco->getConfigSetting('client_address_country')),\n    'clientAddressPostcode' => $modx->getOption('client_address_postcode', $scriptProperties, $romanesco->getConfigSetting('client_address_postcode')),\n    'clientAddressExtended' => $modx->getOption('client_address_extended', $scriptProperties, $romanesco->getConfigSetting('client_address_extended')),\n    'logoPath' => $modx->getOption('logo_path', $scriptProperties, $romanesco->getConfigSetting('logo_path')),\n\n    // Resource\n    'pagetitle' => $modx->resource->get('pagetitle'),\n    'longtitle' => $modx->resource->get('longtitle'),\n    'description' => $modx->resource->get('description'),\n    'introtext' => $modx->resource->get('introtext'),\n    'url' => $modx->makeUrl($modx->resource->id, null, null, 'full'),\n\n    // TVs\n    'headerVisible' => $modx->resource->getTVValue('header_visibility'),\n    'toolbarVisible' => $modx->resource->getTVValue('toolbar_visibility'),\n    'authorID' => $modx->resource->getTVValue('author_id'),\n];\n\n// Organization\nif ($data['clientType'] == 'organization') {\n    $graph\n        ->organization()\n        ->identifier($data['siteURL'] . '#organization')\n        ->name($data['siteName'])\n        ->url($data['siteURL'])\n        ->telephone($data['clientPhone'])\n        ->email($data['clientEmail'])\n        ->address(Schema::postalAddress()\n            ->streetAddress($data['clientAddressStreet'])\n            ->addressLocality($data['clientAddressLocality'])\n            ->addressRegion($data['clientAddressRegion'])\n            ->addressCountry($data['clientAddressCountry'])\n            ->postalCode($data['clientAddressPostcode'])\n        )\n        ->logo(Schema::imageObject()\n            ->identifier($data['siteURL'] . \"#logo\")\n            ->url(str_replace(\"//\", \"/\", $data['siteURL'] . $data['logoPath']))\n            ->caption($data['siteName'])\n        )\n        ->image(Schema::imageObject()\n            ->identifier($data['siteURL'] . \"#image\")\n            \n        )\n    ;\n}\n\n// Person\nif ($data['clientType'] == 'person') {\n    $graph\n        ->person()\n        ->name($data['siteName'])\n        ->url($data['siteURL'])\n        ->telephone($data['clientPhone'])\n        ->email($data['clientEmail'])\n    ;\n}\n\n// Site\n$graph\n    ->webSite()\n    ->identifier($data['siteURL'] . \"#website\")\n    ->name($data['siteName'])\n    ->url($data['siteURL'])\n    ->publisher(Schema::organization()\n        ->identifier($data['siteURL'] . '#organization')\n    )\n;\n\n// Page\n$graph\n    ->webPage()\n    ->identifier($data['url'])\n    ->name($data['longtitle'] ?: $data['pagetitle'])\n    ->description($data['description'] ?: strip_tags($data['introtext']))\n    ->url($data['url'])\n    ->inLanguage($romanesco->getContextSetting('cultureKey', 'web'))\n    ->isPartOf([\n        '@id' => $data['siteURL'] . '#website',\n    ])\n;\n\n// Breadcrumbs\nif ($data['toolbarVisible']) {\n    $crumbs = array($modx->runSnippet('pdoCrumbs', [\n        'from' => 0,\n        'to' => $modx->resource->id,\n        'where' => '{\"alias_visible:!=\":\"0\"}',\n        'return' => 'data'\n    ]));\n\n    $crumbItems = [];\n    foreach ($crumbs[0] as $crumb) {\n        $crumbItems[] = Schema::listItem()\n            ->position($crumb['idx'])\n            ->item([\n                '@id' => $data['siteURL'] . $crumb['uri'],\n                'name' => $crumb['menutitle'] ?? $crumb['pagetitle']\n            ])\n        ;\n    }\n\n    $graph\n        ->breadcrumbList()\n        ->identifier($data['url'] . '#breadcrumb')\n        ->itemListElement($crumbItems)\n    ;\n}\n\n// Load custom properties\n$query = $modx->newQuery('modSnippet', [\n    'name' => 'renderStructuredDataTheme'\n]);\n$query->select('id');\n$output = (bool)$modx->getValue($query->prepare());\nif ($output) {\n    $modx->runSnippet('renderStructuredDataTheme', $data);\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find renderStructuredDataTheme.');\n}\n\n// Write everything to placeholders\n$modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderStructuredData
 *
 * Turn given schema.org properties into a proper JSON-LD array.
 *
 * All types are collected in a central $graph object, which is initiated in the
 * Romanesco class. You can add / overwrite properties from any snippet that
 * references the graph, or by creating a renderStructuredDataTheme snippet.
 * This snippet will be run here and receives an array with all available data.
 *
 * The final JSON graph object is forwarded to the structured_data placeholder.
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

// Use the object initialized within the Romanesco class, to allow overwriting
$graph = &$romanesco->structuredData;

// Assorted array of relevant data
$data = [
    // System / context
    'siteName' => $modx->getOption('site_name', $scriptProperties),
    'siteURL' => $modx->getOption('site_url', $scriptProperties),
    'httpHost' => $modx->getOption('http_host', $scriptProperties),
    'context' => $modx->getOption('context_key', $scriptProperties),

    // ClientConfig
    'clientType' => $modx->getOption('client_type', $scriptProperties, $romanesco->getConfigSetting('client_type')),
    'clientPhone' => $modx->getOption('client_phone', $scriptProperties, $romanesco->getConfigSetting('client_phone')),
    'clientEmail' => $modx->getOption('client_email', $scriptProperties, $romanesco->getConfigSetting('client_email')),
    'clientAddressStreet' => $modx->getOption('client_address_street', $scriptProperties, $romanesco->getConfigSetting('client_address_street')),
    'clientAddressLocality' => $modx->getOption('client_address_locality', $scriptProperties, $romanesco->getConfigSetting('client_address_locality')),
    'clientAddressRegion' => $modx->getOption('client_address_region', $scriptProperties, $romanesco->getConfigSetting('client_address_region')),
    'clientAddressCountry' => $modx->getOption('client_address_country', $scriptProperties, $romanesco->getConfigSetting('client_address_country')),
    'clientAddressPostcode' => $modx->getOption('client_address_postcode', $scriptProperties, $romanesco->getConfigSetting('client_address_postcode')),
    'clientAddressExtended' => $modx->getOption('client_address_extended', $scriptProperties, $romanesco->getConfigSetting('client_address_extended')),
    'logoPath' => $modx->getOption('logo_path', $scriptProperties, $romanesco->getConfigSetting('logo_path')),

    // Resource
    'pagetitle' => $modx->resource->get('pagetitle'),
    'longtitle' => $modx->resource->get('longtitle'),
    'description' => $modx->resource->get('description'),
    'introtext' => $modx->resource->get('introtext'),
    'url' => $modx->makeUrl($modx->resource->id, null, null, 'full'),

    // TVs
    'headerVisible' => $modx->resource->getTVValue('header_visibility'),
    'toolbarVisible' => $modx->resource->getTVValue('toolbar_visibility'),
    'authorID' => $modx->resource->getTVValue('author_id'),
];

// Organization
if ($data['clientType'] == 'organization') {
    $graph
        ->organization()
        ->identifier($data['siteURL'] . '#organization')
        ->name($data['siteName'])
        ->url($data['siteURL'])
        ->telephone($data['clientPhone'])
        ->email($data['clientEmail'])
        ->address(Schema::postalAddress()
            ->streetAddress($data['clientAddressStreet'])
            ->addressLocality($data['clientAddressLocality'])
            ->addressRegion($data['clientAddressRegion'])
            ->addressCountry($data['clientAddressCountry'])
            ->postalCode($data['clientAddressPostcode'])
        )
        ->logo(Schema::imageObject()
            ->identifier($data['siteURL'] . "#logo")
            ->url(str_replace("//", "/", $data['siteURL'] . $data['logoPath']))
            ->caption($data['siteName'])
        )
        ->image(Schema::imageObject()
            ->identifier($data['siteURL'] . "#image")
            
        )
    ;
}

// Person
if ($data['clientType'] == 'person') {
    $graph
        ->person()
        ->name($data['siteName'])
        ->url($data['siteURL'])
        ->telephone($data['clientPhone'])
        ->email($data['clientEmail'])
    ;
}

// Site
$graph
    ->webSite()
    ->identifier($data['siteURL'] . "#website")
    ->name($data['siteName'])
    ->url($data['siteURL'])
    ->publisher(Schema::organization()
        ->identifier($data['siteURL'] . '#organization')
    )
;

// Page
$graph
    ->webPage()
    ->identifier($data['url'])
    ->name($data['longtitle'] ?: $data['pagetitle'])
    ->description($data['description'] ?: strip_tags($data['introtext']))
    ->url($data['url'])
    ->inLanguage($romanesco->getContextSetting('cultureKey', 'web'))
    ->isPartOf([
        '@id' => $data['siteURL'] . '#website',
    ])
;

// Breadcrumbs
if ($data['toolbarVisible']) {
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
}

// Load custom properties
$query = $modx->newQuery('modSnippet', [
    'name' => 'renderStructuredDataTheme'
]);
$query->select('id');
$output = (bool)$modx->getValue($query->prepare());
if ($output) {
    $modx->runSnippet('renderStructuredDataTheme', $data);
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not find renderStructuredDataTheme.');
}

// Write everything to placeholders
$modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

return '';