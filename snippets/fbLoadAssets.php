id: 58
name: fbLoadAssets
category: f_formblocks
snippet: "/**\n * fbLoadAssets snippet\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse FractalFarming\\Romanesco\\Romanesco;\nuse Psr\\Container\\NotFoundExceptionInterface;\n\n/** @var Romanesco $romanesco */\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');\n$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');\n$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');\n$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');\n$devMode = $modx->getOption('romanesco.dev_mode', $scriptProperties, 0);\n$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);\n$validation = $modx->getOption('frontendValidation', $scriptProperties, $modx->getOption('formblocks.frontend_validation'));\n$validationTpl = $modx->getOption('validationTpl', $scriptProperties, 'fbValidation');\n$antiSpamHooks = $modx->getOption('antiSpamHooks', $scriptProperties, $modx->getOption('formblocks.antispam_hooks'));\n$ajax = $modx->getOption('ajaxMode', $scriptProperties, $modx->getOption('formblocks.ajax_mode'));\n$ajaxTpl = $modx->getOption('submitAjaxTpl', $scriptProperties, 'fbSubmitAjax');\n$recaptchaTpl = $modx->getOption('recaptchaTpl', $scriptProperties, 'recaptchaLoadAssets');\n$turnstileTpl = $modx->getOption('turnstileTpl', $scriptProperties, 'turnstileLoadAssets');\n\n// Load strings to insert in asset paths when cache busting is enabled\n$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');\n$cacheBusterJS = $romanesco->getCacheBustingString('JS');\n\n// Load component asynchronously if critical CSS is enabled\n$async = '';\nif ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {\n    $async = ' media=\"print\" onload=\"this.media=\\'all\\'\"';\n}\n\n// Load CSS\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/checkbox.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/form.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/calendar.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n$modx->regClientStartupHTMLBlock('<link rel=\"stylesheet\" href=\"' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css\"' . $async . '>');\n\n// Load JS\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/checkbox.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/form.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathDist . '/components/calendar.min' . $cacheBusterJS . '.js\"></script>');\n$modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/formblocks.min' . $cacheBusterJS . '.js\"></script>');\n\n// Load additional assets for file upload field, if present\nif ($uploadFile) {\n    $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathVendor . '/arrive/arrive.min' . $cacheBusterJS . '.js\"></script>');\n    $modx->regClientHTMLBlock('<script defer src=\"' . $assetsPathJS . '/fileupload.min' . $cacheBusterJS . '.js\"></script>');\n}\n\n// Load assets for Recaptcha v3, if enabled\nif (str_contains($antiSpamHooks, 'recaptchav3') && !$devMode) {\n    $modx->regClientHTMLBlock($modx->getChunk($recaptchaTpl));\n}\n// Load assets for Cloudflare Turnstile, if enabled\nif (str_contains($antiSpamHooks, 'turnstile') && !$devMode) {\n    $modx->regClientHTMLBlock($modx->getChunk($turnstileTpl));\n}\n\n// Load frontend validation, if enabled\nif ($validation) {\n    $modx->regClientHTMLBlock($modx->getChunk($validationTpl));\n}\n\n// Submit form via AJAX (only if frontend validation is disabled)\nif (!$validation && $ajax) {\n    $modx->regClientHTMLBlock($modx->getChunk($ajaxTpl));\n}\n\n// Load custom assets, if present\n// @todo: make this more dynamic\nif (is_file('assets/js/formblocks.min.js')) {\n    $modx->regClientHTMLBlock('<script defer src=\"assets/js/formblocks.min' . $cacheBusterJS . '.js\"></script>');\n} elseif (is_file('assets/js/formblocks.js')) {\n    $modx->regClientHTMLBlock('<script defer src=\"assets/js/formblocks' . $cacheBusterJS . '.js\"></script>');\n}\nif (is_file('assets/js/form-validation.min.js')) {\n    $modx->regClientHTMLBlock('<script defer src=\"assets/js/form-validation.min' . $cacheBusterJS . '.js\"></script>');\n} elseif (is_file('assets/js/form-validation.js')) {\n    $modx->regClientHTMLBlock('<script defer src=\"assets/js/form-validation' . $cacheBusterJS . '.js\"></script>');\n}\n\nreturn '';"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbLoadAssets snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use FractalFarming\Romanesco\Romanesco;
use Psr\Container\NotFoundExceptionInterface;

/** @var Romanesco $romanesco */
try {
    $romanesco = $modx->services->get('romanesco');
} catch (NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

$assetsPathCSS = $modx->getOption('romanesco.semantic_css_path', $scriptProperties, '');
$assetsPathJS = $modx->getOption('romanesco.semantic_js_path', $scriptProperties, '');
$assetsPathVendor = $modx->getOption('romanesco.semantic_vendor_path', $scriptProperties, '');
$assetsPathDist = $modx->getOption('romanesco.semantic_dist_path', $scriptProperties, '');
$devMode = $modx->getOption('romanesco.dev_mode', $scriptProperties, 0);
$uploadFile = $modx->getOption('uploadFile', $scriptProperties, 0);
$validation = $modx->getOption('frontendValidation', $scriptProperties, $modx->getOption('formblocks.frontend_validation'));
$validationTpl = $modx->getOption('validationTpl', $scriptProperties, 'fbValidation');
$antiSpamHooks = $modx->getOption('antiSpamHooks', $scriptProperties, $modx->getOption('formblocks.antispam_hooks'));
$ajax = $modx->getOption('ajaxMode', $scriptProperties, $modx->getOption('formblocks.ajax_mode'));
$ajaxTpl = $modx->getOption('submitAjaxTpl', $scriptProperties, 'fbSubmitAjax');
$recaptchaTpl = $modx->getOption('recaptchaTpl', $scriptProperties, 'recaptchaLoadAssets');
$turnstileTpl = $modx->getOption('turnstileTpl', $scriptProperties, 'turnstileLoadAssets');

// Load strings to insert in asset paths when cache busting is enabled
$cacheBusterCSS = $romanesco->getCacheBustingString('CSS');
$cacheBusterJS = $romanesco->getCacheBustingString('JS');

// Load component asynchronously if critical CSS is enabled
$async = '';
if ($romanesco->getConfigSetting('critical_css', $modx->resource->get('context_key'))) {
    $async = ' media="print" onload="this.media=\'all\'"';
}

// Load CSS
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/checkbox.min' . $cacheBusterCSS . '.css"' . $async . '>');
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterCSS . '.css"' . $async . '>');
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/popup.min' . $cacheBusterCSS . '.css"' . $async . '>');
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/form.min' . $cacheBusterCSS . '.css"' . $async . '>');
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/calendar.min' . $cacheBusterCSS . '.css"' . $async . '>');
$modx->regClientStartupHTMLBlock('<link rel="stylesheet" href="' . $assetsPathDist . '/components/table.min' . $cacheBusterCSS . '.css"' . $async . '>');

// Load JS
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/checkbox.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/dropdown.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/popup.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/form.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathDist . '/components/calendar.min' . $cacheBusterJS . '.js"></script>');
$modx->regClientHTMLBlock('<script defer src="' . $assetsPathJS . '/formblocks.min' . $cacheBusterJS . '.js"></script>');

// Load additional assets for file upload field, if present
if ($uploadFile) {
    $modx->regClientHTMLBlock('<script defer src="' . $assetsPathVendor . '/arrive/arrive.min' . $cacheBusterJS . '.js"></script>');
    $modx->regClientHTMLBlock('<script defer src="' . $assetsPathJS . '/fileupload.min' . $cacheBusterJS . '.js"></script>');
}

// Load assets for Recaptcha v3, if enabled
if (str_contains($antiSpamHooks, 'recaptchav3') && !$devMode) {
    $modx->regClientHTMLBlock($modx->getChunk($recaptchaTpl));
}
// Load assets for Cloudflare Turnstile, if enabled
if (str_contains($antiSpamHooks, 'turnstile') && !$devMode) {
    $modx->regClientHTMLBlock($modx->getChunk($turnstileTpl));
}

// Load frontend validation, if enabled
if ($validation) {
    $modx->regClientHTMLBlock($modx->getChunk($validationTpl));
}

// Submit form via AJAX (only if frontend validation is disabled)
if (!$validation && $ajax) {
    $modx->regClientHTMLBlock($modx->getChunk($ajaxTpl));
}

// Load custom assets, if present
// @todo: make this more dynamic
if (is_file('assets/js/formblocks.min.js')) {
    $modx->regClientHTMLBlock('<script defer src="assets/js/formblocks.min' . $cacheBusterJS . '.js"></script>');
} elseif (is_file('assets/js/formblocks.js')) {
    $modx->regClientHTMLBlock('<script defer src="assets/js/formblocks' . $cacheBusterJS . '.js"></script>');
}
if (is_file('assets/js/form-validation.min.js')) {
    $modx->regClientHTMLBlock('<script defer src="assets/js/form-validation.min' . $cacheBusterJS . '.js"></script>');
} elseif (is_file('assets/js/form-validation.js')) {
    $modx->regClientHTMLBlock('<script defer src="assets/js/form-validation' . $cacheBusterJS . '.js"></script>');
}

return '';