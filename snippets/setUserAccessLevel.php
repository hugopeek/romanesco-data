id: 170
name: setUserAccessLevel
description: 'Check if logged-in user is a regular member, or has admin privileges. The corresponding member groups are set via the member_groups_frontend and admin_groups_frontend system settings.'
category: f_user
snippet: "/**\n * setUserAccessLevel snippet\n *\n * Check if logged-in user is a regular member, or has admin privileges.\n * The corresponding member groups are set via the member_groups_frontend and\n * admin_groups_frontend system settings.\n *\n * Just to be clear: these are frontend access levels only. They have nothing to\n * do with backend access. Depending on the ACL settings, a user could be admin\n * on frontend, but have no manager privileges at all.\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\n$output = $modx->getOption('default', $scriptProperties, 'anonymous');\n\nif (!$modx->getOption('romanesco.set_user_access_level', $scriptProperties)) {\n    $output = '';\n}\nelseif ($modx->user->id) {\n    // First, check if user is admin\n    $adminGroups = $modx->getOption('romanesco.admin_groups_frontend', $scriptProperties);\n    $adminGroups = array_filter(array_map('trim',explode(',',$adminGroups)));\n    foreach($adminGroups as $group) {\n        if ($modx->user->isMember($group)) {\n            $output = 'admin';\n        }\n    }\n\n    // Next, check regular membership\n    if ($output != 'admin') {\n        $memberGroups = $modx->getOption('romanesco.member_groups_frontend', $scriptProperties);\n        $memberGroups = array_filter(array_map('trim',explode(',',$memberGroups)));\n        foreach($memberGroups as $group) {\n            if ($modx->user->isMember($group)) {\n                $output = 'member';\n            }\n        }\n    }\n}\n\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * setUserAccessLevel snippet
 *
 * Check if logged-in user is a regular member, or has admin privileges.
 * The corresponding member groups are set via the member_groups_frontend and
 * admin_groups_frontend system settings.
 *
 * Just to be clear: these are frontend access levels only. They have nothing to
 * do with backend access. Depending on the ACL settings, a user could be admin
 * on frontend, but have no manager privileges at all.
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$output = $modx->getOption('default', $scriptProperties, 'anonymous');

if (!$modx->getOption('romanesco.set_user_access_level', $scriptProperties)) {
    $output = '';
}
elseif ($modx->user->id) {
    // First, check if user is admin
    $adminGroups = $modx->getOption('romanesco.admin_groups_frontend', $scriptProperties);
    $adminGroups = array_filter(array_map('trim',explode(',',$adminGroups)));
    foreach($adminGroups as $group) {
        if ($modx->user->isMember($group)) {
            $output = 'admin';
        }
    }

    // Next, check regular membership
    if ($output != 'admin') {
        $memberGroups = $modx->getOption('romanesco.member_groups_frontend', $scriptProperties);
        $memberGroups = array_filter(array_map('trim',explode(',',$memberGroups)));
        foreach($memberGroups as $group) {
            if ($modx->user->isMember($group)) {
                $output = 'member';
            }
        }
    }
}

$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;