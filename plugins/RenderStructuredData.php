id: 46
name: RenderStructuredData
description: 'Add schema.org JSON-LD data in head.'
category: c_global
plugincode: "/**\n * RenderStructuredData\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central @graph object, which is stored in the\n * Romanesco class. Properties can be redefined by creating a plugin on the same\n * event (OnDocFormSave) with a higher priority.\n *\n * The final graph object is stored as JSON inside the resource properties field.\n *\n * @depends https://github.com/spatie/schema-org\n *\n * @var modX $modx\n * @package romanesco\n */\n\nuse Spatie\\SchemaOrg\\Schema;\nuse Spatie\\SchemaOrg\\Graph;\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        $corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path').'components/romanescobackyard/');\n        $romanesco = $modx->getService('romanesco','Romanesco', $corePath.'model/romanescobackyard/', array('core_path' => $corePath));\n\n        /** @var array $scriptProperties */\n        $toolbarVisible = $modx->getOption('toolbar_visibility', $scriptProperties, true);\n\n        // System / context\n        $siteName = $modx->getOption('site_name', $scriptProperties);\n        $siteURL = $modx->getOption('site_url', $scriptProperties);\n        $httpHost = $modx->getOption('http_host', $scriptProperties);\n        $context = $modx->getOption('context_key', $scriptProperties);\n\n        // ClientConfig\n        $clientType = $modx->getOption('client_type', $scriptProperties, $modx->romanesco->getConfigSetting('client_type'));\n        $clientPhone = $modx->getOption('client_phone', $scriptProperties, $modx->romanesco->getConfigSetting('client_phone'));\n        $clientEmail = $modx->getOption('client_email', $scriptProperties, $modx->romanesco->getConfigSetting('client_email'));\n\n        // Resource\n        $pagetitle = $modx->resource->get('pagetitle');\n        $longtitle = $modx->resource->get('longtitle');\n        $description = $modx->resource->get('description');\n        $introtext = $modx->resource->get('introtext');\n        $url = $modx->makeUrl($modx->resource->id, null, null, 'full');\n\n        // Use the object initialized within the Romanesco class, to allow overwriting\n        $graph = &$romanesco->structuredData;\n\n        // Organization\n        $graph\n            ->organization()\n            ->name($siteName)\n            ->url($siteURL)\n            ->contactPoint(Schema::contactPoint()\n                ->telephone($clientPhone)\n                ->email($clientEmail)\n            )\n        ;\n\n        // Page\n        $graph\n            ->webPage()\n            ->name($longtitle ?: $pagetitle)\n            ->description($description ?: strip_tags($introtext))\n            ->url($url)\n        ;\n\n        // Breadcrumbs\n        if ($toolbarVisible) {\n            $crumbs = array($modx->runSnippet('pdoCrumbs', [\n                'from' => 0,\n                'to' => $modx->resource->id,\n                'where' => '{\"alias_visible:!=\":\"0\"}',\n                'return' => 'data'\n            ]));\n\n            $crumbItems = [];\n            foreach ($crumbs[0] as $crumb) {\n                $crumbItems[] = Schema::listItem()\n                    ->position($crumb['idx'])\n                    ->item([\n                        '@id' => $siteURL . $crumb['uri'],\n                        'name' => $crumb['menutitle'] ?? $crumb['pagetitle']\n                    ])\n                ;\n            }\n\n            $graph\n                ->breadcrumbList()\n                ->identifier('#breadcrumb')\n                ->itemListElement($crumbItems)\n            ;\n        }\n\n        //$modx->log(modX::LOG_LEVEL_ERROR, print_r($graph->toArray(), true));\n\n        /** @var modResource $resource */\n        $resource->setProperties([\"schema\" => $graph],'romanesco');\n        $resource->save();\n        break;\n\n    case 'OnLoadWebDocument':\n        $properties = $modx->resource->getProperties('romanesco');\n\n        if (isset($properties['schema'])) {\n            $modx->regClientStartupHTMLBlock('<script type=\"application/ld+json\">'.json_encode($properties['schema'],JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).'</script>');\n        }\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:44:"romanesco.renderstructureddata.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * RenderStructuredData
 *
 * Turn given schema.org properties into a proper JSON-LD array.
 *
 * All types are collected in a central @graph object, which is stored in the
 * Romanesco class. Properties can be redefined by creating a plugin on the same
 * event (OnDocFormSave) with a higher priority.
 *
 * The final graph object is stored as JSON inside the resource properties field.
 *
 * @depends https://github.com/spatie/schema-org
 *
 * @var modX $modx
 * @package romanesco
 */

use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\Graph;

switch ($modx->event->name) {
    case 'OnDocFormSave':
        $corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path').'components/romanescobackyard/');
        $romanesco = $modx->getService('romanesco','Romanesco', $corePath.'model/romanescobackyard/', array('core_path' => $corePath));

        /** @var array $scriptProperties */
        $toolbarVisible = $modx->getOption('toolbar_visibility', $scriptProperties, true);

        // System / context
        $siteName = $modx->getOption('site_name', $scriptProperties);
        $siteURL = $modx->getOption('site_url', $scriptProperties);
        $httpHost = $modx->getOption('http_host', $scriptProperties);
        $context = $modx->getOption('context_key', $scriptProperties);

        // ClientConfig
        $clientType = $modx->getOption('client_type', $scriptProperties, $modx->romanesco->getConfigSetting('client_type'));
        $clientPhone = $modx->getOption('client_phone', $scriptProperties, $modx->romanesco->getConfigSetting('client_phone'));
        $clientEmail = $modx->getOption('client_email', $scriptProperties, $modx->romanesco->getConfigSetting('client_email'));

        // Resource
        $pagetitle = $modx->resource->get('pagetitle');
        $longtitle = $modx->resource->get('longtitle');
        $description = $modx->resource->get('description');
        $introtext = $modx->resource->get('introtext');
        $url = $modx->makeUrl($modx->resource->id, null, null, 'full');

        // Use the object initialized within the Romanesco class, to allow overwriting
        $graph = &$romanesco->structuredData;

        // Organization
        $graph
            ->organization()
            ->name($siteName)
            ->url($siteURL)
            ->contactPoint(Schema::contactPoint()
                ->telephone($clientPhone)
                ->email($clientEmail)
            )
        ;

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

        //$modx->log(modX::LOG_LEVEL_ERROR, print_r($graph->toArray(), true));

        /** @var modResource $resource */
        $resource->setProperties(["schema" => $graph],'romanesco');
        $resource->save();
        break;

    case 'OnLoadWebDocument':
        $properties = $modx->resource->getProperties('romanesco');

        if (isset($properties['schema'])) {
            $modx->regClientStartupHTMLBlock('<script type="application/ld+json">'.json_encode($properties['schema'],JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).'</script>');
        }
        break;
}