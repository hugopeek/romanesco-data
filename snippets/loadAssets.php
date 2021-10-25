id: 159
name: loadAssets
description: 'Load CSS and JS for a specific component. Some assets are included in semantic.css by default, to keep its file size down.'
category: f_presentation
snippet: "/**\n * loadAssets\n *\n * Generic asset loader. Needs a component value to decide which assets to load.\n *\n * The MODX regClient functions will automatically filter duplicate statements,\n * so you don't need to worry about an element being loaded more than once (as\n * opposed to putting the link / script in your templates).\n *\n * External javascript sources are loaded with defer, to keep their loading\n * sequence intact (based on position in HTML). CSS is loaded asynchronously if\n * it's not directly affecting page styling (modals for example) and if\n * critical CSS is enabled.\n *\n * Cache busting and minification is also taken care of. The biggest limitation\n * is that you can only add one component at the time.\n *\n * You can add custom CSS or JS by defining a custom component:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[[++romanesco.custom_css_path]]/custom[[+minify]][[+cache_buster_css]].css`\n *     &js=`[[++romanesco.custom_js_path]]/custom[[+minify]][[+cache_buster_js]].js`\n *     &inlineJS=`\n *         <script>\n *         window.addEventListener('DOMContentLoaded', function() {\n *             console.log('Something very custom');\n *         });\n *         </script>\n *     `\n * ]]\n *\n * This does support multiple CSS and JS references. Just add them in a valid\n * JSON array:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[\n *         \"[[++romanesco.custom_css_path]]/custom1[[+minify]][[+cache_buster_css:empty=``]].css\",\n *         \"[[++romanesco.custom_css_path]]/custom2[[+minify]][[+cache_buster_css:empty=``]].css\"\n *     ]`\n * ]]\n *\n * You can prevent asynchronous loading of custom CSS with &cssAsync=`0`.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Which component are we loading assets for\n$component = $modx->getOption('component', $scriptProperties, '');\nif (!$component) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');\n    return;\n}\n\n// Custom components bring their own CSS or JS\n$customCSS = $modx->getOption('css', $scriptProperties, '');\n$customJS = $modx->getOption('js', $scriptProperties, '');\n$customInlineJS = $modx->getOption('inlineJS', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Define conditions for loading CSS asynchronously\n$async = array(\n    'always' => ' media=\"print\" onload=\"this.media=\\'all\\'\"',\n    'critical' => '',\n    'custom' => '',\n);\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async['critical'] = $async['always'];\n}\nif ($modx->getOption('cssAsync', $scriptProperties, 1)) {\n    $async['custom'] = $async['always'];\n}\n\nswitch ($component) {\n    case 'hub':\n    case 'status grid':\n    case 'status-grid':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'table':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'step':\n    case 'steps':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        break;\n    case 'modal':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'dimmer':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'syntax-highlighting':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'map':\n        $modx->regClientStartupHTMLBlock(\n            '<link rel=\"stylesheet\" href=\"' . $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\">'\n        );\n        $modx->regClientStartupHTMLBlock(\n            '<script defer src=\"' . $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\"></script>'\n        );\n    case 'custom':\n        if (!function_exists('is_json')) {\n            function is_json($string) {\n                json_decode($string);\n                return json_last_error() === JSON_ERROR_NONE;\n            }\n        }\n        if ($customCSS) {\n            if (is_json($customCSS)) {\n                $customCSS = json_decode($customCSS, true);\n                foreach ($customCSS as $CSS) {\n                    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $CSS . '\"' . $async['custom'] . '>');\n                }\n            } else {\n                $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $customCSS . '\"' . $async['custom'] . '>');\n            }\n        }\n        if ($customJS) {\n            if (is_json($customJS)) {\n                $customJS = json_decode($customJS, true);\n                foreach ($customJS as $JS) {\n                    $modx->regClientHTMLBlock('<script defer src=\"' . $JS . '\"></script>');\n                }\n            } else {\n                $modx->regClientHTMLBlock('<script defer src=\"' . $customJS . '\"></script>');\n            }\n        }\n        if ($customInlineJS) {\n            $modx->regClientHTMLBlock($customInlineJS);\n        }\n        break;\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.loadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.loadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * loadAssets\n *\n * Generic asset loader. Needs a component value to decide which assets to load.\n *\n * The MODX regClient functions will automatically filter duplicate statements,\n * so you don't need to worry about an element being loaded more than once (as\n * opposed to putting the link / script in your templates).\n *\n * External javascript sources are loaded with defer, to keep their loading\n * sequence intact (based on position in HTML). CSS is loaded asynchronously if\n * it's not directly affecting page styling (modals for example) and if\n * critical CSS is enabled.\n *\n * Cache busting and minification is also taken care of. The biggest limitation\n * is that you can only add one component at the time.\n *\n * You can add custom CSS or JS by defining a custom component:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[[++romanesco.custom_css_path]]/custom[[+minify]][[+cache_buster_css]].css`\n *     &js=`[[++romanesco.custom_js_path]]/custom[[+minify]][[+cache_buster_js]].js`\n *     &inlineJS=`\n *         <script>\n *         window.addEventListener('DOMContentLoaded', function() {\n *             console.log('Something very custom');\n *         });\n *         </script>\n *     `\n * ]]\n *\n * This does support multiple CSS and JS references. Just add them in a valid\n * JSON array:\n *\n * [[loadAssets?\n *     &component=`custom`\n *     &css=`[\n *         \"[[++romanesco.custom_css_path]]/custom1[[+minify]][[+cache_buster_css:empty=``]].css\",\n *         \"[[++romanesco.custom_css_path]]/custom2[[+minify]][[+cache_buster_css:empty=``]].css\"\n *     ]`\n * ]]\n *\n * You can prevent asynchronous loading of custom CSS with &cssAsync=`0`.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Which component are we loading assets for\n$component = $modx->getOption('component', $scriptProperties, '');\nif (!$component) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');\n    return;\n}\n\n// Custom components bring their own CSS or JS\n$customCSS = $modx->getOption('css', $scriptProperties, '');\n$customJS = $modx->getOption('js', $scriptProperties, '');\n$customInlineJS = $modx->getOption('inlineJS', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Define conditions for loading CSS asynchronously\n$async = array(\n    'always' => ' media=\"print\" onload=\"this.media=\\'all\\'\"',\n    'critical' => '',\n    'custom' => '',\n);\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async['critical'] = $async['always'];\n}\nif ($modx->getOption('cssAsync', $scriptProperties, 1)) {\n    $async['custom'] = $async['always'];\n}\n\nswitch ($component) {\n    case 'hub':\n    case 'status grid':\n    case 'status-grid':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'table':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'step':\n    case 'steps':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        break;\n    case 'modal':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'dimmer':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async['always'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'syntax-highlighting':\n        $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css\"' . $async['critical'] . '>');\n        $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'map':\n        $modx->regClientStartupHTMLBlock(\n            '<link rel=\"stylesheet\" href=\"' . $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\">'\n        );\n        $modx->regClientStartupHTMLBlock(\n            '<script defer src=\"' . $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\"></script>'\n        );\n    case 'custom':\n        if (!function_exists('is_json')) {\n            function is_json($string) {\n                json_decode($string);\n                return json_last_error() === JSON_ERROR_NONE;\n            }\n        }\n        if ($customCSS) {\n            if (is_json($customCSS)) {\n                $customCSS = json_decode($customCSS, true);\n                foreach ($customCSS as $CSS) {\n                    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $CSS . '\"' . $async['custom'] . '>');\n                }\n            } else {\n                $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $customCSS . '\"' . $async['custom'] . '>');\n            }\n        }\n        if ($customJS) {\n            if (is_json($customJS)) {\n                $customJS = json_decode($customJS, true);\n                foreach ($customJS as $JS) {\n                    $modx->regClientHTMLBlock('<script defer src=\"' . $JS . '\"></script>');\n                }\n            } else {\n                $modx->regClientHTMLBlock('<script defer src=\"' . $customJS . '\"></script>');\n            }\n        }\n        if ($customInlineJS) {\n            $modx->regClientHTMLBlock($customInlineJS);\n        }\n        break;\n}\n\nreturn '';"

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
 * You can prevent asynchronous loading of custom CSS with &cssAsync=`0`.
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');
$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));
if (!($romanesco instanceof Romanesco)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');
    return;
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

// Define conditions for loading CSS asynchronously
$async = array(
    'always' => ' media="print" onload="this.media=\'all\'"',
    'critical' => '',
    'custom' => '',
);
if ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $async['critical'] = $async['always'];
}
if ($modx->getOption('cssAsync', $scriptProperties, 1)) {
    $async['custom'] = $async['always'];
}

switch ($component) {
    case 'hub':
    case 'status grid':
    case 'status-grid':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css"' . $async['critical'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css"' . $async['critical'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $async['always'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css"' . $async['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js"></script>');
        break;
    case 'table':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css"' . $async['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'step':
    case 'steps':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css"' . $async['critical'] . '>');
        break;
    case 'modal':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $async['always'] . '>');
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css"' . $async['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'dimmer':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $async['always'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js"></script>');
        break;
    case 'syntax-highlighting':
        $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css"' . $async['critical'] . '>');
        $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'map':
        $modx->regClientStartupHTMLBlock(
            '<link rel="stylesheet" href="' . $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .
            '" integrity="' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .
            '" crossorigin="">'
        );
        $modx->regClientStartupHTMLBlock(
            '<script defer src="' . $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .
            '" integrity="' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .
            '" crossorigin=""></script>'
        );
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
                    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $CSS . '"' . $async['custom'] . '>');
                }
            } else {
                $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $customCSS . '"' . $async['custom'] . '>');
            }
        }
        if ($customJS) {
            if (is_json($customJS)) {
                $customJS = json_decode($customJS, true);
                foreach ($customJS as $JS) {
                    $modx->regClientHTMLBlock('<script defer src="' . $JS . '"></script>');
                }
            } else {
                $modx->regClientHTMLBlock('<script defer src="' . $customJS . '"></script>');
            }
        }
        if ($customInlineJS) {
            $modx->regClientHTMLBlock($customInlineJS);
        }
        break;
}

return '';