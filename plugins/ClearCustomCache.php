id: 31
name: ClearCustomCache
category: c_global
plugincode: "/*\n * Clear custom cache partitions.\n */\n\nswitch ($modx->event->name) {\n    case 'OnSiteRefresh':\n    case 'OnDocFormSave':\n\n        if ($modx->getOption('romanesco.member_groups')) {\n            $partitions = 'nav_anonymous,nav_member';\n            $response = $modx->runProcessor('cache/partition/refresh', array(\n                'partitions' => $partitions\n            ), array(\n                'processors_path' => MODX_CORE_PATH . 'components/getcache/processors/'\n            ));\n\n            if ($response->isError()) {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'There was an error refreshing (one of) your custom cache partitions. ' . $response->getMessage(), '', 'Cache Refresh');\n            } else {\n                //$oldLoglevel = $modx->getLogLevel();\n                //$modx->setLogLevel(modX::LOG_LEVEL_INFO);\n                $modx->log(modX::LOG_LEVEL_INFO, 'Cache partitions successfully cleared.', '', 'Cache Refresh');\n                //$modx->setLogLevel($oldLoglevel);\n            }\n            break;\n        }\n}\nreturn;"
properties: 'a:0:{}'
disabled: 1
content: "/*\n * Clear custom cache partitions.\n */\n\nswitch ($modx->event->name) {\n    case 'OnSiteRefresh':\n    case 'OnDocFormSave':\n\n        if ($modx->getOption('romanesco.member_groups')) {\n            $partitions = 'nav_anonymous,nav_member';\n            $response = $modx->runProcessor('cache/partition/refresh', array(\n                'partitions' => $partitions\n            ), array(\n                'processors_path' => MODX_CORE_PATH . 'components/getcache/processors/'\n            ));\n\n            if ($response->isError()) {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'There was an error refreshing (one of) your custom cache partitions. ' . $response->getMessage(), '', 'Cache Refresh');\n            } else {\n                //$oldLoglevel = $modx->getLogLevel();\n                //$modx->setLogLevel(modX::LOG_LEVEL_INFO);\n                $modx->log(modX::LOG_LEVEL_INFO, 'Cache partitions successfully cleared.', '', 'Cache Refresh');\n                //$modx->setLogLevel($oldLoglevel);\n            }\n            break;\n        }\n}\nreturn;"

-----


/**
 * ClearCustomCache
 *
 * Currently this only clears cache partitions of navigation groups, to make
 * sure save actions are immediately reflected on frontend.
 *
 * In the future, other partitions can be cleared on certain triggers to lessen
 * the need of clearing the custom cache manually (in MODX manager).
 *
 * @var modX $modx
 * @package romanesco
 */

switch ($modx->event->name) {
    case 'OnSiteRefresh':
    case 'OnDocFormSave':

        if ($modx->getOption('romanesco.member_groups')) {
            $partitions = 'nav_anonymous,nav_member';
            $response = $modx->runProcessor('cache/partition/refresh', array(
                'partitions' => $partitions
            ), array(
                'processors_path' => MODX_CORE_PATH . 'components/getcache/processors/'
            ));

            if ($response->isError()) {
                $modx->log(modX::LOG_LEVEL_ERROR, 'There was an error refreshing (one of) your custom cache partitions. ' . $response->getMessage(), '', 'Cache Refresh');
            } else {
                //$oldLoglevel = $modx->getLogLevel();
                //$modx->setLogLevel(modX::LOG_LEVEL_INFO);
                $modx->log(modX::LOG_LEVEL_INFO, 'Cache partitions successfully cleared.', '', 'Cache Refresh');
                //$modx->setLogLevel($oldLoglevel);
            }
            break;
        }
}
return;