id: 84
name: tabsGenerateNav
description: 'Generate the tab buttons based on data-heading attribute in the tabs themselves. It basically links every tab button to the correct tab content.'
category: f_presentation
snippet: "/**\n * tabsGenerateNav\n *\n * Create tab buttons based on the tab content's HTML.\n * Each content field contains data attributes with the correct text for each heading.\n *\n * Many thanks to @christianseel for the original idea and code.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\nif (!class_exists(Wa72\\HtmlPageDom\\HtmlPageCrawler::class)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');\n    return;\n}\n\nuse Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\n$input = $modx->getOption('input', $scriptProperties, $input);\n$tpl = $modx->getOption('tpl', $scriptProperties, 'tabsNavLeft');\n$rowTpl = $modx->getOption('rowTpl', $scriptProperties, 'tabsNavItem');\n$tplIcon = $modx->getOption('tplIcon', $scriptProperties, 'tabsNavItemIcon');\n\n$prefix = $modx->getOption('prefix', $scriptProperties, '');\n$uid = $modx->getOption('uid', $scriptProperties, '');\n$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\n\n$dom = new HtmlPageCrawler($input);\n\n$tabHeaders = $dom->filter('.item')->each(function(HtmlPageCrawler $node) use ($uid, $modx) {\n    $html = $node->getAttribute('data-tab');\n//    $html = str_replace('[[+unique_idx]]', $uid, $html);\n//    $modx->log(modX::LOG_LEVEL_ERROR, $html);\n    return $node->setAttribute('data-tab', $html);\n});\n$tabSegments = $dom->filter('.tab.segment');\n\nreturn $modx->getChunk($tpl, [\n    'menu' => implode('',$tabHeaders),\n    'segments' => $tabSegments . PHP_EOL,\n    'tabs_total' => count($tabHeaders),\n    'uid' => $uid,\n]);"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * tabsGenerateNav
 *
 * Create tab buttons based on the tab content's HTML.
 * Each content field contains data attributes with the correct text for each heading.
 *
 * Many thanks to @christianseel for the original idea and code.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

if (!class_exists(Wa72\HtmlPageDom\HtmlPageCrawler::class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');
    return;
}

use Wa72\HtmlPageDom\HtmlPageCrawler;

$input = $modx->getOption('input', $scriptProperties, $input);
$tpl = $modx->getOption('tpl', $scriptProperties, 'tabsNavLeft');
$rowTpl = $modx->getOption('rowTpl', $scriptProperties, 'tabsNavItem');
$tplIcon = $modx->getOption('tplIcon', $scriptProperties, 'tabsNavItemIcon');

$prefix = $modx->getOption('prefix', $scriptProperties, '');
$uid = $modx->getOption('uid', $scriptProperties, '');
$placeholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

$dom = new HtmlPageCrawler($input);

$tabHeaders = $dom->filter('.item')->each(function(HtmlPageCrawler $node) use ($uid, $modx) {
    $html = $node->getAttribute('data-tab');
//    $html = str_replace('[[+unique_idx]]', $uid, $html);
//    $modx->log(modX::LOG_LEVEL_ERROR, $html);
    return $node->setAttribute('data-tab', $html);
});
$tabSegments = $dom->filter('.tab.segment');

return $modx->getChunk($tpl, [
    'menu' => implode('',$tabHeaders),
    'segments' => $tabSegments . PHP_EOL,
    'tabs_total' => count($tabHeaders),
    'uid' => $uid,
]);