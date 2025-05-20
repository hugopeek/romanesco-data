id: 44
name: AutomaticReferences
description: 'Turn references to an external link (the ones you can create under TVs > Links) into an actual link. Links are referenced by their number value and must be enclosed in square brackets: [12].'
category: c_content
plugincode: "/**\n * AutomaticReferences plugin\n *\n * Turn references to an external link (the ones you created under TVs > Links)\n * into an actual link. Links are referenced by their number value and must be\n * enclosed in square brackets: [12].\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @package romanesco\n */\n\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\n$tpl = $modx->getOption('tpl', $scriptProperties, 'externalNavItemLabel');\n\nswitch ($modx->event->name) {\n    case 'OnWebPagePrerender':\n\n        // Cached DOM output already includes references\n        $cacheManager = $modx->getCacheManager();\n        $cacheElementKey = '/dom';\n        $cacheOptions = [\n            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n        ];\n        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n        if ($cachedOutput && !$isLoggedIn) {\n            break;\n        }\n\n        // Get processed output of resource\n        $content = &$modx->resource->_output;\n\n        // Generate links if requested\n        if ($modx->resource->getTVValue('auto_references')) {\n            /** @var Romanesco $romanesco */\n            try {\n                $romanesco = $modx->services->get('romanesco');\n            } catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n                $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n            }\n\n            // Get external links for this resource\n            $linkObject = $modx->getIterator('FractalFarming\\Romanesco\\Model\\LinkExternal', [\n                'resource_id' => $modx->resource->get('id'),\n                'deleted' => 0\n            ]);\n\n            if (!is_object($linkObject)) break;\n\n            // Prepare array with link tags\n            $links = [];\n            foreach ($linkObject as $link) {\n                $links[$link->get('number')] = $modx->getChunk($tpl, $link->toArray());\n            }\n\n            // Feed output to HtmlPageDom\n            $dom = new HtmlPageCrawler($content);\n\n            // Search the content area for references\n            $dom->filter('#content')\n                ->filter('p,li')\n                ->each(function (HtmlPageCrawler $node) use ($links) {\n                    $text = $node->getInnerHtml();\n\n                    // Only accept numeric values inside square brackets\n                    preg_match_all('/\\[[0-9]+\\]/', $text, $matches);\n\n                    // Filter duplicate matches to avoid glitches\n                    $matches = array_unique($matches[0]);\n\n                    if (!$matches) return true;\n\n                    foreach ($matches as $match) {\n                        $number = filter_var($match, FILTER_SANITIZE_NUMBER_INT);\n                        $text = str_replace($match, $links[$number], $text);\n                    }\n\n                    $node->setInnerHtml($text);\n                    return true;\n                })\n            ;\n\n            $content = $dom->saveHTML();\n        }\n\n        break;\n}\n\nreturn true;"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * AutomaticReferences plugin
 *
 * Turn references to an external link (the ones you created under TVs > Links)
 * into an actual link. Links are referenced by their number value and must be
 * enclosed in square brackets: [12].
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package romanesco
 */

use FractalFarming\Romanesco\Romanesco;
use Wa72\HtmlPageDom\HtmlPageCrawler;

$tpl = $modx->getOption('tpl', $scriptProperties, 'externalNavItemLabel');

switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        // Cached DOM output already includes references
        $cacheManager = $modx->getCacheManager();
        $cacheElementKey = '/dom';
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
        ];
        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
        if ($cachedOutput && !$isLoggedIn) {
            break;
        }

        // Get processed output of resource
        $content = &$modx->resource->_output;

        // Generate links if requested
        if ($modx->resource->getTVValue('auto_references')) {
            /** @var Romanesco $romanesco */
            try {
                $romanesco = $modx->services->get('romanesco');
            } catch (\Psr\Container\NotFoundExceptionInterface $e) {
                $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
            }

            // Get external links for this resource
            $linkObject = $modx->getIterator('FractalFarming\Romanesco\Model\LinkExternal', [
                'resource_id' => $modx->resource->get('id'),
                'deleted' => 0
            ]);

            if (!is_object($linkObject)) break;

            // Prepare array with link tags
            $links = [];
            foreach ($linkObject as $link) {
                $links[$link->get('number')] = $modx->getChunk($tpl, $link->toArray());
            }

            // Feed output to HtmlPageDom
            $dom = new HtmlPageCrawler($content);

            // Search the content area for references
            $dom->filter('#content')
                ->filter('p,li')
                ->each(function (HtmlPageCrawler $node) use ($links) {
                    $text = $node->getInnerHtml();

                    // Only accept numeric values inside square brackets
                    preg_match_all('/\[[0-9]+\]/', $text, $matches);

                    // Filter duplicate matches to avoid glitches
                    $matches = array_unique($matches[0]);

                    if (!$matches) return true;

                    foreach ($matches as $match) {
                        $number = filter_var($match, FILTER_SANITIZE_NUMBER_INT);
                        $text = str_replace($match, $links[$number], $text);
                    }

                    $node->setInnerHtml($text);
                    return true;
                })
            ;

            $content = $dom->saveHTML();
        }

        break;
}

return true;