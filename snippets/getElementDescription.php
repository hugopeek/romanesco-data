id: 105
name: getElementDescription
description: 'Retrieve the description of an element. Used in the front-end library to prevent having to enter the same information twice. This paragraph is also loaded with getElementDescription!'
category: f_basic
snippet: "/**\n * getElementDescription\n *\n * Retrieve the description of common database objects.\n *\n * You can retrieve another field or property value by specifying either the\n * 'field' or 'property' parameter.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$elementType = $modx->getOption('type', $scriptProperties, '');\n$elementName = $modx->getOption('name', $scriptProperties, '');\n$fieldName = $modx->getOption('field', $scriptProperties, 'description');\n$property = $modx->getOption('property', $scriptProperties, '');\n\n// Set correct database table information based on the element type\nswitch($elementType) {\n    case stripos($elementType, 'electrontv') !== false:\n        $dbTable = \"site_tmplvars\";\n        $dbNameField = \"name\";\n        $modxObject = \"modTemplateVar\";\n        break;\n    case stripos($elementType, 'atom') !== false:\n    case stripos($elementType, 'molecule') !== false:\n    case stripos($elementType,'organism') !== false:\n        $dbTable = \"site_htmlsnippets\";\n        $dbNameField = \"name\";\n        $modxObject = \"modChunk\";\n        break;\n    case stripos($elementType, 'template') !== false:\n        $dbTable = \"site_templates\";\n        $dbNameField = \"templatename\";\n        $modxObject = \"modTemplate\";\n        break;\n    case stripos($elementType, 'page') !== false:\n        $dbTable = \"site_content\";\n        $dbNameField = \"pagetitle\";\n        $modxObject = \"modResource\";\n        break;\n    case stripos($elementType, 'formula') !== false:\n        $dbTable = \"site_snippets\";\n        $dbNameField = \"name\";\n        $modxObject = \"modSnippet\";\n        break;\n    case stripos($elementType, 'computation') !== false:\n        $dbTable = \"site_plugins\";\n        $dbNameField = \"name\";\n        $modxObject = \"modPlugin\";\n        break;\n    case stripos($elementType, 'bosonfield') !== false:\n        $dbTable = \"contentblocks_field\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbField\";\n        break;\n    case stripos($elementType, 'bosonlayout') !== false:\n        $dbTable = \"contentblocks_layout\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbLayout\";\n        break;\n    case stripos($elementType, 'bosontemplate') !== false:\n        $dbTable = \"contentblocks_template\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbTemplate\";\n        break;\n    default:\n        $dbTable = \"\";\n        $dbNameField = \"\";\n        $modxObject = \"\";\n        break;\n}\n\n// In case we are dealing with a ContentBlocks element, load CB service\nif (stripos($dbTable, 'contentblocks')) {\n    $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n    $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n}\n\n// User can opt to select another field or a property value instead\nif ($property) {\n    $fieldName = 'properties';\n}\n\n// Prepare db query and retrieve value\nif ($modxObject) {\n    $query = $modx->newQuery($modxObject, array(\n        $dbNameField => $elementName\n    ));\n    $query->select($fieldName);\n    $output = $modx->getValue($query->prepare());\n\n    // Properties need to be unserialized first\n    if ($property) {\n        $properties = unserialize($output, ['allowed_classes' => false]);\n        $output = $properties[$property]['value'];\n    }\n\n    return $output;\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getElementDescription] ' . $elementName . ' could not be processed');\n    return '';\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:45:"romanesco.getelementdescription.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:46:"romanesco.getelementdescription.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * getElementDescription\n *\n * Retrieve the description of common database objects.\n *\n * You can retrieve another field or property value by specifying either the\n * 'field' or 'property' parameter.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$elementType = $modx->getOption('type', $scriptProperties, '');\n$elementName = $modx->getOption('name', $scriptProperties, '');\n$fieldName = $modx->getOption('field', $scriptProperties, 'description');\n$property = $modx->getOption('property', $scriptProperties, '');\n\n// Set correct database table information based on the element type\nswitch($elementType) {\n    case stripos($elementType, 'electrontv') !== false:\n        $dbTable = \"site_tmplvars\";\n        $dbNameField = \"name\";\n        $modxObject = \"modTemplateVar\";\n        break;\n    case stripos($elementType, 'atom') !== false:\n    case stripos($elementType, 'molecule') !== false:\n    case stripos($elementType,'organism') !== false:\n        $dbTable = \"site_htmlsnippets\";\n        $dbNameField = \"name\";\n        $modxObject = \"modChunk\";\n        break;\n    case stripos($elementType, 'template') !== false:\n        $dbTable = \"site_templates\";\n        $dbNameField = \"templatename\";\n        $modxObject = \"modTemplate\";\n        break;\n    case stripos($elementType, 'page') !== false:\n        $dbTable = \"site_content\";\n        $dbNameField = \"pagetitle\";\n        $modxObject = \"modResource\";\n        break;\n    case stripos($elementType, 'formula') !== false:\n        $dbTable = \"site_snippets\";\n        $dbNameField = \"name\";\n        $modxObject = \"modSnippet\";\n        break;\n    case stripos($elementType, 'computation') !== false:\n        $dbTable = \"site_plugins\";\n        $dbNameField = \"name\";\n        $modxObject = \"modPlugin\";\n        break;\n    case stripos($elementType, 'bosonfield') !== false:\n        $dbTable = \"contentblocks_field\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbField\";\n        break;\n    case stripos($elementType, 'bosonlayout') !== false:\n        $dbTable = \"contentblocks_layout\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbLayout\";\n        break;\n    case stripos($elementType, 'bosontemplate') !== false:\n        $dbTable = \"contentblocks_template\";\n        $dbNameField = \"name\";\n        $modxObject = \"cbTemplate\";\n        break;\n    default:\n        $dbTable = \"\";\n        $dbNameField = \"\";\n        $modxObject = \"\";\n        break;\n}\n\n// In case we are dealing with a ContentBlocks element, load CB service\nif (stripos($dbTable, 'contentblocks')) {\n    $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');\n    $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');\n}\n\n// User can opt to select another field or a property value instead\nif ($property) {\n    $fieldName = 'properties';\n}\n\n// Prepare db query and retrieve value\nif ($modxObject) {\n    $query = $modx->newQuery($modxObject, array(\n        $dbNameField => $elementName\n    ));\n    $query->select($fieldName);\n    $output = $modx->getValue($query->prepare());\n\n    // Properties need to be unserialized first\n    if ($property) {\n        $properties = unserialize($output, ['allowed_classes' => false]);\n        $output = $properties[$property]['value'];\n    }\n\n    return $output;\n} else {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getElementDescription] ' . $elementName . ' could not be processed');\n    return '';\n}"

-----


/**
 * getElementDescription
 *
 * Retrieve the description of common database objects.
 *
 * You can retrieve another field or property value by specifying either the
 * 'field' or 'property' parameter.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$elementType = $modx->getOption('type', $scriptProperties, '');
$elementName = $modx->getOption('name', $scriptProperties, '');
$fieldName = $modx->getOption('field', $scriptProperties, 'description');
$property = $modx->getOption('property', $scriptProperties, '');

// Set correct database table information based on the element type
switch($elementType) {
    case stripos($elementType, 'electrontv') !== false:
        $dbTable = "site_tmplvars";
        $dbNameField = "name";
        $modxObject = "modTemplateVar";
        break;
    case stripos($elementType, 'atom') !== false:
    case stripos($elementType, 'molecule') !== false:
    case stripos($elementType,'organism') !== false:
        $dbTable = "site_htmlsnippets";
        $dbNameField = "name";
        $modxObject = "modChunk";
        break;
    case stripos($elementType, 'template') !== false:
        $dbTable = "site_templates";
        $dbNameField = "templatename";
        $modxObject = "modTemplate";
        break;
    case stripos($elementType, 'page') !== false:
        $dbTable = "site_content";
        $dbNameField = "pagetitle";
        $modxObject = "modResource";
        break;
    case stripos($elementType, 'formula') !== false:
        $dbTable = "site_snippets";
        $dbNameField = "name";
        $modxObject = "modSnippet";
        break;
    case stripos($elementType, 'computation') !== false:
        $dbTable = "site_plugins";
        $dbNameField = "name";
        $modxObject = "modPlugin";
        break;
    case stripos($elementType, 'bosonfield') !== false:
        $dbTable = "contentblocks_field";
        $dbNameField = "name";
        $modxObject = "cbField";
        break;
    case stripos($elementType, 'bosonlayout') !== false:
        $dbTable = "contentblocks_layout";
        $dbNameField = "name";
        $modxObject = "cbLayout";
        break;
    case stripos($elementType, 'bosontemplate') !== false:
        $dbTable = "contentblocks_template";
        $dbNameField = "name";
        $modxObject = "cbTemplate";
        break;
    default:
        $dbTable = "";
        $dbNameField = "";
        $modxObject = "";
        break;
}

// In case we are dealing with a ContentBlocks element, load CB service
if (stripos($dbTable, 'contentblocks')) {
    $cbCorePath = $modx->getOption('contentblocks.core_path', null, $modx->getOption('core_path').'components/contentblocks/');
    $ContentBlocks = $modx->getService('contentblocks','ContentBlocks', $cbCorePath.'model/contentblocks/');
}

// User can opt to select another field or a property value instead
if ($property) {
    $fieldName = 'properties';
}

// Prepare db query and retrieve value
if ($modxObject) {
    $query = $modx->newQuery($modxObject, array(
        $dbNameField => $elementName
    ));
    $query->select($fieldName);
    $output = $modx->getValue($query->prepare());

    // Properties need to be unserialized first
    if ($property) {
        $properties = unserialize($output, ['allowed_classes' => false]);
        $output = $properties[$property]['value'];
    }

    return $output;
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getElementDescription] ' . $elementName . ' could not be processed');
    return '';
}