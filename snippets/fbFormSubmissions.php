id: 181
name: fbFormSubmissions
description: 'Generates a table with all submitted data for given form.'
category: f_formblocks
snippet: "/**\n * fbFormSubmissions Snippet\n *\n * Displays all submissions for a specific form in a Semantic UI table.\n *\n * Usage:\n * [[!fbFormSubmissions?\n *   &name=`contactForm`\n *   &limit=`20`\n *   &sortBy=`date`\n *   &sortDir=`DESC`\n * ]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n */\n\nuse Sterc\\FormIt\\Model\\FormItForm;\n\n// Load formit class\n$formit = $modx->getService('formit', 'FormIt', $modx->getOption('formit.core_path', null, $modx->getOption('core_path') . 'components/formit/') . 'model/formit/');\n\n// Load PDO Tools\n$pdo = $modx->getService('pdoTools');\n\n// Set snippet props\n$formName = $modx->getOption('name', $scriptProperties);\n$limit = $modx->getOption('limit', $scriptProperties, 10);\n$sortBy = $modx->getOption('sortby', $scriptProperties, 'date');\n$sortDir = $modx->getOption('sortdir', $scriptProperties, 'DESC');\n\n// Get form objects\n$q = $modx->newQuery(FormItForm::class);\n$q->where([\n    'form' => $formName,\n]);\n$forms = $modx->getCollection(FormItForm::class, $q);\n$total = $modx->getCount(FormItForm::class, $q);\n\n\n// Extract all unique field names from submissions\n$fields = [];\nforeach ($forms as $submission) {\n\n    // Check if data is encrypted\n    if ($submission->get('encrypted')) {\n        $valuesJSON = $formit->decrypt($submission->get('values'));\n    } else {\n        $valuesJSON = $submission->get('values');\n    }\n\n    $values = json_decode($valuesJSON, true);\n\n    if (is_array($values)) {\n        foreach (array_keys($values) as $field) {\n            if (!in_array($field, $fields)) {\n                $fields[] = $field;\n            }\n        }\n    }\n}\n\nif (empty($fields)) {\n    return '<div class=\"ui error message\">No fields found in submission data.</div>';\n}\n\n// Build the table HTML\n$output = '\n<table class=\"ui small compact celled table\">\n    <thead>\n        <tr>';\n\n$output .= '<th>Submitted</th>';\nforeach ($fields as $field) {\n    $output .= '<th>' . htmlspecialchars($field) . '</th>';\n}\n\n$output .= '\n        </tr>\n    </thead>\n    <tbody>';\n\n// Render table rows\nforeach ($forms as $submission) {\n    $output .= '<tr>';\n\n    // Submission date\n    $output .= '<td>' . date('Y-m-d H:i', $submission->get('date')) . '</td>';\n\n    // Check if data is encrypted\n    if ($submission->get('encrypted')) {\n        $valuesJSON = $formit->decrypt($submission->get('values'));\n    } else {\n        $valuesJSON = $submission->get('values');\n    }\n    $values = json_decode($valuesJSON, true);\n    if (!is_array($values)) {\n        $values = [];\n    }\n\n    // Field values\n    foreach ($fields as $field) {\n        $value = (string)$values[$field] ?? '-';\n        // Truncate long values\n        if (strlen($value) > 50) {\n            $value = substr($value, 0, 47) . '...';\n        }\n        $output .= '<td>' . htmlspecialchars($value) . '</td>';\n    }\n\n    $output .= '</tr>';\n}\n\n$output .= '\n    </tbody>\n</table>';\n\nreturn $output;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:12:"experimental";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:0:"";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * fbFormSubmissions Snippet
 *
 * Displays all submissions for a specific form in a Semantic UI table.
 *
 * Usage:
 * [[!fbFormSubmissions?
 *   &name=`contactForm`
 *   &limit=`20`
 *   &sortBy=`date`
 *   &sortDir=`DESC`
 * ]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use Sterc\FormIt\Model\FormItForm;

// Load formit class
$formit = $modx->getService('formit', 'FormIt', $modx->getOption('formit.core_path', null, $modx->getOption('core_path') . 'components/formit/') . 'model/formit/');

// Load PDO Tools
$pdo = $modx->getService('pdoTools');

// Set snippet props
$formName = $modx->getOption('name', $scriptProperties);
$limit = $modx->getOption('limit', $scriptProperties, 10);
$sortBy = $modx->getOption('sortby', $scriptProperties, 'date');
$sortDir = $modx->getOption('sortdir', $scriptProperties, 'DESC');

// Get form objects
$q = $modx->newQuery(FormItForm::class);
$q->where([
    'form' => $formName,
]);
$forms = $modx->getCollection(FormItForm::class, $q);
$total = $modx->getCount(FormItForm::class, $q);


// Extract all unique field names from submissions
$fields = [];
foreach ($forms as $submission) {

    // Check if data is encrypted
    if ($submission->get('encrypted')) {
        $valuesJSON = $formit->decrypt($submission->get('values'));
    } else {
        $valuesJSON = $submission->get('values');
    }

    $values = json_decode($valuesJSON, true);

    if (is_array($values)) {
        foreach (array_keys($values) as $field) {
            if (!in_array($field, $fields)) {
                $fields[] = $field;
            }
        }
    }
}

if (empty($fields)) {
    return '<div class="ui error message">No fields found in submission data.</div>';
}

// Build the table HTML
$output = '
<table class="ui small compact celled table">
    <thead>
        <tr>';

$output .= '<th>Submitted</th>';
foreach ($fields as $field) {
    $output .= '<th>' . htmlspecialchars($field) . '</th>';
}

$output .= '
        </tr>
    </thead>
    <tbody>';

// Render table rows
foreach ($forms as $submission) {
    $output .= '<tr>';

    // Submission date
    $output .= '<td>' . date('Y-m-d H:i', $submission->get('date')) . '</td>';

    // Check if data is encrypted
    if ($submission->get('encrypted')) {
        $valuesJSON = $formit->decrypt($submission->get('values'));
    } else {
        $valuesJSON = $submission->get('values');
    }
    $values = json_decode($valuesJSON, true);
    if (!is_array($values)) {
        $values = [];
    }

    // Field values
    foreach ($fields as $field) {
        $value = (string)$values[$field] ?? '-';
        // Truncate long values
        if (strlen($value) > 50) {
            $value = substr($value, 0, 47) . '...';
        }
        $output .= '<td>' . htmlspecialchars($value) . '</td>';
    }

    $output .= '</tr>';
}

$output .= '
    </tbody>
</table>';

return $output;