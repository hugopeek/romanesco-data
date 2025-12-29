id: 177
name: UltimateParent
description: 'The classic menu traverser, from our Ultimate Parent Susan Ottwell'
category: f_framework
snippet: "/**\n * @name UltimateParent\n * @param &id The id of the document whose parent you want to find.\n * @param &top The top node for the search.\n * @param &topLevel The top level node for the search (root = level 1)\n *\n * @version 1.3\n * @author Susan Ottwell <sottwell@sottwell.com> March 2006\n * @author Al B <> May 18, 2007\n * @author S. Hamblett <shamblett@cwazy.co.uk>\n * @author Shaun McCormick <shaun@modx.com>\n * @author Jason Coward <modx@modx.com>\n *\n * @license Public Domain, use as you like.\n *\n * @example [[UltimateParent? &id=`45` &top=`6`]]\n * Will find the ultimate parent of document 45 if it is a child of document 6;\n * otherwise it will return 45.\n *\n * @example [[UltimateParent? &topLevel=`2`]]\n * Will find the ultimate parent of the current document at a depth of 2 levels\n * in the document hierarchy, with the root level being level 1.\n *\n * This snippet travels up the document tree from a specified document and\n * returns the \"ultimate\" parent.  Version 2.0 was rewritten to use the new\n * getParentIds function features available only in MODx 0.9.5 or later.\n *\n * Based on the original UltimateParent 1.x snippet by Susan Ottwell\n * <sottwell@sottwell.com>.  The topLevel parameter was introduced by staed and\n * adopted here.\n */\nif (!isset($modx)) return '';\n\n$top = isset($top) && $top ? $top : 0;\n$id = isset($id) && intval($id) ? intval($id) : $modx->resource->get('id');\n$topLevel = isset($topLevel) && $topLevel ? $topLevel : 0;\nif ($id && $id != $top) {\n    $pid = $id;\n    $pids = $modx->getParentIds($id);\n    if (!$topLevel || count($pids) >= $topLevel) {\n        while ($parentIds = $modx->getParentIds($id, 1)) {\n            $pid = array_pop($parentIds);\n            if ($pid == $top) {\n                break;\n            }\n            $id = $pid;\n            $parentIds = $modx->getParentIds($id);\n            if ($topLevel && count($parentIds) < $topLevel) {\n                break;\n            }\n        }\n    }\n}\nreturn $id;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * @name UltimateParent
 * @param &id The id of the document whose parent you want to find.
 * @param &top The top node for the search.
 * @param &topLevel The top level node for the search (root = level 1)
 *
 * @version 1.3
 * @author Susan Ottwell <sottwell@sottwell.com> March 2006
 * @author Al B <> May 18, 2007
 * @author S. Hamblett <shamblett@cwazy.co.uk>
 * @author Shaun McCormick <shaun@modx.com>
 * @author Jason Coward <modx@modx.com>
 *
 * @license Public Domain, use as you like.
 *
 * @example [[UltimateParent? &id=`45` &top=`6`]]
 * Will find the ultimate parent of document 45 if it is a child of document 6;
 * otherwise it will return 45.
 *
 * @example [[UltimateParent? &topLevel=`2`]]
 * Will find the ultimate parent of the current document at a depth of 2 levels
 * in the document hierarchy, with the root level being level 1.
 *
 * This snippet travels up the document tree from a specified document and
 * returns the "ultimate" parent.  Version 2.0 was rewritten to use the new
 * getParentIds function features available only in MODx 0.9.5 or later.
 *
 * Based on the original UltimateParent 1.x snippet by Susan Ottwell
 * <sottwell@sottwell.com>.  The topLevel parameter was introduced by staed and
 * adopted here.
 */
if (!isset($modx)) return '';

$top = isset($top) && $top ? $top : 0;
$id = isset($id) && intval($id) ? intval($id) : $modx->resource->get('id');
$topLevel = isset($topLevel) && $topLevel ? $topLevel : 0;
if ($id && $id != $top) {
    $pid = $id;
    $pids = $modx->getParentIds($id);
    if (!$topLevel || count($pids) >= $topLevel) {
        while ($parentIds = $modx->getParentIds($id, 1)) {
            $pid = array_pop($parentIds);
            if ($pid == $top) {
                break;
            }
            $id = $pid;
            $parentIds = $modx->getParentIds($id);
            if ($topLevel && count($parentIds) < $topLevel) {
                break;
            }
        }
    }
}
return $id;