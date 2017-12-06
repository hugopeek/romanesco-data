id: 73
name: setBoxType
description: 'Output the necessary class names for Overview patterns, based on their template. It was created because the chunks where getting a bit swamped by output modifiers trying to do the same thing.'
category: f_framework
properties: 'a:0:{}'

-----

/**
 * setBoxType
 *
 * This snippet is used by Romanesco to set the appropriate classes for all Overview containers and rows.
 * It was created because the chunks where getting a bit swamped by output modifiers trying to do the same thing.
 *
 * The snippet looks at the chunk name that is set for the Overview being used.
 * If the name matches the case, the placeholders are populated with the values in that case.
 *
 * $box_type - The classes for the container (the overviewOuter chunks, found in Organisms)
 * $row_type - The wrapper chunk for the actual template (useful for managing HTML5 elements or creating link items)
 * $column_type - The class for each individual template (always closely tied to the class of the box_type)
 *
 * If your project needs specific overrides, create a new snippet 'setBoxTypeTheme' and add your switch cases there.
 * These cases will be evaluated first. Make sure that the snippet returns an array if a match was found, or nothing.
 *
 * Example setBoxTypeTheme snippet:
 *
 * <php?
 * switch($input) {
 *     case stripos($input,'Card') !== false:
 *         $box_type = "cards";
 *         $row_type = "";
 *         $column_type = "[[+overview_color]] card";
 *         $grid_settings = "stackable doubling";
 *         break;
 *     default:
 *         return '';
 * }
 *
 * $output = array(
 *     'box_type' => $box_type,
 *     'row_type' => $row_type,
 *     'column_type' => $column_type,
 *     'grid_settings' => $grid_settings,
 * );
 *
 * return $output;
 *
 * ---
 *
 * Be advised: the cases are read from top to bottom, until it finds a match. This means that all following cases will
 * not be processed, so always place input strings that contain the partial value of another string lower on the list!
 *
 * @author Hugo Peek
 */

$input = $modx->getOption('input', $scriptProperties, '');
$prefix = $modx->getOption('prefix', $scriptProperties, '');

// Set prefix first to avoid duplicates
$modx->toPlaceholder('prefix', $prefix);

// Check if there's a theme override present and evaluate these cases first
$themeOverride = $modx->runSnippet('setBoxTypeTheme', (array(
    'input' => $input,
)));

if ($themeOverride) {
    foreach ($themeOverride as $key => $value) {
        $modx->toPlaceholder($key, $value, $prefix);
    }
    return; // No need to continue
}

switch($input) {
    case stripos($input,'LinkCard') !== false:
        $box_type = "link cards";
        $row_type = "link";
        $column_type = "card";
        $grid_settings = "stackable doubling";
        break;
    case stripos($input,'Card') !== false:
        $box_type = "cards";
        $row_type = "";
        $column_type = "card";
        $grid_settings = "stackable doubling";
        break;
    case stripos($input,'Segment') !== false:
        $box_type = "segments";
        $row_type = "segment";
        $column_type = "segment";
        $grid_settings = "";
        break;
    case stripos($input,'ProjectTile') !== false:
        $box_type = "grid";
        $row_type = "";
        $column_type = "ui dimmable column [[+alias]] background";
        $grid_settings = "column stackable doubling";
        break;
    case stripos($input,'PersonTile') !== false:
        $box_type = "grid";
        $row_type = "";
        $column_type = "ui column [[+alias]] background";
        $grid_settings = "column stackable doubling";
        break;
    case stripos($input,'Item') !== false:
        $box_type = "items";
        $row_type = "";
        $column_type = "item";
        $grid_settings = "";
        break;
    case stripos($input,'Compact') !== false:
        $box_type = "middle aligned list";
        $row_type = "";
        $column_type = "item";
        $grid_settings = "";
        break;
    case stripos($input,'IconTop') !== false:
        $box_type = "centered grid";
        $row_type = "";
        $column_type = "center aligned column";
        $grid_settings = "column stackable doubling";
        break;
    case stripos($input,'Logo') !== false:
        $box_type = "centered middle aligned grid";
        $row_type = "";
        $column_type = "center aligned column logo";
        $grid_settings = "column doubling";
        break;
    default:
        $box_type = "grid";
        $row_type = "";
        $column_type = "column";
        $grid_settings = "column stackable doubling";
        break;
}

$modx->toPlaceholder('box_type', $box_type, $prefix);
$modx->toPlaceholder('row_type', $row_type, $prefix);
$modx->toPlaceholder('column_type', $column_type, $prefix);
$modx->toPlaceholder('grid_settings', $grid_settings, $prefix);