id: 49
name: RenderStructuredData
description: 'Add schema.org JSON-LD data in head.'
category: c_global
plugincode: "/**\n * RenderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central graph object, which is initiated in the\n * Romanesco class.\n *\n * This plugin sets the initial data types for each page. Additional data can be\n * added via snippets, as long as they load the graph by reference. The final\n * JSON-LD output is added to the head by the renderStructuredData snippet.\n *\n * @depends https://github.com/spatie/schema-org\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var Romanesco $romanesco\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse MODX\\Revolution\\modTemplate;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\nuse Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nif (!($modx->romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] Romanesco service has wrong type');\n    return;\n}\n$romanesco = $modx->romanesco;\n\n// Kill switch\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\nswitch ($modx->event->name) {\n    case 'OnLoadWebDocument':\n\n        // Get processed output of resource\n        $content = &$modx->resource->_content;\n\n        // Cached DOM output already includes structured data\n        if ($content) {\n            $cacheManager = $modx->getCacheManager();\n            $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);\n            $cacheOptions = [\n                xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n            ];\n            $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n            $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n            if ($cachedOutput && !$isLoggedIn) {\n                $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading structured data from cache');\n                break;\n            }\n        }\n\n        // Assorted array of relevant data\n        $data = $romanesco->getSchemaOptions([\n            'pageType' => 'WebPage',\n            'orgType' => 'Organization',\n        ]);\n\n        // Reference the graph object initialized in the Romanesco class\n        $graph = &$romanesco->structuredData;\n\n        // Establish the kind of template being used\n        $query = $modx\n            ->newQuery(modTemplate::class, [\n                'id' => $modx->resource->get('template')\n            ])\n            ->select('templatename')\n            ->prepare()\n        ;\n        $template = $modx->getValue($query);\n\n        // Set appropriate page type for each template\n        switch ($template) {\n            case str_contains($template, 'Overview'):\n                $data['pageType'] = 'CollectionPage';\n                break;\n            case str_contains($template, 'Detail'):\n                $data['pageType'] = 'WebPage';\n                break;\n            case str_contains($template, 'Article'):\n                $data['pageType'] = 'Article';\n                break;\n            case str_contains($template, 'Person'):\n                $data['pageType'] = 'ProfilePage';\n                break;\n        }\n\n        // Add initial data types to each page\n        // Website and Organization are only added on homepage\n        if ($modx->resource->get('id') == $data['siteStart']) {\n            $graph\n                ->webSite()\n                ->identifier($data['siteURL'] . \"#website\")\n                ->name($data['siteName'])\n                ->url($data['siteURL'])\n                ->publisher(Schema::{$data['clientType']}()\n                    ->identifier($data['siteURL'] . '#' . $data['clientType'])\n                )\n            ;\n            if ($data['clientType'] == 'organization') {\n                $graph\n                    ->{$data['orgType']}()\n                    ->identifier($data['siteURL'] . '#organization')\n                    ->name($data['siteName'])\n                    ->url($data['siteURL'])\n                    ->telephone($data['clientPhone'])\n                    ->email($data['clientEmail'])\n                    ->address(Schema::postalAddress()\n                        ->streetAddress($data['clientAddressStreet'])\n                        ->addressLocality($data['clientAddressLocality'])\n                        ->addressRegion($data['clientAddressRegion'])\n                        ->addressCountry($data['clientAddressCountry'])\n                        ->postalCode($data['clientAddressPostcode'])\n                    )\n                    ->logo(Schema::imageObject()\n                        ->identifier($data['siteURL'] . \"#logo\")\n                        ->url($data['siteURL'] . $data['logoPath'])\n                        ->caption($data['siteName'] . \" logo\")\n                    )\n                    ->image(Schema::imageObject()\n                        ->identifier($data['siteURL'] . \"#image\")\n                        //@todo: which image(s) to fetch here?\n                    )\n                ;\n            }\n            //@todo: Person type is not correct here\n            if ($data['clientType'] == 'person') {\n                $graph\n                    ->person()\n                    ->identifier($data['siteURL'] . '#person')\n                    ->name($data['siteName'])\n                    ->url($data['siteURL'])\n                    ->telephone($data['clientPhone'])\n                    ->email($data['clientEmail'])\n                ;\n            }\n        }\n\n        // Web page\n        $graph\n            ->{$data['pageType']}()\n            ->identifier($data['url'])\n            ->name($data['longtitle'] ?: $data['pagetitle'])\n            ->description($data['description'] ?: strip_tags($data['introtext']))\n            ->url($data['url'])\n            ->inLanguage($data['cultureKey'])\n            ->isPartOf(Schema::webSite()\n                ->identifier($data['siteURL'] . '#website')\n            )\n        ;\n\n        break;\n\n    case 'OnWebPagePrerender':\n        $graph = &$romanesco->structuredData;\n        $data = $romanesco->getSchemaOptions();\n\n        $modx->runSnippet('structuredDataTheme', ['data' => $data]);\n\n        // Add consolidated JSON-LD graph to the HTML\n        // Setting a placeholder or using regClientStartupHtmlBlock doesn't work\n        //  here, so we're relying on our trusted friend HtmlPageDom again.\n        $content = &$modx->resource->_output;\n        $dom = new HtmlPageCrawler($content);\n\n        $dom->filter('script#structured-data')\n            ->setInnerHtml(json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))\n        ;\n\n        $content = $dom->saveHTML();\n\n        break;\n}"
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
use MODX\Revolution\modTemplate;
use FractalFarming\Romanesco\Romanesco;
use Spatie\SchemaOrg\Schema;
use Wa72\HtmlPageDom\HtmlPageCrawler;

if (!($modx->romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] Romanesco service has wrong type');
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

        // Assorted array of relevant data
        $data = $romanesco->getSchemaOptions([
            'pageType' => 'WebPage',
            'orgType' => 'Organization',
        ]);

        // Reference the graph object initialized in the Romanesco class
        $graph = &$romanesco->structuredData;

        // Establish the kind of template being used
        $query = $modx
            ->newQuery(modTemplate::class, [
                'id' => $modx->resource->get('template')
            ])
            ->select('templatename')
            ->prepare()
        ;
        $template = $modx->getValue($query);

        // Set appropriate page type for each template
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
                break;
        }

        // Add initial data types to each page
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
                $graph
                    ->{$data['orgType']}()
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
        $graph
            ->{$data['pageType']}()
            ->identifier($data['url'])
            ->name($data['longtitle'] ?: $data['pagetitle'])
            ->description($data['description'] ?: strip_tags($data['introtext']))
            ->url($data['url'])
            ->inLanguage($data['cultureKey'])
            ->isPartOf(Schema::webSite()
                ->identifier($data['siteURL'] . '#website')
            )
        ;

        break;

    case 'OnWebPagePrerender':
        $graph = &$romanesco->structuredData;
        $data = $romanesco->getSchemaOptions();

        $modx->runSnippet('structuredDataTheme', ['data' => $data]);

        // Add consolidated JSON-LD graph to the HTML
        // Setting a placeholder or using regClientStartupHtmlBlock doesn't work
        //  here, so we're relying on our trusted friend HtmlPageDom again.
        $content = &$modx->resource->_output;
        $dom = new HtmlPageCrawler($content);

        $dom->filter('script#structured-data')
            ->setInnerHtml(json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))
        ;

        $content = $dom->saveHTML();

        break;
}