id: 159
name: loadAssets
description: 'Load CSS and JS for a specific component. Some assets are included in semantic.css by default, to keep its file size down.'
category: f_presentation
snippet: "/**\n * loadAssets\n *\n * Generic asset loader. Needs component value to decide which assets to load.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Which component are we loading assets for\n$component = $modx->getOption('component', $scriptProperties, '');\nif (!$component) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');\n    return;\n}\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Load component asynchronously if critical CSS is enabled\n$async = '';\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async = ' media=\"print\" onload=\"this.media=\\'all\\'\"';\n}\n\nswitch ($component) {\n    case 'hub':\n    case 'status grid':\n    case 'status-grid':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js\"></script>');\n    break;\n    case 'table':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'step':\n    case 'steps':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        break;\n    case 'dimmer':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'syntax-highlighting':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'map':\n        $modx->regClientStartupHTMLBlock('<link href=\"' .\n            $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\" ' . $async . '>');\n        // JS is added to the HEAD of the page (without defer), so maps can be initialized from content area.\n        $modx->regClientStartupHTMLBlock('<script src=\"' .\n            $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\"></script>');\n        break;\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.loadassets.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.loadassets.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * loadAssets\n *\n * Generic asset loader. Needs component value to decide which assets to load.\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$corePath = $modx->getOption('romanescobackyard.core_path', null, $modx->getOption('core_path') . 'components/romanescobackyard/');\n$romanesco = $modx->getService('romanesco','Romanesco',$corePath . 'model/romanescobackyard/',array('core_path' => $corePath));\nif (!($romanesco instanceof Romanesco)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco] Class not found!');\n    return;\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Which component are we loading assets for\n$component = $modx->getOption('component', $scriptProperties, '');\nif (!$component) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[loadAssets] Component not defined!');\n    return;\n}\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Load component asynchronously if critical CSS is enabled\n$async = '';\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async = ' media=\"print\" onload=\"this.media=\\'all\\'\"';\n}\n\nswitch ($component) {\n    case 'hub':\n    case 'status grid':\n    case 'status-grid':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js\"></script>');\n    break;\n    case 'table':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'step':\n    case 'steps':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        break;\n    case 'dimmer':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'syntax-highlighting':\n        $modx->regClientStartupHTMLBlock('<link href=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css\" rel=\"stylesheet\" type=\"text/css\"' . $async . '>');\n        $modx->regClientStartupHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js\"></script>');\n        break;\n    case 'map':\n        $modx->regClientStartupHTMLBlock('<link href=\"' .\n            $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\" ' . $async . '>');\n        // JS is added to the HEAD of the page (without defer), so maps can be initialized from content area.\n        $modx->regClientStartupHTMLBlock('<script src=\"' .\n            $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .\n            '\" integrity=\"' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .\n            '\" crossorigin=\"\"></script>');\n        break;\n}\n\nreturn '';"

-----


/**
 * loadAssets
 *
 * Generic asset loader. Needs component value to decide which assets to load.
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

// Load strings to insert in asset paths when cache busting is enabled
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');
$cacheBusterJS = $romanesco->getCacheBustingString('JS');

// Check if minify assets setting is activated in Configuration
$minify = '';
if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {
    $minify = '.min';
}

// Load component asynchronously if critical CSS is enabled
$async = '';
if ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $async = ' media="print" onload="this.media=\'all\'"';
}

switch ($component) {
    case 'hub':
    case 'status grid':
    case 'status-grid':
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathJS . '/hub' . $minify . $cacheBusterJS . '.js"></script>');
    break;
    case 'table':
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathVendor . '/tablesort/tablesort.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'step':
    case 'steps':
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/step.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        break;
    case 'dimmer':
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathJS . '/dimmer'. $minify . $cacheBusterJS . '.js"></script>');
        break;
    case 'syntax-highlighting':
        $modx->regClientStartupHTMLBlock('<link href="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterCSS . '.css" rel="stylesheet" type="text/css"' . $async . '>');
        $modx->regClientStartupHTMLBlock('<script defer src="' . $assetsPathVendor . '/prism/prism.min' . $cacheBusterJS . '.js"></script>');
        break;
    case 'map':
        $modx->regClientStartupHTMLBlock('<link href="' .
            $modx->getOption('romanesco.leaflet_css_url', $scriptProperties, '') .
            '" integrity="' . $modx->getOption('romanesco.leaflet_css_integrity', $scriptProperties, '') .
            '" crossorigin="" ' . $async . '>');
        // JS is added to the HEAD of the page (without defer), so maps can be initialized from content area.
        $modx->regClientStartupHTMLBlock('<script src="' .
            $modx->getOption('romanesco.leaflet_js_url', $scriptProperties, '') .
            '" integrity="' . $modx->getOption('romanesco.leaflet_js_integrity', $scriptProperties, '') .
            '" crossorigin=""></script>');
        break;
}

return '';