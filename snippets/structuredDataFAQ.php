id: 186
name: structuredDataFAQ
description: 'Loops through all FAQ content blocks on a page and adds the questions + answers to the structured data in the head.'
category: f_dat_structured
snippet: "/**\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\n$data = $romanesco->getSchemaOptions([\n    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties)\n]);\n$graph = &$romanesco->structuredData;\n\n// Get the current count of FAQ items added so far\n$currentCount = (int)$data['faqItemsCount'] ?? 0;\n\n$faqs = $modx->runSnippet('cbGetFieldContent', [\n    'resource' => $modx->resource->get('id'),\n    'field' => $modx->getOption('romanesco.cb_field_faq_id', $scriptProperties),\n    'limit' => 1,\n    'offset' => $data['fieldIdx'],\n    'returnAsJSON' => true\n]);\n$faqs = json_decode($faqs, true);\n\n$faqItems = [];\nforeach ($faqs[0]['rows'] as $idx => $faq) {\n    $faqItems[] = Schema::question()\n        ->name($faq['heading']['value'] ?? '')\n        ->position($currentCount + $idx + 1)\n        ->acceptedAnswer(\n            Schema::answer()\n                ->text(strip_tags($faq['content']['value'] ?? ''))\n            )\n        ;\n}\n\n// Retrieve previous items first if page has multiple FAQ blocks\nif ($data['fieldIdx'] > 0) {\n    $previousItems = $data['faqItems'] ?? [];\n    $allItems = array_merge($previousItems, $faqItems);\n    $graph\n        ->fAQPage()\n        ->mainEntity($allItems)\n    ;\n} else {\n    $graph\n        ->fAQPage()\n        ->mainEntity($faqItems)\n        ->isPartOf(Schema::webPage()\n            ->identifier($data['url'])\n        )\n    ;\n}\n\n// Store data for the next snippet call\n$romanesco->setSchemaOption('faqItems', $allItems ?? $faqItems);\n$romanesco->setSchemaOption('faqItemsCount', $currentCount + count($faqItems));\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
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

$data = $romanesco->getSchemaOptions([
    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties)
]);
$graph = &$romanesco->structuredData;

// Get the current count of FAQ items added so far
$currentCount = (int)$data['faqItemsCount'] ?? 0;

$faqs = $modx->runSnippet('cbGetFieldContent', [
    'resource' => $modx->resource->get('id'),
    'field' => $modx->getOption('romanesco.cb_field_faq_id', $scriptProperties),
    'limit' => 1,
    'offset' => $data['fieldIdx'],
    'returnAsJSON' => true
]);
$faqs = json_decode($faqs, true);

$faqItems = [];
foreach ($faqs[0]['rows'] as $idx => $faq) {
    $faqItems[] = Schema::question()
        ->name($faq['heading']['value'] ?? '')
        ->position($currentCount + $idx + 1)
        ->acceptedAnswer(
            Schema::answer()
                ->text(strip_tags($faq['content']['value'] ?? ''))
            )
        ;
}

// Retrieve previous items first if page has multiple FAQ blocks
if ($data['fieldIdx'] > 0) {
    $previousItems = $data['faqItems'] ?? [];
    $allItems = array_merge($previousItems, $faqItems);
    $graph
        ->fAQPage()
        ->mainEntity($allItems)
    ;
} else {
    $graph
        ->fAQPage()
        ->mainEntity($faqItems)
        ->isPartOf(Schema::webPage()
            ->identifier($data['url'])
        )
    ;
}

// Store data for the next snippet call
$romanesco->setSchemaOption('faqItems', $allItems ?? $faqItems);
$romanesco->setSchemaOption('faqItemsCount', $currentCount + count($faqItems));

return;