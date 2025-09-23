id: 47
name: ReadingTime
description: 'Calculate the time needed to read the full text of an article.'
category: c_content
plugincode: "/**\n * ReadingTime plugin\n *\n * Calculate the time needed to read the full text of an article.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    case 'OnDocFormSave':\n        /** @var modResource $resource */\n\n        // Clear reading time to trigger recalculation\n        $resource->setTVValue('reading_time', '');\n\n        break;\n\n    case 'OnWebPagePrerender':\n\n        if ($modx->resource->getTVValue('reading_time')) {\n            break;\n        }\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n\n        // Cached DOM output already has reading time set\n        $cacheManager = $modx->getCacheManager();\n        $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);\n        $cacheOptions = [\n            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n        ];\n        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n        if ($cachedOutput && !$isLoggedIn) {\n            $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading reading time from cache');\n            break;\n        }\n\n        // Feed output to HtmlPageDom\n        $dom = new HtmlPageCrawler($content);\n\n        // Isolate article content\n        $article = $dom->filter('#content');\n\n        // Calculate reading time\n        $words = str_word_count(strip_tags($article));\n        $wpm = (int)$modx->getOption(\"romanesco.reading_time_wpm\", null, 180); // wpm = words per minute\n        $time = round($words / $wpm);\n\n        // Set singular or plural label\n        $label = $modx->lexicon('romanesco.article.reading_time');\n        if ($time <= 1) $label = $modx->lexicon('romanesco.article.reading_time_1');\n\n        // Add to content (empty text to avoid duplicates)\n        $dom->filter('.reading-time .value')->makeEmpty()->append(\"$time\");\n        $dom->filter('.reading-time .label')->makeEmpty()->append(\"$label\");\n        $content = $dom->saveHTML();\n\n        // Save to TV (make sure it is assigned to the template!)\n        $modx->resource->setTVValue('reading_time', $time);\n        $modx->resource->save();\n\n        break;\n}\n\nreturn true;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * ReadingTime plugin
 *
 * Calculate the time needed to read the full text of an article.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

use Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    case 'OnDocFormSave':
        /** @var modResource $resource */

        // Clear reading time to trigger recalculation
        $resource->setTVValue('reading_time', '');

        break;

    case 'OnWebPagePrerender':

        if ($modx->resource->getTVValue('reading_time')) {
            break;
        }

        // Get processed output of resource
        $content = &$modx->resource->_output;

        // Cached DOM output already has reading time set
        $cacheManager = $modx->getCacheManager();
        $cacheElementKey = '/dom.'. hash('xxh3', $_SERVER['REQUEST_URI']);
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
        ];
        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
        if ($cachedOutput && !$isLoggedIn) {
            $modx->log(modX::LOG_LEVEL_DEBUG, '[Romanesco3x] Loading reading time from cache');
            break;
        }

        // Feed output to HtmlPageDom
        $dom = new HtmlPageCrawler($content);

        // Isolate article content
        $article = $dom->filter('#content');

        // Calculate reading time
        $words = str_word_count(strip_tags($article));
        $wpm = (int)$modx->getOption("romanesco.reading_time_wpm", null, 180); // wpm = words per minute
        $time = round($words / $wpm);

        // Set singular or plural label
        $label = $modx->lexicon('romanesco.article.reading_time');
        if ($time <= 1) $label = $modx->lexicon('romanesco.article.reading_time_1');

        // Add to content (empty text to avoid duplicates)
        $dom->filter('.reading-time .value')->makeEmpty()->append("$time");
        $dom->filter('.reading-time .label')->makeEmpty()->append("$label");
        $content = $dom->saveHTML();

        // Save to TV (make sure it is assigned to the template!)
        $modx->resource->setTVValue('reading_time', $time);
        $modx->resource->save();

        break;
}

return true;