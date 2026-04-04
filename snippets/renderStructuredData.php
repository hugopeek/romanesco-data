id: 182
name: renderStructuredData
description: 'Companion of the RenderStructuredData plugin. This snippet lets you add custom attributes to the global structuredData object.'
category: f_data
snippet: "/**\n * renderStructuredData\n *\n * NOT USED ANYMORE. ADDING DATA TO HTML IS HANDLED IN PLUGIN AGAIN.\n *\n * Turn given schema.org properties into a proper JSON-LD array.\n *\n * All types are collected in a central $graph object, which is initiated in the\n * Romanesco class.\n *\n * You can add / overwrite data types from any snippet that references the graph\n * or by creating a structuredDataTheme snippet. This snippet will be run after\n * everything else, so default types can be overwritten.\n *\n * NB: Put this snippet at the bottom of the page, because the graph cannot be\n * modified anymore once the placeholder is set!\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Spatie\\SchemaOrg\\Schema;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n// Kill switch\nif (!$romanesco->getConfigSetting('structured_data')) return;\n\n// Load global graph\n$graph = &$romanesco->structuredData;\n$data = $romanesco->getSchemaOptions();\n\n// Load custom properties\n$modx->runSnippet('structuredDataTheme', ['data' => $data]);\n\n// Write everything to page head\n$output = json_encode($graph, JSON_UNESCAPED_SLASHES);\n$modx->regClientStartupHTMLBlock($modx->getChunk('structuredDataSite', ['structured_data' => $output]));\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderStructuredData
 *
 * NOT USED ANYMORE. ADDING DATA TO HTML IS HANDLED IN PLUGIN AGAIN.
 *
 * Turn given schema.org properties into a proper JSON-LD array.
 *
 * All types are collected in a central $graph object, which is initiated in the
 * Romanesco class.
 *
 * You can add / overwrite data types from any snippet that references the graph
 * or by creating a structuredDataTheme snippet. This snippet will be run after
 * everything else, so default types can be overwritten.
 *
 * NB: Put this snippet at the bottom of the page, because the graph cannot be
 * modified anymore once the placeholder is set!
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

// Kill switch
if (!$romanesco->getConfigSetting('structured_data')) return;

// Load global graph
$graph = &$romanesco->structuredData;
$data = $romanesco->getSchemaOptions();

// Load custom properties
$modx->runSnippet('structuredDataTheme', ['data' => $data]);

// Write everything to page head
$output = json_encode($graph, JSON_UNESCAPED_SLASHES);
$modx->regClientStartupHTMLBlock($modx->getChunk('structuredDataSite', ['structured_data' => $output]));

return;