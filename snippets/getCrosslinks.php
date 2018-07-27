id: 69
name: getCrosslinks
description: 'Return the IDs of resources that link to current resource through a given TV. Did that make sense? For example: you can show things like relevant tags or reviews in blog posts and vice versa.'
category: f_resources
snippet: "if (!$tvName) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable tvName is not given.');\n    return;\n}\n\nif (!$page) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable page is not given.');\n    return;\n}\n\n$templateVar = \"SELECT `id` FROM `\" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . \"site_tmplvars` WHERE `name` = '\" . $tvName . \"'\";\n\n$result = $modx->query($templateVar);\n$templateVarId = 0;\nif (!is_object($result)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 18.');\n    return;\n} else {\n    $row = $result->fetch(PDO::FETCH_ASSOC);\n    $templateVarId = $row['id'];\n}\nif (!$templateVarId) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] '. $templateVarName . ' is geen valide template variabel.');\n    return;\n}\n\n$crosslinkIds = \"SELECT `contentid`, `value` FROM `\" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . \"site_tmplvar_contentvalues` WHERE `tmplvarid` = '\" . $templateVarId . \"' AND `value` LIKE '%\" . $page . \"%'\";\n$result = $modx->query($crosslinkIds);\n$resultsArray = array();\nif (!is_object($result)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 33');\n    return;\n} else {\n    while ($r = $result->fetch(PDO::FETCH_ASSOC)) {\n        $r['value'] = explode('||', $r['value']);\n        if (in_array($page ,$r['value'])) {\n            array_push($resultsArray, $r['contentid']);\n        }\n    }\n    return implode(\",\", $resultsArray);\n}"
properties: 'a:0:{}'
content: "if (!$tvName) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable tvName is not given.');\n    return;\n}\n\nif (!$page) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable page is not given.');\n    return;\n}\n\n$templateVar = \"SELECT `id` FROM `\" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . \"site_tmplvars` WHERE `name` = '\" . $tvName . \"'\";\n\n$result = $modx->query($templateVar);\n$templateVarId = 0;\nif (!is_object($result)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 18.');\n    return;\n} else {\n    $row = $result->fetch(PDO::FETCH_ASSOC);\n    $templateVarId = $row['id'];\n}\nif (!$templateVarId) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] '. $templateVarName . ' is geen valide template variabel.');\n    return;\n}\n\n$crosslinkIds = \"SELECT `contentid`, `value` FROM `\" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . \"site_tmplvar_contentvalues` WHERE `tmplvarid` = '\" . $templateVarId . \"' AND `value` LIKE '%\" . $page . \"%'\";\n$result = $modx->query($crosslinkIds);\n$resultsArray = array();\nif (!is_object($result)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 33');\n    return;\n} else {\n    while ($r = $result->fetch(PDO::FETCH_ASSOC)) {\n        $r['value'] = explode('||', $r['value']);\n        if (in_array($page ,$r['value'])) {\n            array_push($resultsArray, $r['contentid']);\n        }\n    }\n    return implode(\",\", $resultsArray);\n}"

-----


if (!$tvName) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable tvName is not given.');
    return;
}

if (!$page) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] The variable page is not given.');
    return;
}

$templateVar = "SELECT `id` FROM `" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . "site_tmplvars` WHERE `name` = '" . $tvName . "'";

$result = $modx->query($templateVar);
$templateVarId = 0;
if (!is_object($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 18.');
    return;
} else {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $templateVarId = $row['id'];
}
if (!$templateVarId) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] '. $templateVarName . ' is geen valide template variabel.');
    return;
}

$crosslinkIds = "SELECT `contentid`, `value` FROM `" . $modx->getOption(xPDO::OPT_TABLE_PREFIX) . "site_tmplvar_contentvalues` WHERE `tmplvarid` = '" . $templateVarId . "' AND `value` LIKE '%" . $page . "%'";
$result = $modx->query($crosslinkIds);
$resultsArray = array();
if (!is_object($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[getCrosslinks] An error has occured: result is not an object. Line 33');
    return;
} else {
    while ($r = $result->fetch(PDO::FETCH_ASSOC)) {
        $r['value'] = explode('||', $r['value']);
        if (in_array($page ,$r['value'])) {
            array_push($resultsArray, $r['contentid']);
        }
    }
    return implode(",", $resultsArray);
}