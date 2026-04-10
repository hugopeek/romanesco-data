id: 44
name: renderResources
description: 'Customized version of the renderResources snippet. This version forwards snippet call parameters as placeholders, which can be integrated in the rendered output.'
category: f_resource
snippet: "/**\n * renderResources\n *\n * A snippet for MODX Revolution that renders the output of a collection of Resources.\n *\n * @author Jason Coward\n *\n * TEMPLATES\n *\n * tpl - Name of a chunk serving as a wrapper template for resources\n * [NOTE: if not provided, the output is not wrapped]\n * tplOdd - (Opt) Name of a chunk serving as a wrapper template for resources with an odd idx value\n * (see idx property)\n * tplFirst - (Opt) Name of a chunk serving as a wrapper template for the first resource (see first\n * property)\n * tplLast - (Opt) Name of a chunk serving as a wrapper template for the last resource (see last\n * property)\n * tpl_{n} - (Opt) Name of a chunk serving as wrapper template for the nth resource\n *\n * SELECTION\n *\n * parents - Comma-delimited list of ids serving as parents\n *\n * contexts - (Opt) Comma-delimited list of context keys to limit results by; if empty, contexts for all specified\n * parents will be used (all contexts if 0 is specified) [default=]\n *\n * depth - (Opt) Integer value indicating depth to search for resources from each parent [default=10]\n *\n * tvFilters - (Opt) Delimited-list of TemplateVar values to filter resources by. Supports two\n * delimiters and two value search formats. THe first delimiter || represents a logical OR and the\n * primary grouping mechanism.  Within each group you can provide a comma-delimited list of values.\n * These values can be either tied to a specific TemplateVar by name, e.g. myTV==value, or just the\n * value, indicating you are searching for the value in any TemplateVar tied to the Resource. An\n * example would be &tvFilters=`filter2==one,filter1==bar%||filter1==foo`\n * [NOTE: filtering by values uses a LIKE query and % is considered a wildcard.]\n * [NOTE: this only looks at the raw value set for specific Resource, i. e. there must be a value\n * specifically set for the Resource and it is not evaluated.]\n *\n * where - (Opt) A JSON expression of criteria to build any additional where clauses from. An example would be\n * &where=`{{\"alias:LIKE\":\"foo%\", \"OR:alias:LIKE\":\"%bar\"},{\"OR:pagetitle:=\":\"foobar\", \"AND:description:=\":\"raboof\"}}`\n *\n * sortby - (Opt) Field to sort by or a JSON array, e.g. {\"publishedon\":\"ASC\",\"createdon\":\"DESC\"} [default=publishedon]\n * sortbyTV - (opt) A Template Variable name to sort by (if supplied, this precedes the sortby value) [default=]\n * sortbyTVType - (Opt) A data type to CAST a TV Value to in order to sort on it properly [default=string]\n * sortbyAlias - (Opt) Query alias for sortby field [default=]\n * sortbyEscaped - (Opt) Escapes the field name(s) specified in sortby [default=0]\n * sortdir - (Opt) Order which to sort by [default=DESC]\n * sortdirTV - (Opt) Order which to sort by a TV [default=DESC]\n * limit - (Opt) Limits the number of resources returned [default=5]\n * offset - (Opt) An offset of resources returned by the criteria to skip [default=0]\n * dbCacheFlag - (Opt) Controls caching of db queries; 0|false = do not cache result set; 1 = cache result set\n * according to cache settings, any other integer value = number of seconds to cache result set [default=0]\n *\n * OPTIONS\n *\n * idx - (Opt) You can define the starting idx of the resources, which is an property that is\n * incremented as each resource is rendered [default=1]\n * first - (Opt) Define the idx which represents the first resource (see tplFirst) [default=1]\n * last - (Opt) Define the idx which represents the last resource (see tplLast) [default=# of\n * resources being summarized + first - 1]\n * outputSeparator - (Opt) An optional string to separate each tpl instance [default=\"\\n\"]\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\nuse MODX\\Revolution\\modX;\n\nif (!function_exists('getDivisors')) {\n    function getDivisors($integer) {\n        $divisors = array();\n        for ($i = $integer; $i > 1; $i--) {\n            if (($integer % $i) === 0) {\n                $divisors[] = $i;\n            }\n        }\n        return $divisors;\n    }\n}\n\n$output = array();\n$outputSeparator = isset($outputSeparator) ? $outputSeparator : \"\\n\";\n\n/* set default properties */\n$tpl = !empty($tpl) ? $tpl : '';\n\n$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('id'));\narray_walk($parents, 'trim');\n$parents = array_unique($parents);\n$depth = isset($depth) ? (integer) $depth : 10;\n\n$tvFilters = !empty($tvFilters) ? explode('||', $tvFilters) : array();\n\n$where = !empty($where) ? $modx->fromJSON($where) : array();\n$showUnpublished = !empty($showUnpublished) ? true : false;\n$showDeleted = !empty($showDeleted) ? true : false;\n\n$sortby = isset($sortby) ? $sortby : 'publishedon';\n$sortbyTV = isset($sortbyTV) ? $sortbyTV : '';\n$sortbyAlias = isset($sortbyAlias) ? $sortbyAlias : 'modResource';\n$sortbyEscaped = !empty($sortbyEscaped) ? true : false;\n$sortdir = isset($sortdir) ? $sortdir : 'DESC';\n$sortdirTV = isset($sortdirTV) ? $sortdirTV : 'DESC';\n$limit = isset($limit) ? (integer) $limit : 5;\n$offset = isset($offset) ? (integer) $offset : 0;\n$totalVar = !empty($totalVar) ? $totalVar : 'total';\n\n$dbCacheFlag = !isset($dbCacheFlag) ? false : $dbCacheFlag;\nif (is_string($dbCacheFlag) || is_numeric($dbCacheFlag)) {\n    if ($dbCacheFlag == '0') {\n        $dbCacheFlag = false;\n    } elseif ($dbCacheFlag == '1') {\n        $dbCacheFlag = true;\n    } else {\n        $dbCacheFlag = (integer) $dbCacheFlag;\n    }\n}\n\n/* multiple context support */\n$contextArray = array();\n$contextSpecified = false;\nif (!empty($context)) {\n    $contextArray = explode(',',$context);\n    array_walk($contextArray, 'trim');\n    $contexts = array();\n    foreach ($contextArray as $ctx) {\n        $contexts[] = $modx->quote($ctx);\n    }\n    $context = implode(',',$contexts);\n    $contextSpecified = true;\n    unset($contexts,$ctx);\n} else {\n    $context = $modx->quote($modx->context->get('key'));\n}\n\n$pcMap = array();\n$pcQuery = $modx->newQuery('modResource', array('id:IN' => $parents), $dbCacheFlag);\n$pcQuery->select(array('id', 'context_key'));\nif ($pcQuery->prepare() && $pcQuery->stmt->execute()) {\n    foreach ($pcQuery->stmt->fetchAll(PDO::FETCH_ASSOC) as $pcRow) {\n        $pcMap[(integer) $pcRow['id']] = $pcRow['context_key'];\n    }\n}\n\n$children = array();\n$parentArray = array();\nforeach ($parents as $parent) {\n    $parent = (integer) $parent;\n    if ($parent === 0) {\n        $pchildren = array();\n        if ($contextSpecified) {\n            foreach ($contextArray as $pCtx) {\n                if (!in_array($pCtx, $contextArray)) {\n                    continue;\n                }\n                $options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();\n                $pcchildren = $modx->getChildIds($parent, $depth, $options);\n                if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);\n            }\n        } else {\n            $cQuery = $modx->newQuery('modContext', array('key:!=' => 'mgr'));\n            $cQuery->select(array('key'));\n            if ($cQuery->prepare() && $cQuery->stmt->execute()) {\n                foreach ($cQuery->stmt->fetchAll(PDO::FETCH_COLUMN) as $pCtx) {\n                    $options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();\n                    $pcchildren = $modx->getChildIds($parent, $depth, $options);\n                    if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);\n                }\n            }\n        }\n        $parentArray[] = $parent;\n    } else {\n        $pContext = array_key_exists($parent, $pcMap) ? $pcMap[$parent] : false;\n        if (isset($debug) && $debug) $modx->log(modX::LOG_LEVEL_ERROR, \"context for {$parent} is {$pContext}\");\n        if ($pContext && $contextSpecified && !in_array($pContext, $contextArray, true)) {\n            $parent = next($parents);\n            continue;\n        }\n        $parentArray[] = $parent;\n        $options = !empty($pContext) && $pContext !== $modx->context->get('key') ? array('context' => $pContext) : array();\n        $pchildren = $modx->getChildIds($parent, $depth, $options);\n    }\n    if (!empty($pchildren)) $children = array_merge($children, $pchildren);\n    $parent = next($parents);\n}\n$parents = array_merge($parentArray, $children);\n\n$criteria = array(\"modResource.parent IN (\" . implode(',', $parents) . \")\");\nif ($contextSpecified) {\n    $contextResourceTbl = $modx->getTableName('modContextResource');\n    $criteria[] = \"(modResource.context_key IN ({$context}) OR EXISTS(SELECT 1 FROM {$contextResourceTbl} ctx WHERE ctx.resource = modResource.id AND ctx.context_key IN ({$context})))\";\n}\nif (empty($showDeleted)) {\n    $criteria['deleted'] = '0';\n}\nif (empty($showUnpublished)) {\n    $criteria['published'] = '1';\n}\nif (empty($showHidden)) {\n    $criteria['hidemenu'] = '0';\n}\nif (!empty($hideContainers)) {\n    $criteria['isfolder'] = '0';\n}\n$criteria['class_key:IN'] = array('modDocument', 'modStaticResource');\n/** @var xPDOQuery $criteria */\n$criteria = $modx->newQuery('modResource', $criteria);\n$criteria->innerJoin('modContentType', 'ContentType', array('ContentType.binary' => false, \"ContentType.id = modResource.content_type\"));\nif (!empty($tvFilters)) {\n    $tmplVarTbl = $modx->getTableName('modTemplateVar');\n    $tmplVarResourceTbl = $modx->getTableName('modTemplateVarResource');\n    $conditions = array();\n    $operators = array(\n        '<=>' => '<=>',\n        '===' => '=',\n        '!==' => '!=',\n        '<>' => '<>',\n        '==' => 'LIKE',\n        '!=' => 'NOT LIKE',\n        '<<' => '<',\n        '<=' => '<=',\n        '=<' => '=<',\n        '>>' => '>',\n        '>=' => '>=',\n        '=>' => '=>'\n    );\n    foreach ($tvFilters as $fGroup => $tvFilter) {\n        $filterGroup = array();\n        $filters = explode(',', $tvFilter);\n        $multiple = count($filters) > 0;\n        foreach ($filters as $filter) {\n            $operator = '==';\n            $sqlOperator = 'LIKE';\n            foreach ($operators as $op => $opSymbol) {\n                if (strpos($filter, $op, 1) !== false) {\n                    $operator = $op;\n                    $sqlOperator = $opSymbol;\n                    break;\n                }\n            }\n            $tvValueField = 'tvr.value';\n            $tvDefaultField = 'tv.default_text';\n            $f = explode($operator, $filter);\n            if (count($f) == 2) {\n                $tvName = $modx->quote($f[0]);\n                if (is_numeric($f[1]) && !in_array($sqlOperator, array('LIKE', 'NOT LIKE'))) {\n                    $tvValue = $f[1];\n                    if ($f[1] == (integer)$f[1]) {\n                        $tvValueField = \"CAST({$tvValueField} AS SIGNED INTEGER)\";\n                        $tvDefaultField = \"CAST({$tvDefaultField} AS SIGNED INTEGER)\";\n                    } else {\n                        $tvValueField = \"CAST({$tvValueField} AS DECIMAL)\";\n                        $tvDefaultField = \"CAST({$tvDefaultField} AS DECIMAL)\";\n                    }\n                } else {\n                    $tvValue = $modx->quote($f[1]);\n                }\n                if ($multiple) {\n                    $filterGroup[] =\n                        \"(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) \" .\n                        \"OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) \" .\n                        \")\";\n                } else {\n                    $filterGroup =\n                        \"(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) \" .\n                        \"OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) \" .\n                        \")\";\n                }\n            } elseif (count($f) == 1) {\n                $tvValue = $modx->quote($f[0]);\n                if ($multiple) {\n                    $filterGroup[] = \"EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)\";\n                } else {\n                    $filterGroup = \"EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)\";\n                }\n            }\n        }\n        $conditions[] = $filterGroup;\n    }\n    if (!empty($conditions)) {\n        $firstGroup = true;\n        foreach ($conditions as $cGroup => $c) {\n            if (is_array($c)) {\n                $first = true;\n                foreach ($c as $cond) {\n                    if ($first && !$firstGroup) {\n                        $criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_OR, null, $cGroup);\n                    } else {\n                        $criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_AND, null, $cGroup);\n                    }\n                    $first = false;\n                }\n            } else {\n                $criteria->condition($criteria->query['where'][0][1], $c, $firstGroup ? xPDOQuery::SQL_AND : xPDOQuery::SQL_OR, null, $cGroup);\n            }\n            $firstGroup = false;\n        }\n    }\n}\n/* include/exclude resources, via &resources=`123,-456` prop */\nif (!empty($resources)) {\n    $resourceConditions = array();\n    $resources = explode(',',$resources);\n    $include = array();\n    $exclude = array();\n    foreach ($resources as $resource) {\n        $resource = (int)$resource;\n        if ($resource == 0) continue;\n        if ($resource < 0) {\n            $exclude[] = abs($resource);\n        } else {\n            $include[] = $resource;\n        }\n    }\n    if (!empty($include)) {\n        $criteria->where(array('OR:modResource.id:IN' => $include), xPDOQuery::SQL_OR);\n    }\n    if (!empty($exclude)) {\n        $criteria->where(array('modResource.id:NOT IN' => $exclude), xPDOQuery::SQL_AND, null, 1);\n    }\n}\nif (!empty($where)) {\n    $criteria->where($where);\n}\n\nif (!empty($sortbyTV)) {\n    $criteria->leftJoin('modTemplateVar', 'tvDefault', array(\n        \"tvDefault.name\" => $sortbyTV\n    ));\n    $criteria->leftJoin('modTemplateVarResource', 'tvSort', array(\n        \"tvSort.contentid = modResource.id\",\n        \"tvSort.tmplvarid = tvDefault.id\"\n    ));\n    if (empty($sortbyTVType)) $sortbyTVType = 'string';\n    if ($modx->getOption('dbtype') === 'mysql') {\n        switch ($sortbyTVType) {\n            case 'integer':\n                $criteria->select(\"CAST(IFNULL(tvSort.value, tvDefault.default_text) AS SIGNED INTEGER) AS sortTV\");\n                break;\n            case 'decimal':\n                $criteria->select(\"CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV\");\n                break;\n            case 'datetime':\n                $criteria->select(\"CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV\");\n                break;\n            case 'string':\n            default:\n                $criteria->select(\"IFNULL(tvSort.value, tvDefault.default_text) AS sortTV\");\n                break;\n        }\n    } elseif ($modx->getOption('dbtype') === 'sqlsrv') {\n        switch ($sortbyTVType) {\n            case 'integer':\n                $criteria->select(\"CAST(ISNULL(tvSort.value, tvDefault.default_text) AS BIGINT) AS sortTV\");\n                break;\n            case 'decimal':\n                $criteria->select(\"CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV\");\n                break;\n            case 'datetime':\n                $criteria->select(\"CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV\");\n                break;\n            case 'string':\n            default:\n                $criteria->select(\"ISNULL(tvSort.value, tvDefault.default_text) AS sortTV\");\n                break;\n        }\n    }\n    $criteria->sortby(\"sortTV\", $sortdirTV);\n}\nif (!empty($sortby)) {\n    if (strpos($sortby, '{') === 0) {\n        $sorts = $modx->fromJSON($sortby);\n    } else {\n        $sorts = array($sortby => $sortdir);\n    }\n    if (is_array($sorts)) {\n        foreach ($sorts as $sort => $dir) {\n            if ($sortbyEscaped) $sort = $modx->escape($sort);\n            if (!empty($sortbyAlias)) $sort = $modx->escape($sortbyAlias) . \".{$sort}\";\n            $criteria->sortby($sort, $dir);\n        }\n    }\n}\n\n$total = $modx->getCount('modResource', $criteria);\n$modx->setPlaceholder($totalVar, $total);\n\n\nif (!empty($limit)) $criteria->limit($limit, $offset);\n\nif (!empty($debug)) {\n    $criteria->prepare();\n    $modx->log(modX::LOG_LEVEL_ERROR, $criteria->toSQL());\n}\n$collection = $modx->getCollection('modResource', $criteria, $dbCacheFlag);\n\n$idx = !empty($idx) && $idx !== '0' ? (integer) $idx : 1;\n$first = isset($first) && empty($first) ? 1 : (integer) $first;\n$last = empty($last) ? (count($collection) + $idx - 1) : (integer) $last;\n\n$maxIterations = empty($maxIterations) || (integer) $maxIterations < 1 ? 10 : (integer) $maxIterations;\n$currentResource = $modx->resource;\n$currentResourceIdentifier = $modx->resourceIdentifier;\n$currentElementCache = $modx->elementCache;\n\n// CUSTOM\n// Set host and script properties as placeholder\n$hostProperties = [];\n$prefix = $modx->getOption('prefix', $scriptProperties, 'host.');\n$hostProperties = $currentResource->toArray();\nforeach ($hostProperties as $property => $value) {\n    $modx->setPlaceholder($prefix . $property, $value);\n}\nforeach ($scriptProperties as $property => $value) {\n    $modx->setPlaceholder($property, $value);\n}\n\n/** @var modResource $resource */\nforeach ($collection as $resourceId => $resource) {\n    $odd = ($idx & 1);\n    $properties = array_merge(\n        $scriptProperties\n        ,array(\n            'idx' => $idx\n            ,'first' => $first\n            ,'last' => $last\n        )\n    );\n    $resourceTpl = null;\n    $tplidx = 'tpl_' . $idx;\n    if (!empty($$tplidx)) {\n        $resourceTpl = $modx->parser->getElement('modChunk', $$tplidx);\n    }\n    if ($idx > 1 && $resourceTpl === null) {\n        $divisors = getDivisors($idx);\n        if (!empty($divisors)) {\n            foreach ($divisors as $divisor) {\n                $tplnth = 'tpl_n' . $divisor;\n                if (!empty($$tplnth)) {\n                    $resourceTpl = $modx->parser->getElement('modChunk', $$tplnth);\n                    if ($resourceTpl !== null) {\n                        break;\n                    }\n                }\n            }\n        }\n    }\n    if ($idx == $first && $resourceTpl === null && !empty($tplFirst)) {\n        $resourceTpl = $modx->parser->getElement('modChunk', $tplFirst);\n    }\n    if ($idx == $last && $resourceTpl === null && !empty($tplLast)) {\n        $resourceTpl = $modx->parser->getElement('modChunk', $tplLast);\n    }\n    if ($odd && $resourceTpl === null && !empty($tplOdd)) {\n        $resourceTpl = $modx->parser->getElement('modChunk', $tplOdd);\n    }\n    if (!empty($tpl) && $resourceTpl === null) {\n        $resourceTpl = $modx->parser->getElement('modChunk', $tpl);\n    }\n\n    $modx->resource = $resource;\n    $modx->resourceIdentifier = $resource->get('id');\n    $modx->elementCache = array();\n    $resourceOutput = $modx->resource->process();\n    $modx->parser->processElementTags('', $resourceOutput, true, false, '[[', ']]', array(), $maxIterations);\n    $modx->parser->processElementTags('', $resourceOutput, true, true, '[[', ']]', array(), $maxIterations);\n\n    if (empty($resourceTpl)) {\n        $output[]= $resourceOutput;\n    } else {\n        $output[]= $resourceTpl->process(array_merge($properties, array('output' => $resourceOutput)));\n    }\n    $idx++;\n}\n\n$modx->elementCache = $currentElementCache;\n$modx->resourceIdentifier = $currentResourceIdentifier;\n$modx->resource = $currentResource;\n\n// CUSTOM\n// Unset placeholders, so they don't spill over into the host resource itself\nforeach ($hostProperties as $property => $value) {\n    $modx->unsetPlaceholder($prefix . $property);\n}\nforeach ($scriptProperties as $property => $value) {\n    $modx->unsetPlaceholder($property);\n}\n\n/* output */\n$toSeparatePlaceholders = $modx->getOption('toSeparatePlaceholders',$scriptProperties,false);\nif (!empty($toSeparatePlaceholders)) {\n    $modx->setPlaceholders($output,$toSeparatePlaceholders);\n    return '';\n}\n\n$output = implode($outputSeparator, $output);\n$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);\nif (!empty($toPlaceholder)) {\n    $modx->setPlaceholder($toPlaceholder,$output);\n    return '';\n}\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:6:"review";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * renderResources
 *
 * A snippet for MODX Revolution that renders the output of a collection of Resources.
 *
 * @author Jason Coward
 *
 * TEMPLATES
 *
 * tpl - Name of a chunk serving as a wrapper template for resources
 * [NOTE: if not provided, the output is not wrapped]
 * tplOdd - (Opt) Name of a chunk serving as a wrapper template for resources with an odd idx value
 * (see idx property)
 * tplFirst - (Opt) Name of a chunk serving as a wrapper template for the first resource (see first
 * property)
 * tplLast - (Opt) Name of a chunk serving as a wrapper template for the last resource (see last
 * property)
 * tpl_{n} - (Opt) Name of a chunk serving as wrapper template for the nth resource
 *
 * SELECTION
 *
 * parents - Comma-delimited list of ids serving as parents
 *
 * contexts - (Opt) Comma-delimited list of context keys to limit results by; if empty, contexts for all specified
 * parents will be used (all contexts if 0 is specified) [default=]
 *
 * depth - (Opt) Integer value indicating depth to search for resources from each parent [default=10]
 *
 * tvFilters - (Opt) Delimited-list of TemplateVar values to filter resources by. Supports two
 * delimiters and two value search formats. THe first delimiter || represents a logical OR and the
 * primary grouping mechanism.  Within each group you can provide a comma-delimited list of values.
 * These values can be either tied to a specific TemplateVar by name, e.g. myTV==value, or just the
 * value, indicating you are searching for the value in any TemplateVar tied to the Resource. An
 * example would be &tvFilters=`filter2==one,filter1==bar%||filter1==foo`
 * [NOTE: filtering by values uses a LIKE query and % is considered a wildcard.]
 * [NOTE: this only looks at the raw value set for specific Resource, i. e. there must be a value
 * specifically set for the Resource and it is not evaluated.]
 *
 * where - (Opt) A JSON expression of criteria to build any additional where clauses from. An example would be
 * &where=`{{"alias:LIKE":"foo%", "OR:alias:LIKE":"%bar"},{"OR:pagetitle:=":"foobar", "AND:description:=":"raboof"}}`
 *
 * sortby - (Opt) Field to sort by or a JSON array, e.g. {"publishedon":"ASC","createdon":"DESC"} [default=publishedon]
 * sortbyTV - (opt) A Template Variable name to sort by (if supplied, this precedes the sortby value) [default=]
 * sortbyTVType - (Opt) A data type to CAST a TV Value to in order to sort on it properly [default=string]
 * sortbyAlias - (Opt) Query alias for sortby field [default=]
 * sortbyEscaped - (Opt) Escapes the field name(s) specified in sortby [default=0]
 * sortdir - (Opt) Order which to sort by [default=DESC]
 * sortdirTV - (Opt) Order which to sort by a TV [default=DESC]
 * limit - (Opt) Limits the number of resources returned [default=5]
 * offset - (Opt) An offset of resources returned by the criteria to skip [default=0]
 * dbCacheFlag - (Opt) Controls caching of db queries; 0|false = do not cache result set; 1 = cache result set
 * according to cache settings, any other integer value = number of seconds to cache result set [default=0]
 *
 * OPTIONS
 *
 * idx - (Opt) You can define the starting idx of the resources, which is an property that is
 * incremented as each resource is rendered [default=1]
 * first - (Opt) Define the idx which represents the first resource (see tplFirst) [default=1]
 * last - (Opt) Define the idx which represents the last resource (see tplLast) [default=# of
 * resources being summarized + first - 1]
 * outputSeparator - (Opt) An optional string to separate each tpl instance [default="\n"]
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use MODX\Revolution\modX;

if (!function_exists('getDivisors')) {
    function getDivisors($integer) {
        $divisors = array();
        for ($i = $integer; $i > 1; $i--) {
            if (($integer % $i) === 0) {
                $divisors[] = $i;
            }
        }
        return $divisors;
    }
}

$output = array();
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\n";

/* set default properties */
$tpl = !empty($tpl) ? $tpl : '';

$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('id'));
array_walk($parents, 'trim');
$parents = array_unique($parents);
$depth = isset($depth) ? (integer) $depth : 10;

$tvFilters = !empty($tvFilters) ? explode('||', $tvFilters) : array();

$where = !empty($where) ? $modx->fromJSON($where) : array();
$showUnpublished = !empty($showUnpublished) ? true : false;
$showDeleted = !empty($showDeleted) ? true : false;

$sortby = isset($sortby) ? $sortby : 'publishedon';
$sortbyTV = isset($sortbyTV) ? $sortbyTV : '';
$sortbyAlias = isset($sortbyAlias) ? $sortbyAlias : 'modResource';
$sortbyEscaped = !empty($sortbyEscaped) ? true : false;
$sortdir = isset($sortdir) ? $sortdir : 'DESC';
$sortdirTV = isset($sortdirTV) ? $sortdirTV : 'DESC';
$limit = isset($limit) ? (integer) $limit : 5;
$offset = isset($offset) ? (integer) $offset : 0;
$totalVar = !empty($totalVar) ? $totalVar : 'total';

$dbCacheFlag = !isset($dbCacheFlag) ? false : $dbCacheFlag;
if (is_string($dbCacheFlag) || is_numeric($dbCacheFlag)) {
    if ($dbCacheFlag == '0') {
        $dbCacheFlag = false;
    } elseif ($dbCacheFlag == '1') {
        $dbCacheFlag = true;
    } else {
        $dbCacheFlag = (integer) $dbCacheFlag;
    }
}

/* multiple context support */
$contextArray = array();
$contextSpecified = false;
if (!empty($context)) {
    $contextArray = explode(',',$context);
    array_walk($contextArray, 'trim');
    $contexts = array();
    foreach ($contextArray as $ctx) {
        $contexts[] = $modx->quote($ctx);
    }
    $context = implode(',',$contexts);
    $contextSpecified = true;
    unset($contexts,$ctx);
} else {
    $context = $modx->quote($modx->context->get('key'));
}

$pcMap = array();
$pcQuery = $modx->newQuery('modResource', array('id:IN' => $parents), $dbCacheFlag);
$pcQuery->select(array('id', 'context_key'));
if ($pcQuery->prepare() && $pcQuery->stmt->execute()) {
    foreach ($pcQuery->stmt->fetchAll(PDO::FETCH_ASSOC) as $pcRow) {
        $pcMap[(integer) $pcRow['id']] = $pcRow['context_key'];
    }
}

$children = array();
$parentArray = array();
foreach ($parents as $parent) {
    $parent = (integer) $parent;
    if ($parent === 0) {
        $pchildren = array();
        if ($contextSpecified) {
            foreach ($contextArray as $pCtx) {
                if (!in_array($pCtx, $contextArray)) {
                    continue;
                }
                $options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();
                $pcchildren = $modx->getChildIds($parent, $depth, $options);
                if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);
            }
        } else {
            $cQuery = $modx->newQuery('modContext', array('key:!=' => 'mgr'));
            $cQuery->select(array('key'));
            if ($cQuery->prepare() && $cQuery->stmt->execute()) {
                foreach ($cQuery->stmt->fetchAll(PDO::FETCH_COLUMN) as $pCtx) {
                    $options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();
                    $pcchildren = $modx->getChildIds($parent, $depth, $options);
                    if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);
                }
            }
        }
        $parentArray[] = $parent;
    } else {
        $pContext = array_key_exists($parent, $pcMap) ? $pcMap[$parent] : false;
        if (isset($debug) && $debug) $modx->log(modX::LOG_LEVEL_ERROR, "context for {$parent} is {$pContext}");
        if ($pContext && $contextSpecified && !in_array($pContext, $contextArray, true)) {
            $parent = next($parents);
            continue;
        }
        $parentArray[] = $parent;
        $options = !empty($pContext) && $pContext !== $modx->context->get('key') ? array('context' => $pContext) : array();
        $pchildren = $modx->getChildIds($parent, $depth, $options);
    }
    if (!empty($pchildren)) $children = array_merge($children, $pchildren);
    $parent = next($parents);
}
$parents = array_merge($parentArray, $children);

$criteria = array("modResource.parent IN (" . implode(',', $parents) . ")");
if ($contextSpecified) {
    $contextResourceTbl = $modx->getTableName('modContextResource');
    $criteria[] = "(modResource.context_key IN ({$context}) OR EXISTS(SELECT 1 FROM {$contextResourceTbl} ctx WHERE ctx.resource = modResource.id AND ctx.context_key IN ({$context})))";
}
if (empty($showDeleted)) {
    $criteria['deleted'] = '0';
}
if (empty($showUnpublished)) {
    $criteria['published'] = '1';
}
if (empty($showHidden)) {
    $criteria['hidemenu'] = '0';
}
if (!empty($hideContainers)) {
    $criteria['isfolder'] = '0';
}
$criteria['class_key:IN'] = array('modDocument', 'modStaticResource');
/** @var xPDOQuery $criteria */
$criteria = $modx->newQuery('modResource', $criteria);
$criteria->innerJoin('modContentType', 'ContentType', array('ContentType.binary' => false, "ContentType.id = modResource.content_type"));
if (!empty($tvFilters)) {
    $tmplVarTbl = $modx->getTableName('modTemplateVar');
    $tmplVarResourceTbl = $modx->getTableName('modTemplateVarResource');
    $conditions = array();
    $operators = array(
        '<=>' => '<=>',
        '===' => '=',
        '!==' => '!=',
        '<>' => '<>',
        '==' => 'LIKE',
        '!=' => 'NOT LIKE',
        '<<' => '<',
        '<=' => '<=',
        '=<' => '=<',
        '>>' => '>',
        '>=' => '>=',
        '=>' => '=>'
    );
    foreach ($tvFilters as $fGroup => $tvFilter) {
        $filterGroup = array();
        $filters = explode(',', $tvFilter);
        $multiple = count($filters) > 0;
        foreach ($filters as $filter) {
            $operator = '==';
            $sqlOperator = 'LIKE';
            foreach ($operators as $op => $opSymbol) {
                if (strpos($filter, $op, 1) !== false) {
                    $operator = $op;
                    $sqlOperator = $opSymbol;
                    break;
                }
            }
            $tvValueField = 'tvr.value';
            $tvDefaultField = 'tv.default_text';
            $f = explode($operator, $filter);
            if (count($f) == 2) {
                $tvName = $modx->quote($f[0]);
                if (is_numeric($f[1]) && !in_array($sqlOperator, array('LIKE', 'NOT LIKE'))) {
                    $tvValue = $f[1];
                    if ($f[1] == (integer)$f[1]) {
                        $tvValueField = "CAST({$tvValueField} AS SIGNED INTEGER)";
                        $tvDefaultField = "CAST({$tvDefaultField} AS SIGNED INTEGER)";
                    } else {
                        $tvValueField = "CAST({$tvValueField} AS DECIMAL)";
                        $tvDefaultField = "CAST({$tvDefaultField} AS DECIMAL)";
                    }
                } else {
                    $tvValue = $modx->quote($f[1]);
                }
                if ($multiple) {
                    $filterGroup[] =
                        "(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) " .
                        "OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) " .
                        ")";
                } else {
                    $filterGroup =
                        "(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) " .
                        "OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) " .
                        ")";
                }
            } elseif (count($f) == 1) {
                $tvValue = $modx->quote($f[0]);
                if ($multiple) {
                    $filterGroup[] = "EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)";
                } else {
                    $filterGroup = "EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)";
                }
            }
        }
        $conditions[] = $filterGroup;
    }
    if (!empty($conditions)) {
        $firstGroup = true;
        foreach ($conditions as $cGroup => $c) {
            if (is_array($c)) {
                $first = true;
                foreach ($c as $cond) {
                    if ($first && !$firstGroup) {
                        $criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_OR, null, $cGroup);
                    } else {
                        $criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_AND, null, $cGroup);
                    }
                    $first = false;
                }
            } else {
                $criteria->condition($criteria->query['where'][0][1], $c, $firstGroup ? xPDOQuery::SQL_AND : xPDOQuery::SQL_OR, null, $cGroup);
            }
            $firstGroup = false;
        }
    }
}
/* include/exclude resources, via &resources=`123,-456` prop */
if (!empty($resources)) {
    $resourceConditions = array();
    $resources = explode(',',$resources);
    $include = array();
    $exclude = array();
    foreach ($resources as $resource) {
        $resource = (int)$resource;
        if ($resource == 0) continue;
        if ($resource < 0) {
            $exclude[] = abs($resource);
        } else {
            $include[] = $resource;
        }
    }
    if (!empty($include)) {
        $criteria->where(array('OR:modResource.id:IN' => $include), xPDOQuery::SQL_OR);
    }
    if (!empty($exclude)) {
        $criteria->where(array('modResource.id:NOT IN' => $exclude), xPDOQuery::SQL_AND, null, 1);
    }
}
if (!empty($where)) {
    $criteria->where($where);
}

if (!empty($sortbyTV)) {
    $criteria->leftJoin('modTemplateVar', 'tvDefault', array(
        "tvDefault.name" => $sortbyTV
    ));
    $criteria->leftJoin('modTemplateVarResource', 'tvSort', array(
        "tvSort.contentid = modResource.id",
        "tvSort.tmplvarid = tvDefault.id"
    ));
    if (empty($sortbyTVType)) $sortbyTVType = 'string';
    if ($modx->getOption('dbtype') === 'mysql') {
        switch ($sortbyTVType) {
            case 'integer':
                $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS SIGNED INTEGER) AS sortTV");
                break;
            case 'decimal':
                $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
                break;
            case 'datetime':
                $criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
                break;
            case 'string':
            default:
                $criteria->select("IFNULL(tvSort.value, tvDefault.default_text) AS sortTV");
                break;
        }
    } elseif ($modx->getOption('dbtype') === 'sqlsrv') {
        switch ($sortbyTVType) {
            case 'integer':
                $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS BIGINT) AS sortTV");
                break;
            case 'decimal':
                $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
                break;
            case 'datetime':
                $criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
                break;
            case 'string':
            default:
                $criteria->select("ISNULL(tvSort.value, tvDefault.default_text) AS sortTV");
                break;
        }
    }
    $criteria->sortby("sortTV", $sortdirTV);
}
if (!empty($sortby)) {
    if (strpos($sortby, '{') === 0) {
        $sorts = $modx->fromJSON($sortby);
    } else {
        $sorts = array($sortby => $sortdir);
    }
    if (is_array($sorts)) {
        foreach ($sorts as $sort => $dir) {
            if ($sortbyEscaped) $sort = $modx->escape($sort);
            if (!empty($sortbyAlias)) $sort = $modx->escape($sortbyAlias) . ".{$sort}";
            $criteria->sortby($sort, $dir);
        }
    }
}

$total = $modx->getCount('modResource', $criteria);
$modx->setPlaceholder($totalVar, $total);


if (!empty($limit)) $criteria->limit($limit, $offset);

if (!empty($debug)) {
    $criteria->prepare();
    $modx->log(modX::LOG_LEVEL_ERROR, $criteria->toSQL());
}
$collection = $modx->getCollection('modResource', $criteria, $dbCacheFlag);

$idx = !empty($idx) && $idx !== '0' ? (integer) $idx : 1;
$first = isset($first) && empty($first) ? 1 : (integer) $first;
$last = empty($last) ? (count($collection) + $idx - 1) : (integer) $last;

$maxIterations = empty($maxIterations) || (integer) $maxIterations < 1 ? 10 : (integer) $maxIterations;
$currentResource = $modx->resource;
$currentResourceIdentifier = $modx->resourceIdentifier;
$currentElementCache = $modx->elementCache;

// CUSTOM
// Set host and script properties as placeholder
$hostProperties = [];
$prefix = $modx->getOption('prefix', $scriptProperties, 'host.');
$hostProperties = $currentResource->toArray();
foreach ($hostProperties as $property => $value) {
    $modx->setPlaceholder($prefix . $property, $value);
}
foreach ($scriptProperties as $property => $value) {
    $modx->setPlaceholder($property, $value);
}

/** @var modResource $resource */
foreach ($collection as $resourceId => $resource) {
    $odd = ($idx & 1);
    $properties = array_merge(
        $scriptProperties
        ,array(
            'idx' => $idx
            ,'first' => $first
            ,'last' => $last
        )
    );
    $resourceTpl = null;
    $tplidx = 'tpl_' . $idx;
    if (!empty($$tplidx)) {
        $resourceTpl = $modx->parser->getElement('modChunk', $$tplidx);
    }
    if ($idx > 1 && $resourceTpl === null) {
        $divisors = getDivisors($idx);
        if (!empty($divisors)) {
            foreach ($divisors as $divisor) {
                $tplnth = 'tpl_n' . $divisor;
                if (!empty($$tplnth)) {
                    $resourceTpl = $modx->parser->getElement('modChunk', $$tplnth);
                    if ($resourceTpl !== null) {
                        break;
                    }
                }
            }
        }
    }
    if ($idx == $first && $resourceTpl === null && !empty($tplFirst)) {
        $resourceTpl = $modx->parser->getElement('modChunk', $tplFirst);
    }
    if ($idx == $last && $resourceTpl === null && !empty($tplLast)) {
        $resourceTpl = $modx->parser->getElement('modChunk', $tplLast);
    }
    if ($odd && $resourceTpl === null && !empty($tplOdd)) {
        $resourceTpl = $modx->parser->getElement('modChunk', $tplOdd);
    }
    if (!empty($tpl) && $resourceTpl === null) {
        $resourceTpl = $modx->parser->getElement('modChunk', $tpl);
    }

    $modx->resource = $resource;
    $modx->resourceIdentifier = $resource->get('id');
    $modx->elementCache = array();
    $resourceOutput = $modx->resource->process();
    $modx->parser->processElementTags('', $resourceOutput, true, false, '[[', ']]', array(), $maxIterations);
    $modx->parser->processElementTags('', $resourceOutput, true, true, '[[', ']]', array(), $maxIterations);

    if (empty($resourceTpl)) {
        $output[]= $resourceOutput;
    } else {
        $output[]= $resourceTpl->process(array_merge($properties, array('output' => $resourceOutput)));
    }
    $idx++;
}

$modx->elementCache = $currentElementCache;
$modx->resourceIdentifier = $currentResourceIdentifier;
$modx->resource = $currentResource;

// CUSTOM
// Unset placeholders, so they don't spill over into the host resource itself
foreach ($hostProperties as $property => $value) {
    $modx->unsetPlaceholder($prefix . $property);
}
foreach ($scriptProperties as $property => $value) {
    $modx->unsetPlaceholder($property);
}

/* output */
$toSeparatePlaceholders = $modx->getOption('toSeparatePlaceholders',$scriptProperties,false);
if (!empty($toSeparatePlaceholders)) {
    $modx->setPlaceholders($output,$toSeparatePlaceholders);
    return '';
}

$output = implode($outputSeparator, $output);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder,$output);
    return '';
}
return $output;