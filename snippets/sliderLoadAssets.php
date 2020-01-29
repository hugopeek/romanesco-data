id: 75
name: sliderLoadAssets
description: 'Load CSS and JS dependencies for Slick slider.'
category: f_presentation
snippet: "/**\n * sliderLoadAssets\n *\n * Loads dependencies for the Slick carousel (http://kenwheeler.github.io/slick/).\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n\n// Head\n//$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick.css');\n//$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick-theme.css');\n//$modx->regClientCSS($assetsPathVendor . '/slick-lightbox/slick-lightbox.css');\n$modx->regClientCSS($assetsPathCSS . '/slider.min.css');\n\n// Footer\n//$modx->regClientScript('//code.jquery.com/jquery-migrate-1.2.1.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-carousel/slick.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-lightbox/slick-lightbox.min.js');\n$modx->regClientScript($assetsPathJS . '/slider.min.js');\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * sliderLoadAssets\n *\n * Loads dependencies for the Slick carousel (http://kenwheeler.github.io/slick/).\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n\n// Head\n//$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick.css');\n//$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick-theme.css');\n//$modx->regClientCSS($assetsPathVendor . '/slick-lightbox/slick-lightbox.css');\n$modx->regClientCSS($assetsPathCSS . '/slider.min.css');\n\n// Footer\n//$modx->regClientScript('//code.jquery.com/jquery-migrate-1.2.1.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-carousel/slick.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-lightbox/slick-lightbox.min.js');\n$modx->regClientScript($assetsPathJS . '/slider.min.js');\n\nreturn '';"

-----


/**
 * sliderLoadAssets
 *
 * Loads dependencies for the Swiper carousel (https://swiperjs.com/).
 */

$uid = $modx->getOption('uid', $scriptProperties, 0);
$init = $modx->getOption('init', $scriptProperties, 'true');
$columns = $modx->getOption('columns', $scriptProperties, 1);
$scroll = $modx->getOption('slidesToScroll', $scriptProperties, 1);
$direction = $modx->getOption('direction', $scriptProperties, 'horizontal');
$spacing = $modx->getOption('spacing', $scriptProperties, 'none');
$behaviour = $modx->getOption('behaviour', $scriptProperties, '');
$transition = $modx->getOption('transition', $scriptProperties, 'slide');
$lazyload = $modx->getOption('lazyLoad', $scriptProperties, 0);
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
$var = 'swiper' . $uid;

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

        $(modalID).modal('show');

        $var.update();
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
$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');

// Head
//$modx->regClientCSS($assetsPathCSS . '/slider' . $minify . '.css');
$modx->regClientCSS($assetsPathCSS . '/swiper' . $minify . '.css');

// Footer
//$modx->regClientScript($assetsPathJS . '/slider' . $minify . '.js');
$modx->regClientScript('/' . $assetsPathVendor . '/swiper/swiper.min.js');
$modx->regClientScript('/' . $assetsPathJS . '/swiper' . $minify . '.js');
$modx->regClientHTMLBlock($modx->getChunk($tpl, array(
    'var' => $var,
    'id' => $id,
    'init' => $init,
    'cols' => $columns,
    'slides_to_scroll' => $scroll,
    'direction' => $direction,
    'spacing' => $spacing,
    'loop' => $loop ?? 'false',
    'free' => $free ?? 'false',
    'center' => $center ?? 'false',
    'auto_height' => $autoHeight ?? 'false',
    'autoplay' => $autoplay ?? 'false',
    'transition' => $transition,
    'pagination' => $pagination ?? '',
    'clickable' => $clickable,
    'breakpoints' => $breakpoints ?? '',
    'effects' => $effects[$transition] ?? '',
    'init_lightbox' => $initLightbox ?? '',
)));

return '';