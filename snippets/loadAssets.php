id: 159
name: loadAssets
description: 'Load CSS and JS for a specific component. Some assets are included in semantic.css by default, to keep its file size down.'
category: f_presentation
snippet: "/**\n * loadAssets\n *\n * Generic asset loader. Needs a component value to decide which assets to load.\n *\n * The MODX regClient functions will automatically filter duplicate statements,\n * so you don't need to worry about an element being loaded more than once (as\n * opposed to putting the link / script in your templates).\n *\n * External javascript sources are loaded with defer, to keep their loading\n * sequence intact (based on position in HTML). CSS is loaded asynchronously if\n * it's not directly affecting page styling (modals for example) and if\n * critical CSS is enabled.\n *\n * Cache busting and minification is also taken care of. The biggest limitation\n * is that you can only add one component at the time.\n *\n * You can add custom CSS or JS by defining a custom component:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[[++romanesco.custom_css_path]]/custom[[+minify]][[+cache_buster_css]].css`\n *     &js=`[[++romanesco.custom_js_path]]/custom[[+minify]][[+cache_buster_js]].js`\n *     &inlineJS=`\n *         <script>\n *         window.addEventListener('DOMContentLoaded', function() {\n *             console.log('Something very custom');\n *         });\n *         </script>\n *     `\n * ]]\n *\n * This does support multiple CSS and JS references. Just add them in a valid\n * JSON array:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[\n *         \"[[++romanesco.custom_css_path]]/custom1[[+minify]][[+cache_buster_css:empty=``]].css\",\n *         \"[[++romanesco.custom_css_path]]/custom2[[+minify]][[+cache_buster_css:empty=``]].css\"\n *     ]`\n * ]]\n *\n * You can prevent asynchronous loading of custom assets with &async=`0`.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\n/** @var Romanesco $romanesco */\n\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Which component are we loading assets for\n$component = $modx->getOption('component', $scriptProperties, '');\nif (!$component) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');\n    return;\n}\n\n// Custom components bring their own CSS or JS\n$customCSS = $modx->getOption('css', $scriptProperties, '');\n$customJS = $modx->getOption('js', $scriptProperties, '');\n$customInlineJS = $modx->getOption('inlineJS', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Define conditions for loading assets asynchronously\n$asyncCSS = [\n    'always' => ' media=\"print\" onload=\"this.media=\\'all\\'\"',\n    'critical' => '',\n    'custom' => '',\n];\n$asyncJS = [\n    'always' => ' async',\n    'custom' => '',\n];\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $asyncCSS['critical'] = $asyncCSS['always'];\n}\nif ($modx->getOption('asyncCSS', $scriptProperties, 1)) {\n    $asyncCSS['custom'] = $asyncCSS['always'];\n}\nif ($modx->getOption('asyncJS', $scriptProperties, 0)) {\n    $asyncJS['custom'] = $asyncJS['always'];\n}\n\nswitch ($component) {\n    case 'hub':\n    case 'status grid':\n    case 'status-grid':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'table':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'tab':\n    case 'tabs':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/tab.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/tab.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'step':\n    case 'steps':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        break;\n    case 'dropdown':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'dropdown-css':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        break;\n    case 'popup':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'lightbox':\n        // Borrow swiper styling and fall through to next case\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathCSS . '/swiper.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n    case 'lightbox':\n    case 'modal':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'dimmer':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'embed':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/embed.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/embed.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'toast':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/toast.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/toast.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'rating':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/rating.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/rating.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'search':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/search.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/api.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/search.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'loader':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/loader.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['always'] . '>');\n        break;\n    case 'feed':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/feed.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        break;\n    case 'flag':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/flag.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        break;\n    case 'code':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'map':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathVendor . '/leaflet/leaflet' . $cacheBusterCSS . '.css\"' . $asyncCSS['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/leaflet/leaflet' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'custom':\n        if (!function_exists('is_json')) {\n            function is_json($string) {\n                json_decode($string);\n                return json_last_error() === JSON_ERROR_NONE;\n            }\n        }\n        if ($customCSS) {\n            if (is_json($customCSS)) {\n                $customCSS = json_decode($customCSS, true);\n                foreach ($customCSS as $CSS) {\n                    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $CSS . '\"' . $asyncCSS['custom'] . '>');\n                }\n            } else {\n                $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $customCSS . '\"' . $asyncCSS['custom'] . '>');\n            }\n        }\n        if ($customJS) {\n            if (is_json($customJS)) {\n                $customJS = json_decode($customJS, true);\n                foreach ($customJS as $JS) {\n                    $modx->regClientHTMLBlock('<script defer' . $asyncJS['custom'] . ' src=\"' . $JS . '\"></script>');\n                }\n            } else {\n                $modx->regClientHTMLBlock('<script defer' . $asyncJS['custom'] . ' src=\"' . $customJS . '\"></script>');\n            }\n        }\n        if ($customInlineJS) {\n            $modx->regClientHTMLBlock($customInlineJS);\n        }\n        break;\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * loadAssets
 *
 * Generic asset loader. Needs a component value to decide which assets to load.
 *
 * The MODX regClient functions will automatically filter duplicate statements,
 * so you don't need to worry about an element being loaded more than once (as
 * opposed to putting the link / script in your templates).
 *
 * External javascript sources are loaded with defer, to keep their loading
 * sequence intact (based on position in HTML). CSS is loaded asynchronously if
 * it's not directly affecting page styling (modals for example) and if
 * critical CSS is enabled.
 *
 * Cache busting and minification is also taken care of. The biggest limitation
 * is that you can only add one component at the time.
 *
 * You can add custom CSS or JS by defining a custom component:
 *
 * [[loadAssets?
 *     &component=`custom`
 *     &css=`[[++romanesco.custom_css_path]]/custom[[+minify]][[+cache_buster_css]].css`
 *     &js=`[[++romanesco.custom_js_path]]/custom[[+minify]][[+cache_buster_js]].js`
 *     &inlineJS=`
 *         <script>
 *         window.addEventListener('DOMContentLoaded', function() {
 *             console.log('Something very custom');
 *         });
 *         </script>
 *     `
 * ]]
 *
 * This does support multiple CSS and JS references. Just add them in a valid
 * JSON array:
 *
 * [[loadAssets?
 *     &component=`custom`
 *     &css=`[
 *         "[[++romanesco.custom_css_path]]/custom1[[+minify]][[+cache_buster_css:empty=``]].css",
 *         "[[++romanesco.custom_css_path]]/custom2[[+minify]][[+cache_buster_css:empty=``]].css"
 *     ]`
 * ]]
 *
 * You can prevent asynchronous loading of custom assets with &async=`0`.
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use FractalFarming\Romanesco\Romanesco;
/** @var Romanesco $romanesco */

try {
    $romanesco = $modx->services->get('romanesco');
} catch (\Psr\Container\NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');

// Which component are we loading assets for
$component = $modx->getOption('component', $scriptProperties, '');
if (!$component) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');
    return;
}

// Custom components bring their own CSS or JS
$customCSS = $modx->getOption('css', $scriptProperties, '');
$customJS = $modx->getOption('js', $scriptProperties, '');
$customInlineJS = $modx->getOption('inlineJS', $scriptProperties, '');

// Load strings to insert in asset paths when cache busting is enabled
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');
$cacheBusterJS = $romanesco->getCacheBustingString('JS');

// Check if minify assets setting is activated in Configuration
$minify = '';
if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {
    $minify = '.min';
}

// Define conditions for loading assets asynchronously
$asyncCSS = [
    'always' => ' media="print" onload="this.media=\'all\'"',
    'critical' => '',
    'custom' => '',
];
$asyncJS = [
    'always' => ' async',
    'custom' => '',
];
if ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $asyncCSS['critical'] = $asyncCSS['always'];
}
if ($modx->getOption('asyncCSS', $scriptProperties, 1)) {
    $asyncCSS['custom'] = $asyncCSS['always'];
}
if ($modx->getOption('asyncJS', $scriptProperties, 0)) {
    $asyncJS['custom'] = $asyncJS['always'];
}

switch ($component) {
    case 'hub':
    case 'status grid':
    case 'status-grid':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js"></script>');
        break;
    case 'table':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'tab':
    case 'tabs':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/tab.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/tab.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'step':
    case 'steps':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        break;
    case 'dropdown':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'dropdown-css':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        break;
    case 'popup':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'lightbox':
        // Borrow swiper styling and fall through to next case
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathCSS . '/swiper.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
    case 'lightbox':
    case 'modal':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'dimmer':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'embed':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/embed.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/embed.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'toast':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/toast.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/toast.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'rating':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/rating.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/rating.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'search':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/search.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/api.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/search.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'loader':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/loader.min' . $cacheBusterCSS . '.css"' . $asyncCSS['always'] . '>');
        break;
    case 'feed':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/feed.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        break;
    case 'flag':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/flag.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        break;
    case 'code':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'map':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathVendor . '/leaflet/leaflet' . $cacheBusterCSS . '.css"' . $asyncCSS['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/leaflet/leaflet' . $cacheBusterJS . '.js"></script>');
        break;
    case 'custom':
        if (!function_exists('is_json')) {
            function is_json($string) {
                json_decode($string);
                return json_last_error() === JSON_ERROR_NONE;
            }
        }
        if ($customCSS) {
            if (is_json($customCSS)) {
                $customCSS = json_decode($customCSS, true);
                foreach ($customCSS as $CSS) {
                    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $CSS . '"' . $asyncCSS['custom'] . '>');
                }
            } else {
                $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $customCSS . '"' . $asyncCSS['custom'] . '>');
            }
        }
        if ($customJS) {
            if (is_json($customJS)) {
                $customJS = json_decode($customJS, true);
                foreach ($customJS as $JS) {
                    $modx->regClientHTMLBlock('<script defer' . $asyncJS['custom'] . ' src="' . $JS . '"></script>');
                }
            } else {
                $modx->regClientHTMLBlock('<script defer' . $asyncJS['custom'] . ' src="' . $customJS . '"></script>');
            }
        }
        if ($customInlineJS) {
            $modx->regClientHTMLBlock($customInlineJS);
        }
        break;
}

return '';