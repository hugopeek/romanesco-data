id: 178
name: cacheWarmup
description: 'Visit all URLs in sitemap to warm up the cache.'
category: f_performance
snippet: "/**\n * cacheWarmup\n *\n * Visit all URLs in sitemap to warm up the cache.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse EliasHaeussler\\CacheWarmup;\n\n$sitemapURL = $modx->getOption('sitemap_url', $scriptProperties, '');\n\n// Instantiate\n$cacheWarmer = new CacheWarmup\\CacheWarmer();\n\n// Configure\n$cacheWarmer->addSitemaps($sitemapURL);\n\n// Run\n$result = $cacheWarmer->run();\n\n// Report\n$output = '';\nif (!empty($successfulUrls = $result->getSuccessful())) {\n    foreach ($successfulUrls as $url) {\n        $modx->log(modX::LOG_LEVEL_INFO, '[CacheWarmup] Successfully warmed up ' . $url->getUri());\n    }\n    $output = \"Successfully warmed up all URLs.\";\n}\nif (!empty($failedUrls = $result->getFailed())) {\n    foreach ($failedUrls as $url) {\n        $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Failed to warm up ' . $url->getUri());\n    }\n    $output = \"Failed to warm up all URLs. Please refer to error log for details.\";\n}\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * cacheWarmup
 *
 * Visit all URLs in sitemap to warm up the cache.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use EliasHaeussler\CacheWarmup;

$sitemapURL = $modx->getOption('sitemap_url', $scriptProperties, '');

// Instantiate
$cacheWarmer = new CacheWarmup\CacheWarmer();

// Configure
$cacheWarmer->addSitemaps($sitemapURL);

// Run
$result = $cacheWarmer->run();

// Report
$output = '';
if (!empty($successfulUrls = $result->getSuccessful())) {
    foreach ($successfulUrls as $url) {
        $modx->log(modX::LOG_LEVEL_INFO, '[CacheWarmup] Successfully warmed up ' . $url->getUri());
    }
    $output = "Successfully warmed up all URLs.";
}
if (!empty($failedUrls = $result->getFailed())) {
    foreach ($failedUrls as $url) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[CacheWarmup] Failed to warm up ' . $url->getUri());
    }
    $output = "Failed to warm up all URLs. Please refer to error log for details.";
}

return $output;