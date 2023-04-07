id: 61
name: fbPrefixOutput
category: f_fb_modifier
snippet: "$id = $modx->resource->get('id');\n//$idx = $modx->getPlaceholder('unique_idx');\n$options = !empty($options) ? $options: 'fb' . $id . '-';\n\nreturn $options.$input;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.fbprefixoutput.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.fbprefixoutput.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


$id = $modx->resource->get('id');
//$idx = $modx->getPlaceholder('unique_idx');
$options = !empty($options) ? $options: 'fb' . $id . '-';

return $options.$input;