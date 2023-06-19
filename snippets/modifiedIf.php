id: 106
name: modifiedIf
description: 'Customized If snippet with additional ''contains'', ''containsnot'' and ''isnumeric'' operators, output to placeholder and option to prevent chunks from parsing before If statement is evaluated.'
category: f_framework
snippet: "/**\n * If\n *\n * Simple if (conditional) snippet.\n *\n * Copyright 2009-2010 by Jason Coward <jason@modx.com> and Shaun McCormick\n * <shaun@modx.com>\n *\n * If is free software; you can redistribute it and/or modify it under the terms\n * of the GNU General Public License as published by the Free Software\n * Foundation; either version 2 of the License, or (at your option) any later\n * version.\n *\n * If is distributed in the hope that it will be useful, but WITHOUT ANY\n * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR\n * A PARTICULAR PURPOSE. See the GNU General Public License for more details.\n *\n * You should have received a copy of the GNU General Public License along with\n * If; if not, write to the Free Software Foundation, Inc., 59 Temple Place,\n * Suite 330, Boston, MA 02111-1307 USA\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\nif (!empty($debug)) {\n    print_r($scriptProperties);\n    if (!empty($die)) die();\n}\n$modx->parser->processElementTags('',$subject,true,true);\n\n$output = '';\n$operator = !empty($operator) ? $operator : '';\n$operand = !isset($operand) ? '' : $operand;\nif (isset($subject)) {\n    if (!empty($operator)) {\n        $operator = strtolower($operator);\n        switch ($operator) {\n            case '!=':\n            case 'ne':\n            case 'neq':\n            case 'not':\n            case 'isnot':\n            case 'isnt':\n            case 'unequal':\n            case 'notequal':\n                $output = (($subject != $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n            case '<':\n            case 'lt':\n            case 'less':\n            case 'lessthan':\n                $output = (($subject < $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n            case '>':\n            case 'gt':\n            case 'greater':\n            case 'greaterthan':\n                $output = (($subject > $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n            case '<=':\n            case 'lte':\n            case 'lessthanequals':\n            case 'lessthanorequalto':\n                $output = (($subject <= $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n            case '>=':\n            case 'gte':\n            case 'greaterthanequals':\n            case 'greaterthanequalto':\n                $output = (($subject >= $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n            case 'isempty':\n            case 'empty':\n                $output = empty($subject) ? $then : (isset($else) ? $else : '');\n                break;\n            case '!empty':\n            case 'notempty':\n            case 'isnotempty':\n                $output = !empty($subject) && $subject != '' ? $then : (isset($else) ? $else : '');\n                break;\n            case 'isnull':\n            case 'null':\n                $output = $subject == null || strtolower($subject) == 'null' ? $then : (isset($else) ? $else : '');\n                break;\n            case 'iselement':\n            case 'element':\n                if (empty($operand) && $operand == '') break;\n                $operand = str_replace('mod','',$operand);\n                $query = $modx->newQuery('mod'.ucfirst($operand), array(\n                    $operand == 'template' ? 'templatename' : 'name' => $subject\n                ));\n                $query->select('id');\n                $output = $modx->getValue($query->prepare()) ? $then : (isset($else) ? $else : '');\n                break;\n            case 'inarray':\n            case 'in_array':\n            case 'ia':\n                $operand = explode(',',$operand);\n                $output = in_array($subject,$operand) ? $then : (isset($else) ? $else : '');\n                break;\n            case 'containsnot':\n            case 'includesnot':\n                $output = strpos($subject,$operand) == false ? $then : (isset($else) ? $else : '');\n                break;\n            case 'contains':\n            case 'includes':\n                $output = strpos($subject,$operand) !== false ? $then : (isset($else) ? $else : '');\n                break;\n            case 'numeric':\n            case 'isnumeric':\n                $output = is_numeric($subject) !== false ? $then : (isset($else) ? $else : '');\n                break;\n            case '==':\n            case '=':\n            case 'eq':\n            case 'is':\n            case 'equal':\n            case 'equals':\n            case 'equalto':\n            default:\n                $output = (($subject == $operand) ? $then : (isset($else) ? $else : ''));\n                break;\n        }\n    }\n}\nif (!empty($debug)) { var_dump($output); }\nunset($subject);\n\n// Prevent chunks or snippets from parsing before the If statement is evaluated.\n// You can also use the mosquito technique, but that may cause issues in more complex scenarios.\n$outputAsTpl = $modx->getOption('outputAsTpl', $scriptProperties, false);\nif ($outputAsTpl) {\n    $output = $modx->getChunk($output, $scriptProperties);\n}\n\n// Output either to placeholder, or directly\n$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);\nif ($toPlaceholder) {\n    $modx->setPlaceholder($toPlaceholder, $output);\n    return '';\n}\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:34:"romanesco.modifiedif.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:35:"romanesco.modifiedif.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * If
 *
 * Simple if (conditional) snippet.
 *
 * Copyright 2009-2010 by Jason Coward <jason@modx.com> and Shaun McCormick
 * <shaun@modx.com>
 *
 * If is free software; you can redistribute it and/or modify it under the terms
 * of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * If is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * If; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

if (!empty($debug)) {
    print_r($scriptProperties);
    if (!empty($die)) die();
}
$modx->parser->processElementTags('',$subject,true,true);

$output = '';
$operator = !empty($operator) ? $operator : '';
$operand = !isset($operand) ? '' : $operand;
if (isset($subject)) {
    if (!empty($operator)) {
        $operator = strtolower($operator);
        switch ($operator) {
            case '!=':
            case 'ne':
            case 'neq':
            case 'not':
            case 'isnot':
            case 'isnt':
            case 'unequal':
            case 'notequal':
                $output = (($subject != $operand) ? $then : (isset($else) ? $else : ''));
                break;
            case '<':
            case 'lt':
            case 'less':
            case 'lessthan':
                $output = (($subject < $operand) ? $then : (isset($else) ? $else : ''));
                break;
            case '>':
            case 'gt':
            case 'greater':
            case 'greaterthan':
                $output = (($subject > $operand) ? $then : (isset($else) ? $else : ''));
                break;
            case '<=':
            case 'lte':
            case 'lessthanequals':
            case 'lessthanorequalto':
                $output = (($subject <= $operand) ? $then : (isset($else) ? $else : ''));
                break;
            case '>=':
            case 'gte':
            case 'greaterthanequals':
            case 'greaterthanequalto':
                $output = (($subject >= $operand) ? $then : (isset($else) ? $else : ''));
                break;
            case 'isempty':
            case 'empty':
                $output = empty($subject) ? $then : (isset($else) ? $else : '');
                break;
            case '!empty':
            case 'notempty':
            case 'isnotempty':
                $output = !empty($subject) && $subject != '' ? $then : (isset($else) ? $else : '');
                break;
            case 'isnull':
            case 'null':
                $output = $subject == null || strtolower($subject) == 'null' ? $then : (isset($else) ? $else : '');
                break;
            case 'iselement':
            case 'element':
                if (empty($operand) && $operand == '') break;
                $operand = str_replace('mod','',$operand);
                $query = $modx->newQuery('mod'.ucfirst($operand), array(
                    $operand == 'template' ? 'templatename' : 'name' => $subject
                ));
                $query->select('id');
                $output = $modx->getValue($query->prepare()) ? $then : (isset($else) ? $else : '');
                break;
            case 'inarray':
            case 'in_array':
            case 'ia':
                $operand = explode(',',$operand);
                $output = in_array($subject,$operand) ? $then : (isset($else) ? $else : '');
                break;
            case 'containsnot':
            case 'includesnot':
                $output = strpos($subject,$operand) == false ? $then : (isset($else) ? $else : '');
                break;
            case 'contains':
            case 'includes':
                $output = strpos($subject,$operand) !== false ? $then : (isset($else) ? $else : '');
                break;
            case 'numeric':
            case 'isnumeric':
                $output = is_numeric($subject) !== false ? $then : (isset($else) ? $else : '');
                break;
            case '==':
            case '=':
            case 'eq':
            case 'is':
            case 'equal':
            case 'equals':
            case 'equalto':
            default:
                $output = (($subject == $operand) ? $then : (isset($else) ? $else : ''));
                break;
        }
    }
}
if (!empty($debug)) { var_dump($output); }
unset($subject);

// Prevent chunks or snippets from parsing before the If statement is evaluated.
// You can also use the mosquito technique, but that may cause issues in more complex scenarios.
$outputAsTpl = $modx->getOption('outputAsTpl', $scriptProperties, false);
if ($outputAsTpl) {
    $output = $modx->getChunk($output, $scriptProperties);
}

// Output either to placeholder, or directly
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if ($toPlaceholder) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;