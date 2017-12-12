id: 63
name: fbStripAsAlias
category: f_fb_modifiers
snippet: "$input = strip_tags($input); // strip HTML\n$input = strtolower($input); // convert to lowercase\n$input = preg_replace('/[^\\A-Za-z0-9 _-]/', '', $input); // strip non-alphanumeric characters\n$input = preg_replace('/\\s+/', '-', $input); // convert white-space to dash\n$input = preg_replace('/-+/', '-', $input);  // convert multiple dashes to one\n$input = trim($input, '-'); // trim excess\n\nreturn $input;"
properties: 'a:0:{}'
content: "$input = strip_tags($input); // strip HTML\n$input = strtolower($input); // convert to lowercase\n$input = preg_replace('/[^\\A-Za-z0-9 _-]/', '', $input); // strip non-alphanumeric characters\n$input = preg_replace('/\\s+/', '-', $input); // convert white-space to dash\n$input = preg_replace('/-+/', '-', $input);  // convert multiple dashes to one\n$input = trim($input, '-'); // trim excess\n\nreturn $input;"

-----


$input = strip_tags($input); // strip HTML
$input = strtolower($input); // convert to lowercase
$input = preg_replace('/[^\A-Za-z0-9 _-]/', '', $input); // strip non-alphanumeric characters
$input = preg_replace('/\s+/', '-', $input); // convert white-space to dash
$input = preg_replace('/-+/', '-', $input);  // convert multiple dashes to one
$input = trim($input, '-'); // trim excess

return $input;