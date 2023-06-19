id: 153
name: renderContentType
description: 'Takes an ID as input and returns the content type. Mainly intended as snippet renderer for Collections, but can be used independently or as output modifier too.'
category: f_resource
snippet: "/**\n * renderContentType\n *\n * @var modX $modx\n * @var array $scriptProperties\n * @var string $input\n * @var string $options\n */\n\n$id = $modx->getOption('id', $scriptProperties, $input);\n$type = $modx->getObject('modContentType', $id);\n\nif (is_object($type)) {\n    return $type->get('name');\n}\n\nreturn;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.rendercontenttype.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.rendercontenttype.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderContentType
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $input
 * @var string $options
 */

$id = $modx->getOption('id', $scriptProperties, $input);
$type = $modx->getObject('modContentType', $id);

if (is_object($type)) {
    return $type->get('name');
}

return;