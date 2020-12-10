id: 101
name: setPatternType
category: f_hub
snippet: "switch($input) {\n    case stripos($input,'electrons') !== false:\n        $type = \"Electron\";\n        $type_s = \"E\";\n        break;\n    case stripos($input,'atoms') !== false:\n        $type = \"Atom\";\n        $type_s = \"A\";\n        break;\n    case stripos($input,'molecules') !== false:\n        $type = \"Molecule\";\n        $type_s = \"M\";\n        break;\n    case stripos($input,'organisms') !== false:\n        $type = \"Organism\";\n        $type_s = \"O\";\n        break;\n    case stripos($input,'templates') !== false:\n        $type = \"Template\";\n        $type_s = \"T\";\n        break;\n    case stripos($input,'pages') !== false:\n        $type = \"Page\";\n        $type_s = \"P\";\n        break;\n    case stripos($input,'formulas') !== false:\n        $type = \"Formula\";\n        $type_s = \"F\";\n        break;\n    case stripos($input,'computation') !== false:\n        $type = \"Computation\";\n        $type_s = \"C\";\n        break;\n    case stripos($input,'boson') !== false:\n        $type = \"Boson\";\n        $type_s = \"B\";\n        break;\n    default:\n        $type = \"undefined\";\n        $type_s = \"U\";\n        break;\n}\n\n//$modx->toPlaceholder('type', $type);\n//$modx->toPlaceholder('type_s', $type_s);\n\nreturn $type_s;"
properties: 'a:2:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:38:"romanesco.setpatterntype.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}s:14:"elementExample";a:7:{s:4:"name";s:14:"elementExample";s:4:"desc";s:39:"romanesco.setpatterntype.elementExample";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:0:"";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'
content: "switch($input) {\n    case stripos($input,'electrons') !== false:\n        $type = \"Electron\";\n        $type_s = \"E\";\n        break;\n    case stripos($input,'atoms') !== false:\n        $type = \"Atom\";\n        $type_s = \"A\";\n        break;\n    case stripos($input,'molecules') !== false:\n        $type = \"Molecule\";\n        $type_s = \"M\";\n        break;\n    case stripos($input,'organisms') !== false:\n        $type = \"Organism\";\n        $type_s = \"O\";\n        break;\n    case stripos($input,'templates') !== false:\n        $type = \"Template\";\n        $type_s = \"T\";\n        break;\n    case stripos($input,'pages') !== false:\n        $type = \"Page\";\n        $type_s = \"P\";\n        break;\n    case stripos($input,'formulas') !== false:\n        $type = \"Formula\";\n        $type_s = \"F\";\n        break;\n    case stripos($input,'computation') !== false:\n        $type = \"Computation\";\n        $type_s = \"C\";\n        break;\n    case stripos($input,'boson') !== false:\n        $type = \"Boson\";\n        $type_s = \"B\";\n        break;\n    default:\n        $type = \"undefined\";\n        $type_s = \"U\";\n        break;\n}\n\n//$modx->toPlaceholder('type', $type);\n//$modx->toPlaceholder('type_s', $type_s);\n\nreturn $type_s;"

-----


switch($input) {
    case stripos($input,'electrons') !== false:
        $type = "Electron";
        $type_s = "E";
        break;
    case stripos($input,'atoms') !== false:
        $type = "Atom";
        $type_s = "A";
        break;
    case stripos($input,'molecules') !== false:
        $type = "Molecule";
        $type_s = "M";
        break;
    case stripos($input,'organisms') !== false:
        $type = "Organism";
        $type_s = "O";
        break;
    case stripos($input,'templates') !== false:
        $type = "Template";
        $type_s = "T";
        break;
    case stripos($input,'pages') !== false:
        $type = "Page";
        $type_s = "P";
        break;
    case stripos($input,'formulas') !== false:
        $type = "Formula";
        $type_s = "F";
        break;
    case stripos($input,'computation') !== false:
        $type = "Computation";
        $type_s = "C";
        break;
    case stripos($input,'boson') !== false:
        $type = "Boson";
        $type_s = "B";
        break;
    default:
        $type = "undefined";
        $type_s = "U";
        break;
}

//$modx->toPlaceholder('type', $type);
//$modx->toPlaceholder('type_s', $type_s);

return $type_s;