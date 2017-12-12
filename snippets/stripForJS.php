id: 117
name: stripForJS
description: 'Prepare the input for being used in Javascript. This means escaping certain characters to make sure the surrounding HTML doesn''t break.'
category: f_modifiers
snippet: "$output = $input;\n$output = str_replace('/', '\\/', $output);\n$output = str_replace(\"'\", \"\\'\", $output);\n$output = str_replace(\"\\n\", '', $output);\n$output = preg_replace(\"/(>+(\\s)*<+)/\", '><', $output);\n$output = preg_replace(\"/\\s+/\", ' ', $output);\nreturn $output;"
properties: 'a:0:{}'
content: "$output = $input;\n$output = str_replace('/', '\\/', $output);\n$output = str_replace(\"'\", \"\\'\", $output);\n$output = str_replace(\"\\n\", '', $output);\n$output = preg_replace(\"/(>+(\\s)*<+)/\", '><', $output);\n$output = preg_replace(\"/\\s+/\", ' ', $output);\nreturn $output;"

-----


$output = $input;
$output = str_replace('/', '\/', $output);
$output = str_replace("'", "\'", $output);
$output = str_replace("\n", '', $output);
$output = preg_replace("/(>+(\s)*<+)/", '><', $output);
$output = preg_replace("/\s+/", ' ', $output);
return $output;