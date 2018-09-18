id: 84
name: tabsGenerateNav
description: 'Generate the tab buttons based on data-heading attribute in the tabs themselves. It basically links every tab button to the correct tab content.'
category: f_presentation
snippet: "/**\n * tabsGenerateNav\n *\n * Create tab buttons based on the tab content's HTML.\n * Each content field contains data attributes with the correct text for each heading.\n *\n * Many thanks to @christianseel for the original idea and code.\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tabsNavItem');\n$tplIcon = $modx->getOption('tplIcon', $scriptProperties, 'tabsNavItemIcon');\n\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n$doc = new DOMDocument();\n\n// Set error level to suppress warnings in log over special characters in HTML\n$internalErrors = libxml_use_internal_errors(true);\n\n// Load HTML\n$doc->loadHTML('<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">' . $input);\n\n// Restore error level\n$internalErrors = libxml_use_internal_errors(false);\n\n$divs = $doc->getElementsByTagName('div'); // Little flaky.. If div element changes, navigation breaks.\n\n$tabs = array();\n\n$idx = 1;\nforeach($divs as $div) {\n    if ($div->hasAttribute('data-tab')) {\n        $tabs[$div->getAttribute('data-tab')] = array(\n            'heading' => $div->getAttribute('data-heading'),\n            'level' => $div->getAttribute('data-heading-level'),\n            'subtitle' => $div->getAttribute('data-heading-subtitle'),\n            'icon' => $div->getAttribute('data-heading-icon')\n        );\n    }\n}\n\n$tabheaders = '';\n\n$idx = 1;\nforeach($tabs as $tab) {\n    if ($tab['icon'] > 0) {\n        $tpl = $tplIcon;\n    }\n\n    $properties = array(\n        'idx' => $idx,\n        'heading' => $tab['heading'],\n        'level' => $tab['level'],\n        'subtitle' => $tab['subtitle'],\n        'icon' => $tab['icon'],\n    );\n\n    $tabheaders .= $modx->getChunk($tpl, $properties);\n    $idx++;\n}\n\n// Return placeholder with idx, so tab menu can be justified\n$modx->toPlaceholder('tabs_total', $idx - 1);\n\n// Output either to placeholder, or directly\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $tabheaders, $prefix);\n    return '';\n}\nreturn $tabheaders;"
properties: 'a:0:{}'
content: "/**\n * tabsGenerateNav\n *\n * Create tab buttons based on the tab content's HTML.\n * Each content field contains data attributes with the correct text for each heading.\n *\n * Many thanks to @christianseel for the original idea and code.\n */\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tabsNavItem');\n$tplIcon = $modx->getOption('tplIcon', $scriptProperties, 'tabsNavItemIcon');\n\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n$doc = new DOMDocument();\n\n// Set error level to suppress warnings in log over special characters in HTML\n$internalErrors = libxml_use_internal_errors(true);\n\n// Load HTML\n$doc->loadHTML('<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">' . $input);\n\n// Restore error level\n$internalErrors = libxml_use_internal_errors(false);\n\n$divs = $doc->getElementsByTagName('div'); // Little flaky.. If div element changes, navigation breaks.\n\n$tabs = array();\n\n$idx = 1;\nforeach($divs as $div) {\n    if ($div->hasAttribute('data-tab')) {\n        $tabs[$div->getAttribute('data-tab')] = array(\n            'heading' => $div->getAttribute('data-heading'),\n            'level' => $div->getAttribute('data-heading-level'),\n            'subtitle' => $div->getAttribute('data-heading-subtitle'),\n            'icon' => $div->getAttribute('data-heading-icon')\n        );\n    }\n}\n\n$tabheaders = '';\n\n$idx = 1;\nforeach($tabs as $tab) {\n    if ($tab['icon'] > 0) {\n        $tpl = $tplIcon;\n    }\n\n    $properties = array(\n        'idx' => $idx,\n        'heading' => $tab['heading'],\n        'level' => $tab['level'],\n        'subtitle' => $tab['subtitle'],\n        'icon' => $tab['icon'],\n    );\n\n    $tabheaders .= $modx->getChunk($tpl, $properties);\n    $idx++;\n}\n\n// Return placeholder with idx, so tab menu can be justified\n$modx->toPlaceholder('tabs_total', $idx - 1);\n\n// Output either to placeholder, or directly\nif ($placeholder) {\n    $modx->toPlaceholder('pl', $prefix);\n    $modx->toPlaceholder($placeholder, $tabheaders, $prefix);\n    return '';\n}\nreturn $tabheaders;"

-----


/**
 * tabsGenerateNav
 *
 * Create tab buttons based on the tab content's HTML.
 * Each content field contains data attributes with the correct text for each heading.
 *
 * Many thanks to @christianseel for the original idea and code.
 */

$input = $modx->getOption('input', $scriptProperties, $input);
$tpl = $modx->getOption('tpl', $scriptProperties, 'tabsNavItem');
$tplIcon = $modx->getOption('tplIcon', $scriptProperties, 'tabsNavItemIcon');

$prefix = $modx->getOption('prefix', $scriptProperties, '');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

$doc = new DOMDocument();

// Set error level to suppress warnings in log over special characters in HTML
$internalErrors = libxml_use_internal_errors(true);

// Load HTML
$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $input);

// Restore error level
$internalErrors = libxml_use_internal_errors(false);

$divs = $doc->getElementsByTagName('div'); // Little flaky.. If div element changes, navigation breaks.

$tabs = array();

$idx = 1;
foreach($divs as $div) {
    if ($div->hasAttribute('data-tab')) {
        $tabs[$div->getAttribute('data-tab')] = array(
            'heading' => $div->getAttribute('data-heading'),
            'level' => $div->getAttribute('data-heading-level'),
            'subtitle' => $div->getAttribute('data-heading-subtitle'),
            'icon' => $div->getAttribute('data-heading-icon')
        );
    }
}

$tabheaders = '';

$idx = 1;
foreach($tabs as $tab) {
    if ($tab['icon'] > 0) {
        $tpl = $tplIcon;
    }

    $properties = array(
        'idx' => $idx,
        'heading' => $tab['heading'],
        'level' => $tab['level'],
        'subtitle' => $tab['subtitle'],
        'icon' => $tab['icon'],
    );

    $tabheaders .= $modx->getChunk($tpl, $properties);
    $idx++;
}

// Return placeholder with idx, so tab menu can be justified
$modx->toPlaceholder('tabs_total', $idx - 1);

// Output either to placeholder, or directly
if ($placeholder) {
    $modx->toPlaceholder('pl', $prefix);
    $modx->toPlaceholder($placeholder, $tabheaders, $prefix);
    return '';
}
return $tabheaders;