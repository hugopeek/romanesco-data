id: 137
name: FibonacciSequence
description: 'Generate a sequence of Fibonacci numbers. In a Fibonacci sequence, every number after the first two is the sum of the two preceding ones.'
category: f_framework
snippet: "/**\n * FibonacciSequence\n *\n * Generate a sequence of Fibonacci numbers. In a Fibonacci sequence, every\n * number after the first two is the sum of the two preceding ones.\n *\n * You can indicate where to start and how many numbers to generate:\n *\n * [[FibonacciSequence?\n *    &limit=`9`\n *    &start=`65`\n * ]]\n *\n * If you want to retrieve a specific number from inside the sequence, you can\n * do so using the position parameter:\n *\n * [[FibonacciSequence?\n *    &start=`40`\n *    &position=`5`\n * ]]\n *\n * Without any parameters, the script will output a comma delimited sequence of\n * 10 numbers.\n *\n * [[FibonacciSequence]]\n * will output: 0,1,1,2,3,5,8,13,21,34\n *\n * @original http://www.hashbangcode.com/blog/get-fibonacci-numbers-using-php\n */\n\n$limit = $modx->getOption('limit', $scriptProperties, 10);\n$start = $modx->getOption('start', $scriptProperties, 0);\n$position = $modx->getOption('position', $scriptProperties, '');\n$delimiter = $modx->getOption('delimiter', $scriptProperties, ',');\n\nif ($start > 0) {\n    $second = $start * 2;\n} else {\n    $second = 1;\n}\n\n$sequence = array();\n\nif (!function_exists('fibonacciSequence')) {\n    function fibonacciSequence($limit, $start, $second, $position){\n        $sequence = array($start, $second);\n\n        if ($position > $limit) {\n            $limit = $position;\n        }\n\n        for ($i=2; $i<=$limit; ++$i) {\n            if ($i >= $limit) {\n                break;\n            } else {\n                $sequence[$i] = $sequence[$i-1] + $sequence[$i-2];\n            }\n        }\n\n        if ($position) {\n            return $sequence[$position - 1];\n        } else {\n            return $sequence;\n        }\n    }\n}\n\n$output = fibonacciSequence($limit, $start, $second, $position);\n\nif ($position) {\n    return $output;\n} else {\n    return implode($delimiter, $output);\n}"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:41:"romanesco.fibonaccisequence.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:42:"romanesco.fibonaccisequence.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "/**\n * FibonacciSequence\n *\n * Generate a sequence of Fibonacci numbers. In a Fibonacci sequence, every\n * number after the first two is the sum of the two preceding ones.\n *\n * You can indicate where to start and how many numbers to generate:\n *\n * [[FibonacciSequence?\n *    &limit=`9`\n *    &start=`65`\n * ]]\n *\n * If you want to retrieve a specific number from inside the sequence, you can\n * do so using the position parameter:\n *\n * [[FibonacciSequence?\n *    &start=`40`\n *    &position=`5`\n * ]]\n *\n * Without any parameters, the script will output a comma delimited sequence of\n * 10 numbers.\n *\n * [[FibonacciSequence]]\n * will output: 0,1,1,2,3,5,8,13,21,34\n *\n * @original http://www.hashbangcode.com/blog/get-fibonacci-numbers-using-php\n */\n\n$limit = $modx->getOption('limit', $scriptProperties, 10);\n$start = $modx->getOption('start', $scriptProperties, 0);\n$position = $modx->getOption('position', $scriptProperties, '');\n$delimiter = $modx->getOption('delimiter', $scriptProperties, ',');\n\nif ($start > 0) {\n    $second = $start * 2;\n} else {\n    $second = 1;\n}\n\n$sequence = array();\n\nif (!function_exists('fibonacciSequence')) {\n    function fibonacciSequence($limit, $start, $second, $position){\n        $sequence = array($start, $second);\n\n        if ($position > $limit) {\n            $limit = $position;\n        }\n\n        for ($i=2; $i<=$limit; ++$i) {\n            if ($i >= $limit) {\n                break;\n            } else {\n                $sequence[$i] = $sequence[$i-1] + $sequence[$i-2];\n            }\n        }\n\n        if ($position) {\n            return $sequence[$position - 1];\n        } else {\n            return $sequence;\n        }\n    }\n}\n\n$output = fibonacciSequence($limit, $start, $second, $position);\n\nif ($position) {\n    return $output;\n} else {\n    return implode($delimiter, $output);\n}"

-----


/**
 * FibonacciSequence
 *
 * Generate a sequence of Fibonacci numbers. In a Fibonacci sequence, every
 * number after the first two is the sum of the two preceding ones.
 *
 * You can indicate where to start and how many numbers to generate:
 *
 * [[FibonacciSequence?
 *    &limit=`9`
 *    &start=`65`
 * ]]
 *
 * If you want to retrieve a specific number from inside the sequence, you can
 * do so using the position parameter:
 *
 * [[FibonacciSequence?
 *    &start=`40`
 *    &position=`5`
 * ]]
 *
 * Without any parameters, the script will output a comma delimited sequence of
 * 10 numbers.
 *
 * [[FibonacciSequence]]
 * will output: 0,1,1,2,3,5,8,13,21,34
 *
 * @original http://www.hashbangcode.com/blog/get-fibonacci-numbers-using-php
 */

$limit = $modx->getOption('limit', $scriptProperties, 10);
$start = $modx->getOption('start', $scriptProperties, 0);
$position = $modx->getOption('position', $scriptProperties, '');
$delimiter = $modx->getOption('delimiter', $scriptProperties, ',');

if ($start > 0) {
    $second = $start * 2;
} else {
    $second = 1;
}

$sequence = array();

if (!function_exists('fibonacciSequence')) {
    function fibonacciSequence($limit, $start, $second, $position){
        $sequence = array($start, $second);

        if ($position > $limit) {
            $limit = $position;
        }

        for ($i=2; $i<=$limit; ++$i) {
            if ($i >= $limit) {
                break;
            } else {
                $sequence[$i] = $sequence[$i-1] + $sequence[$i-2];
            }
        }

        if ($position) {
            return $sequence[$position - 1];
        } else {
            return $sequence;
        }
    }
}

$output = fibonacciSequence($limit, $start, $second, $position);

if ($position) {
    return $output;
} else {
    return implode($delimiter, $output);
}