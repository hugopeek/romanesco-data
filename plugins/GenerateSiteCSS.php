id: 40
name: GenerateSiteCSS
description: 'Creates site.css file for each context, with their own global backgrounds. If you want a context to have its own set of backgrounds, you need to add a child page under Global Backgrounds.'
category: c_performance
plugincode: "/**\n * GenerateSiteCSS\n *\n * Creates site.css file for each context, with their own global backgrounds.\n *\n * If you want a context to have its own set of backgrounds, you need to create\n * a child page under the Global Backgrounds container for it. Make sure the\n * template is GlobalBackgrounds too and that the alias matches the context_key!\n *\n * A default stylesheet (site.css) is also generated, containing only the\n * backgrounds at root level of the Global Backgrounds container.\n *\n * CSS files are regenerated each time a GlobalBackgrounds resource is saved.\n *\n * NB! The plugin priority should be set to something higher than 0. Otherwise,\n * users will need to save the resource twice to see their changes reflected.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var Romanesco $romanesco\n *\n * @package romanesco\n */\n\nuse MODX\\Revolution\\modX;\nuse Psr\\Container\\NotFoundExceptionInterface;\nuse FractalFarming\\Romanesco\\Romanesco;\n\ntry {\n    $romanesco = $modx->services->get('romanesco');\n} catch (NotFoundExceptionInterface $e) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());\n}\n\n// Css validator should be loaded through Romanesco\nif (!class_exists(CssLint\\Linter::class)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[CssLint] Class not found!');\n    return;\n}\n\nuse CssLint\\Linter;\n\nswitch ($modx->event->name) {\n    case 'OnBeforeDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        // Exit if resource template is not GlobalBackground(s)\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            break;\n        }\n\n        // Clear event output to avoid rogue messages popping up again\n        $modx->event->_output = '';\n\n        // Validate the CSS gradient field\n        if ($templateID == 27)\n        {\n            // Prepare an array with submitted ContentBlocks data\n            $cbData = $resource->get('contentblocks');\n            $cbData = json_decode($cbData, true);\n\n            // It's probably just 1 background field, but let's not assume anything\n            $fields = $cbData[0]['content']['main'] ?? [];\n            foreach ($fields as $field) {\n                if ($field['field'] != 109) continue;\n                $i = 0;\n\n                foreach ($field['rows'] as $row) {\n                    $i++;\n\n                    $image = 'url(' . $row['image']['url'] . ')';\n                    $position = $row['position']['value'] ? : 'center center';\n                    $size = $row['size']['value'] ? : 'cover';\n                    $repeat = $row['repeat']['value'] ? : 'no-repeat';\n                    $attachment = $row['attachment']['value'] ? : 'scroll';\n                    $gradient = $row['gradient']['value'];\n                    $background = $row['image']['url'] ? $image : $gradient;\n                    $css = \"\n.background::before {\n    background:\n        $background\n        $position /\n        $size\n        $repeat\n        $attachment\n        !important\n    ;\n}\";\n\n                    // Validate CSS\n                    $cssLinter = new Linter();\n                    $lintResult = $cssLinter->lintString($css);\n                    $errors = [];\n                    foreach ($lintResult as $error) {\n                        $errors[] = $error->__toString();\n                    }\n                    if ($errors) {\n                        $modx->log(modX::LOG_LEVEL_ERROR, \"CSS for background $id is not valid:\" . $css . \"\\n\" . implode(\"\\n\", $errors));\n                        $modx->event->output(\"The CSS in layer $i is not valid!<br>\");\n                        $modx->event->output(\"Please check the error log for details.\");\n                        return true;\n                    }\n                }\n            }\n        }\n\n        break;\n\n    case 'OnDocFormSave':\n        /**\n         * @var modResource $resource\n         * @var int $id\n         */\n\n        $exit = false;\n\n        // Exit if resource template is not GlobalBackground(s)\n        $templateID = $resource->get('template');\n        if ($templateID != 27 && $templateID != 8) {\n            $exit = true;\n        }\n\n        // ...but continue if a header background is (being) set\n        if ($resource->getTVValue('header_background_img')) {\n            $exit = false;\n        }\n\n        // Leave the EU?\n        if ($exit) break;\n\n        // Generate CSS\n        $romanesco->generateBackgroundCSS();\n\n        // Bump CSS version number to force refresh\n        $romanesco->bumpVersionNumber();\n\n        // Clear cache\n        $modx->cacheManager->refresh();\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * GenerateSiteCSS
 *
 * Creates site.css file for each context, with their own global backgrounds.
 *
 * If you want a context to have its own set of backgrounds, you need to create
 * a child page under the Global Backgrounds container for it. Make sure the
 * template is GlobalBackgrounds too and that the alias matches the context_key!
 *
 * A default stylesheet (site.css) is also generated, containing only the
 * backgrounds at root level of the Global Backgrounds container.
 *
 * CSS files are regenerated each time a GlobalBackgrounds resource is saved.
 *
 * NB! The plugin priority should be set to something higher than 0. Otherwise,
 * users will need to save the resource twice to see their changes reflected.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var Romanesco $romanesco
 *
 * @package romanesco
 */

use MODX\Revolution\modX;
use Psr\Container\NotFoundExceptionInterface;
use FractalFarming\Romanesco\Romanesco;

try {
    $romanesco = $modx->services->get('romanesco');
} catch (NotFoundExceptionInterface $e) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[Romanesco3x] ' . $e->getMessage());
}

// Css validator should be loaded through Romanesco
if (!class_exists(CssLint\Linter::class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[CssLint] Class not found!');
    return;
}

use CssLint\Linter;

switch ($modx->event->name) {
    case 'OnBeforeDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        // Exit if resource template is not GlobalBackground(s)
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            break;
        }

        // Clear event output to avoid rogue messages popping up again
        $modx->event->_output = '';

        // Validate the CSS gradient field
        if ($templateID == 27)
        {
            // Prepare an array with submitted ContentBlocks data
            $cbData = $resource->get('contentblocks');
            $cbData = json_decode($cbData, true);

            // It's probably just 1 background field, but let's not assume anything
            $fields = $cbData[0]['content']['main'] ?? [];
            foreach ($fields as $field) {
                if ($field['field'] != 109) continue;
                $i = 0;

                foreach ($field['rows'] as $row) {
                    $i++;

                    $image = 'url(' . $row['image']['url'] . ')';
                    $position = $row['position']['value'] ? : 'center center';
                    $size = $row['size']['value'] ? : 'cover';
                    $repeat = $row['repeat']['value'] ? : 'no-repeat';
                    $attachment = $row['attachment']['value'] ? : 'scroll';
                    $gradient = $row['gradient']['value'];
                    $background = $row['image']['url'] ? $image : $gradient;
                    $css = "
.background::before {
    background:
        $background
        $position /
        $size
        $repeat
        $attachment
        !important
    ;
}";

                    // Validate CSS
                    $cssLinter = new Linter();
                    $lintResult = $cssLinter->lintString($css);
                    $errors = [];
                    foreach ($lintResult as $error) {
                        $errors[] = $error->__toString();
                    }
                    if ($errors) {
                        $modx->log(modX::LOG_LEVEL_ERROR, "CSS for background $id is not valid:" . $css . "\n" . implode("\n", $errors));
                        $modx->event->output("The CSS in layer $i is not valid!<br>");
                        $modx->event->output("Please check the error log for details.");
                        return true;
                    }
                }
            }
        }

        break;

    case 'OnDocFormSave':
        /**
         * @var modResource $resource
         * @var int $id
         */

        $exit = false;

        // Exit if resource template is not GlobalBackground(s)
        $templateID = $resource->get('template');
        if ($templateID != 27 && $templateID != 8) {
            $exit = true;
        }

        // ...but continue if a header background is (being) set
        if ($resource->getTVValue('header_background_img')) {
            $exit = false;
        }

        // Leave the EU?
        if ($exit) break;

        // Generate CSS
        $romanesco->generateBackgroundCSS();

        // Bump CSS version number to force refresh
        $romanesco->bumpVersionNumber();

        // Clear cache
        $modx->cacheManager->refresh();

        break;
}