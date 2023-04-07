id: 162
name: fbSetStoredValues
description: 'Populate field values with available data stored in the user session. Enabled by default when using multi-page forms.'
category: f_fb_hook
snippet: "/**\n * fbSetStoredValues snippet\n *\n * Populate field values with available data stored in the user session.\n * Enabled by default when using multi-page forms.\n *\n * NB! This only applies to fields in the current step. Other steps still rely\n * on FormItRetriever for populating the hidden fields in each form.\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var FormIt $formit\n * @var fiHooks $hook\n *\n * @package romanesco\n */\n\n// Erase session data if it's no longer valid\nif (Time() > $_SESSION['formitStore']['valid']) {\n    $_SESSION['formitStore'] = '';\n    return true;\n}\n\n// Get and set stored values\n$storedValues = $_SESSION['formitStore']['data'];\n$hook->setValues($storedValues);\n\nreturn true;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.fbsetstoredvalues.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.fbsetstoredvalues.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbSetStoredValues snippet
 *
 * Populate field values with available data stored in the user session.
 * Enabled by default when using multi-page forms.
 *
 * NB! This only applies to fields in the current step. Other steps still rely
 * on FormItRetriever for populating the hidden fields in each form.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var FormIt $formit
 * @var fiHooks $hook
 *
 * @package romanesco
 */

// Erase session data if it's no longer valid
if (Time() > $_SESSION['formitStore']['valid']) {
    $_SESSION['formitStore'] = '';
    return true;
}

// Get and set stored values
$storedValues = $_SESSION['formitStore']['data'];
$hook->setValues($storedValues);

return true;