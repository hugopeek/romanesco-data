id: 71
name: resourceMediaPath
description: 'Standalone version of a snippet that comes with MIGX. Generates subfolders in media sources. Keeps your folder structure tidy when adding lots of images in lots of resources (e.g. galleries).'
category: f_resource
snippet: "/**\n * resourceMediaPath\n *\n * Dynamically calculates the upload path for a given resource.\n *\n * This Snippet is meant to dynamically calculate your baseBath attribute\n * for custom Media Sources. This is useful if you wish to shepard uploaded\n * images to a folder dedicated to a given resource. E.g. page 123 would\n * have its own images that page 456 could not reference.\n *\n * USAGE\n * [[resourceMediaPath? &pathTpl=`assets/businesses/{id}/`]]\n * [[resourceMediaPath? &pathTpl=`assets/resourceimages/{id}/` &checkTVs=`mymigxtv`]]\n * [[resourceMediaPath? &pathTpl=`assets/test/{breadcrumb}`]]\n * [[resourceMediaPath? &pathTpl=`assets/test/{breadcrumb}` &breadcrumbdepth=`2`]]\n *\n * PARAMETERS\n * &pathTpl string formatting string specifying the file path.\n *\t\tRelative to MODX base_path\n *\t\tAvailable placeholders: {id}, {pagetitle}, {parent}\n * &docid (optional) integer page id\n * &createFolder (optional) boolean whether to create folder or not\n * &checkTVs (optional) comma-separated list of TVs to check, before directory is created\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$pathTpl = $modx->getOption('pathTpl', $scriptProperties, '');\n$docid = $modx->getOption('docid', $scriptProperties, '');\n$createfolder = $modx->getOption('createFolder', $scriptProperties, false);\n$tvname = $modx->getOption('tvname', $scriptProperties, '');\n$checktvs = $modx->getOption('checkTVs', $scriptProperties, false);\n\n$path = '';\n$fullpath = '';\n$createpath = false;\n$fallbackpath = $modx->getOption('fallbackPath', $scriptProperties, 'assets/migxfallback/');\n\nif (empty($pathTpl)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[resourceMediaPath]: pathTpl not specified.');\n    return;\n}\n\nif (empty($docid) && $modx->getPlaceholder('mediasource_docid')) {\n    // placeholder was set by some script\n    // warning: the parser may not render placeholders, e.g. &docid=`[[*parent]]` may fail\n    $docid = $modx->getPlaceholder('mediasource_docid');\n}\n\nif (empty($docid) && $modx->getPlaceholder('docid')) {\n    // placeholder was set by some script\n    // warning: the parser may not render placeholders, e.g. &docid=`[[*parent]]` may fail\n    $docid = $modx->getPlaceholder('docid');\n}\nif (empty($docid)) {\n\n    //on frontend\n    if (is_object($modx->resource)) {\n        $docid = $modx->resource->get('id');\n    }\n    //on manager resource/update page\n    else {\n        $createpath = $createfolder;\n\n        // Read the &id param from an Ajax request\n        $parsedUrl = parse_url($_SERVER['HTTP_REFERER']);\n        if (isset($parsedUrl['query'])) {\n            parse_str($parsedUrl['query'], $parsedQuery);\n        }\n\n        // Avoid docid to be set to parent container\n        $requestAction = $_REQUEST['a'] ?? '';\n        $action = $parsedQuery['a'] ?? '';\n        if (!$action && $requestAction || $action == $requestAction) {\n            $docid = $_REQUEST['id'] ?? '';\n        }\n        elseif ($action === 'resource/update') {\n            $docid = (int)$parsedQuery['amp;id'] ?? (int)$parsedQuery['id'] ?? 0;\n        }\n    }\n}\n\nif (empty($docid)) {\n    $modx->log(modX::LOG_LEVEL_DEBUG, '[resourceMediaPath]: docid could not be determined.');\n}\n\nif (empty($docid) || empty($pathTpl)) {\n    $path = $fallbackpath;\n    $fullpath = $modx->getOption('base_path') . $fallbackpath;\n    $createpath = true;\n}\n\nif (empty($fullpath) && $resource = $modx->getObject('modResource', $docid)) {\n    $path = $pathTpl;\n    $ultimateParent = '';\n    if (strstr($path, '{breadcrumb}') || strstr($path, '{ultimateparent}')) {\n        $depth = $modx->getOption('breadcrumbdepth', $scriptProperties, 10);\n        $ctx = $resource->get('context_key');\n        $parentids = $modx->getParentIds($docid, $depth, array('context' => $ctx));\n        $breadcrumbdepth = $modx->getOption('breadcrumbdepth', $scriptProperties, count($parentids));\n        $breadcrumbdepth = $breadcrumbdepth > count($parentids) ? count($parentids) : $breadcrumbdepth;\n        if (count($parentids) > 1) {\n            $parentids = array_reverse($parentids);\n            $parentids[] = $docid;\n            $ultimateParent = $parentids[1];\n        } else {\n            $ultimateParent = $docid;\n            $parentids = array();\n            $parentids[] = $docid;\n        }\n    }\n\n    if (strstr($path, '{breadcrumb}')) {\n        $breadcrumbpath = '';\n        for ($i = 1; $i <= $breadcrumbdepth; $i++) {\n            $breadcrumbpath .= $parentids[$i] . '/';\n        }\n        $path = str_replace('{breadcrumb}', $breadcrumbpath, $path);\n    }\n\n    if (!empty($tvname)){\n        $path = str_replace('{tv_value}', $resource->getTVValue($tvname), $path);\n    }\n    $path = str_replace('{id}', $docid, $path);\n    $path = str_replace('{pagetitle}', $resource->get('pagetitle'), $path);\n    $path = str_replace('{alias}', $resource->get('alias'), $path);\n    $path = str_replace('{parent}', $resource->get('parent'), $path);\n    $path = str_replace('{context_key}', $resource->get('context_key'), $path);\n    $path = str_replace('{ultimateparent}', $ultimateParent, $path);\n    if ($template = $resource->getOne('Template')) {\n        $path = str_replace('{templatename}', $template->get('templatename'), $path);\n    }\n    if ($user = $modx->user) {\n        $path = str_replace('{username}', $modx->user->get('username'), $path);\n        $path = str_replace('{userid}', $modx->user->get('id'), $path);\n    }\n\n    $fullpath = $modx->getOption('base_path') . $path;\n\n    if ($createpath && $checktvs){\n        $createpath = false;\n        if ($template) {\n            $tvs = explode(',',$checktvs);\n            foreach ($tvs as $tv){\n                if ($template->hasTemplateVar($tv)){\n                    $createpath = true;\n                }\n            }\n        }\n\n    }\n\n} else {\n    $modx->log(modX::LOG_LEVEL_DEBUG, sprintf('[resourceMediaPath]: resource not found (page id %s).', $docid));\n}\n\nif ($createpath && !file_exists($fullpath)) {\n\n    $permissions = octdec('0' . (int)($modx->getOption('new_folder_permissions', null, '755', true)));\n    if (!@mkdir($fullpath, $permissions, true)) {\n        $modx->log(modX::LOG_LEVEL_DEBUG, sprintf('[resourceMediaPath]: could not create directory %s).', $fullpath));\n    } else {\n        chmod($fullpath, $permissions);\n    }\n}\n\nreturn $path;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.resourcemediapath.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:11:"conflicting";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.resourcemediapath.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * resourceMediaPath
 *
 * Dynamically calculates the upload path for a given resource.
 *
 * This Snippet is meant to dynamically calculate your baseBath attribute
 * for custom Media Sources. This is useful if you wish to shepard uploaded
 * images to a folder dedicated to a given resource. E.g. page 123 would
 * have its own images that page 456 could not reference.
 *
 * USAGE
 * [[resourceMediaPath? &pathTpl=`assets/businesses/{id}/`]]
 * [[resourceMediaPath? &pathTpl=`assets/resourceimages/{id}/` &checkTVs=`mymigxtv`]]
 * [[resourceMediaPath? &pathTpl=`assets/test/{breadcrumb}`]]
 * [[resourceMediaPath? &pathTpl=`assets/test/{breadcrumb}` &breadcrumbdepth=`2`]]
 *
 * PARAMETERS
 * &pathTpl string formatting string specifying the file path.
 *		Relative to MODX base_path
 *		Available placeholders: {id}, {pagetitle}, {parent}
 * &docid (optional) integer page id
 * &createFolder (optional) boolean whether to create folder or not
 * &checkTVs (optional) comma-separated list of TVs to check, before directory is created
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$pathTpl = $modx->getOption('pathTpl', $scriptProperties, '');
$docid = $modx->getOption('docid', $scriptProperties, '');
$createfolder = $modx->getOption('createFolder', $scriptProperties, false);
$tvname = $modx->getOption('tvname', $scriptProperties, '');
$checktvs = $modx->getOption('checkTVs', $scriptProperties, false);

$path = '';
$fullpath = '';
$createpath = false;
$fallbackpath = $modx->getOption('fallbackPath', $scriptProperties, 'assets/migxfallback/');

if (empty($pathTpl)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[resourceMediaPath]: pathTpl not specified.');
    return;
}

if (empty($docid) && $modx->getPlaceholder('mediasource_docid')) {
    // placeholder was set by some script
    // warning: the parser may not render placeholders, e.g. &docid=`[[*parent]]` may fail
    $docid = $modx->getPlaceholder('mediasource_docid');
}

if (empty($docid) && $modx->getPlaceholder('docid')) {
    // placeholder was set by some script
    // warning: the parser may not render placeholders, e.g. &docid=`[[*parent]]` may fail
    $docid = $modx->getPlaceholder('docid');
}
if (empty($docid)) {

    //on frontend
    if (is_object($modx->resource)) {
        $docid = $modx->resource->get('id');
    }
    //on manager resource/update page
    else {
        $createpath = $createfolder;

        // Read the &id param from an Ajax request
        $parsedUrl = parse_url($_SERVER['HTTP_REFERER']);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $parsedQuery);
        }

        // Avoid docid to be set to parent container
        $requestAction = $_REQUEST['a'] ?? '';
        $action = $parsedQuery['a'] ?? '';
        if (!$action && $requestAction || $action == $requestAction) {
            $docid = $_REQUEST['id'] ?? '';
        }
        elseif ($action === 'resource/update') {
            $docid = (int)$parsedQuery['amp;id'] ?? (int)$parsedQuery['id'] ?? 0;
        }
    }
}

if (empty($docid)) {
    $modx->log(modX::LOG_LEVEL_DEBUG, '[resourceMediaPath]: docid could not be determined.');
}

if (empty($docid) || empty($pathTpl)) {
    $path = $fallbackpath;
    $fullpath = $modx->getOption('base_path') . $fallbackpath;
    $createpath = true;
}

if (empty($fullpath) && $resource = $modx->getObject('modResource', $docid)) {
    $path = $pathTpl;
    $ultimateParent = '';
    if (strstr($path, '{breadcrumb}') || strstr($path, '{ultimateparent}')) {
        $depth = $modx->getOption('breadcrumbdepth', $scriptProperties, 10);
        $ctx = $resource->get('context_key');
        $parentids = $modx->getParentIds($docid, $depth, array('context' => $ctx));
        $breadcrumbdepth = $modx->getOption('breadcrumbdepth', $scriptProperties, count($parentids));
        $breadcrumbdepth = $breadcrumbdepth > count($parentids) ? count($parentids) : $breadcrumbdepth;
        if (count($parentids) > 1) {
            $parentids = array_reverse($parentids);
            $parentids[] = $docid;
            $ultimateParent = $parentids[1];
        } else {
            $ultimateParent = $docid;
            $parentids = array();
            $parentids[] = $docid;
        }
    }

    if (strstr($path, '{breadcrumb}')) {
        $breadcrumbpath = '';
        for ($i = 1; $i <= $breadcrumbdepth; $i++) {
            $breadcrumbpath .= $parentids[$i] . '/';
        }
        $path = str_replace('{breadcrumb}', $breadcrumbpath, $path);
    }

    if (!empty($tvname)){
        $path = str_replace('{tv_value}', $resource->getTVValue($tvname), $path);
    }
    $path = str_replace('{id}', $docid, $path);
    $path = str_replace('{pagetitle}', $resource->get('pagetitle'), $path);
    $path = str_replace('{alias}', $resource->get('alias'), $path);
    $path = str_replace('{parent}', $resource->get('parent'), $path);
    $path = str_replace('{context_key}', $resource->get('context_key'), $path);
    $path = str_replace('{ultimateparent}', $ultimateParent, $path);
    if ($template = $resource->getOne('Template')) {
        $path = str_replace('{templatename}', $template->get('templatename'), $path);
    }
    if ($user = $modx->user) {
        $path = str_replace('{username}', $modx->user->get('username'), $path);
        $path = str_replace('{userid}', $modx->user->get('id'), $path);
    }

    $fullpath = $modx->getOption('base_path') . $path;

    if ($createpath && $checktvs){
        $createpath = false;
        if ($template) {
            $tvs = explode(',',$checktvs);
            foreach ($tvs as $tv){
                if ($template->hasTemplateVar($tv)){
                    $createpath = true;
                }
            }
        }

    }

} else {
    $modx->log(modX::LOG_LEVEL_DEBUG, sprintf('[resourceMediaPath]: resource not found (page id %s).', $docid));
}

if ($createpath && !file_exists($fullpath)) {

    $permissions = octdec('0' . (int)($modx->getOption('new_folder_permissions', null, '755', true)));
    if (!@mkdir($fullpath, $permissions, true)) {
        $modx->log(modX::LOG_LEVEL_DEBUG, sprintf('[resourceMediaPath]: could not create directory %s).', $fullpath));
    } else {
        chmod($fullpath, $permissions);
    }
}

return $path;