id: 49
name: RenderStructuredData
description: 'Add schema.org JSON-LD data in head.'
category: c_global
plugincode: "/**\n * RenderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central graph object, which is initiated in the\n * Romanesco class.\n *\n * This plugin sets the initial data types for each page. Additional data can be\n * added via snippets, as long as they load the graph by reference. The final\n * JSON-LD output is added to the head by the renderStructuredData snippet.\n *\n * @depends https://github.com/spatie/schema-org\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\nswitch ($modx->event->name) {\n    case 'OnLoadWebDocument':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_content;\n\n        // Cached DOM output already includes structured data\n        if ($content) {\n            $cacheManager = $modx->getCacheManager();\n            $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);\n            $cacheOptions = [\n                xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n            ];\n            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n            $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n            if ($cachedOutput && !$isLoggedIn) {\n                $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading structured data from cache');\n                break;\n            }\n        }\n\n        /** @var Romanesco $romanesco */\n        try {\n            $romanesco = $modx->services->get('romanesco');\n        } catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n            $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n        }\n\n        // Kill switch\n        if (!$romanesco->getConfigSetting('structured_data')) break;\n\n        // Assorted array of relevant data\n        $data = $romanesco->getSchemaOptions();\n\n        // Reference the graph object initialized in the Romanesco class\n        $graph = &$romanesco->structuredData;\n\n        // Add initial data types to each page\n        $graph\n            ->webSite()\n            ->identifier($data['siteURL'] . \"#website\")\n            ->name($data['siteName'])\n            ->url($data['siteURL'])\n            ->publisher(Schema::{$data['clientType']}()\n                ->identifier($data['siteURL'] . '#' . $data['clientType'])\n            )\n        ;\n        $graph\n            ->webPage()\n            ->identifier($data['url'])\n            ->name($data['longtitle'] ?: $data['pagetitle'])\n            ->description($data['description'] ?: strip_tags($data['introtext']))\n            ->url($data['url'])\n            ->inLanguage($romanesco->getContextSetting('cultureKey', 'web'))\n            ->isPartOf([\n                '@id' => $data['siteURL'] . '#website',\n            ])\n        ;\n\n        if ($data['clientType'] == 'organization') {\n            $graph\n                ->organization()\n                ->identifier($data['siteURL'] . '#organization')\n                ->name($data['siteName'])\n                ->url($data['siteURL'])\n                ->telephone($data['clientPhone'])\n                ->email($data['clientEmail'])\n                ->address(Schema::postalAddress()\n                    ->streetAddress($data['clientAddressStreet'])\n                    ->addressLocality($data['clientAddressLocality'])\n                    ->addressRegion($data['clientAddressRegion'])\n                    ->addressCountry($data['clientAddressCountry'])\n                    ->postalCode($data['clientAddressPostcode'])\n                )\n                ->logo(Schema::imageObject()\n                    ->identifier($data['siteURL'] . \"#logo\")\n                    ->url(str_replace(\"//\", \"/\", $data['siteURL'] . $data['logoPath']))\n                    ->caption($data['siteName'])\n                )\n                ->image(Schema::imageObject()\n                    ->identifier($data['siteURL'] . \"#image\")\n\n                )\n            ;\n        }\n\n        if ($data['clientType'] == 'person') {\n            $graph\n                ->person()\n                ->identifier($data['siteURL'] . '#person')\n                ->name($data['siteName'])\n                ->url($data['siteURL'])\n                ->telephone($data['clientPhone'])\n                ->email($data['clientEmail'])\n            ;\n        }\n\n        break;\n}"
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
 * @package romanesco
 */

use MODX\Revolution\modX;
use FractalFarming\Romanesco\Romanesco;
use Spatie\SchemaOrg\Schema;

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

        /** @var Romanesco $romanesco */
        try {
            $romanesco = $modx->services->get('romanesco');
        } catch (\Psr\Container\NotFoundExceptionInterface $e) {
            $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
        }

        // Kill switch
        if (!$romanesco->getConfigSetting('structured_data')) break;

        // Assorted array of relevant data
        $data = $romanesco->getSchemaOptions();

        // Reference the graph object initialized in the Romanesco class
        $graph = &$romanesco->structuredData;

        // Add initial data types to each page
        $graph
            ->webSite()
            ->identifier($data['siteURL'] . "#website")
            ->name($data['siteName'])
            ->url($data['siteURL'])
            ->publisher(Schema::{$data['clientType']}()
                ->identifier($data['siteURL'] . '#' . $data['clientType'])
            )
        ;
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

        break;
}