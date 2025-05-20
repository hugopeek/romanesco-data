id: 75
name: sliderLoadAssets
description: 'Load CSS and JS dependencies for Swiper slider. It also initializes a Swiper instance for each slider, with it''s own parameters. This means you can use multiple sliders on one page.'
category: f_presentation
snippet: "/**\n * sliderLoadAssets\n *\n * Loads dependencies for the Swiper carousel (https://swiperjs.com/).\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nuse FractalFarming\\Romanesco\\Romanesco;\n/** @var Romanesco $romanesco */\n\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (\\Psr\\Container\\NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n$uid = $modx->getOption('uid', $scriptProperties, 0);\n$init = $modx->getOption('init', $scriptProperties, 'true');\n$columns = $modx->getOption('columns', $scriptProperties, 1);\n$scroll = $modx->getOption('slidesToScroll', $scriptProperties, 1);\n$direction = $modx->getOption('direction', $scriptProperties, 'horizontal');\n$spacing = $modx->getOption('spacing', $scriptProperties, 'none');\n$overflow = $modx->getOption('watchOverflow', $scriptProperties, 'true');\n$behaviour = $modx->getOption('behaviour', $scriptProperties, '');\n$transition = $modx->getOption('transition', $scriptProperties, 'slide');\n$pagination = $modx->getOption('pagination', $scriptProperties, 'none');\n$responsive = $modx->getOption('responsive', $scriptProperties, 0);\n$mobile = $modx->getOption('mobileOnly', $scriptProperties, 0);\n$lightbox = $modx->getOption('lightbox', $scriptProperties, 0);\n$tpl = $modx->getOption('tpl', $scriptProperties, 'sliderInitJS');\n\n// Convert option values to JS settings\n// Keep in mind that 'true' / 'false' needs to be a string here\n// -----------------------------------------------------------------------------\n\n// Set element ID and variable name\n$id = 'swiper-' . $uid;\n$var = 'Swiper' . $uid;\n\n// Convert semantic padding to numeric value\nswitch ($spacing) {\n    case 'relaxed':\n        $spacing = 20;\n        break;\n    case 'very relaxed':\n        $spacing = 30;\n        break;\n    default:\n        $spacing = 0;\n        break;\n}\n\n// Create variable for each behaviour setting\n$behaviour = explode(',', $behaviour);\nforeach ($behaviour as $option) {\n    $$option = 'true';\n}\n\n// Only bullet pagination can be clickable\n$clickable = ($pagination == 'bullets') ? 'true' : 'false';\n\n// Effects\n$effects = array(\n    'fade' => '\n        fadeEffect: {\n            crossFade: true\n        },\n    ',\n    'coverflow' => '\n        coverflowEffect: {\n            rotate: 30,\n            slideShadows: false,\n        },\n    ',\n    'flip' => '\n        flipEffect: {\n            rotate: 30,\n            slideShadows: false,\n        },\n    ',\n    'cube' => '\n        cubeEffect: {\n            slideShadows: false,\n        },\n    ',\n);\n\n// Responsive\nif ($responsive) {\n    $breakpoints = \"\n    breakpoints: {\n        '@0.75': {\n            slidesPerView: \" . round($columns / 2) . \",\n            spaceBetween: \" . $spacing / 2 . \",\n        },\n        '@1.00': {\n            slidesPerView: \" . round($columns * 0.75) . \",\n            spaceBetween: $spacing,\n        },\n        '@1.50': {\n            slidesPerView: $columns,\n            spaceBetween: \" . $spacing * 1.5 . \",\n        },\n    },\n    \";\n\n    // This feature is mobile-first, so set columns for smallest screens\n    $columns = round($columns / 4);\n}\n\n// Init lightbox modals with Swiper inside\nif ($lightbox == 1) {\n    $init = 'false';\n    $initLightbox = \"\n    $('#gallery-$uid .ui.lightbox.image').click(function () {\n        var idx = $(this).data('idx');\n        var modalID = '#lightbox-$uid';\n        var lazyLoadLightbox = new LazyLoad({\n            elements_selector: modalID + ' .lazy'\n        });\n\n        $(modalID)\n            .modal({\n                onVisible: function() {\n                    lazyLoadLightbox.loadAll();\n                    lazyLoadInstance.update();\n                }\n            })\n            .modal('show')\n        ;\n        $var.init();\n        $var.slideTo(idx, 0, false);\n    });\n    \";\n}\n\n// Use different tpl chunk for mobile only JS\nif ($mobile) {\n    $init = 'true';\n    $tpl = 'sliderMobileInitJS';\n}\n\n// Load assets in head and footer\n// -----------------------------------------------------------------------------\n\n// Check if minify assets setting is activated in Configuration\n$minify = '';\nif ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {\n    $minify = '.min';\n}\n\n// Paths\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Load component asynchronously if critical CSS is enabled\n$async = '';\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async = ' media=\"print\" onload=\"this.media=\\'all\\'\"';\n}\n\n// Head\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathCSS . '/swiper' . $minify . $cacheBusterCSS . '.css\"' . $async . '>');\n\n// Footer\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/swiper/swiper-bundle.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock($modx->getChunk($tpl, array(\n    'var' => $var,\n    'id' => $id,\n    'init' => $init,\n    'cols' => $columns,\n    'slides_to_scroll' => $scroll,\n    'direction' => $direction,\n    'spacing' => $spacing,\n    'overflow' => $overflow ?? 'true',\n    'loop' => $loop ?? 'false',\n    'free' => $free ?? 'false',\n    'center' => $center ?? 'false',\n    'auto_height' => $autoHeight ?? 'false',\n    'autoplay' => $autoplay ?? 'false',\n    'keyboard' => $keyboard ?? 'false',\n    'transition' => $transition,\n    'pagination' => $pagination ?? '',\n    'clickable' => $clickable,\n    'breakpoints' => $breakpoints ?? '',\n    'effects' => $effects[$transition] ?? '',\n    'init_lightbox' => $initLightbox ?? '',\n)));\n\n// Load modal assets if lightbox is active\nif ($lightbox == 1) {\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n    $modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n    $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js\"></script>');\n    $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js\"></script>');\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * sliderLoadAssets
 *
 * Loads dependencies for the Swiper carousel (https://swiperjs.com/).
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

use FractalFarming\Romanesco\Romanesco;
/** @var Romanesco $romanesco */

try {
    $romanesco = $modx->services->get('romanesco');
} catch (\Psr\Container\NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

$uid = $modx->getOption('uid', $scriptProperties, 0);
$init = $modx->getOption('init', $scriptProperties, 'true');
$columns = $modx->getOption('columns', $scriptProperties, 1);
$scroll = $modx->getOption('slidesToScroll', $scriptProperties, 1);
$direction = $modx->getOption('direction', $scriptProperties, 'horizontal');
$spacing = $modx->getOption('spacing', $scriptProperties, 'none');
$overflow = $modx->getOption('watchOverflow', $scriptProperties, 'true');
$behaviour = $modx->getOption('behaviour', $scriptProperties, '');
$transition = $modx->getOption('transition', $scriptProperties, 'slide');
$pagination = $modx->getOption('pagination', $scriptProperties, 'none');
$responsive = $modx->getOption('responsive', $scriptProperties, 0);
$mobile = $modx->getOption('mobileOnly', $scriptProperties, 0);
$lightbox = $modx->getOption('lightbox', $scriptProperties, 0);
$tpl = $modx->getOption('tpl', $scriptProperties, 'sliderInitJS');

// Convert option values to JS settings
// Keep in mind that 'true' / 'false' needs to be a string here
// -----------------------------------------------------------------------------

// Set element ID and variable name
$id = 'swiper-' . $uid;
$var = 'Swiper' . $uid;

// Convert semantic padding to numeric value
switch ($spacing) {
    case 'relaxed':
        $spacing = 20;
        break;
    case 'very relaxed':
        $spacing = 30;
        break;
    default:
        $spacing = 0;
        break;
}

// Create variable for each behaviour setting
$behaviour = explode(',', $behaviour);
foreach ($behaviour as $option) {
    $$option = 'true';
}

// Only bullet pagination can be clickable
$clickable = ($pagination == 'bullets') ? 'true' : 'false';

// Effects
$effects = array(
    'fade' => '
        fadeEffect: {
            crossFade: true
        },
    ',
    'coverflow' => '
        coverflowEffect: {
            rotate: 30,
            slideShadows: false,
        },
    ',
    'flip' => '
        flipEffect: {
            rotate: 30,
            slideShadows: false,
        },
    ',
    'cube' => '
        cubeEffect: {
            slideShadows: false,
        },
    ',
);

// Responsive
if ($responsive) {
    $breakpoints = "
    breakpoints: {
        '@0.75': {
            slidesPerView: " . round($columns / 2) . ",
            spaceBetween: " . $spacing / 2 . ",
        },
        '@1.00': {
            slidesPerView: " . round($columns * 0.75) . ",
            spaceBetween: $spacing,
        },
        '@1.50': {
            slidesPerView: $columns,
            spaceBetween: " . $spacing * 1.5 . ",
        },
    },
    ";

    // This feature is mobile-first, so set columns for smallest screens
    $columns = round($columns / 4);
}

// Init lightbox modals with Swiper inside
if ($lightbox == 1) {
    $init = 'false';
    $initLightbox = "
    $('#gallery-$uid .ui.lightbox.image').click(function () {
        var idx = $(this).data('idx');
        var modalID = '#lightbox-$uid';
        var lazyLoadLightbox = new LazyLoad({
            elements_selector: modalID + ' .lazy'
        });

        $(modalID)
            .modal({
                onVisible: function() {
                    lazyLoadLightbox.loadAll();
                    lazyLoadInstance.update();
                }
            })
            .modal('show')
        ;
        $var.init();
        $var.slideTo(idx, 0, false);
    });
    ";
}

// Use different tpl chunk for mobile only JS
if ($mobile) {
    $init = 'true';
    $tpl = 'sliderMobileInitJS';
}

// Load assets in head and footer
// -----------------------------------------------------------------------------

// Check if minify assets setting is activated in Configuration
$minify = '';
if ($modx->getObject('cgSetting', array('key' => 'minify_css_js'))->get('value') == 1) {
    $minify = '.min';
}

// Paths
$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');

// Load strings to insert in asset paths when cache busting is enabled
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');
$cacheBusterJS = $romanesco->getCacheBustingString('JS');

// Load component asynchronously if critical CSS is enabled
$async = '';
if ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $async = ' media="print" onload="this.media=\'all\'"';
}

// Head
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathCSS . '/swiper' . $minify . $cacheBusterCSS . '.css"' . $async . '>');

// Footer
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/swiper/swiper-bundle.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock($modx->getChunk($tpl, array(
    'var' => $var,
    'id' => $id,
    'init' => $init,
    'cols' => $columns,
    'slides_to_scroll' => $scroll,
    'direction' => $direction,
    'spacing' => $spacing,
    'overflow' => $overflow ?? 'true',
    'loop' => $loop ?? 'false',
    'free' => $free ?? 'false',
    'center' => $center ?? 'false',
    'auto_height' => $autoHeight ?? 'false',
    'autoplay' => $autoplay ?? 'false',
    'keyboard' => $keyboard ?? 'false',
    'transition' => $transition,
    'pagination' => $pagination ?? '',
    'clickable' => $clickable,
    'breakpoints' => $breakpoints ?? '',
    'effects' => $effects[$transition] ?? '',
    'init_lightbox' => $initLightbox ?? '',
)));

// Load modal assets if lightbox is active
if ($lightbox == 1) {
    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterCSS . '.css"' . $async . '>');
    $modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/modal.min' . $cacheBusterCSS . '.css"' . $async . '>');
    $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dimmer.min' . $cacheBusterJS . '.js"></script>');
    $modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/modal.min' . $cacheBusterJS . '.js"></script>');
}

return '';