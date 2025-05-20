id: 46
name: RenderStructuredData
description: 'Add schema.org JSON-LD data in head.'
category: c_global
plugincode: "/**\n * RenderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central @graph object, which is stored in the\n * Romanesco class. Properties can be redefined by creating a plugin on the same\n * event (OnLoadWebDocument) with a higher priority.\n *\n * The final graph object is stored as JSON inside the resource properties field.\n *\n * @depends https://github.com/spatie/schema-org\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\nswitch ($modx->event->name) {\n    case 'OnLoadWebDocument':\n\n        // Cached DOM output already includes structured data\n        $cacheManager = $modx->getCacheManager();\n        $cacheElementKey = '/dom';\n        $cacheOptions = [\n            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n        ];\n        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n        if ($cachedOutput && !$isLoggedIn) {\n            break;\n        }\n\n        /** @var Romanesco $romanesco */\n        try {\n            $romanesco = $modx->services->get('romanesco');\n        } catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n        }\n\n        // System / context\n        $siteName = $modx->getOption('site_name', $scriptProperties);\n        $siteURL = $modx->getOption('site_url', $scriptProperties);\n        $httpHost = $modx->getOption('http_host', $scriptProperties);\n        $context = $modx->getOption('context_key', $scriptProperties);\n\n        // ClientConfig\n        $clientType = $modx->getOption('client_type', $scriptProperties, $romanesco->getConfigSetting('client_type'));\n        $clientPhone = $modx->getOption('client_phone', $scriptProperties, $romanesco->getConfigSetting('client_phone'));\n        $clientEmail = $modx->getOption('client_email', $scriptProperties, $romanesco->getConfigSetting('client_email'));\n        $logoPath = $modx->getOption('logo_path', $scriptProperties, $romanesco->getConfigSetting('logo_path'));\n\n        // Resource\n        $pagetitle = $modx->resource->get('pagetitle');\n        $longtitle = $modx->resource->get('longtitle');\n        $description = $modx->resource->get('description');\n        $introtext = $modx->resource->get('introtext');\n        $url = $modx->makeUrl($modx->resource->id, null, null, 'full');\n\n        // TVs\n        $headerVisible = $modx->resource->getTVValue('header_visibility');\n        $toolbarVisible = $modx->resource->getTVValue('toolbar_visibility');\n        $authorID = $modx->resource->getTVValue('author_id');\n\n        // Use the object initialized within the Romanesco class, to allow overwriting\n        $graph = &$romanesco->structuredData;\n\n        // Organization\n        if ($clientType == 'organization') {\n            $graph\n                ->organization()\n                ->name($siteName)\n                ->url($siteURL)\n                ->contactPoint(Schema::contactPoint()\n                    ->telephone($clientPhone)\n                    ->email($clientEmail)\n                )\n                ->logo(Schema::imageObject()\n                    ->identifier($siteURL . \"#logo\")\n                    ->url(str_replace(\"//\", \"/\", $siteURL . $logoPath))\n                    ->caption($siteName)\n                )\n                ->image([\n                    '@id' => $siteURL . \"#logo\"\n                ])\n            ;\n        }\n\n        // Person\n        if ($clientType == 'person') {\n            $graph\n                ->person()\n                ->name($siteName)\n                ->url($siteURL)\n                ->contactPoint(Schema::contactPoint()\n                    ->telephone($clientPhone)\n                    ->email($clientEmail)\n                )\n            ;\n        }\n\n        // Page\n        $graph\n            ->webPage()\n            ->name($longtitle ?: $pagetitle)\n            ->description($description ?: strip_tags($introtext))\n            ->url($url)\n        ;\n\n        // Breadcrumbs\n        if ($toolbarVisible) {\n            $crumbs = array($modx->runSnippet('pdoCrumbs', [\n                'from' => 0,\n                'to' => $modx->resource->id,\n                'where' => '{\"alias_visible:!=\":\"0\"}',\n                'return' => 'data'\n            ]));\n\n            $crumbItems = [];\n            foreach ($crumbs[0] as $crumb) {\n                $crumbItems[] = Schema::listItem()\n                    ->position($crumb['idx'])\n                    ->item([\n                        '@id' => $siteURL . $crumb['uri'],\n                        'name' => $crumb['menutitle'] ?? $crumb['pagetitle']\n                    ])\n                ;\n            }\n\n            $graph\n                ->breadcrumbList()\n                ->identifier('#breadcrumb')\n                ->itemListElement($crumbItems)\n            ;\n        }\n\n        $modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES));\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * RenderStructuredData
 *
 * Turn given schema.org properties into a proper JSON-LD array.
 *
 * All types are collected in a central @graph object, which is stored in the
 * Romanesco class. Properties can be redefined by creating a plugin on the same
 * event (OnLoadWebDocument) with a higher priority.
 *
 * The final graph object is stored as JSON inside the resource properties field.
 *
 * @depends https://github.com/spatie/schema-org
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

use FractalFarming\Romanesco\Romanesco;
use Spatie\SchemaOrg\Schema;

switch ($modx->event->name) {
    case 'OnLoadWebDocument':

        // Cached DOM output already includes structured data
        $cacheManager = $modx->getCacheManager();
        $cacheElementKey = '/dom';
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
        ];
        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
        if ($cachedOutput && !$isLoggedIn) {
            break;
        }

        /** @var Romanesco $romanesco */
        try {
            $romanesco = $modx->services->get('romanesco');
        } catch (\Psr\Container\NotFoundExceptionInterface $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
        }

        // System / context
        $siteName = $modx->getOption('site_name', $scriptProperties);
        $siteURL = $modx->getOption('site_url', $scriptProperties);
        $httpHost = $modx->getOption('http_host', $scriptProperties);
        $context = $modx->getOption('context_key', $scriptProperties);

        // ClientConfig
        $clientType = $modx->getOption('client_type', $scriptProperties, $romanesco->getConfigSetting('client_type'));
        $clientPhone = $modx->getOption('client_phone', $scriptProperties, $romanesco->getConfigSetting('client_phone'));
        $clientEmail = $modx->getOption('client_email', $scriptProperties, $romanesco->getConfigSetting('client_email'));
        $logoPath = $modx->getOption('logo_path', $scriptProperties, $romanesco->getConfigSetting('logo_path'));

        // Resource
        $pagetitle = $modx->resource->get('pagetitle');
        $longtitle = $modx->resource->get('longtitle');
        $description = $modx->resource->get('description');
        $introtext = $modx->resource->get('introtext');
        $url = $modx->makeUrl($modx->resource->id, null, null, 'full');

        // TVs
        $headerVisible = $modx->resource->getTVValue('header_visibility');
        $toolbarVisible = $modx->resource->getTVValue('toolbar_visibility');
        $authorID = $modx->resource->getTVValue('author_id');

        // Use the object initialized within the Romanesco class, to allow overwriting
        $graph = &$romanesco->structuredData;

        // Organization
        if ($clientType == 'organization') {
            $graph
                ->organization()
                ->name($siteName)
                ->url($siteURL)
                ->contactPoint(Schema::contactPoint()
                    ->telephone($clientPhone)
                    ->email($clientEmail)
                )
                ->logo(Schema::imageObject()
                    ->identifier($siteURL . "#logo")
                    ->url(str_replace("//", "/", $siteURL . $logoPath))
                    ->caption($siteName)
                )
                ->image([
                    '@id' => $siteURL . "#logo"
                ])
            ;
        }

        // Person
        if ($clientType == 'person') {
            $graph
                ->person()
                ->name($siteName)
                ->url($siteURL)
                ->contactPoint(Schema::contactPoint()
                    ->telephone($clientPhone)
                    ->email($clientEmail)
                )
            ;
        }

        // Page
        $graph
            ->webPage()
            ->name($longtitle ?: $pagetitle)
            ->description($description ?: strip_tags($introtext))
            ->url($url)
        ;

        // Breadcrumbs
        if ($toolbarVisible) {
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
                        '@id' => $siteURL . $crumb['uri'],
                        'name' => $crumb['menutitle'] ?? $crumb['pagetitle']
                    ])
                ;
            }

            $graph
                ->breadcrumbList()
                ->identifier('#breadcrumb')
                ->itemListElement($crumbItems)
            ;
        }

        $modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES));

        break;
}