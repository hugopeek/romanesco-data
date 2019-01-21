id: 75
name: sliderLoadAssets
description: 'Load CSS and JS dependencies for Slick slider.'
category: f_presentation
snippet: "/**\n * sliderLoadAssets\n *\n * Loads dependencies for the Slick carousel (http://kenwheeler.github.io/slick/).\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n\n// Head\n$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick.css');\n$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick-theme.css');\n$modx->regClientCSS($assetsPathVendor . '/slick-lightbox/slick-lightbox.css');\n\n// Footer\n//$modx->regClientScript('//code.jquery.com/jquery-migrate-1.2.1.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-carousel/slick.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-lightbox/slick-lightbox.min.js');\n$modx->regClientScript($assetsPathJS . '/slider.js');\n\nreturn '';"
properties: 'a:0:{}'
content: "/**\n * sliderLoadAssets\n *\n * Loads dependencies for the Slick carousel (http://kenwheeler.github.io/slick/).\n */\n\n$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');\n\n// Head\n$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick.css');\n$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick-theme.css');\n$modx->regClientCSS($assetsPathVendor . '/slick-lightbox/slick-lightbox.css');\n\n// Footer\n//$modx->regClientScript('//code.jquery.com/jquery-migrate-1.2.1.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-carousel/slick.min.js');\n$modx->regClientScript($assetsPathVendor . '/slick-lightbox/slick-lightbox.min.js');\n$modx->regClientScript($assetsPathJS . '/slider.js');\n\nreturn '';"

-----


/**
 * sliderLoadAssets
 *
 * Loads dependencies for the Slick carousel (http://kenwheeler.github.io/slick/).
 */

$assetsPathCSS = $modx->getOption('romanesco.custom_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.custom_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.custom_vendor_path', $scriptProperties, '');

// Head
$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick.css');
$modx->regClientCSS($assetsPathVendor . '/slick-carousel/slick-theme.css');
$modx->regClientCSS($assetsPathVendor . '/slick-lightbox/slick-lightbox.css');

// Footer
//$modx->regClientScript('//code.jquery.com/jquery-migrate-1.2.1.min.js');
$modx->regClientScript($assetsPathVendor . '/slick-carousel/slick.min.js');
$modx->regClientScript($assetsPathVendor . '/slick-lightbox/slick-lightbox.min.js');
$modx->regClientScript($assetsPathJS . '/slider.js');

return '';