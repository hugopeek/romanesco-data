id: 38
name: ProcessMarkdown
description: 'Ensure correct Markdown rendering by setting the proper MIME type. Set the MIME type back to HTML when viewing the resource in the browser, to prevent the page from being downloaded as file.'
category: c_content
plugincode: "/**\n * ProcessMarkdown\n *\n * Retain original Markdown markup by setting the proper MIME type for a\n * Markdown resource. Set the MIME type back to HTML when viewing the resource\n * in the browser, to prevent the page from being downloaded as file.\n *\n * In addition, HtmlPageDom is used to optimize the output in order to receive\n * the correct styling from Semantic UI.\n *\n * For rendering Markdown as HTML, install the Markdown extra from modstore.pro:\n * https://modstore.pro/packages/content/markdown\n *\n * Process markdown in your template like this:\n *\n * [[*content:Markdown]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\nif (!class_exists(\\Wa72\\HtmlPageDom\\HtmlPageCrawler::class)) {\n    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');\n    return;\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    // Set content type to Markdown when resource has a markdown template\n    case 'OnBeforeDocFormSave':\n        /**\n         * @var modResource $resource\n         */\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n\n        if (!is_object($template)) {\n            break;\n        }\n\n        // Any template with 'Markdown' in its name qualifies\n        // Note to self: you need to revert the content type manually if you assign a non-markdown template again\n        if (stripos($template->get('templatename'), 'markdown') !== false) {\n            $resource->set('contentType', 'text/x-markdown');\n            $resource->set('content_type', '11');\n\n            // Also disable any active text editor\n            $resource->set('richtext', 0);\n        }\n\n        break;\n\n    // Use HTML mime type when viewed as a web page\n    // Based on: https://github.com/GoldCoastMedia/modx-xhtml-mime-switch\n    case 'OnLoadWebDocument':\n        $resource = &$modx->resource;\n\n        // Make sure its Markdown\n        if ($resource->get('content_type') !== 11) {\n            break;\n        }\n\n        // Switch back to HTML\n        $resource->ContentType->set('mime_type', 'text/html');\n        break;\n\n    // Process output with HtmlPageDom\n    case 'OnWebPagePrerender':\n        // Make sure content is Markdown\n        if ($modx->resource->get('content_type') !== 11) {\n            break;\n        }\n\n        // Cached DOM output already includes processed Markdown\n        $cacheManager = $modx->getCacheManager();\n        $cacheElementKey = '/dom';\n        $cacheOptions = [\n            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()\n        ];\n        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);\n        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));\n        if ($cachedOutput && !$isLoggedIn) {\n            break;\n        }\n\n        $output = &$modx->resource->_output;\n        $dom = new HtmlPageCrawler($output);\n\n        $dom->filter('#markdown img')\n            ->each(function (HtmlPageCrawler $image)\n            {\n                // Prevent images from overflowing their container\n                $image->addClass('ui rounded raised image');\n\n                // Apply native lazy load\n                $image->setAttribute('loading','lazy');\n            })\n        ;\n\n        $dom->filter('#markdown a')\n            ->each(function (HtmlPageCrawler $link)\n            {\n                // Turn into button if desired\n                if (str_contains($link, 'Continue to:')) {\n                    $link->addClass('ui big primary button');\n                }\n            })\n        ;\n\n        // Turn tables into Semantic tables (if needed)\n        $dom->filter('#markdown table:not(.ui.table)')->addClass('ui table');\n\n        // Add language class to code blocks that do not specify a language\n        $dom->filter('pre')->addClass('language-html');\n        $dom->filter('code')\n            ->addClass('language-html')\n            ->each(function (HtmlPageCrawler $code)\n            {\n                // Code snippets in markdown files are rendered by MODX.\n                // To prevent this, you can add a space to all outer tags in\n                //  your markdown file. So [[snippet]] becomes [ [snippet] ].\n                // Annoying, but the easiest way around it.\n                // At least we can revert it here again:\n                $output = str_replace('[ [','[[',$code);\n                $output = str_replace('] ]',']]',$output);\n                $code->replaceWith($output);\n            })\n        ;\n\n        // Prettier HR\n        $dom->filter('#markdown hr')->replaceWith('<div class=\"ui divider\"></div>');\n\n        // Add SUI list class, but only to first level\n        $dom->filter('#markdown ul > li > ul')->addClass('nested');\n        $dom->filter('#markdown ul:not(.nested)')->addClass('ui list');\n        $dom->filter('#markdown ol > li > ol')->addClass('nested');\n        $dom->filter('#markdown ol:not(.nested)')->addClass('ui list');\n\n        // Turn Obsidian callouts into FUI messages\n        $dom->filter('#markdown blockquote')\n            ->each(function (HtmlPageCrawler $quote)\n            {\n                if ($quote->hasClass('callout')) {\n                    $classes = $quote->getAttribute('class');\n                    $class = substr($classes, strpos($classes, ' ') + 1);\n\n                    // Add FUI classes\n                    switch ($class) {\n                        case 'note':\n                        case 'todo':\n                            $class = 'info';\n                            break;\n                        case 'check':\n                        case 'done':\n                            $class = 'success';\n                            break;\n                        case 'caution':\n                        case 'attention':\n                            $class = 'warning';\n                            break;\n                        case 'failure':\n                        case 'fail':\n                        case 'missing':\n                        case 'bug':\n                            $class = 'error';\n                            break;\n                        case 'abstract':\n                        case 'summary':\n                        case 'tldr':\n                        case 'important':\n                            $class = 'primary';\n                            break;\n                        case 'question':\n                        case 'help':\n                        case 'faq':\n                        case 'tip':\n                        case 'hint':\n                        case 'example':\n                            $class = 'secondary';\n                            break;\n                        default:\n                            $class = '';\n                    }\n\n                    // Apply FUI classes\n                    $quote->addClass(\"ui $class message\");\n                };\n            })\n        ;\n\n        // Remove redundant heading in articles\n        $dom->filter('body.publication #markdown h1:first-child')->remove();\n\n        $output = $dom->saveHTML();\n\n        break;\n}"
properties: 'a:1:{s:13:"elementStatus";a:7:{s:4:"name";s:13:"elementStatus";s:4:"desc";s:39:"romanesco.processmarkdown.elementStatus";s:4:"type";s:9:"textfield";s:7:"options";s:0:"";s:5:"value";s:5:"solid";s:7:"lexicon";s:20:"romanesco:properties";s:4:"area";s:0:"";}}'

-----


/**
 * ProcessMarkdown
 *
 * Retain original Markdown markup by setting the proper MIME type for a
 * Markdown resource. Set the MIME type back to HTML when viewing the resource
 * in the browser, to prevent the page from being downloaded as file.
 *
 * In addition, HtmlPageDom is used to optimize the output in order to receive
 * the correct styling from Semantic UI.
 *
 * For rendering Markdown as HTML, install the Markdown extra from modstore.pro:
 * https://modstore.pro/packages/content/markdown
 *
 * Process markdown in your template like this:
 *
 * [[*content:Markdown]]
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package romanesco
 */

if (!class_exists(\Wa72\HtmlPageDom\HtmlPageCrawler::class)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[HtmlPageDom] Class not found!');
    return;
}

use \Wa72\HtmlPageDom\HtmlPageCrawler;

switch ($modx->event->name) {
    // Set content type to Markdown when resource has a markdown template
    case 'OnBeforeDocFormSave':
        /**
         * @var modResource $resource
         */

        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));

        if (!is_object($template)) {
            break;
        }

        // Any template with 'Markdown' in its name qualifies
        // Note to self: you need to revert the content type manually if you assign a non-markdown template again
        if (stripos($template->get('templatename'), 'markdown') !== false) {
            $resource->set('contentType', 'text/x-markdown');
            $resource->set('content_type', '11');

            // Also disable any active text editor
            $resource->set('richtext', 0);
        }

        break;

    // Use HTML mime type when viewed as a web page
    // Based on: https://github.com/GoldCoastMedia/modx-xhtml-mime-switch
    case 'OnLoadWebDocument':
        $resource = &$modx->resource;

        // Make sure its Markdown
        if ($resource->get('content_type') !== 11) {
            break;
        }

        // Switch back to HTML
        $resource->ContentType->set('mime_type', 'text/html');
        break;

    // Process output with HtmlPageDom
    case 'OnWebPagePrerender':
        // Make sure content is Markdown
        if ($modx->resource->get('content_type') !== 11) {
            break;
        }

        // Cached DOM output already includes processed Markdown
        $cacheManager = $modx->getCacheManager();
        $cacheElementKey = '/dom';
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => 'resource/' . $modx->resource->getCacheKey()
        ];
        $cachedOutput = $cacheManager->get($cacheElementKey, $cacheOptions);
        $isLoggedIn = $modx->user->hasSessionContext($modx->context->get('key'));
        if ($cachedOutput && !$isLoggedIn) {
            break;
        }

        $output = &$modx->resource->_output;
        $dom = new HtmlPageCrawler($output);

        $dom->filter('#markdown img')
            ->each(function (HtmlPageCrawler $image)
            {
                // Prevent images from overflowing their container
                $image->addClass('ui rounded raised image');

                // Apply native lazy load
                $image->setAttribute('loading','lazy');
            })
        ;

        $dom->filter('#markdown a')
            ->each(function (HtmlPageCrawler $link)
            {
                // Turn into button if desired
                if (str_contains($link, 'Continue to:')) {
                    $link->addClass('ui big primary button');
                }
            })
        ;

        // Turn tables into Semantic tables (if needed)
        $dom->filter('#markdown table:not(.ui.table)')->addClass('ui table');

        // Add language class to code blocks that do not specify a language
        $dom->filter('pre')->addClass('language-html');
        $dom->filter('code')
            ->addClass('language-html')
            ->each(function (HtmlPageCrawler $code)
            {
                // Code snippets in markdown files are rendered by MODX.
                // To prevent this, you can add a space to all outer tags in
                //  your markdown file. So [[snippet]] becomes [ [snippet] ].
                // Annoying, but the easiest way around it.
                // At least we can revert it here again:
                $output = str_replace('[ [','[[',$code);
                $output = str_replace('] ]',']]',$output);
                $code->replaceWith($output);
            })
        ;

        // Prettier HR
        $dom->filter('#markdown hr')->replaceWith('<div class="ui divider"></div>');

        // Add SUI list class, but only to first level
        $dom->filter('#markdown ul > li > ul')->addClass('nested');
        $dom->filter('#markdown ul:not(.nested)')->addClass('ui list');
        $dom->filter('#markdown ol > li > ol')->addClass('nested');
        $dom->filter('#markdown ol:not(.nested)')->addClass('ui list');

        // Turn Obsidian callouts into FUI messages
        $dom->filter('#markdown blockquote')
            ->each(function (HtmlPageCrawler $quote)
            {
                if ($quote->hasClass('callout')) {
                    $classes = $quote->getAttribute('class');
                    $class = substr($classes, strpos($classes, ' ') + 1);

                    // Add FUI classes
                    switch ($class) {
                        case 'note':
                        case 'todo':
                            $class = 'info';
                            break;
                        case 'check':
                        case 'done':
                            $class = 'success';
                            break;
                        case 'caution':
                        case 'attention':
                            $class = 'warning';
                            break;
                        case 'failure':
                        case 'fail':
                        case 'missing':
                        case 'bug':
                            $class = 'error';
                            break;
                        case 'abstract':
                        case 'summary':
                        case 'tldr':
                        case 'important':
                            $class = 'primary';
                            break;
                        case 'question':
                        case 'help':
                        case 'faq':
                        case 'tip':
                        case 'hint':
                        case 'example':
                            $class = 'secondary';
                            break;
                        default:
                            $class = '';
                    }

                    // Apply FUI classes
                    $quote->addClass("ui $class message");
                };
            })
        ;

        // Remove redundant heading in articles
        $dom->filter('body.publication #markdown h1:first-child')->remove();

        $output = $dom->saveHTML();

        break;
}