id: 187
name: structuredDataCTA
description: 'Loops through all CTA content blocks on a page, finds a suitable action type and adds this to the structured data in the head.'
category: f_dat_structured
snippet: "/**\n * renderCtaData\n *\n * WIP.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\n$data = $romanesco->getSchemaOptions([\n    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties)\n]);\n$graph = &$romanesco->structuredData;\n\n// Get the current count of cta items added so far\n$currentCount = (int)$modx->getPlaceholder('cta_items_count') ?? 0;\n\n$ctas = $modx->runSnippet('cbGetFieldContent', [\n    'resource' => $modx->resource->get('id'),\n    'field' => $modx->getOption('romanesco.cb_field_cta_id', $scriptProperties),\n    'limit' => 1,\n    'offset' => $data['fieldIdx'],\n    'returnAsJSON' => true\n]);\n$ctas = json_decode($ctas, true);\n\n$ctaItems = [];\nforeach ($ctas[0]['rows'] as $idx => $cta) {\n    $ctaItems[] = Schema::communicateAction()\n        ->position($currentCount + $idx + 1);\n}\n\n// Update the running count for the next snippet call\n$modx->setPlaceholder('cta_items_count', $currentCount + count($ctaItems));\n\n// Retrieve previous items first if page has multiple cta blocks\nif ($data['fieldIdx'] > 0) {\n    $previousItems = $modx->getPlaceholder('cta_all_items') ?? [];\n    $allItems = array_merge($previousItems, $ctaItems);\n    $graph->webPage()->potentialAction($allItems);\n} else {\n    $graph->webPage()->potentialAction($ctaItems);\n}\n\n// Store all items for the next snippet call\n$modx->setPlaceholder('cta_all_items', $allItems ?? $ctaItems);\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

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

$data = $romanesco->getSchemaOptions([
    'fieldIdx' => $modx->getOption('fieldIdx', $scriptProperties)
]);
$graph = &$romanesco->structuredData;

// Get the current count of cta items added so far
$currentCount = (int)$modx->getPlaceholder('cta_items_count') ?? 0;

$ctas = $modx->runSnippet('cbGetFieldContent', [
    'resource' => $modx->resource->get('id'),
    'field' => $modx->getOption('romanesco.cb_field_cta_id', $scriptProperties),
    'limit' => 1,
    'offset' => $data['fieldIdx'],
    'returnAsJSON' => true
]);
$ctas = json_decode($ctas, true);

$ctaItems = [];
foreach ($ctas[0]['rows'] as $idx => $cta) {
    $ctaItems[] = Schema::communicateAction()
        ->position($currentCount + $idx + 1);
}

// Update the running count for the next snippet call
$modx->setPlaceholder('cta_items_count', $currentCount + count($ctaItems));

// Retrieve previous items first if page has multiple cta blocks
if ($data['fieldIdx'] > 0) {
    $previousItems = $modx->getPlaceholder('cta_all_items') ?? [];
    $allItems = array_merge($previousItems, $ctaItems);
    $graph->webPage()->potentialAction($allItems);
} else {
    $graph->webPage()->potentialAction($ctaItems);
}

// Store all items for the next snippet call
$modx->setPlaceholder('cta_all_items', $allItems ?? $ctaItems);

return '';