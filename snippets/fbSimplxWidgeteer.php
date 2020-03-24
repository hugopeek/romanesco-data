id: 59
name: fbSimplxWidgeteer
description: 'DEPRECATED. Form report is now generated with fbFormReport.'
category: f_formblocks
snippet: "/**\n * fbSimplxWidgeteer\n *\n * A slightly modified version of the SIMPLX Widgeteer snippet, by Lars Wallin.\n * Used by FormBlocks for handling the templating of the emailTpl.\n *\n * This script now uses getChunk instead of parseChunk, which makes it possible to use output modifiers in tpl chunks.\n *\n * @author Lars Wallin\n */\n\n//require_once($modx->config['base_path'].\"assets/snippets/simplx/simplx_widgeteer.php\");\n\nif(!class_exists('simplx_widgeteer')){\n\n    class simplx_widgeteer{\n        public $debugmode = false;\n        public $dataSet;\n        public $dataSetUrl;\n        public $dataSetArray;\n        public $dataSetRoot;\n        public $useChunkMatching = true;\n        public $chunkMatchingSelector = '';\n        public $staticChunkName = '';\n        public $chunkPrefix = '';\n        public $chunkMatchRoot = false;\n        public $preprocessor = '';\n        private $iterator = 0;\n        private $traversalStack = array();\n        private $traversalObjectStack = array();\n        private $traversalContext = '';\n\n        // Only one instance per request.\n        private static $chunkCache = array();\n\n        public function preprocess($dSet,$preprocessor=null){\n            global $modx;\n\n            if ($preprocessor) {\n\n                $dSet = $modx->runSnippet($preprocessor,array('dataSet'=>$dSet));\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: preprocess(), Done processing. Dataset now contains \"'.$dSet.'\".');\n\n            }\n            return $dSet;\n        }\n\n        public function loadDataSet($dSet){\n            global $modx;\n            $dset;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSet(), Dataset contains \"'.$dSet.'\".');\n\n            if($this->preprocessor){\n                $dSet = $this->preprocess($dSet,$this->preprocessor);\n                $this->dataSet = $dSet;\n            }\n\n\n            $this->dataSetArray = $this->decode($dSet);\n\n            if(is_array($this->dataSetArray)){\n                return true;\n            } else {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSet(): Exception, Unable to decode the dataset.');\n                return false;\n            }\n\n            //$this->cacheDataSet($dSet);\n\n        }\n\n        public function loadCachedDataSet($dataSetKey){\n            global $modx;\n            $dSet = $modx->cacheManager->get($dataSetKey);\n            \n            if ($dSet) {\n                return $dSet;\n            } else {\n                return '';\n            }\n\n        }\n\n        public function cacheDataSet($dSet){\n            global $modx;\n            // For now we just cache remote dataSets...\n            if ($this->dataSetUrl != '') {\n                $dataSetLocator = urlencode($this->dataSetUrl);\n                $modx->cacheManager->set(('dataSet.'.$dataSetLocator),$this->dataSetArray);\n            }\n        }\n\n        public function loadDataSource($dSourceURL){\n            global $modx;\n\n            $result = false;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Fetching from URL \"'.$dSourceURL.'\".');\n\n            if ($dSourceURL != \"\") {\n\n                $ch = curl_init();\n                curl_setopt($ch, CURLOPT_URL, $dSourceURL);\n                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);\n\n                $output = curl_exec($ch);\n                curl_close($ch);\n\n                $this->dataSetUrl = $dSourceURL;\n\n                if ($output != \"\") {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Got the following output \"'.$output.'\".<br/><br/>');\n\n                    $result = $this->loadDataSet($output);\n\n                    if($result){\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), loadDataSet() returned \"true\".');\n\n                    } else {\n                        $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, loadDataSet() returned \"false\". Aborting.');\n\n                    }\n\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, no valid output from \"'.$dSourceURL.'\".');\n                }\n            }\n        }\n\n        public function setDataRoot($elName){\n\n            $this->dataSetRoot = $elName;\n            $this->dataSetArray = $this->dataSetArray[$this->dataSetRoot];\n\n        }\n\n        public function decode($json){\n            return json_decode($json,true);\n\n        }\n\n        public function encode($object){\n            return json_encode($object);\n\n        }\n\n\n        function parse(&$obj=null){\n            global $modx;\n            $result = '';\n\n            if(!isset($obj)){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() Got no initial argument.');\n\n                if($this->dataSetArray){\n                    $obj = &$this->dataSetArray;\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, Missing valid dataset. Aborting.');\n                    return false;\n                }\n\n            } else {\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() starting with argument \"'.json_encode($obj).'\".');\n            }\n\n            if($this->dataSetRoot != ''){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Checking existance of dataSetRoot \"'.$this->dataSetRoot.'\".');\n\n                /*\n                if(array_key_exists($this->dataSetRoot,$this->dataSetArray)){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), dataSetRoot \"'.$this->dataSetRoot.'\" was found in the dataset.');\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, dataSetRoot \"'.$this->dataSetRoot.'\" was NOT found in the dataset. Aborting.');\n                    return false;\n                }\n                */\n\n                $context = $this->dataSetRoot;\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Starting recursive parse.');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), -------------------------------------');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');\n\n            foreach ($obj as  $key => &$val) {\n\n                switch($this->typeCheck($val)){\n                    case \"list\":\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($val,$key);\n                        break;\n                    case \"object\":\n                        if(!$this->dataSetRoot){\n                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is an object ({}). Calling parseObject().');\n                            $result = $this->parseObject($val,$key);\n                        }\n                        break;\n                    case \"simple\":\n                        if(!$this->dataSetRoot){\n                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is a simple type (string or number). Calling parseSimpleType().');\n                            $result = $this->parseSimpleType($val,$key);\n                        }\n                        break;\n                    default :\n                        break;\n                }\n                $obj[$key] = $result;\n\n            }\n\n            $result = implode(' ',$obj);\n\n\n            // If this is the last render call we have the option to wrap the result in the\n            // rootChunk.\n\n            if($this->chunkMatchRoot == 'true'){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Chunk matching root element.');\n\n                $rootChunk = $modx->getChunk(($this->chunkPrefix.$this->dataSetRoot));\n\n                if($rootChunk != ''){\n                    $result = str_replace('[[+content]]',$result,$rootChunk);\n                } else {\n\n                }\n\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Done parsing. Returning \"'.$result.'\".');\n\n            return $result;\n        }\n\n        function parseObject(&$obj,$context){\n            global $modx;\n\n            $result = '';\n\n            /*\n            FIX\n            Add a reference to the actual, untemplated, object.\n            Now the stack contains templated data\n            */\n\n\n            // Add the object to the stack\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() adding object \"'.$context.'\" to stack \"'.json_encode($obj).'\".');\n            $this->traversalObjectStack[$context] = $obj;\n\n            foreach ($obj as  $key => &$val) {\n\n                switch($this->typeCheck($val)){\n                    case 'list':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($val,$key);\n                        break;\n                    case 'object':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is an object ({}). Calling parseObject().');\n                        $result = $this->parseObject($val,$key);\n                        break;\n                    case 'simple':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is a simple type (string or number). Calling parseSimpleType().');\n                        $result = $this->parseSimpleType($val,$key);\n\n                        break;\n                    default:\n                        break;\n                }\n                $obj[$key] = $result;\n            }\n\n            $result = $this->template($obj,$context);\n\n            //if($debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() popping object \"'.$context.'\" from stack.');\n\n            // Pop it from the stack\n            //array_pop($this->traversalObjectStack);\n\n            return $result;\n        }\n\n        function parseList(&$list,&$context){\n            global $modx;\n            $result = '';\n            $iterator = 0;\n\n            foreach ($list as &$index) {\n\n                switch($this->typeCheck($index)){\n                    case 'list':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($index,$iterator);\n                        break;\n                    case 'object':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is an object ({}). Calling parseObject().');\n                        $result = $this->parseObject($index,$context);\n                        break;\n                    case 'simple':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is a simple type (string or number). Calling parseSimpleType().');\n                        $result = $this->parseSimpleType($index,$context);\n                        break;\n                    default:\n                        break;\n                }\n                $list[$iterator] = $result;\n                $iterator++;\n            }\n            $result = implode(' ',$list);\n            return $result;\n        }\n\n        function parseSimpleType(&$value,&$context){\n            global $modx;\n            $prefContext = ($this->chunkPrefix.$context);\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseSimpleType(), This item (\"'.$context.'\") contains value \"'.$value.'\".');\n\n            if(!array_key_exists($prefContext,self::$chunkCache)){\n                self::$chunkCache[$prefContext] = $modx->getChunk($prefContext);\n                $chunk = self::$chunkCache[$prefContext];\n            } else {\n                $chunk = self::$chunkCache[$prefContext];\n            }\n\n            if($chunk){\n                $result = str_replace('[[+value]]',$value,$chunk);\n                return $result;\n            } else {\n                return $value;\n            }\n\n        }\n\n        function parseFieldPointer(&$type,&$context){\n            global $modx;\n            $chunk = $modx->getChunk(($this->chunkPrefix.$context));\n            if($chunk != ''){\n                $result = str_replace('[[+value]]',$type,$chunk);\n                return $result;\n            } else {\n                return $type;\n            }\n\n        }\n\n        function template(&$collection,$tmplName) {\n            global $modx;\n            $res = null;\n            $tempVar;\n\n            if ($this->useChunkMatching) {\n\n                // Get the current chunkMatchingSelector key from the $collection list.\n                // This is used later to choose which Chunk to use as template.\n                if(array_key_exists($this->chunkMatchingSelector,$collection)){\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector \"'.$this->chunkMatchingSelector.'\" was found in collection \"'.$tmplName.'\".');\n                    $chunkName = $collection[$this->chunkMatchingSelector];\n                } else {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector \"'.$this->chunkMatchingSelector.'\" was NOT found in collection \"'.$tmplName.'\".');\n                    $chunkName = '';\n                }\n\n                if ($chunkName === '') {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk name is \"\".');\n                    /*\n                      If nothing was returned from the assignment above we have found no selector. We have to\n                      use another way to match the current collection in the $collection list.\n                      The way that json is structured it is very likely that the parent key is the name of the\n                      object type in the collection.\n                      Example\n\n                        {\n                          \"contact\":[\n                            {\n                              \"name\":\"Mini Me\",\n                              \"shoesize\":{\"eu\":\"23\"}\n                            },\n                            {\n                              \"name\":\"Big Dude\",\n                              \"shoesize\":{\"eu\":\"49\"}\n                            }\n\n                          ]\n                        }\n\n                      In the example above its implied that each item in the \"contact\" collection is of typ... contact :)\n                      Similarly the \"shoesize\" property is a complex value that would be best matched to the key \"shoesize\".\n\n                    */\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), It is very likely that the parent key is the name of the object type. Setting $chunkName to \"'.$tmplName.'\"');\n                    $chunkName = $tmplName;\n                } else {\n                }\n\n                if($chunkName != ''){\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parsing Chunk \"'.$chunkName.'\".');\n                    //$tempVar = $modx->parseChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n                    $tempVar = $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n\n                    $res .= $tempVar;\n                } else {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), $chunkName is still empty.');\n                }\n            } else {\n                //$tempVar .= $modx->parseChunk($this->staticChunkName, $collection, '[[+', ']]');\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk matching is turned off. Using static Chunk \"'.$this->staticChunkName.'\".');\n\n                $tempVar .= $modx->getChunk($this->staticChunkName, $collection, '[[+', ']]');\n                $res .= $tempVar;\n\n            }\n            if(!$res){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned an invalid result. Setting the template result to \"\".');\n                $res = '';\n            } else {\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned a valid result. Calling templateNSPlaceholders() to find and template namespaced tags.');\n                $res = $this->templateNSPlaceholders($res);\n            }\n            return $res;\n        }\n\n        function parseChunk($chunkName, $chunkArr, $prefix='[[+', $suffix=']]'){\n            global $modx;\n            $chunk='';\n\n            if(!array_key_exists($chunkName,self::$chunkCache)){\n                self::$chunkCache[$chunkName] = $modx->getChunk($chunkName);\n                $chunk = self::$chunkCache[$chunkName];\n            } else {\n                $chunk = self::$chunkCache[$chunkName];\n            }\n\n            if (!empty($chunk) || $chunk === '0') {\n                if(is_array($chunkArr)) {\n                    reset($chunkArr);\n                    while (list($key, $value)= each($chunkArr)) {\n                        $chunk= str_replace($prefix.$key.$suffix, $value, $chunk);\n                    }\n                }\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseChunk(), Done parsing, returning \"'.$chunk.'\".');\n\n            return $chunk;\n        }\n\n\n        // Templates namespaced placeholders\n        function templateNSPlaceholders($chunk){\n            global $modx;\n            $collection = null;\n            $currentType = '';\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: Starting templateNSPlaceholders(). Checking \"'.$chunk.'\"');\n\n            // Find any unparsed placeholders\n\n            $nsSeparator = '.';\n            $forwardSeparator = '-';\n            $backSeparator = '+';\n            $patternSeparator = '?';\n            $placeholders;\n\n            $pattern = \"/\\[\\[\\+[^\\]]*\\]\\]/\";\n\n            preg_match_all($pattern, $chunk, $placeholders);\n\n\n            if(!is_array($placeholders) || count($placeholders[0])<=0){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), there where no placeholders in this chunk. Returning what we got.');\n\n                return $chunk;\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), we got placeholders \"'.json_encode($placeholders).'\"');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            // Set local references to the traversal stack (history stack). Local refs should speed up access.\n            $stackRootPointer = &$this->traversalObjectStack;\n            $pointer = &$stackRootPointer;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The traversal stack has the following items: \"'.json_encode($pointer).'\"');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Going into the placeholders foreach.');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            // The actual result of the preg_match is in the first index of the $placeholders array.\n            $placeholders = $placeholders[0];\n\n            foreach($placeholders as $placeholder){\n\n                $value = '';\n\n                // Make a local copy for performance impr.\n                $current = $placeholder;\n\n                // Only parse the placeholder if it has a separator\n                if(!strpos($current,$nsSeparator)===false){\n\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() the current placeholder is: \"'.$current.'\"');\n\n                    // Remove the tags\n                    $path = str_replace(array('[[+',']]'), '', $current);\n\n                    // Explode the current placeholder using the namespace separator\n                    $parts = explode($nsSeparator,$path);\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() After the cleanup the placeholder looks like this: \"'.json_encode($parts).'\" .');\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Looping through the parts of the current placeholder.');\n\n\n                    // Iteration counter\n                    $i = 1;\n\n                    // Lets loop through all parts in the namespace array.\n                    foreach($parts as $part){\n\n                        switch($part){\n                            case '<':\n                                break;\n                            case '>':\n                                break;\n                            case '?':\n                                break;\n\n                            default:\n\n                                /*\n                                  Ok, lets look in the current array position if we have a match for the namespace part.\n\n                                  Right now we only support cronological paths so if the part is not found, its no\n                                  use iterating on, so we simply return the chunk we got.\n\n                                */\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Rotation nr \"'.$i.'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current part is \"'.$part.'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Lets start looking in the stack at our present position.');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current pointer in the stack contains: \"'.json_encode($pointer).'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n                                // Get a pointer to the current value\n                                $value = &$pointer[$part];\n\n                                if(!isset($value)){\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position in the stack had no \"'.$part.'\" reference. This means that the path is invalid and we can set value to \"\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                    unset($value);\n                                    $value = '';\n                                }\n\n\n                                /*\n                                    Got something! Lets see if its a string/num/bool, or an array...\n                                */\n\n\n                                // If we still have an array, we havent reached the target\n\n                                $currentType = $this->typeCheck($value);\n\n                                if($currentType!=='simple'){\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position \"'.json_encode($value).'\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position \"'.json_encode($value).'\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n                                    // Increment the i counter which is just used for debug purposes.\n                                    $i++;\n\n                                    // unset the pointer so that we dont screw with the history stack\n                                    unset($pointer);\n\n                                    // If we got an array, we need to step into it to get something usefull.\n                                    if($currentType==='list'){\n                                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() * Current stack item is an array.');\n                                        $pointer = &$value[0];\n\n                                    } else {\n                                        // Redirect the pointer ref to the new, current position in the traversal history stack\n                                        $pointer = &$value;\n\n                                    }\n\n                                    // unset the value ref so that we dont screw with the history stack\n                                    unset($value);\n\n                                } else {\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position returned the string \"'.$value.'\". Lets keep it for templating.');\n                                }\n                        }\n                    }\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing this placeholder. Lets replace the it with this value \"'.$value.'\"');\n\n                    // Replace the original placeholder with the retrieved value.\n                    $chunk = str_replace(('[[+'.$path.']]'),$value,$chunk);\n\n                    // unset the pointer so that we dont screw with the history stack\n                    unset($pointer);\n                    // Backup to the beginning of the stack so that the next placeholder can reference the complete history.\n                    $pointer = $stackRootPointer;\n\n                }\n\n\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing all placeholders.');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() returning \"'.$chunk.'\"');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            return $chunk;\n        }\n\n\n        // Facilitates Chunk caching\n\n        function getChunkTemplate($templateName){\n            if(array_key_exists($templateName,self::$chunkCache)){\n                return self::$chunkCache[$templateName];\n            } else {\n                self::$chunkCache[$templateName] &= $modx->getChunk($templateName);\n                return self::$chunkCache[$templateName];\n\n            }\n        }\n\n        // Facilitates Chunk processing using cached objects\n        function parseChunkTemplate($templateName,&$collection){\n            if(array_key_exists($templateName,self::$chunkCache)){\n                return $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n            } else {\n                return $modx->getChunk($templateName);\n            }\n        }\n\n        // Utility function to check type of Array\n        function is_assoc (&$arr) {\n            try{\n                return (is_array($arr) && (!count($arr) || count(array_filter(array_keys($arr),'is_string')) == count($arr)));\n            }catch(Exception $e){\n                return false;\n            }\n        }\n\n        function typeCheck(&$var){\n            $val = '';\n            if(is_array($var)){\n                if ($this->is_assoc($var)) {\n                    $val = 'object';\n\n                } else {\n                    $val = 'list';\n                }\n            } else {\n                $val = 'simple';\n            }\n            return $val;\n        }\n\n    }\n\n\n} else {\n\n}\n\n/*\n\n  ----------------------------------------------------------------------------------------------\n\n  Below is the actual snippet code which sets defaults, validates the input, instantiates the\n  Widgeteer object and runs the logic...\n\n*/\n\n$dataSourceUrl = isset($dataSourceUrl) ? $dataSourceUrl : 'null';\n$dataSourceUrl = isset($dataSetUrl) ? $dataSetUrl : $dataSourceUrl; //New interface parameter to mend naming consistency issue.\n$staticChunkName = isset($staticChunkName) ? $staticChunkName : 'null';\n$dataSet = isset($dataSet) ? ($dataSet) : 'null';\n$useChunkMatching = isset($useChunkMatching) ? $useChunkMatching : true;\n$chunkMatchingSelector = isset($chunkMatchingSelector) ? $chunkMatchingSelector : '';\n$dataSetRoot = isset($dataSetRoot) ? $dataSetRoot : 'null';\n$chunkMatchRoot = isset($chunkMatchRoot) ? $chunkMatchRoot : false;\n$chunkPrefix = isset($chunkPrefix) ? $chunkPrefix : '';\n$dataSet = str_replace(array('|xq|','|xe|','|xa|'),array('?','=','&') , $dataSet);\n$preprocessor = isset($preprocessor) ? $preprocessor : '';\n\n\n$debugmode = isset($debugmode) ? $debugmode : false;\n\nif($debugmode){\n    $modx->setLogLevel(modX::LOG_LEVEL_DEBUG);\n}\n\nif($dataSourceUrl == 'null' && $dataSet == 'null'){\n    print '{\"result\":[{\"objecttypename\":\"exception\",\"errorcode\"=\"0\",\"message\":\"The dataSet parameter is empty.\"}]}';\n} else {\n\n    $w = new simplx_widgeteer();\n    $w->debugmode = $debugmode;\n\n    /*\n      PHP bug perhaps? $useChunkMatching evaluates as true even if its false!?\n      I have to \"switch poles\" in order to get the right effect...\n    */\n    if($useChunkMatching && $staticChunkName != 'null'){\n\n        $w->useChunkMatching = false;\n        $w->staticChunkName = $staticChunkName;\n\n    } else {\n        $w->useChunkMatching = true;\n        $w->chunkMatchingSelector = $chunkMatchingSelector;\n    }\n\n    $w->chunkMatchRoot = $chunkMatchRoot;\n    $w->chunkPrefix = $chunkPrefix;\n    $w->preprocessor = $preprocessor;\n\n    if($dataSourceUrl != 'null'){\n        $dataSourceUrl = urldecode($dataSourceUrl);\n        $w->loadDataSource($dataSourceUrl);\n\n    } else {\n\n        $w->loadDataSet(utf8_encode($dataSet));\n    }\n\n    if($dataSetRoot != 'null'){\n        $w->setDataRoot($dataSetRoot);\n    }\n\n    return $w->parse();\n}"
properties: 'a:0:{}'
content: "/**\n * fbSimplxWidgeteer\n *\n * A slightly modified version of the SIMPLX Widgeteer snippet, by Lars Wallin.\n * Used by FormBlocks for handling the templating of the emailTpl.\n *\n * This script now uses getChunk instead of parseChunk, which makes it possible to use output modifiers in tpl chunks.\n *\n * @author Lars Wallin\n */\n\n//require_once($modx->config['base_path'].\"assets/snippets/simplx/simplx_widgeteer.php\");\n\nif(!class_exists('simplx_widgeteer')){\n\n    class simplx_widgeteer{\n        public $debugmode = false;\n        public $dataSet;\n        public $dataSetUrl;\n        public $dataSetArray;\n        public $dataSetRoot;\n        public $useChunkMatching = true;\n        public $chunkMatchingSelector = '';\n        public $staticChunkName = '';\n        public $chunkPrefix = '';\n        public $chunkMatchRoot = false;\n        public $preprocessor = '';\n        private $iterator = 0;\n        private $traversalStack = array();\n        private $traversalObjectStack = array();\n        private $traversalContext = '';\n\n        // Only one instance per request.\n        private static $chunkCache = array();\n\n        public function preprocess($dSet,$preprocessor=null){\n            global $modx;\n\n            if ($preprocessor) {\n\n                $dSet = $modx->runSnippet($preprocessor,array('dataSet'=>$dSet));\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: preprocess(), Done processing. Dataset now contains \"'.$dSet.'\".');\n\n            }\n            return $dSet;\n        }\n\n        public function loadDataSet($dSet){\n            global $modx;\n            $dset;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSet(), Dataset contains \"'.$dSet.'\".');\n\n            if($this->preprocessor){\n                $dSet = $this->preprocess($dSet,$this->preprocessor);\n                $this->dataSet = $dSet;\n            }\n\n\n            $this->dataSetArray = $this->decode($dSet);\n\n            if(is_array($this->dataSetArray)){\n                return true;\n            } else {\n                $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSet(): Exception, Unable to decode the dataset.');\n                return false;\n            }\n\n            //$this->cacheDataSet($dSet);\n\n        }\n\n        public function loadCachedDataSet($dataSetKey){\n            global $modx;\n            $dSet = $modx->cacheManager->get($dataSetKey);\n            \n            if ($dSet) {\n                return $dSet;\n            } else {\n                return '';\n            }\n\n        }\n\n        public function cacheDataSet($dSet){\n            global $modx;\n            // For now we just cache remote dataSets...\n            if ($this->dataSetUrl != '') {\n                $dataSetLocator = urlencode($this->dataSetUrl);\n                $modx->cacheManager->set(('dataSet.'.$dataSetLocator),$this->dataSetArray);\n            }\n        }\n\n        public function loadDataSource($dSourceURL){\n            global $modx;\n\n            $result = false;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Fetching from URL \"'.$dSourceURL.'\".');\n\n            if ($dSourceURL != \"\") {\n\n                $ch = curl_init();\n                curl_setopt($ch, CURLOPT_URL, $dSourceURL);\n                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);\n\n                $output = curl_exec($ch);\n                curl_close($ch);\n\n                $this->dataSetUrl = $dSourceURL;\n\n                if ($output != \"\") {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Got the following output \"'.$output.'\".<br/><br/>');\n\n                    $result = $this->loadDataSet($output);\n\n                    if($result){\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), loadDataSet() returned \"true\".');\n\n                    } else {\n                        $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, loadDataSet() returned \"false\". Aborting.');\n\n                    }\n\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, no valid output from \"'.$dSourceURL.'\".');\n                }\n            }\n        }\n\n        public function setDataRoot($elName){\n\n            $this->dataSetRoot = $elName;\n            $this->dataSetArray = $this->dataSetArray[$this->dataSetRoot];\n\n        }\n\n        public function decode($json){\n            return json_decode($json,true);\n\n        }\n\n        public function encode($object){\n            return json_encode($object);\n\n        }\n\n\n        function parse(&$obj=null){\n            global $modx;\n            $result = '';\n\n            if(!isset($obj)){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() Got no initial argument.');\n\n                if($this->dataSetArray){\n                    $obj = &$this->dataSetArray;\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, Missing valid dataset. Aborting.');\n                    return false;\n                }\n\n            } else {\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() starting with argument \"'.json_encode($obj).'\".');\n            }\n\n            if($this->dataSetRoot != ''){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Checking existance of dataSetRoot \"'.$this->dataSetRoot.'\".');\n\n                /*\n                if(array_key_exists($this->dataSetRoot,$this->dataSetArray)){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), dataSetRoot \"'.$this->dataSetRoot.'\" was found in the dataset.');\n                } else {\n                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, dataSetRoot \"'.$this->dataSetRoot.'\" was NOT found in the dataset. Aborting.');\n                    return false;\n                }\n                */\n\n                $context = $this->dataSetRoot;\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Starting recursive parse.');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), -------------------------------------');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');\n\n            foreach ($obj as  $key => &$val) {\n\n                switch($this->typeCheck($val)){\n                    case \"list\":\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($val,$key);\n                        break;\n                    case \"object\":\n                        if(!$this->dataSetRoot){\n                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is an object ({}). Calling parseObject().');\n                            $result = $this->parseObject($val,$key);\n                        }\n                        break;\n                    case \"simple\":\n                        if(!$this->dataSetRoot){\n                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item (\"'.$key.'\") is a simple type (string or number). Calling parseSimpleType().');\n                            $result = $this->parseSimpleType($val,$key);\n                        }\n                        break;\n                    default :\n                        break;\n                }\n                $obj[$key] = $result;\n\n            }\n\n            $result = implode(' ',$obj);\n\n\n            // If this is the last render call we have the option to wrap the result in the\n            // rootChunk.\n\n            if($this->chunkMatchRoot == 'true'){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Chunk matching root element.');\n\n                $rootChunk = $modx->getChunk(($this->chunkPrefix.$this->dataSetRoot));\n\n                if($rootChunk != ''){\n                    $result = str_replace('[[+content]]',$result,$rootChunk);\n                } else {\n\n                }\n\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Done parsing. Returning \"'.$result.'\".');\n\n            return $result;\n        }\n\n        function parseObject(&$obj,$context){\n            global $modx;\n\n            $result = '';\n\n            /*\n            FIX\n            Add a reference to the actual, untemplated, object.\n            Now the stack contains templated data\n            */\n\n\n            // Add the object to the stack\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() adding object \"'.$context.'\" to stack \"'.json_encode($obj).'\".');\n            $this->traversalObjectStack[$context] = $obj;\n\n            foreach ($obj as  $key => &$val) {\n\n                switch($this->typeCheck($val)){\n                    case 'list':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($val,$key);\n                        break;\n                    case 'object':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is an object ({}). Calling parseObject().');\n                        $result = $this->parseObject($val,$key);\n                        break;\n                    case 'simple':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item (\"'.$key.'\") is a simple type (string or number). Calling parseSimpleType().');\n                        $result = $this->parseSimpleType($val,$key);\n\n                        break;\n                    default:\n                        break;\n                }\n                $obj[$key] = $result;\n            }\n\n            $result = $this->template($obj,$context);\n\n            //if($debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() popping object \"'.$context.'\" from stack.');\n\n            // Pop it from the stack\n            //array_pop($this->traversalObjectStack);\n\n            return $result;\n        }\n\n        function parseList(&$list,&$context){\n            global $modx;\n            $result = '';\n            $iterator = 0;\n\n            foreach ($list as &$index) {\n\n                switch($this->typeCheck($index)){\n                    case 'list':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is a list ([]). Calling parseList().');\n                        $result = $this->parseList($index,$iterator);\n                        break;\n                    case 'object':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is an object ({}). Calling parseObject().');\n                        $result = $this->parseObject($index,$context);\n                        break;\n                    case 'simple':\n                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item (\"'.$context.'\") is a simple type (string or number). Calling parseSimpleType().');\n                        $result = $this->parseSimpleType($index,$context);\n                        break;\n                    default:\n                        break;\n                }\n                $list[$iterator] = $result;\n                $iterator++;\n            }\n            $result = implode(' ',$list);\n            return $result;\n        }\n\n        function parseSimpleType(&$value,&$context){\n            global $modx;\n            $prefContext = ($this->chunkPrefix.$context);\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseSimpleType(), This item (\"'.$context.'\") contains value \"'.$value.'\".');\n\n            if(!array_key_exists($prefContext,self::$chunkCache)){\n                self::$chunkCache[$prefContext] = $modx->getChunk($prefContext);\n                $chunk = self::$chunkCache[$prefContext];\n            } else {\n                $chunk = self::$chunkCache[$prefContext];\n            }\n\n            if($chunk){\n                $result = str_replace('[[+value]]',$value,$chunk);\n                return $result;\n            } else {\n                return $value;\n            }\n\n        }\n\n        function parseFieldPointer(&$type,&$context){\n            global $modx;\n            $chunk = $modx->getChunk(($this->chunkPrefix.$context));\n            if($chunk != ''){\n                $result = str_replace('[[+value]]',$type,$chunk);\n                return $result;\n            } else {\n                return $type;\n            }\n\n        }\n\n        function template(&$collection,$tmplName) {\n            global $modx;\n            $res = null;\n            $tempVar;\n\n            if ($this->useChunkMatching) {\n\n                // Get the current chunkMatchingSelector key from the $collection list.\n                // This is used later to choose which Chunk to use as template.\n                if(array_key_exists($this->chunkMatchingSelector,$collection)){\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector \"'.$this->chunkMatchingSelector.'\" was found in collection \"'.$tmplName.'\".');\n                    $chunkName = $collection[$this->chunkMatchingSelector];\n                } else {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector \"'.$this->chunkMatchingSelector.'\" was NOT found in collection \"'.$tmplName.'\".');\n                    $chunkName = '';\n                }\n\n                if ($chunkName === '') {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk name is \"\".');\n                    /*\n                      If nothing was returned from the assignment above we have found no selector. We have to\n                      use another way to match the current collection in the $collection list.\n                      The way that json is structured it is very likely that the parent key is the name of the\n                      object type in the collection.\n                      Example\n\n                        {\n                          \"contact\":[\n                            {\n                              \"name\":\"Mini Me\",\n                              \"shoesize\":{\"eu\":\"23\"}\n                            },\n                            {\n                              \"name\":\"Big Dude\",\n                              \"shoesize\":{\"eu\":\"49\"}\n                            }\n\n                          ]\n                        }\n\n                      In the example above its implied that each item in the \"contact\" collection is of typ... contact :)\n                      Similarly the \"shoesize\" property is a complex value that would be best matched to the key \"shoesize\".\n\n                    */\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), It is very likely that the parent key is the name of the object type. Setting $chunkName to \"'.$tmplName.'\"');\n                    $chunkName = $tmplName;\n                } else {\n                }\n\n                if($chunkName != ''){\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parsing Chunk \"'.$chunkName.'\".');\n                    //$tempVar = $modx->parseChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n                    $tempVar = $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n\n                    $res .= $tempVar;\n                } else {\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), $chunkName is still empty.');\n                }\n            } else {\n                //$tempVar .= $modx->parseChunk($this->staticChunkName, $collection, '[[+', ']]');\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk matching is turned off. Using static Chunk \"'.$this->staticChunkName.'\".');\n\n                $tempVar .= $modx->getChunk($this->staticChunkName, $collection, '[[+', ']]');\n                $res .= $tempVar;\n\n            }\n            if(!$res){\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned an invalid result. Setting the template result to \"\".');\n                $res = '';\n            } else {\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned a valid result. Calling templateNSPlaceholders() to find and template namespaced tags.');\n                $res = $this->templateNSPlaceholders($res);\n            }\n            return $res;\n        }\n\n        function parseChunk($chunkName, $chunkArr, $prefix='[[+', $suffix=']]'){\n            global $modx;\n            $chunk='';\n\n            if(!array_key_exists($chunkName,self::$chunkCache)){\n                self::$chunkCache[$chunkName] = $modx->getChunk($chunkName);\n                $chunk = self::$chunkCache[$chunkName];\n            } else {\n                $chunk = self::$chunkCache[$chunkName];\n            }\n\n            if (!empty($chunk) || $chunk === '0') {\n                if(is_array($chunkArr)) {\n                    reset($chunkArr);\n                    while (list($key, $value)= each($chunkArr)) {\n                        $chunk= str_replace($prefix.$key.$suffix, $value, $chunk);\n                    }\n                }\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseChunk(), Done parsing, returning \"'.$chunk.'\".');\n\n            return $chunk;\n        }\n\n\n        // Templates namespaced placeholders\n        function templateNSPlaceholders($chunk){\n            global $modx;\n            $collection = null;\n            $currentType = '';\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: Starting templateNSPlaceholders(). Checking \"'.$chunk.'\"');\n\n            // Find any unparsed placeholders\n\n            $nsSeparator = '.';\n            $forwardSeparator = '-';\n            $backSeparator = '+';\n            $patternSeparator = '?';\n            $placeholders;\n\n            $pattern = \"/\\[\\[\\+[^\\]]*\\]\\]/\";\n\n            preg_match_all($pattern, $chunk, $placeholders);\n\n\n            if(!is_array($placeholders) || count($placeholders[0])<=0){\n\n                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), there where no placeholders in this chunk. Returning what we got.');\n\n                return $chunk;\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), we got placeholders \"'.json_encode($placeholders).'\"');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            // Set local references to the traversal stack (history stack). Local refs should speed up access.\n            $stackRootPointer = &$this->traversalObjectStack;\n            $pointer = &$stackRootPointer;\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The traversal stack has the following items: \"'.json_encode($pointer).'\"');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Going into the placeholders foreach.');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            // The actual result of the preg_match is in the first index of the $placeholders array.\n            $placeholders = $placeholders[0];\n\n            foreach($placeholders as $placeholder){\n\n                $value = '';\n\n                // Make a local copy for performance impr.\n                $current = $placeholder;\n\n                // Only parse the placeholder if it has a separator\n                if(!strpos($current,$nsSeparator)===false){\n\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() the current placeholder is: \"'.$current.'\"');\n\n                    // Remove the tags\n                    $path = str_replace(array('[[+',']]'), '', $current);\n\n                    // Explode the current placeholder using the namespace separator\n                    $parts = explode($nsSeparator,$path);\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() After the cleanup the placeholder looks like this: \"'.json_encode($parts).'\" .');\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Looping through the parts of the current placeholder.');\n\n\n                    // Iteration counter\n                    $i = 1;\n\n                    // Lets loop through all parts in the namespace array.\n                    foreach($parts as $part){\n\n                        switch($part){\n                            case '<':\n                                break;\n                            case '>':\n                                break;\n                            case '?':\n                                break;\n\n                            default:\n\n                                /*\n                                  Ok, lets look in the current array position if we have a match for the namespace part.\n\n                                  Right now we only support cronological paths so if the part is not found, its no\n                                  use iterating on, so we simply return the chunk we got.\n\n                                */\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Rotation nr \"'.$i.'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current part is \"'.$part.'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Lets start looking in the stack at our present position.');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current pointer in the stack contains: \"'.json_encode($pointer).'\".');\n\n                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n                                // Get a pointer to the current value\n                                $value = &$pointer[$part];\n\n                                if(!isset($value)){\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position in the stack had no \"'.$part.'\" reference. This means that the path is invalid and we can set value to \"\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                    unset($value);\n                                    $value = '';\n                                }\n\n\n                                /*\n                                    Got something! Lets see if its a string/num/bool, or an array...\n                                */\n\n\n                                // If we still have an array, we havent reached the target\n\n                                $currentType = $this->typeCheck($value);\n\n                                if($currentType!=='simple'){\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position \"'.json_encode($value).'\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position \"'.json_encode($value).'\".');\n\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n\n                                    // Increment the i counter which is just used for debug purposes.\n                                    $i++;\n\n                                    // unset the pointer so that we dont screw with the history stack\n                                    unset($pointer);\n\n                                    // If we got an array, we need to step into it to get something usefull.\n                                    if($currentType==='list'){\n                                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() * Current stack item is an array.');\n                                        $pointer = &$value[0];\n\n                                    } else {\n                                        // Redirect the pointer ref to the new, current position in the traversal history stack\n                                        $pointer = &$value;\n\n                                    }\n\n                                    // unset the value ref so that we dont screw with the history stack\n                                    unset($value);\n\n                                } else {\n                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position returned the string \"'.$value.'\". Lets keep it for templating.');\n                                }\n                        }\n                    }\n\n                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing this placeholder. Lets replace the it with this value \"'.$value.'\"');\n\n                    // Replace the original placeholder with the retrieved value.\n                    $chunk = str_replace(('[[+'.$path.']]'),$value,$chunk);\n\n                    // unset the pointer so that we dont screw with the history stack\n                    unset($pointer);\n                    // Backup to the beginning of the stack so that the next placeholder can reference the complete history.\n                    $pointer = $stackRootPointer;\n\n                }\n\n\n            }\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing all placeholders.');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() returning \"'.$chunk.'\"');\n            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');\n\n            return $chunk;\n        }\n\n\n        // Facilitates Chunk caching\n\n        function getChunkTemplate($templateName){\n            if(array_key_exists($templateName,self::$chunkCache)){\n                return self::$chunkCache[$templateName];\n            } else {\n                self::$chunkCache[$templateName] &= $modx->getChunk($templateName);\n                return self::$chunkCache[$templateName];\n\n            }\n        }\n\n        // Facilitates Chunk processing using cached objects\n        function parseChunkTemplate($templateName,&$collection){\n            if(array_key_exists($templateName,self::$chunkCache)){\n                return $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');\n            } else {\n                return $modx->getChunk($templateName);\n            }\n        }\n\n        // Utility function to check type of Array\n        function is_assoc (&$arr) {\n            try{\n                return (is_array($arr) && (!count($arr) || count(array_filter(array_keys($arr),'is_string')) == count($arr)));\n            }catch(Exception $e){\n                return false;\n            }\n        }\n\n        function typeCheck(&$var){\n            $val = '';\n            if(is_array($var)){\n                if ($this->is_assoc($var)) {\n                    $val = 'object';\n\n                } else {\n                    $val = 'list';\n                }\n            } else {\n                $val = 'simple';\n            }\n            return $val;\n        }\n\n    }\n\n\n} else {\n\n}\n\n/*\n\n  ----------------------------------------------------------------------------------------------\n\n  Below is the actual snippet code which sets defaults, validates the input, instantiates the\n  Widgeteer object and runs the logic...\n\n*/\n\n$dataSourceUrl = isset($dataSourceUrl) ? $dataSourceUrl : 'null';\n$dataSourceUrl = isset($dataSetUrl) ? $dataSetUrl : $dataSourceUrl; //New interface parameter to mend naming consistency issue.\n$staticChunkName = isset($staticChunkName) ? $staticChunkName : 'null';\n$dataSet = isset($dataSet) ? ($dataSet) : 'null';\n$useChunkMatching = isset($useChunkMatching) ? $useChunkMatching : true;\n$chunkMatchingSelector = isset($chunkMatchingSelector) ? $chunkMatchingSelector : '';\n$dataSetRoot = isset($dataSetRoot) ? $dataSetRoot : 'null';\n$chunkMatchRoot = isset($chunkMatchRoot) ? $chunkMatchRoot : false;\n$chunkPrefix = isset($chunkPrefix) ? $chunkPrefix : '';\n$dataSet = str_replace(array('|xq|','|xe|','|xa|'),array('?','=','&') , $dataSet);\n$preprocessor = isset($preprocessor) ? $preprocessor : '';\n\n\n$debugmode = isset($debugmode) ? $debugmode : false;\n\nif($debugmode){\n    $modx->setLogLevel(modX::LOG_LEVEL_DEBUG);\n}\n\nif($dataSourceUrl == 'null' && $dataSet == 'null'){\n    print '{\"result\":[{\"objecttypename\":\"exception\",\"errorcode\"=\"0\",\"message\":\"The dataSet parameter is empty.\"}]}';\n} else {\n\n    $w = new simplx_widgeteer();\n    $w->debugmode = $debugmode;\n\n    /*\n      PHP bug perhaps? $useChunkMatching evaluates as true even if its false!?\n      I have to \"switch poles\" in order to get the right effect...\n    */\n    if($useChunkMatching && $staticChunkName != 'null'){\n\n        $w->useChunkMatching = false;\n        $w->staticChunkName = $staticChunkName;\n\n    } else {\n        $w->useChunkMatching = true;\n        $w->chunkMatchingSelector = $chunkMatchingSelector;\n    }\n\n    $w->chunkMatchRoot = $chunkMatchRoot;\n    $w->chunkPrefix = $chunkPrefix;\n    $w->preprocessor = $preprocessor;\n\n    if($dataSourceUrl != 'null'){\n        $dataSourceUrl = urldecode($dataSourceUrl);\n        $w->loadDataSource($dataSourceUrl);\n\n    } else {\n\n        $w->loadDataSet(utf8_encode($dataSet));\n    }\n\n    if($dataSetRoot != 'null'){\n        $w->setDataRoot($dataSetRoot);\n    }\n\n    return $w->parse();\n}"

-----


/**
 * fbSimplxWidgeteer
 *
 * DEPRECATED. Form report is now generated with fbFormReport.
 *
 * A slightly modified version of the SIMPLX Widgeteer snippet, by Lars Wallin.
 * Used by FormBlocks for handling the templating of the emailTpl.
 *
 * This script now uses getChunk instead of parseChunk, which makes it possible to use output modifiers in tpl chunks.
 *
 * @author Lars Wallin
 */

//require_once($modx->config['base_path']."assets/snippets/simplx/simplx_widgeteer.php");

if(!class_exists('simplx_widgeteer')){

    class simplx_widgeteer{
        public $debugmode = false;
        public $dataSet;
        public $dataSetUrl;
        public $dataSetArray;
        public $dataSetRoot;
        public $useChunkMatching = true;
        public $chunkMatchingSelector = '';
        public $staticChunkName = '';
        public $chunkPrefix = '';
        public $chunkMatchRoot = false;
        public $preprocessor = '';
        private $iterator = 0;
        private $traversalStack = array();
        private $traversalObjectStack = array();
        private $traversalContext = '';

        // Only one instance per request.
        private static $chunkCache = array();

        public function preprocess($dSet,$preprocessor=null){
            global $modx;

            if ($preprocessor) {

                $dSet = $modx->runSnippet($preprocessor,array('dataSet'=>$dSet));

                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: preprocess(), Done processing. Dataset now contains "'.$dSet.'".');

            }
            return $dSet;
        }

        public function loadDataSet($dSet){
            global $modx;
            $dset;

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSet(), Dataset contains "'.$dSet.'".');

            if($this->preprocessor){
                $dSet = $this->preprocess($dSet,$this->preprocessor);
                $this->dataSet = $dSet;
            }


            $this->dataSetArray = $this->decode($dSet);

            if(is_array($this->dataSetArray)){
                return true;
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSet(): Exception, Unable to decode the dataset.');
                return false;
            }

            //$this->cacheDataSet($dSet);

        }

        public function loadCachedDataSet($dataSetKey){
            global $modx;
            $dSet = $modx->cacheManager->get($dataSetKey);
            
            if ($dSet) {
                return $dSet;
            } else {
                return '';
            }

        }

        public function cacheDataSet($dSet){
            global $modx;
            // For now we just cache remote dataSets...
            if ($this->dataSetUrl != '') {
                $dataSetLocator = urlencode($this->dataSetUrl);
                $modx->cacheManager->set(('dataSet.'.$dataSetLocator),$this->dataSetArray);
            }
        }

        public function loadDataSource($dSourceURL){
            global $modx;

            $result = false;

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Fetching from URL "'.$dSourceURL.'".');

            if ($dSourceURL != "") {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $dSourceURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $output = curl_exec($ch);
                curl_close($ch);

                $this->dataSetUrl = $dSourceURL;

                if ($output != "") {
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), Got the following output "'.$output.'".<br/><br/>');

                    $result = $this->loadDataSet($output);

                    if($result){
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: loadDataSource(), loadDataSet() returned "true".');

                    } else {
                        $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, loadDataSet() returned "false". Aborting.');

                    }

                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: loadDataSource(): Exception, no valid output from "'.$dSourceURL.'".');
                }
            }
        }

        public function setDataRoot($elName){

            $this->dataSetRoot = $elName;
            $this->dataSetArray = $this->dataSetArray[$this->dataSetRoot];

        }

        public function decode($json){
            return json_decode($json,true);

        }

        public function encode($object){
            return json_encode($object);

        }


        function parse(&$obj=null){
            global $modx;
            $result = '';

            if(!isset($obj)){

                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() Got no initial argument.');

                if($this->dataSetArray){
                    $obj = &$this->dataSetArray;
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, Missing valid dataset. Aborting.');
                    return false;
                }

            } else {
                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse() starting with argument "'.json_encode($obj).'".');
            }

            if($this->dataSetRoot != ''){
                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Checking existance of dataSetRoot "'.$this->dataSetRoot.'".');

                /*
                if(array_key_exists($this->dataSetRoot,$this->dataSetArray)){
                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), dataSetRoot "'.$this->dataSetRoot.'" was found in the dataset.');
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, 'Widgeteer: parse(): Exception, dataSetRoot "'.$this->dataSetRoot.'" was NOT found in the dataset. Aborting.');
                    return false;
                }
                */

                $context = $this->dataSetRoot;
            }

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Starting recursive parse.');
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), -------------------------------------');
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,'');

            foreach ($obj as  $key => &$val) {

                switch($this->typeCheck($val)){
                    case "list":
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item ("'.$key.'") is a list ([]). Calling parseList().');
                        $result = $this->parseList($val,$key);
                        break;
                    case "object":
                        if(!$this->dataSetRoot){
                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item ("'.$key.'") is an object ({}). Calling parseObject().');
                            $result = $this->parseObject($val,$key);
                        }
                        break;
                    case "simple":
                        if(!$this->dataSetRoot){
                            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), This item ("'.$key.'") is a simple type (string or number). Calling parseSimpleType().');
                            $result = $this->parseSimpleType($val,$key);
                        }
                        break;
                    default :
                        break;
                }
                $obj[$key] = $result;

            }

            $result = implode(' ',$obj);


            // If this is the last render call we have the option to wrap the result in the
            // rootChunk.

            if($this->chunkMatchRoot == 'true'){

                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Chunk matching root element.');

                $rootChunk = $modx->getChunk(($this->chunkPrefix.$this->dataSetRoot));

                if($rootChunk != ''){
                    $result = str_replace('[[+content]]',$result,$rootChunk);
                } else {

                }

            }

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parse(), Done parsing. Returning "'.$result.'".');

            return $result;
        }

        function parseObject(&$obj,$context){
            global $modx;

            $result = '';

            /*
            FIX
            Add a reference to the actual, untemplated, object.
            Now the stack contains templated data
            */


            // Add the object to the stack
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() adding object "'.$context.'" to stack "'.json_encode($obj).'".');
            $this->traversalObjectStack[$context] = $obj;

            foreach ($obj as  $key => &$val) {

                switch($this->typeCheck($val)){
                    case 'list':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item ("'.$key.'") is a list ([]). Calling parseList().');
                        $result = $this->parseList($val,$key);
                        break;
                    case 'object':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item ("'.$key.'") is an object ({}). Calling parseObject().');
                        $result = $this->parseObject($val,$key);
                        break;
                    case 'simple':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseObject(), This item ("'.$key.'") is a simple type (string or number). Calling parseSimpleType().');
                        $result = $this->parseSimpleType($val,$key);

                        break;
                    default:
                        break;
                }
                $obj[$key] = $result;
            }

            $result = $this->template($obj,$context);

            //if($debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseobject() popping object "'.$context.'" from stack.');

            // Pop it from the stack
            //array_pop($this->traversalObjectStack);

            return $result;
        }

        function parseList(&$list,&$context){
            global $modx;
            $result = '';
            $iterator = 0;

            foreach ($list as &$index) {

                switch($this->typeCheck($index)){
                    case 'list':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item ("'.$context.'") is a list ([]). Calling parseList().');
                        $result = $this->parseList($index,$iterator);
                        break;
                    case 'object':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item ("'.$context.'") is an object ({}). Calling parseObject().');
                        $result = $this->parseObject($index,$context);
                        break;
                    case 'simple':
                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseList(), This item ("'.$context.'") is a simple type (string or number). Calling parseSimpleType().');
                        $result = $this->parseSimpleType($index,$context);
                        break;
                    default:
                        break;
                }
                $list[$iterator] = $result;
                $iterator++;
            }
            $result = implode(' ',$list);
            return $result;
        }

        function parseSimpleType(&$value,&$context){
            global $modx;
            $prefContext = ($this->chunkPrefix.$context);

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseSimpleType(), This item ("'.$context.'") contains value "'.$value.'".');

            if(!array_key_exists($prefContext,self::$chunkCache)){
                self::$chunkCache[$prefContext] = $modx->getChunk($prefContext);
                $chunk = self::$chunkCache[$prefContext];
            } else {
                $chunk = self::$chunkCache[$prefContext];
            }

            if($chunk){
                $result = str_replace('[[+value]]',$value,$chunk);
                return $result;
            } else {
                return $value;
            }

        }

        function parseFieldPointer(&$type,&$context){
            global $modx;
            $chunk = $modx->getChunk(($this->chunkPrefix.$context));
            if($chunk != ''){
                $result = str_replace('[[+value]]',$type,$chunk);
                return $result;
            } else {
                return $type;
            }

        }

        function template(&$collection,$tmplName) {
            global $modx;
            $res = null;
            $tempVar;

            if ($this->useChunkMatching) {

                // Get the current chunkMatchingSelector key from the $collection list.
                // This is used later to choose which Chunk to use as template.
                if(array_key_exists($this->chunkMatchingSelector,$collection)){
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector "'.$this->chunkMatchingSelector.'" was found in collection "'.$tmplName.'".');
                    $chunkName = $collection[$this->chunkMatchingSelector];
                } else {
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), chunkMatchingSelector "'.$this->chunkMatchingSelector.'" was NOT found in collection "'.$tmplName.'".');
                    $chunkName = '';
                }

                if ($chunkName === '') {
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk name is "".');
                    /*
                      If nothing was returned from the assignment above we have found no selector. We have to
                      use another way to match the current collection in the $collection list.
                      The way that json is structured it is very likely that the parent key is the name of the
                      object type in the collection.
                      Example

                        {
                          "contact":[
                            {
                              "name":"Mini Me",
                              "shoesize":{"eu":"23"}
                            },
                            {
                              "name":"Big Dude",
                              "shoesize":{"eu":"49"}
                            }

                          ]
                        }

                      In the example above its implied that each item in the "contact" collection is of typ... contact :)
                      Similarly the "shoesize" property is a complex value that would be best matched to the key "shoesize".

                    */
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), It is very likely that the parent key is the name of the object type. Setting $chunkName to "'.$tmplName.'"');
                    $chunkName = $tmplName;
                } else {
                }

                if($chunkName != ''){

                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parsing Chunk "'.$chunkName.'".');
                    //$tempVar = $modx->parseChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');
                    $tempVar = $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');

                    $res .= $tempVar;
                } else {
                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), $chunkName is still empty.');
                }
            } else {
                //$tempVar .= $modx->parseChunk($this->staticChunkName, $collection, '[[+', ']]');

                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), Chunk matching is turned off. Using static Chunk "'.$this->staticChunkName.'".');

                $tempVar .= $modx->getChunk($this->staticChunkName, $collection, '[[+', ']]');
                $res .= $tempVar;

            }
            if(!$res){
                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned an invalid result. Setting the template result to "".');
                $res = '';
            } else {
                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: template(), parseChunk() returned a valid result. Calling templateNSPlaceholders() to find and template namespaced tags.');
                $res = $this->templateNSPlaceholders($res);
            }
            return $res;
        }

        function parseChunk($chunkName, $chunkArr, $prefix='[[+', $suffix=']]'){
            global $modx;
            $chunk='';

            if(!array_key_exists($chunkName,self::$chunkCache)){
                self::$chunkCache[$chunkName] = $modx->getChunk($chunkName);
                $chunk = self::$chunkCache[$chunkName];
            } else {
                $chunk = self::$chunkCache[$chunkName];
            }

            if (!empty($chunk) || $chunk === '0') {
                if(is_array($chunkArr)) {
                    reset($chunkArr);
                    while (list($key, $value)= each($chunkArr)) {
                        $chunk= str_replace($prefix.$key.$suffix, $value, $chunk);
                    }
                }
            }

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: parseChunk(), Done parsing, returning "'.$chunk.'".');

            return $chunk;
        }


        // Templates namespaced placeholders
        function templateNSPlaceholders($chunk){
            global $modx;
            $collection = null;
            $currentType = '';

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: Starting templateNSPlaceholders(). Checking "'.$chunk.'"');

            // Find any unparsed placeholders

            $nsSeparator = '.';
            $forwardSeparator = '-';
            $backSeparator = '+';
            $patternSeparator = '?';
            $placeholders;

            $pattern = "/\[\[\+[^\]]*\]\]/";

            preg_match_all($pattern, $chunk, $placeholders);


            if(!is_array($placeholders) || count($placeholders[0])<=0){

                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), there where no placeholders in this chunk. Returning what we got.');

                return $chunk;
            }

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templateNSPlaceholders(), we got placeholders "'.json_encode($placeholders).'"');

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

            // Set local references to the traversal stack (history stack). Local refs should speed up access.
            $stackRootPointer = &$this->traversalObjectStack;
            $pointer = &$stackRootPointer;

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The traversal stack has the following items: "'.json_encode($pointer).'"');

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');


            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Going into the placeholders foreach.');

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

            // The actual result of the preg_match is in the first index of the $placeholders array.
            $placeholders = $placeholders[0];

            foreach($placeholders as $placeholder){

                $value = '';

                // Make a local copy for performance impr.
                $current = $placeholder;

                // Only parse the placeholder if it has a separator
                if(!strpos($current,$nsSeparator)===false){


                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() the current placeholder is: "'.$current.'"');

                    // Remove the tags
                    $path = str_replace(array('[[+',']]'), '', $current);

                    // Explode the current placeholder using the namespace separator
                    $parts = explode($nsSeparator,$path);

                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() After the cleanup the placeholder looks like this: "'.json_encode($parts).'" .');

                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Looping through the parts of the current placeholder.');


                    // Iteration counter
                    $i = 1;

                    // Lets loop through all parts in the namespace array.
                    foreach($parts as $part){

                        switch($part){
                            case '<':
                                break;
                            case '>':
                                break;
                            case '?':
                                break;

                            default:

                                /*
                                  Ok, lets look in the current array position if we have a match for the namespace part.

                                  Right now we only support cronological paths so if the part is not found, its no
                                  use iterating on, so we simply return the chunk we got.

                                */

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Rotation nr "'.$i.'".');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current part is "'.$part.'".');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() Lets start looking in the stack at our present position.');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current pointer in the stack contains: "'.json_encode($pointer).'".');

                                if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');


                                // Get a pointer to the current value
                                $value = &$pointer[$part];

                                if(!isset($value)){
                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position in the stack had no "'.$part.'" reference. This means that the path is invalid and we can set value to "".');

                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

                                    unset($value);
                                    $value = '';
                                }


                                /*
                                    Got something! Lets see if its a string/num/bool, or an array...
                                */


                                // If we still have an array, we havent reached the target

                                $currentType = $this->typeCheck($value);

                                if($currentType!=='simple'){

                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position "'.json_encode($value).'".');

                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() We got an array back from the current stack position "'.json_encode($value).'".');

                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');


                                    // Increment the i counter which is just used for debug purposes.
                                    $i++;

                                    // unset the pointer so that we dont screw with the history stack
                                    unset($pointer);

                                    // If we got an array, we need to step into it to get something usefull.
                                    if($currentType==='list'){
                                        if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() * Current stack item is an array.');
                                        $pointer = &$value[0];

                                    } else {
                                        // Redirect the pointer ref to the new, current position in the traversal history stack
                                        $pointer = &$value;

                                    }

                                    // unset the value ref so that we dont screw with the history stack
                                    unset($value);

                                } else {
                                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() The current position returned the string "'.$value.'". Lets keep it for templating.');
                                }
                        }
                    }

                    if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing this placeholder. Lets replace the it with this value "'.$value.'"');

                    // Replace the original placeholder with the retrieved value.
                    $chunk = str_replace(('[[+'.$path.']]'),$value,$chunk);

                    // unset the pointer so that we dont screw with the history stack
                    unset($pointer);
                    // Backup to the beginning of the stack so that the next placeholder can reference the complete history.
                    $pointer = $stackRootPointer;

                }


            }

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() done with parsing all placeholders.');
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG, 'Widgeteer: templatePlaceholders() returning "'.$chunk.'"');
            if($this->debugmode) $modx->log(modX::LOG_LEVEL_DEBUG,' ');

            return $chunk;
        }


        // Facilitates Chunk caching

        function getChunkTemplate($templateName){
            if(array_key_exists($templateName,self::$chunkCache)){
                return self::$chunkCache[$templateName];
            } else {
                self::$chunkCache[$templateName] &= $modx->getChunk($templateName);
                return self::$chunkCache[$templateName];

            }
        }

        // Facilitates Chunk processing using cached objects
        function parseChunkTemplate($templateName,&$collection){
            if(array_key_exists($templateName,self::$chunkCache)){
                return $modx->getChunk(($this->chunkPrefix.''.$chunkName), $collection, '[[+', ']]');
            } else {
                return $modx->getChunk($templateName);
            }
        }

        // Utility function to check type of Array
        function is_assoc (&$arr) {
            try{
                return (is_array($arr) && (!count($arr) || count(array_filter(array_keys($arr),'is_string')) == count($arr)));
            }catch(Exception $e){
                return false;
            }
        }

        function typeCheck(&$var){
            $val = '';
            if(is_array($var)){
                if ($this->is_assoc($var)) {
                    $val = 'object';

                } else {
                    $val = 'list';
                }
            } else {
                $val = 'simple';
            }
            return $val;
        }

    }


} else {

}

/*

  ----------------------------------------------------------------------------------------------

  Below is the actual snippet code which sets defaults, validates the input, instantiates the
  Widgeteer object and runs the logic...

*/

$dataSourceUrl = isset($dataSourceUrl) ? $dataSourceUrl : 'null';
$dataSourceUrl = isset($dataSetUrl) ? $dataSetUrl : $dataSourceUrl; //New interface parameter to mend naming consistency issue.
$staticChunkName = isset($staticChunkName) ? $staticChunkName : 'null';
$dataSet = isset($dataSet) ? ($dataSet) : 'null';
$useChunkMatching = isset($useChunkMatching) ? $useChunkMatching : true;
$chunkMatchingSelector = isset($chunkMatchingSelector) ? $chunkMatchingSelector : '';
$dataSetRoot = isset($dataSetRoot) ? $dataSetRoot : 'null';
$chunkMatchRoot = isset($chunkMatchRoot) ? $chunkMatchRoot : false;
$chunkPrefix = isset($chunkPrefix) ? $chunkPrefix : '';
$dataSet = str_replace(array('|xq|','|xe|','|xa|'),array('?','=','&') , $dataSet);
$preprocessor = isset($preprocessor) ? $preprocessor : '';


$debugmode = isset($debugmode) ? $debugmode : false;

if($debugmode){
    $modx->setLogLevel(modX::LOG_LEVEL_DEBUG);
}

if($dataSourceUrl == 'null' && $dataSet == 'null'){
    print '{"result":[{"objecttypename":"exception","errorcode"="0","message":"The dataSet parameter is empty."}]}';
} else {

    $w = new simplx_widgeteer();
    $w->debugmode = $debugmode;

    /*
      PHP bug perhaps? $useChunkMatching evaluates as true even if its false!?
      I have to "switch poles" in order to get the right effect...
    */
    if($useChunkMatching && $staticChunkName != 'null'){

        $w->useChunkMatching = false;
        $w->staticChunkName = $staticChunkName;

    } else {
        $w->useChunkMatching = true;
        $w->chunkMatchingSelector = $chunkMatchingSelector;
    }

    $w->chunkMatchRoot = $chunkMatchRoot;
    $w->chunkPrefix = $chunkPrefix;
    $w->preprocessor = $preprocessor;

    if($dataSourceUrl != 'null'){
        $dataSourceUrl = urldecode($dataSourceUrl);
        $w->loadDataSource($dataSourceUrl);

    } else {

        $w->loadDataSet(utf8_encode($dataSet));
    }

    if($dataSetRoot != 'null'){
        $w->setDataRoot($dataSetRoot);
    }

    return $w->parse();
}