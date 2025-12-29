id: 176
name: Switch
description: 'Customized Switch snippet, with optional output to placeholder.'
category: f_framework
snippet: "/**\n * Switch\n *\n * Created by Uros Likar\n * uros.likar@gmail.com\n *\n * Update to 1.1.0 by\n * Thomas Jakobi\n * thomas.jakobi@partout.info\n */\n\n$default = $modx->getOption('default', $scriptProperties, '');\n$get = trim($modx->getOption('get', $scriptProperties, false));\n\n$output = $default;\nif ($get !== false) {\n    foreach ($scriptProperties as $key => $value) {\n        if (substr($key, 0, 1) == 'c' && strlen($key) > 1 && isset($scriptProperties['do' . substr($key, 1)])) {\n            if ($value == $get) {\n                $output = $scriptProperties['do' . substr($key, 1)];\n                break;\n            }\n        }\n    }\n}\n\n// Output either to placeholder, or directly\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * Switch
 *
 * Created by Uros Likar
 * uros.likar@gmail.com
 *
 * Update to 1.1.0 by
 * Thomas Jakobi
 * thomas.jakobi@partout.info
 */

$default = $modx->getOption('default', $scriptProperties, '');
$get = trim($modx->getOption('get', $scriptProperties, false));

$output = $default;
if ($get !== false) {
    foreach ($scriptProperties as $key => $value) {
        if (substr($key, 0, 1) == 'c' && strlen($key) > 1 && isset($scriptProperties['do' . substr($key, 1)])) {
            if ($value == $get) {
                $output = $scriptProperties['do' . substr($key, 1)];
                break;
            }
        }
    }
}

// Output either to placeholder, or directly
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;