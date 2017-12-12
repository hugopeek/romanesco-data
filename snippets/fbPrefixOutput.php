id: 61
name: fbPrefixOutput
category: f_fb_modifiers
snippet: "$id = $modx->resource->get('id');\n//$idx = $modx->getPlaceholder('unique_idx');\n$options = !empty($options) ? $options: 'fb' . $id . '-';\n\nreturn $options.$input;"
properties: 'a:0:{}'
content: "$id = $modx->resource->get('id');\n//$idx = $modx->getPlaceholder('unique_idx');\n$options = !empty($options) ? $options: 'fb' . $id . '-';\n\nreturn $options.$input;"

-----


$id = $modx->resource->get('id');
//$idx = $modx->getPlaceholder('unique_idx');
$options = !empty($options) ? $options: 'fb' . $id . '-';

return $options.$input;