id: 183
name: renderFaqData
category: f_data
snippet: "/**\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n$siteName = $modx->getOption('site_name', $scriptProperties);\n$siteURL = $modx->getOption('site_url', $scriptProperties);\n$url = $modx->makeUrl($modx->resource->id, null, null, 'full');\n\n// Use the object initialized within the Romanesco class, to allow overwriting\n$graph = &$romanesco->structuredData;\n\n$faqs = $modx->runSnippet('cbGetFieldContent', [\n    'resource' => $modx->resource->get('id'),\n    'field' => $modx->getOption('romanesco.cb_field_faq_id', $scriptProperties),\n    'returnAsJSON' => true\n]);\n$faqs = json_decode($faqs, true);\n\n$faqItems = [];\nforeach ($faqs[0]['rows'] as $idx => $faq) {\n    $faqItems[] = Schema::question()\n        ->name($faq['heading']['value'] ?? '')\n        ->position($idx + 1)\n        ->acceptedAnswer(\n            Schema::answer()\n                ->text(strip_tags($faq['content']['value'] ?? ''))\n            )\n        ;\n}\n\n$graph\n    ->fAQPage()\n    ->mainEntity($faqItems)\n;\n\n$modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

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

$siteName = $modx->getOption('site_name', $scriptProperties);
$siteURL = $modx->getOption('site_url', $scriptProperties);
$url = $modx->makeUrl($modx->resource->id, null, null, 'full');

// Use the object initialized within the Romanesco class, to allow overwriting
$graph = &$romanesco->structuredData;

$faqs = $modx->runSnippet('cbGetFieldContent', [
    'resource' => $modx->resource->get('id'),
    'field' => $modx->getOption('romanesco.cb_field_faq_id', $scriptProperties),
    'returnAsJSON' => true
]);
$faqs = json_decode($faqs, true);

$faqItems = [];
foreach ($faqs[0]['rows'] as $idx => $faq) {
    $faqItems[] = Schema::question()
        ->name($faq['heading']['value'] ?? '')
        ->position($idx + 1)
        ->acceptedAnswer(
            Schema::answer()
                ->text(strip_tags($faq['content']['value'] ?? ''))
            )
        ;
}

$graph
    ->fAQPage()
    ->mainEntity($faqItems)
;

$modx->setPlaceholder('structured_data', json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

return '';