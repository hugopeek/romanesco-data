id: 185
name: structuredDataOverview
description: 'This adds the CollectionPage type to the schema, when an overview is used in the content area.'
category: f_dat_structured
snippet: "/**\n * structuredDataOverview\n *\n * This multifunctional snippet will build up a CollectionPage object,\n * containing data from all Overview blocks on the page.\n *\n * This snippet can be called multiple times on a page, including within\n * overview row templates. Always specify the type parameter to ensure the\n * correct structured data is added to the graph.\n *\n * Important: When looping through overview items, the snippet relies on\n * scriptProperties — not the central schema options — to add each item's data.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var Romanesco $romanesco\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\nuse Spatie\\SchemaOrg\\CollectionPage;\n\n$romanesco = $modx->romanesco;\n\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\n$data = $romanesco->getSchemaOptions([\n    'type' => $modx->getOption('type', $scriptProperties, 'CollectionPage'),\n    'uid' => $modx->getOption('uid', $scriptProperties),\n    'idx' => $modx->getOption('idx', $scriptProperties),\n    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties, 0),\n]);\n$graph = &$romanesco->structuredData;\n\n// Limit to one collection\nif ($data['fieldIdx'] > 0) return;\n\n// Prepare different data types\n$collectionItems = $data['collectionItems'] ?? [];\n$collectionMentions = $data['collectionMentions'] ?? [];\n\nswitch ($data['type']) {\n    case 'CollectionPage':\n        $graph\n            ->getOrCreate(CollectionPage::class)\n            ->identifier(\"{$data['url']}#collection\")\n            ->isPartOf(Schema::webPage()\n                ->identifier($data['url'])\n            )\n        ;\n        break;\n\n    case 'Article':\n        $collectionItems[] = Schema::Article()\n            ->headline($scriptProperties['headline'] ?? '')\n            ->abstract($scriptProperties['abstract'] ?? '')\n            ->author(Schema::person()\n                ->name($scriptProperties['authorName'] ?? '')\n                ->url($modx->makeUrl($scriptProperties['authorId'], null, null, 'full'))\n            )\n            ->datePublished($scriptProperties['datePublished'] ?? '')\n            ->dateModified($scriptProperties['dateModified'] ?? '')\n            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))\n        ;\n        $graph\n            ->collectionPage()\n            ->hasPart($collectionItems)\n        ;\n        $romanesco->setSchemaOption('collectionItems', $collectionItems);\n        break;\n\n    case 'Person':\n        $collectionMentions[] = Schema::Person()\n            ->name($scriptProperties['name'] ?? '')\n            ->jobTitle($scriptProperties['jobTitle'] ?? '')\n            ->memberOf(Schema::Organization()\n                ->identifier($data['siteURL'] . '#organization')\n            )\n            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))\n        ;\n        $graph\n            ->collectionPage()\n            ->mentions($collectionMentions)\n        ;\n        $romanesco->setSchemaOption('collectionMentions', $collectionMentions);\n        break;\n\n    default:\n        $collectionItems[] = Schema::{$data['type']}()\n            ->name($scriptProperties['name'] ?? '')\n            ->description($scriptProperties['description'] ?? '')\n            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))\n        ;\n        $graph\n            ->collectionPage()\n            ->hasPart($collectionItems)\n        ;\n        $romanesco->setSchemaOption('collectionItems', $collectionItems);\n        break;\n}\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * structuredDataOverview
 *
 * This multifunctional snippet will build up a CollectionPage object,
 * containing data from all Overview blocks on the page.
 *
 * This snippet can be called multiple times on a page, including within
 * overview row templates. Always specify the type parameter to ensure the
 * correct structured data is added to the graph.
 *
 * Important: When looping through overview items, the snippet relies on
 * scriptProperties — not the central schema options — to add each item's data.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var Romanesco $romanesco
 * @package romanesco
 */

use MODX\Revolution\modX;
use FractalFarming\Romanesco\Romanesco;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\CollectionPage;

$romanesco = $modx->romanesco;

if (!$romanesco->getConfigSetting('structured_data')) return;

$data = $romanesco->getSchemaOptions([
    'type' => $modx->getOption('type', $scriptProperties, 'CollectionPage'),
    'uid' => $modx->getOption('uid', $scriptProperties),
    'idx' => $modx->getOption('idx', $scriptProperties),
    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties, 0),
]);
$graph = &$romanesco->structuredData;

// Limit to one collection
if ($data['fieldIdx'] > 0) return;

// Prepare different data types
$collectionItems = $data['collectionItems'] ?? [];
$collectionMentions = $data['collectionMentions'] ?? [];

switch ($data['type']) {
    case 'CollectionPage':
        $graph
            ->getOrCreate(CollectionPage::class)
            ->identifier("{$data['url']}#collection")
            ->isPartOf(Schema::webPage()
                ->identifier($data['url'])
            )
        ;
        break;

    case 'Article':
        $collectionItems[] = Schema::Article()
            ->headline($scriptProperties['headline'] ?? '')
            ->abstract($scriptProperties['abstract'] ?? '')
            ->author(Schema::person()
                ->name($scriptProperties['authorName'] ?? '')
                ->url($modx->makeUrl($scriptProperties['authorId'], null, null, 'full'))
            )
            ->datePublished($scriptProperties['datePublished'] ?? '')
            ->dateModified($scriptProperties['dateModified'] ?? '')
            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))
        ;
        $graph
            ->collectionPage()
            ->hasPart($collectionItems)
        ;
        $romanesco->setSchemaOption('collectionItems', $collectionItems);
        break;

    case 'Person':
        $collectionMentions[] = Schema::Person()
            ->name($scriptProperties['name'] ?? '')
            ->jobTitle($scriptProperties['jobTitle'] ?? '')
            ->memberOf(Schema::Organization()
                ->identifier($data['siteURL'] . '#organization')
            )
            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))
        ;
        $graph
            ->collectionPage()
            ->mentions($collectionMentions)
        ;
        $romanesco->setSchemaOption('collectionMentions', $collectionMentions);
        break;

    default:
        $collectionItems[] = Schema::{$data['type']}()
            ->name($scriptProperties['name'] ?? '')
            ->description($scriptProperties['description'] ?? '')
            ->url($modx->makeUrl($scriptProperties['id'], null, null, 'full'))
        ;
        $graph
            ->collectionPage()
            ->hasPart($collectionItems)
        ;
        $romanesco->setSchemaOption('collectionItems', $collectionItems);
        break;
}

return;