id: 38
name: ProcessMarkdown
category: c_content
plugincode: "/**\n * ProcessMarkdown\n *\n * Retain original Markdown markup by setting the proper MIME type for a\n * Markdown resource. Set the MIME type back to HTML when viewing the resource\n * in the browser, to prevent the page from being downloaded as file.\n *\n * In addition, HtmlPageDom is used to optimize the output in order to receive\n * the correct styling from Semantic UI.\n *\n * For rendering Markdown as HTML, install the Markdown extra from modstore.pro:\n * https://modstore.pro/packages/content/markdown\n *\n * Process markdown in your template like this:\n *\n * [[*content:Markdown]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$pdCorePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $pdCorePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    // Set content type to Markdown when resource has a markdown template\n    case 'OnBeforeDocFormSave':\n        /**\n         * @var modResource $resource\n         */\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n\n        if (!is_object($template)) {\n            break;\n        }\n\n        // Any template with 'Markdown' in its name qualifies\n        // Note to self: you need to revert the content type manually if you assign a non-markdown template again\n        if (stripos($template->get('templatename'), 'markdown') !== false) {\n            $resource->set('contentType', 'text/x-markdown');\n            $resource->set('content_type', '11');\n\n            // Also disable any active text editor\n            $resource->set('richtext', 0);\n        }\n\n        break;\n\n    // Use HTML mime type when viewed as a web page\n    // Based on: https://github.com/GoldCoastMedia/modx-xhtml-mime-switch\n    case 'OnWebPagePrerender':\n        $resource = &$modx->resource;\n\n        if ($resource->get('content_type') !== 11) {\n            break;\n        }\n\n        // Header content types\n        $header = (object) array(\n            'markdown'  => 'text/x-markdown',\n            'html' => 'text/html',\n        );\n\n        // Get the document type\n        $markdown = $resource->get('contentType') === $header->markdown;\n\n        // Switch back to HTML\n        if ($markdown) {\n            $resource->ContentType->set('mime_type', $header->html);\n        }\n\n        // Process output with HtmlPageDom\n        $output = &$resource->_output;\n        $dom = new HtmlPageCrawler($output);\n\n        // Fix image URLs and prevent them from overflowing their container\n        $dom->filter('img')\n            ->each(function (HtmlPageCrawler $image) {\n                $src = $image->getAttribute('src');\n                $image\n                    ->setAttribute('src', 'notes/' . $src)\n                    ->addClass('ui image')\n                ;\n            })\n        ;\n\n        // Remove .md extension from links\n        $dom->filter('a')\n            ->each(function (HtmlPageCrawler $link) {\n                $href = $link->getAttribute('href');\n                $link->setAttribute('href', str_replace('.md','',$href));\n\n                // Turn into button if desired\n                if (strpos($link,'Continue to:') !== false) {\n                    $link->addClass('ui big primary button');\n                }\n            })\n        ;\n\n        // Turn tables into Semantic tables\n        $dom->filter('table')->addClass('ui compact table');\n\n        // Add language class to code blocks that do not specify a language\n        $dom->filter('pre')->addClass('language-html');\n        $dom->filter('code')->addClass('language-html');\n\n        // And a few other things\n        $dom->filter('hr')->replaceWith('<div class=\"ui divider\"></div>');\n        //$dom->filter('ul')->addClass('ui list');\n        //$dom->filter('ol')->addClass('ui list');\n\n        $output = $dom->saveHTML();\n\n        break;\n}"
properties: 'a:0:{}'
content: "/**\n * ProcessMarkdown\n *\n * Retain original Markdown markup by setting the proper MIME type for a\n * Markdown resource. Set the MIME type back to HTML when viewing the resource\n * in the browser, to prevent the page from being downloaded as file.\n *\n * In addition, HtmlPageDom is used to optimize the output in order to receive\n * the correct styling from Semantic UI.\n *\n * For rendering Markdown as HTML, install the Markdown extra from modstore.pro:\n * https://modstore.pro/packages/content/markdown\n *\n * Process markdown in your template like this:\n *\n * [[*content:Markdown]]\n *\n * @var modX $modx\n * @var array $scriptProperties\n *\n * @package romanesco\n */\n\n$pdCorePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');\n\nif (!class_exists('\\Wa72\\HtmlPageDom\\HtmlPageCrawler')) {\n    require $pdCorePath . 'vendor/autoload.php';\n}\n\nuse \\Wa72\\HtmlPageDom\\HtmlPageCrawler;\n\nswitch ($modx->event->name) {\n    // Set content type to Markdown when resource has a markdown template\n    case 'OnBeforeDocFormSave':\n        /**\n         * @var modResource $resource\n         */\n\n        $template = $modx->getObject('modTemplate', array('id' => $resource->get('template')));\n\n        if (!is_object($template)) {\n            break;\n        }\n\n        // Any template with 'Markdown' in its name qualifies\n        // Note to self: you need to revert the content type manually if you assign a non-markdown template again\n        if (stripos($template->get('templatename'), 'markdown') !== false) {\n            $resource->set('contentType', 'text/x-markdown');\n            $resource->set('content_type', '11');\n\n            // Also disable any active text editor\n            $resource->set('richtext', 0);\n        }\n\n        break;\n\n    // Use HTML mime type when viewed as a web page\n    // Based on: https://github.com/GoldCoastMedia/modx-xhtml-mime-switch\n    case 'OnWebPagePrerender':\n        $resource = &$modx->resource;\n\n        if ($resource->get('content_type') !== 11) {\n            break;\n        }\n\n        // Header content types\n        $header = (object) array(\n            'markdown'  => 'text/x-markdown',\n            'html' => 'text/html',\n        );\n\n        // Get the document type\n        $markdown = $resource->get('contentType') === $header->markdown;\n\n        // Switch back to HTML\n        if ($markdown) {\n            $resource->ContentType->set('mime_type', $header->html);\n        }\n\n        // Process output with HtmlPageDom\n        $output = &$resource->_output;\n        $dom = new HtmlPageCrawler($output);\n\n        // Fix image URLs and prevent them from overflowing their container\n        $dom->filter('img')\n            ->each(function (HtmlPageCrawler $image) {\n                $src = $image->getAttribute('src');\n                $image\n                    ->setAttribute('src', 'notes/' . $src)\n                    ->addClass('ui image')\n                ;\n            })\n        ;\n\n        // Remove .md extension from links\n        $dom->filter('a')\n            ->each(function (HtmlPageCrawler $link) {\n                $href = $link->getAttribute('href');\n                $link->setAttribute('href', str_replace('.md','',$href));\n\n                // Turn into button if desired\n                if (strpos($link,'Continue to:') !== false) {\n                    $link->addClass('ui big primary button');\n                }\n            })\n        ;\n\n        // Turn tables into Semantic tables\n        $dom->filter('table')->addClass('ui compact table');\n\n        // Add language class to code blocks that do not specify a language\n        $dom->filter('pre')->addClass('language-html');\n        $dom->filter('code')->addClass('language-html');\n\n        // And a few other things\n        $dom->filter('hr')->replaceWith('<div class=\"ui divider\"></div>');\n        //$dom->filter('ul')->addClass('ui list');\n        //$dom->filter('ol')->addClass('ui list');\n\n        $output = $dom->saveHTML();\n\n        break;\n}"

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

$pdCorePath = $modx->getOption('htmlpagedom.core_path', null, $modx->getOption('core_path') . 'components/htmlpagedom/');

if (!class_exists('\Wa72\HtmlPageDom\HtmlPageCrawler')) {
    require $pdCorePath . 'vendor/autoload.php';
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
    case 'OnWebPagePrerender':
        $resource = &$modx->resource;

        if ($resource->get('content_type') !== 11) {
            break;
        }

        // Header content types
        $header = (object) array(
            'markdown'  => 'text/x-markdown',
            'html' => 'text/html',
        );

        // Get the document type
        $markdown = $resource->get('contentType') === $header->markdown;

        // Switch back to HTML
        if ($markdown) {
            $resource->ContentType->set('mime_type', $header->html);
        }

        // Process output with HtmlPageDom
        $output = &$resource->_output;
        $dom = new HtmlPageCrawler($output);

        // Fix image URLs and prevent them from overflowing their container
        $dom->filter('img')
            ->each(function (HtmlPageCrawler $image) {
                $src = $image->getAttribute('src');
                $image
                    ->setAttribute('src', 'notes/' . $src)
                    ->addClass('ui image')
                ;
            })
        ;

        // Remove .md extension from links
        $dom->filter('a')
            ->each(function (HtmlPageCrawler $link) {
                $href = $link->getAttribute('href');
                $link->setAttribute('href', str_replace('.md','',$href));

                // Turn into button if desired
                if (strpos($link,'Continue to:') !== false) {
                    $link->addClass('ui big primary button');
                }
            })
        ;

        // Turn tables into Semantic tables
        $dom->filter('table')->addClass('ui compact table');

        // Add language class to code blocks that do not specify a language
        $dom->filter('pre')->addClass('language-html');
        $dom->filter('code')->addClass('language-html');

        // And a few other things
        $dom->filter('hr')->replaceWith('<div class="ui divider"></div>');
        //$dom->filter('ul')->addClass('ui list');
        //$dom->filter('ol')->addClass('ui list');

        $output = $dom->saveHTML();

        break;
}