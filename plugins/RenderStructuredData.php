id: 49
name: RenderStructuredData
description: 'Add schema.org JSON-LD data in head.'
category: c_global
plugincode: "/**\n * RenderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central graph object, which is initiated in the\n * Romanesco class.\n *\n * This plugin sets the initial data types for each page. Additional data can be\n * added via snippets, as long as they load the graph by reference. The final\n * JSON-LD output is added to the head by the renderStructuredData snippet.\n *\n * @depends https://github.com/spatie/schema-org\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var Romanesco $romanesco\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse MODX\\Revolution\\modChunk;\nuse MODX\\Revolution\\modTemplate;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse FractalFarming\\Romanesco\\Model\\SocialConnect;\nuse FractalFarming\\Romanesco\\Model\\SocialConnectResource;\nuse Spatie\\SchemaOrg\\Schema;\nuse Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nif (!($modx->romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] Romanesco service could not be loaded');\n    return;\n}\n$romanesco = $modx->romanesco;\n\n// Kill switch\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\nswitch ($modx->event->name) {\n    case 'OnLoadWebDocument':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_content;\n\n        // Cached DOM output already includes structured data\n        if ($content) {\n            $cacheManager = $modx->getCacheManager();\n            $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);\n            $cacheOptions = [\n                xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n            ];\n            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n            $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n            if ($cachedOutput && !$isLoggedIn) {\n                $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading structured data from cache');\n                break;\n            }\n        }\n\n        // Get media source ID for logo path setting\n        $cgSetting = $modx->getObject('cgSetting', ['key' => 'logo_path']);\n        $logoPathMediaSourceID = (int)$cgSetting?->get('source') ?? '';\n\n        // Assorted array of relevant data\n        $data = [\n            // System / context\n            'siteStart' => $modx->getOption('site_start'),\n            'siteName' => $modx->getOption('site_name'),\n            'siteURL' => $modx->getOption('site_url'),\n            'httpHost' => $modx->getOption('http_host'),\n            'cultureKey' => $romanesco->getContextSetting('cultureKey', $modx->resource->get('context_key') ?? 'web', 'en'),\n\n            // ClientConfig\n            'clientType' => $romanesco->getConfigSetting('client_type'),\n            'clientPhone' => $romanesco->getConfigSetting('client_phone'),\n            'clientEmail' => $romanesco->getConfigSetting('client_email'),\n            'clientAddressStreet' => $romanesco->getConfigSetting('client_address_street'),\n            'clientAddressLocality' => $romanesco->getConfigSetting('client_address_locality'),\n            'clientAddressRegion' => $romanesco->getConfigSetting('client_address_region'),\n            'clientAddressCountry' => $romanesco->getConfigSetting('client_address_country'),\n            'clientAddressPostcode' => $romanesco->getConfigSetting('client_address_postcode'),\n            'clientAddressExtended' => $romanesco->getConfigSetting('client_address_extended'),\n            'logoPath' => $romanesco->getMediaSourcePath($logoPathMediaSourceID, $romanesco->getConfigSetting('logo_path')),\n\n            // Resource (if available)\n            'pagetitle' => $modx->resource->get('pagetitle') ?? '',\n            'longtitle' => $modx->resource->get('longtitle') ?? '',\n            'menutitle' => $modx->resource->get('menutitle') ?? '',\n            'description' => $modx->resource->get('description') ?? '',\n            'introtext' => strip_tags($modx->resource->get('introtext')) ?? '',\n            'url' => $modx->resource->get('id') ? $modx->makeUrl($modx->resource->get('id'), null, null, 'full') : '',\n            'context' => $modx->resource->get('context_key') ?? '',\n            'publishedon' => $modx->resource->get('publishedon') ?? '',\n            'editedon' => $modx->resource->get('editedon') ?? '',\n\n            // Schema types\n            'pageType' => 'WebPage',\n            'orgType' => 'Organization',\n        ];\n\n        // Initialize main types\n        $webPage = Schema::webPage();\n\n        // Set appropriate data for each template\n        $query = $modx\n            ->newQuery(modTemplate::class, [\n                'id' => $modx->resource->get('template')\n            ])\n            ->select('templatename')\n            ->prepare()\n        ;\n        $template = $modx->getValue($query);\n\n        switch ($template) {\n            case str_contains($template, 'Overview'):\n                $data['pageType'] = 'CollectionPage';\n                break;\n\n            case str_contains($template, 'Detail'):\n                $data['pageType'] = 'WebPage';\n                break;\n\n            case str_contains($template, 'Article'):\n                $data['pageType'] = 'Article';\n                break;\n\n            case str_contains($template, 'Person'):\n                $data['pageType'] = 'ProfilePage';\n\n                // Get profile picture\n                $data['personImage'] = '';\n                if ($image = $modx->resource->getTVValue('person_image')) {\n                    $imagePath = $modx->runSnippet('ImagePlus', [\n                        'value' => $image,\n                        'options' => 'w=800&q=85&zc=1',\n                    ]);\n                    $data['personImage'] = $data['siteURL'] . ltrim($imagePath, '/');\n                }\n\n                // Get social connections attached to resource\n                $resourceFields = $modx->resource->toArray();\n                $templateVars = $modx->resource->getTemplateVars();\n                foreach ($templateVars as $tv) {\n                    $resourceFields[$tv->get('name')] = $tv->get('value');\n                }\n                $socialConnections = $modx->getCollection(SocialConnectResource::class, [\n                    'parent_id' => $modx->resource->get('id'),\n                    'active' => 1\n                ]);\n                $data['sameAs'] = [];\n                foreach ($socialConnections as $connection) {\n                    $urlContent = $connection->get('url');\n                    $chunk = $modx->newObject(modChunk::class);\n                    $chunk->setContent($urlContent);\n                    $chunk->setProperties($resourceFields);\n                    $chunk->setCacheable(false);\n                    $data['sameAs'][] = $chunk->process([\n                        'name' => $connection->get('name'),\n                        'title' => $connection->get('title'),\n                        'username' => $connection->get('username'),\n                    ]);\n                }\n\n                // Get data from TVs\n                $data[] = [\n                    'personFirstname' => $modx->resource->getTVValue('person_firstname') ?? '',\n                    'personLastname' => $modx->resource->getTVValue('person_lastname') ?? '',\n                    'personJobtitle' => $modx->resource->getTVValue('person_jobtitle') ?? '',\n                    'personEmail' => $modx->resource->getTVValue('contact_email') ?? '',\n                    'personPhone' => $modx->resource->getTVValue('contact_phone') ?? '',\n                ];\n\n                $webPage = Schema::profilePage();\n                $webPage\n                    ->mainEntity(Schema::person()\n                        ->name($data['pagetitle'])\n                        ->email($data['personEmail'])\n                        ->telephone($data['personPhone'])\n                        ->jobTitle($data['personJobtitle'])\n                        ->sameAs($data['sameAs'])\n                        ->image(Schema::imageObject()\n                            ->url($data['personImage'])\n                        )\n                    )\n                ;\n                break;\n        }\n\n        // Reference the graph object initialized in the Romanesco class\n        $graph = &$romanesco->structuredData;\n\n        // Website and Organization are only added on homepage\n        if ($modx->resource->get('id') == $data['siteStart']) {\n            $graph\n                ->webSite()\n                ->identifier($data['siteURL'] . \"#website\")\n                ->name($data['siteName'])\n                ->url($data['siteURL'])\n                ->publisher(Schema::{$data['clientType']}()\n                    ->identifier($data['siteURL'] . '#' . $data['clientType'])\n                )\n            ;\n            if ($data['clientType'] == 'organization') {\n                $socialConnections = $modx->getCollection(SocialConnect::class, [\n                    'parent_id' => 0,\n                    'active' => 1\n                ]);\n                $data['sameAs'] = [];\n                foreach ($socialConnections as $connection) {\n                    $urlContent = $connection->get('url');\n                    $chunk = $modx->newObject(modChunk::class);\n                    $chunk->setContent($urlContent);\n                    $chunk->setCacheable(false);\n                    $data['sameAs'][] = $chunk->process([\n                        'name' => $connection->get('name'),\n                        'title' => $connection->get('title'),\n                        'username' => $connection->get('username'),\n                    ]);\n                }\n\n                $graph\n                    ->{$data['orgType']}()\n                    ->identifier($data['siteURL'] . '#organization')\n                    ->name($data['siteName'])\n                    ->url($data['siteURL'])\n                    ->telephone($data['clientPhone'])\n                    ->email($data['clientEmail'])\n                    ->sameAs($data['sameAs'])\n                    ->address(Schema::postalAddress()\n                        ->streetAddress($data['clientAddressStreet'])\n                        ->addressLocality($data['clientAddressLocality'])\n                        ->addressRegion($data['clientAddressRegion'])\n                        ->addressCountry($data['clientAddressCountry'])\n                        ->postalCode($data['clientAddressPostcode'])\n                    )\n                    ->logo(Schema::imageObject()\n                        ->identifier($data['siteURL'] . \"#logo\")\n                        ->url($data['siteURL'] . $data['logoPath'])\n                        ->caption($data['siteName'] . \" logo\")\n                    )\n                    ->image(Schema::imageObject()\n                        ->identifier($data['siteURL'] . \"#image\")\n                        //@todo: which image(s) to fetch here?\n                    )\n                ;\n            }\n            //@todo: Person type is not correct here\n            if ($data['clientType'] == 'person') {\n                $graph\n                    ->person()\n                    ->identifier($data['siteURL'] . '#person')\n                    ->name($data['siteName'])\n                    ->url($data['siteURL'])\n                    ->telephone($data['clientPhone'])\n                    ->email($data['clientEmail'])\n                ;\n            }\n        }\n\n        // Web page\n        $webPage\n            ->identifier($data['url'])\n            ->name($data['longtitle'] ?? $data['pagetitle'])\n            ->description($data['description'] ?? strip_tags($data['introtext']))\n            ->url($data['url'])\n            ->inLanguage($data['cultureKey'])\n            ->isPartOf(Schema::webSite()\n                ->identifier($data['siteURL'] . '#website')\n            )\n        ;\n        $graph->add($webPage);\n\n        // Store options array for reuse\n        $romanesco->setSchemaOptions($data);\n\n        break;\n\n    case 'OnWebPagePrerender':\n        $graph = &$romanesco->structuredData;\n        $data = $romanesco->getSchemaOptions();\n\n        // Load custom data (runsnippet doesn't run if snippet doesn't snip)\n        $modx->runSnippet('structuredDataTheme', ['data' => $data]);\n\n        // Add consolidated JSON-LD graph to the HTML\n        // Setting a placeholder or using regClientStartupHtmlBlock doesn't work\n        //  here, so we're relying on our trusted friend HtmlPageDom again.\n        $content = &$modx->resource->_output;\n        $dom = new HtmlPageCrawler($content);\n\n        // Minify the output based on config setting\n        $flags = JSON_UNESCAPED_SLASHES;\n        if (!$romanesco->getConfigSetting('minify_css_js')) {\n            $flags |= JSON_PRETTY_PRINT;\n        }\n        $dom->filter('script#structured-data')\n            ->setInnerHtml(json_encode($graph, $flags))\n        ;\n\n        $content = $dom->saveHTML();\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * RenderStructuredData
 *
 * Turn given schema.org properties into a proper JSON-LD array.
 *
 * All types are collected in a central graph object, which is initiated in the
 * Romanesco class.
 *
 * This plugin sets the initial data types for each page. Additional data can be
 * added via snippets, as long as they load the graph by reference. The final
 * JSON-LD output is added to the head by the renderStructuredData snippet.
 *
 * @depends https://github.com/spatie/schema-org
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var Romanesco $romanesco
 * @package romanesco
 */

use MODX\Revolution\modX;
use MODX\Revolution\modChunk;
use MODX\Revolution\modTemplate;
use FractalFarming\Romanesco\Romanesco;
use FractalFarming\Romanesco\Model\SocialConnect;
use FractalFarming\Romanesco\Model\SocialConnectResource;
use Spatie\SchemaOrg\Schema;
use Wa72\HtmlPageDom\HtmlPageCrawler;

if (!($modx->romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] Romanesco service could not be loaded');
    return;
}
$romanesco = $modx->romanesco;

// Kill switch
if (!$romanesco->getConfigSetting('structured_data')) return;

switch ($modx->event->name) {
    case 'OnLoadWebDocument':

        // Get processed output of resource
        $content = &$modx->resource->_content;

        // Cached DOM output already includes structured data
        if ($content) {
            $cacheManager = $modx->getCacheManager();
            $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);
            $cacheOptions = [
                xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
            ];
            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
            $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
            if ($cachedOutput && !$isLoggedIn) {
                $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading structured data from cache');
                break;
            }
        }

        // Get media source ID for logo path setting
        $cgSetting = $modx->getObject('cgSetting', ['key' => 'logo_path']);
        $logoPathMediaSourceID = (int)$cgSetting?->get('source') ?? '';

        // Assorted array of relevant data
        $data = [
            // System / context
            'siteStart' => $modx->getOption('site_start'),
            'siteName' => $modx->getOption('site_name'),
            'siteURL' => $modx->getOption('site_url'),
            'httpHost' => $modx->getOption('http_host'),
            'cultureKey' => $romanesco->getContextSetting('cultureKey', $modx->resource->get('context_key') ?? 'web', 'en'),

            // ClientConfig
            'clientType' => $romanesco->getConfigSetting('client_type'),
            'clientPhone' => $romanesco->getConfigSetting('client_phone'),
            'clientEmail' => $romanesco->getConfigSetting('client_email'),
            'clientAddressStreet' => $romanesco->getConfigSetting('client_address_street'),
            'clientAddressLocality' => $romanesco->getConfigSetting('client_address_locality'),
            'clientAddressRegion' => $romanesco->getConfigSetting('client_address_region'),
            'clientAddressCountry' => $romanesco->getConfigSetting('client_address_country'),
            'clientAddressPostcode' => $romanesco->getConfigSetting('client_address_postcode'),
            'clientAddressExtended' => $romanesco->getConfigSetting('client_address_extended'),
            'logoPath' => $romanesco->getMediaSourcePath($logoPathMediaSourceID, $romanesco->getConfigSetting('logo_path')),

            // Resource (if available)
            'pagetitle' => $modx->resource->get('pagetitle') ?? '',
            'longtitle' => $modx->resource->get('longtitle') ?? '',
            'menutitle' => $modx->resource->get('menutitle') ?? '',
            'description' => $modx->resource->get('description') ?? '',
            'introtext' => strip_tags($modx->resource->get('introtext')) ?? '',
            'url' => $modx->resource->get('id') ? $modx->makeUrl($modx->resource->get('id'), null, null, 'full') : '',
            'context' => $modx->resource->get('context_key') ?? '',
            'publishedon' => $modx->resource->get('publishedon') ?? '',
            'editedon' => $modx->resource->get('editedon') ?? '',

            // Schema types
            'pageType' => 'WebPage',
            'orgType' => 'Organization',
        ];

        // Initialize main types
        $webPage = Schema::webPage();

        // Set appropriate data for each template
        $query = $modx
            ->newQuery(modTemplate::class, [
                'id' => $modx->resource->get('template')
            ])
            ->select('templatename')
            ->prepare()
        ;
        $template = $modx->getValue($query);

        switch ($template) {
            case str_contains($template, 'Overview'):
                $data['pageType'] = 'CollectionPage';
                break;

            case str_contains($template, 'Detail'):
                $data['pageType'] = 'WebPage';
                break;

            case str_contains($template, 'Article'):
                $data['pageType'] = 'Article';
                break;

            case str_contains($template, 'Person'):
                $data['pageType'] = 'ProfilePage';

                // Get profile picture
                $data['personImage'] = '';
                if ($image = $modx->resource->getTVValue('person_image')) {
                    $imagePath = $modx->runSnippet('ImagePlus', [
                        'value' => $image,
                        'options' => 'w=800&q=85&zc=1',
                    ]);
                    $data['personImage'] = $data['siteURL'] . ltrim($imagePath, '/');
                }

                // Get social connections attached to resource
                $resourceFields = $modx->resource->toArray();
                $templateVars = $modx->resource->getTemplateVars();
                foreach ($templateVars as $tv) {
                    $resourceFields[$tv->get('name')] = $tv->get('value');
                }
                $socialConnections = $modx->getCollection(SocialConnectResource::class, [
                    'parent_id' => $modx->resource->get('id'),
                    'active' => 1
                ]);
                $data['sameAs'] = [];
                foreach ($socialConnections as $connection) {
                    $urlContent = $connection->get('url');
                    $chunk = $modx->newObject(modChunk::class);
                    $chunk->setContent($urlContent);
                    $chunk->setProperties($resourceFields);
                    $chunk->setCacheable(false);
                    $data['sameAs'][] = $chunk->process([
                        'name' => $connection->get('name'),
                        'title' => $connection->get('title'),
                        'username' => $connection->get('username'),
                    ]);
                }

                // Get data from TVs
                $data[] = [
                    'personFirstname' => $modx->resource->getTVValue('person_firstname') ?? '',
                    'personLastname' => $modx->resource->getTVValue('person_lastname') ?? '',
                    'personJobtitle' => $modx->resource->getTVValue('person_jobtitle') ?? '',
                    'personEmail' => $modx->resource->getTVValue('contact_email') ?? '',
                    'personPhone' => $modx->resource->getTVValue('contact_phone') ?? '',
                ];

                $webPage = Schema::profilePage();
                $webPage
                    ->mainEntity(Schema::person()
                        ->name($data['pagetitle'])
                        ->email($data['personEmail'])
                        ->telephone($data['personPhone'])
                        ->jobTitle($data['personJobtitle'])
                        ->sameAs($data['sameAs'])
                        ->image(Schema::imageObject()
                            ->url($data['personImage'])
                        )
                    )
                ;
                break;
        }

        // Reference the graph object initialized in the Romanesco class
        $graph = &$romanesco->structuredData;

        // Website and Organization are only added on homepage
        if ($modx->resource->get('id') == $data['siteStart']) {
            $graph
                ->webSite()
                ->identifier($data['siteURL'] . "#website")
                ->name($data['siteName'])
                ->url($data['siteURL'])
                ->publisher(Schema::{$data['clientType']}()
                    ->identifier($data['siteURL'] . '#' . $data['clientType'])
                )
            ;
            if ($data['clientType'] == 'organization') {
                $socialConnections = $modx->getCollection(SocialConnect::class, [
                    'parent_id' => 0,
                    'active' => 1
                ]);
                $data['sameAs'] = [];
                foreach ($socialConnections as $connection) {
                    $urlContent = $connection->get('url');
                    $chunk = $modx->newObject(modChunk::class);
                    $chunk->setContent($urlContent);
                    $chunk->setCacheable(false);
                    $data['sameAs'][] = $chunk->process([
                        'name' => $connection->get('name'),
                        'title' => $connection->get('title'),
                        'username' => $connection->get('username'),
                    ]);
                }

                $graph
                    ->{$data['orgType']}()
                    ->identifier($data['siteURL'] . '#organization')
                    ->name($data['siteName'])
                    ->url($data['siteURL'])
                    ->telephone($data['clientPhone'])
                    ->email($data['clientEmail'])
                    ->sameAs($data['sameAs'])
                    ->address(Schema::postalAddress()
                        ->streetAddress($data['clientAddressStreet'])
                        ->addressLocality($data['clientAddressLocality'])
                        ->addressRegion($data['clientAddressRegion'])
                        ->addressCountry($data['clientAddressCountry'])
                        ->postalCode($data['clientAddressPostcode'])
                    )
                    ->logo(Schema::imageObject()
                        ->identifier($data['siteURL'] . "#logo")
                        ->url($data['siteURL'] . $data['logoPath'])
                        ->caption($data['siteName'] . " logo")
                    )
                    ->image(Schema::imageObject()
                        ->identifier($data['siteURL'] . "#image")
                        //@todo: which image(s) to fetch here?
                    )
                ;
            }
            //@todo: Person type is not correct here
            if ($data['clientType'] == 'person') {
                $graph
                    ->person()
                    ->identifier($data['siteURL'] . '#person')
                    ->name($data['siteName'])
                    ->url($data['siteURL'])
                    ->telephone($data['clientPhone'])
                    ->email($data['clientEmail'])
                ;
            }
        }

        // Web page
        $webPage
            ->identifier($data['url'])
            ->name($data['longtitle'] ?? $data['pagetitle'])
            ->description($data['description'] ?? strip_tags($data['introtext']))
            ->url($data['url'])
            ->inLanguage($data['cultureKey'])
            ->isPartOf(Schema::webSite()
                ->identifier($data['siteURL'] . '#website')
            )
        ;
        $graph->add($webPage);

        // Store options array for reuse
        $romanesco->setSchemaOptions($data);

        break;

    case 'OnWebPagePrerender':
        $graph = &$romanesco->structuredData;
        $data = $romanesco->getSchemaOptions();

        // Load custom data (runsnippet doesn't run if snippet doesn't snip)
        $modx->runSnippet('structuredDataTheme', ['data' => $data]);

        // Add consolidated JSON-LD graph to the HTML
        // Setting a placeholder or using regClientStartupHtmlBlock doesn't work
        //  here, so we're relying on our trusted friend HtmlPageDom again.
        $content = &$modx->resource->_output;
        $dom = new HtmlPageCrawler($content);

        // Minify the output based on config setting
        $flags = JSON_UNESCAPED_SLASHES;
        if (!$romanesco->getConfigSetting('minify_css_js')) {
            $flags |= JSON_PRETTY_PRINT;
        }
        $dom->filter('script#structured-data')
            ->setInnerHtml(json_encode($graph, $flags))
        ;

        $content = $dom->saveHTML();

        break;
}