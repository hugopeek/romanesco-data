id: 74
name: iconInputOptions
description: 'Generate input options with all Semantic UI icon classes.'
category: f_presentation
snippet: "/**\n * iconInputOptions\n * Based on fontAwesomeInputOptions, but modified to be used with Semantic UI.\n * MODX Snippet\n * @author YJ Tso @sepiariver\n * GPL, no warranties, etc.\n *\n * Usage: execute in TV input options, preferably with @CHUNK binding\n * alternatively install as Content Blocks input (link to repo coming soon)\n */\n\n// source file\n$cssUrl = $modx->getOption('cssUrl', $scriptProperties, 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');\n// scan options\n$regexPrefix = $modx->getOption('regexPrefix', $scriptProperties, 'fa-');\n// label text output options\n$mode = $modx->getOption('mode', $scriptProperties, 'tv'); // can be 'tv' or 'cb'\n$titleCaseLabels = $modx->getOption('titleCaseLabels', $scriptProperties, 1);\n$operator = $modx->getOption('operator', $scriptProperties, '');\nif (empty($operator)) {\n    $operator = ($mode === 'cb') ? '=' : '==';\n}\n// value text output options\n$outputPrefix = $modx->getOption('classPrefix', $scriptProperties, 'fa-');\n// list output options\n$separator = $modx->getOption('separator', $scriptProperties, '');\nif (empty($separator)) {\n    $separator = ($mode === 'cb') ? \"\\n\" : '||';\n}\n$excludeClasses = array_filter(array_map('trim', explode(',', $modx->getOption('excludeClasses', $scriptProperties, 'ul,li'))));\n// check cache\n$cacheKey = $modx->getOption('cacheKey', $scriptProperties, 'fontawesomecsssource');\n$provider = $modx->cacheManager->getCacheProvider('default');\n$css = $provider->get($cacheKey);\nif (!$css) {\n    // get source file\n    $css = file_get_contents($cssUrl);\n    if ($css) {\n        $provider->set($cacheKey, $css, 0);\n    } else {\n        $modx->log(modX::LOG_LEVEL_ERROR, '[iconInputOptions] could not get css source!');\n        return '';\n    }\n}\n// output\n$output = array();\n$regex = \"/\" . $regexPrefix . \"([\\w.]*)/\";\nif (preg_match_all($regex, $css, $matches)) {\n\n    $icons = array_diff($matches[1], $excludeClasses);\n    foreach($icons as $icon) {\n\n        $label = ($titleCaseLabels) ? ucwords(str_replace('.', ' ', $icon)) : $icon;\n        $icon = str_replace('.', ' ', $icon);\n        $output[] = $label . $operator . $icon . $outputPrefix;\n    }\n}\nreturn implode($separator, $output);"
properties: 'a:0:{}'
content: "/**\n * iconInputOptions\n * Based on fontAwesomeInputOptions, but modified to be used with Semantic UI.\n * MODX Snippet\n * @author YJ Tso @sepiariver\n * GPL, no warranties, etc.\n *\n * Usage: execute in TV input options, preferably with @CHUNK binding\n * alternatively install as Content Blocks input (link to repo coming soon)\n */\n\n// source file\n$cssUrl = $modx->getOption('cssUrl', $scriptProperties, 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');\n// scan options\n$regexPrefix = $modx->getOption('regexPrefix', $scriptProperties, 'fa-');\n// label text output options\n$mode = $modx->getOption('mode', $scriptProperties, 'tv'); // can be 'tv' or 'cb'\n$titleCaseLabels = $modx->getOption('titleCaseLabels', $scriptProperties, 1);\n$operator = $modx->getOption('operator', $scriptProperties, '');\nif (empty($operator)) {\n    $operator = ($mode === 'cb') ? '=' : '==';\n}\n// value text output options\n$outputPrefix = $modx->getOption('classPrefix', $scriptProperties, 'fa-');\n// list output options\n$separator = $modx->getOption('separator', $scriptProperties, '');\nif (empty($separator)) {\n    $separator = ($mode === 'cb') ? \"\\n\" : '||';\n}\n$excludeClasses = array_filter(array_map('trim', explode(',', $modx->getOption('excludeClasses', $scriptProperties, 'ul,li'))));\n// check cache\n$cacheKey = $modx->getOption('cacheKey', $scriptProperties, 'fontawesomecsssource');\n$provider = $modx->cacheManager->getCacheProvider('default');\n$css = $provider->get($cacheKey);\nif (!$css) {\n    // get source file\n    $css = file_get_contents($cssUrl);\n    if ($css) {\n        $provider->set($cacheKey, $css, 0);\n    } else {\n        $modx->log(modX::LOG_LEVEL_ERROR, '[iconInputOptions] could not get css source!');\n        return '';\n    }\n}\n// output\n$output = array();\n$regex = \"/\" . $regexPrefix . \"([\\w.]*)/\";\nif (preg_match_all($regex, $css, $matches)) {\n\n    $icons = array_diff($matches[1], $excludeClasses);\n    foreach($icons as $icon) {\n\n        $label = ($titleCaseLabels) ? ucwords(str_replace('.', ' ', $icon)) : $icon;\n        $icon = str_replace('.', ' ', $icon);\n        $output[] = $label . $operator . $icon . $outputPrefix;\n    }\n}\nreturn implode($separator, $output);"

-----


/**
 * iconInputOptions
 * Based on fontAwesomeInputOptions, but modified to be used with Semantic UI.
 * MODX Snippet
 * @author YJ Tso @sepiariver
 * GPL, no warranties, etc.
 *
 * Usage: execute in TV input options, preferably with @CHUNK binding
 * alternatively install as Content Blocks input (link to repo coming soon)
 */

// source file
$cssUrl = $modx->getOption('cssUrl', $scriptProperties, 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
// scan options
$regexPrefix = $modx->getOption('regexPrefix', $scriptProperties, 'fa-');
// label text output options
$mode = $modx->getOption('mode', $scriptProperties, 'tv'); // can be 'tv' or 'cb'
$titleCaseLabels = $modx->getOption('titleCaseLabels', $scriptProperties, 1);
$operator = $modx->getOption('operator', $scriptProperties, '');
if (empty($operator)) {
    $operator = ($mode === 'cb') ? '=' : '==';
}
// value text output options
$outputPrefix = $modx->getOption('classPrefix', $scriptProperties, 'fa-');
// list output options
$separator = $modx->getOption('separator', $scriptProperties, '');
if (empty($separator)) {
    $separator = ($mode === 'cb') ? "\n" : '||';
}
$excludeClasses = array_filter(array_map('trim', explode(',', $modx->getOption('excludeClasses', $scriptProperties, 'ul,li'))));
// check cache
$cacheKey = $modx->getOption('cacheKey', $scriptProperties, 'fontawesomecsssource');
$provider = $modx->cacheManager->getCacheProvider('default');
$css = $provider->get($cacheKey);
if (!$css) {
    // get source file
    $css = file_get_contents($cssUrl);
    if ($css) {
        $provider->set($cacheKey, $css, 0);
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR, '[iconInputOptions] could not get css source!');
        return '';
    }
}
// output
$output = array();
$regex = "/" . $regexPrefix . "([\w.]*)/";
if (preg_match_all($regex, $css, $matches)) {

    $icons = array_diff($matches[1], $excludeClasses);
    foreach($icons as $icon) {

        $label = ($titleCaseLabels) ? ucwords(str_replace('.', ' ', $icon)) : $icon;
        $icon = str_replace('.', ' ', $icon);
        $output[] = $label . $operator . $icon . $outputPrefix;
    }
}
return implode($separator, $output);