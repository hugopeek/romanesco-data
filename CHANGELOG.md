# Changelog for the Romanesco Soil data files

## v1.12.1
Released on ...

New features:
- Add post hook for pThumb to optimize the image and generate a WebP version
- Add ContentBlock with Table of Contents menu

Fixes and improvements:
- Don't remove rows in mobile reversed grids (breaks multi-row nested layouts)
- Fix responsive content image sizes in stackable on tablet grids
- If grids are stackable on tablet, also hide designated mobile elements
- Use placeholder to detect ToC instead of toc_enabled_templates system setting
- Show ToC submenu in dropdown on tablet and mobile
- Move shared overview settings to separate molecules
- Fix regClient scripts not being parsed in Markdown pages on first load
- Load external JS from head with defer
- Load all conditional assets through a single snippet
- Don't use tag label variant anywhere (excluded from default semantic.css)
- Add SUI list class to ul and ol tags in Markdown (only at first level)
- Remove conditionals for empty logo paths (to clear media source path)
- Add theme variables for inverted logo paths
- Only load alternative_tracking_code if Google Analytics is empty
- Rename matomo_tracking_code to alternative_tracking_code
- Move Leaflet asset paths and integrity hashes to system settings
- Move img_breakpoints to system settings
- Add pThumb post-hook to system settings (customization in pThumb core)
- Add img_quality as system setting too (baseline for optional post-processing)
- Fix icon buttons in generated tab navigation
- Only preload backgrounds.css if critical CSS is enabled
- Add missing ID to FormBlocks dropdown select and math question fields
- Add lazy load setting to Overview Fluid layout

## v1.12.0
Released on August 30, 2021

New features:
- Use Markdown content with any template
- Add operations script with update command
- Integrate MailBlocks (as stand-alone package)

Fixes and improvements:
- Update Backyard
- Rename some CB fields and layouts to be more uniform
- Fix lists with connected patterns in front-end library
- Add class to empty grid columns
- Load conditional CSS assets and Google fonts asynchronously
- Load Google fonts with direct link and v2 syntax [BREAKING CHANGE]
- Add cbLayoutCTA chunk (not referenced directly)
- Make sure width or height is not 0 in fixed overview and gallery images
- Fix scenario where mobile-only slider would not return to original state
- Fix issues with slides not receiving correct width when initialized
- Add Collections template for Notes
- Rename toolbarKnowledgeBase to toolbarNote
- Rename HeaderVertical templates to BasicVertical
- Merge NoteMarkdown and NoteContentBlocks templates
- Add option to show pages with hidden alias in breadcrumbs
- Rename cbOverviewRowImg chunks to imgOverview [BREAKING CHANGE]
- Fix critical CSS not being shared between templates (if configured)
- Set pagetitle as fallback for overview image ALT text
- Exclude all menus from Gitify except Configuration
- Include defaults from Soil

## v1.11.1
Released on June 10, 2021

> Romanesco Patterns 1.0.0-beta5

Fixes and improvements:
- Pin MIGX to 2.13.0-pl in Gitify configs
- Set default responsive image scaling value to 51
- Fix email not being sent when using dynamic emailTo (and empty regular field)
- Fix required label of collapsible select option in FormBlocks
- Add 'open in new window' option to Image with Link CB
- Make sure placeholder prefixes are unique when generating background CSS
- Properly display MODX outer tags from Markdown files (if they've been split)
- Add option to generate critical CSS for pages behind htpasswd wall
- Fix returnFirstHit snippet skipping over hits
- Add gallery lightbox after the footer (and not inside)
- Make Fibonacci sequence generator more flexible
- Let MODX clear overview cache if custom cache is disabled

## v1.11.0
Released on April 19, 2021

> Romanesco Patterns 1.0.0-beta4

New features:
- Add ability to schedule critical CSS generation
- Look for masthead chunk in header
- Add TV for overriding subtitle in Overviews
- Implement cache buster for static assets
- Add output modifier for replacing regex pattern

Fixes and improvements:
- Preload critical CSS file from HTML (disable HTTP/2 server push)
- Allow theme override for structured organization data
- Don't render theme chunks before checking if they exist
- Add iselement condition to modifiedIf
- Also manipulate DOM of Markdown resources
- Remove nested block level elements from heading tags
- Move most common subtitles out of heading tags
- Rename all headingHierarchy chunks to headingOverview [BREAKING CHANGE]
- Remove YAML front matter from Markdown notes
- Accept semantic version numbers for generated styling assets
- Fix broken Gitify configuration for Collections tables
- Move off-canvas navigation above content wrapper to prevent redraw in JS
- Add warning not to edit site.css directly
- Don't display globally excluded resources in Overviews either
- Prevent bad link tag errors from CTAs that don't have a link

## v1.10.0
Released on February 20, 2021

> Romanesco Patterns 1.0.0-beta3

New features:
- Load patterns dynamically in front-end library
- Add ContentBlock for displaying Vimeo video
- Add preview and status properties to all elements

Fixes and improvements:
- Add layout and styling options to Table CB
- Enable lazy load by default in image and overview CBs
- Fix a bunch of default settings in CB fields
- Rename content_types data folder to content_type in Gitify config
- Simplify context policy settings and remove Redactor policies
- Center copyright footer content on mobile
- Add small credits badge with information popup to copyright footer
- Skip own element properties in returnFirstHit snippet
- Add option to adjust column size to content width in CB settings
- Scroll back to top after using pagination in overviews (can be disabled)
- Only display avatar image in publication elements if author page exists
- Add overview image template for ImagePlus with fixed dimensions
- Don't show hidden forms, CTAs, backgrounds and footers in CB selectors
- Fix behaviour and styling glitches in FormBlocks checkbox / radio fields
- Fix footer CTA backgrounds referenced by ID
- Make sure unique_idx placeholder in overviews is actually unique
- Add system setting for defining templates with ToC
- Load Leaflet map assets via chunk
- Add ability to turn Cards CB into a slider
- Fix quality placeholder not being forwarded to responsive images srcsets
- Modify getElementDescription snippet to get other fields or property values
- Add column for hidemenu value in FormBlocks collection view
- Fix issue with special characters in FormBlocks select option help texts
- Add option to customize the list of CB layout backgrounds
- Remove resource-based theme and background CSS from Backyard
- Add raw placeholder chunks for tag and label
- Change HTML tag of footer CTA from footer to aside
- Load SUI form component asynchronous if critical CSS is enabled
- Fix issue where processor sometimes used stale alias in critical CSS snippet
- Save resource in critical CSS snippet, to generate TV value with file path
- Generate critical CSS with correct multi-context Configuration settings
- Move collections_settings table to defaults in Gitify configs
- Correct depth and offset placeholders in overviews

## v1.9.1
Released on November 3, 2020

> Romanesco Patterns 1.0.0-beta2

Fixes and improvements:
- Remove head tag from head chunk [BREAKING CHANGE]
- Use Matomo by inserting full tracking code directly
- Make background in note templates transparent
- Fix leaking placeholders in global backgrounds CSS
- Fall back gracefully when critical CSS file not found
- Store full path to critical CSS file in a hidden TV
- Don't use minified SUI / project assets if Configuration setting is off
- Fix cols placeholder in overviews
- Update Gitify system settings exclusions
- Prevent build from erasing custom lexicon entries
- Limit overview grids to 2 rows in manager previews
- Accept additional responsive image scales
- Allow templates to override the critical CSS file of the page
- Add option to generate critical CSS in sequence instead of parallel
- Handle context aware configuration settings in critical CSS plugin
- Disable deprecated global backgrounds using MIGX TV

## v1.9.0
Released on August 27, 2020

> Romanesco Patterns 1.0.0-beta1

New features:
- Add ability to generate critical CSS for each resource

Fixes and improvements:
- Prevent empty logo path in Configuration from triggering a CSS rebuild
- Fix placeholder for due date in status grid
- Accept overview fallback images for each image type
- Don't transform input to lowercase in removeDuplicateLines snippet
- Don't add header class to content headers if they already have one
- Replace hardcoded Menu text in main nav with lexicon key
- Add classes with placeholder in main accordion navigation
- Fix background in article template introduction segment
- Fix path to default context CSS in GenerateStaticCSS
- Add ability to place slider controls outside of container
- Turn lightbox into fullscreen modal and lazy load images
- Make Gallery images sizes responsive
- Add option to lazy load Gallery images
- Add link rel options to Button and Image CBs
- Fix path to custom context CSS in GenerateStaticCSS

## v1.8.4
Released on July 1, 2020

> Romanesco Patterns 0.16.4-pl

Fixes and improvements:
- Refactor main accordion menu
- Remove deleted patterns from extract
- Add option to change main menu size
- Fix broken favicon file names and colors
- Add setting for custom favicon path
- Add option to activate FormSource in FormBlocks reports
- Add ability to change custom CSS path (per context, if needed)
- Create absolute path for CSS backgrounds that won't be thumbnailed
- Fix CSS background not being thumbnailed due to negative crop margins
- Rename Semantic assets paths in system settings
- Correctly escape double quote and backslash characters in JSON-LD output

## v1.8.3
Released on June 15, 2020

> Romanesco Patterns 0.16.3-pl

Fixes and improvements:
- Add scrolldir.js for displaying menu on mobile when scrolling up
- Add ability to override headerTitles chunk
- Load CB image settings with chunks
- Add caption and credits to Image CB
- Add positioning and sizing options to Image CB
- Point to separate gulpfile when generating CSS per context
- Update Jquery to v3.5.1
- Fix static downloads not having any content
- Write theme.variables output directly to file
- Make UpdateStyling plugin work with context-aware Configuration settings
- Fix form redirectTo placeholder not always returning correct value
- Break comma separated lists into rows in form emails
- Fix submission of 'other' values in all forms
- Look for menu title first when adding multi-step form headings
- Fix sort order of field data in multi-step form emails
- Fix reapplying checkbox values and 'other' fields in multi-step forms
- Add row templates for autogenerated form fields based on input options
- Fix faulty multi-step placeholder in form CB
- Disable TinyMCE in CB headings to prevent unwanted <br> tags
- Add context-aware Configuration settings to project .gitify
- Add rawAlias placeholder chunk
- Fix heading level placeholder in Accordion
- Add Redactor v3 configuration sets to Gitify
- Exclude analytics keys and dev configuration settings from project .gitify
- Load custom form assets through fbLoadAssets (if present)

## v1.8.2
Released on April 20, 2020

> Romanesco Patterns 0.16.2-pl

New features:
- Add CB for displaying FAQs with structured markup

Fixes and improvements:
- Add option to embed Youtube video directly
- Add option to manually correct aspect ratio of Youtube embed
- Ensure highest resolution thumbnail is fetched for Youtube embed placeholder
- Switch to JSON-LD for structured breadcrumbs data
- Improve structured data JSON-LD snippet in head
- Reorganize data elements folder structure
- Use uniform placeholders in Accordions, Cards and Tabs
- Add heading level to accordions
- Don't let ToC menu include headings outside of content area
- Add Collections view with ArticleReadability template as default

## v1.8.1
Released on April 14, 2020

> Romanesco Patterns 0.16.1-pl

Fixes and improvements:
- Inherit comment toggle and article author TV values
- Prevent background from appearing if responsive crop is not defined
- Improve layout of publication templates
- Add button templates for creating neighbor menu
- Add class to content headers without class name
- Conditionally load assets for registration forms
- Remove form class from search field
- Implement multi-page form functionality into FormBlocks
- Add options to adjust type, size and alignment of form buttons
- Fix finding value in multidimensional array with jsonGetValue
- Add first and last placeholders to splitString snippet
- Add class to video embed, to initialize them separately from other embeds

## v1.8.0
Released on March 30, 2020

> Patterns: 0.16.0-pl

New features:
- Add ability to create multi page forms
- Add social connect button for WhatsApp
- Add social share button for sharing URLs via email
- Add steps navigation to show the completion status of an activity
- Add TV input option for selecting a country (or countries)
- Add pagination type setting to Overviews

Fixes and improvements:
- Load CSS/JS assets for modal, step and form components only if used on page
- Add ability to identify the last placeholder of splitString output
- Fix duplicated Backyard pages after updating Romanesco due to different URI
- Change CB link detection pattern to accept internal links starting with `[[~`
- Add TV to control save form option per form
- Remove Google+ elements
- Make sure spam submissions will also fail on 2nd attempt in forms
- Switch to fbFormReport for generating email messages
- Grab required form fields directly from CB properties array for validation
- Add field template for input options to form dropdowns and options
- Add multiple select option to form dropdowns
- Add option to center align buttons individually
- Call setUserPlaceholders cached in article overviews
- Improve layout and visibility of sharing widget in articleTraditional sidebar
- Make header backgrounds context sensitive in generated CSS
- Regenerate static CSS when changing a header background
- Remove orphaned files from extract
- Fix timeline in project hub by referencing the correct classname
- Fix position of availability TVs in Background and CTA form customizations

## v1.7.3
Released on March 10, 2020

> Patterns: 0.15.3-pl

Fixes and improvements:
- Allow using a Global Background image without defining any crops
- Set higher event priority for generateStaticCSS plugin
- Prevent fatal error in jsonGetObject when JSON input is not present or invalid

## v1.7.2
Released on March 7, 2020

> Patterns: 0.15.2-pl

Fixes and improvements:
- Move media sources to defaults (in romanesco-soil)
- Add keyboard control to slider
- Switch to Swiper in Presentation template
- Use medium class instead of empty value for field sizes
- Don't initialize slider if there aren't enough slides
- Fix calculation of max thumb height in lazy load placeholders
- Fix lazy loading of images in slider
- Add system default setting for CB layout backgrounds
- Add non-white class to body if custom background is set
- Fix property sets in pagination icon chunks

## v1.7.1
Released on January 29, 2020

> Patterns: 0.15.1-pl

New features:
- Add license (GPL-v3)

Fixes and improvements:
- Improve styling of off-canvas mobile menu
- Replace Slick slider with Swiper
- Move Overview pagination wrapper to chunk tpl

## v1.7.0
Released on January 14, 2020

> Patterns: 0.15.0-pl

New features:
- Add layout for creating sliders with content elements
- Add template for creating presentations
- Add pagination with AJAX support
- Add lazy loading to Image CB and image overviews

Fixes and improvements:
- Rename slider classes to avoid collisions with new FUI slider module
- Convert new lines to line breaks in Quotes
- Add suffix with version number to main CSS and JS assets
- Optimize caching of chunks in Overview templates
- Generate responsive content images with srcset and sizes
- Tweak form customizations for Backgrounds and CTAs
- Add round and removeDuplicateLines snippets
- Use alias to specify ID in form template
- Use system setting to specify title format in head
- Manage background availability inside selectors
- Generate static CSS file with Global Backgrounds (per context if needed)
- Reference Global Backgrounds by template ID when loading custom CSS
- Add system settings for Mapbox username and style_id

## v1.6.5
Released on December 17, 2019

> Patterns: 0.14.6-pl

New features:
- Add Date and Date Range fields to FormBlocks

Fixes and improvements:
- Add test form with all elements to Backyard
- Fix special characters breaking FB option labels
- Optimize FormBlocks validation processing
- Improve inheritance of FormBlocks label position settings
- Load home breadcrumb with tpl chunk
- Fix structured data errors in breadcrumbs
- Fix field size setting in Message CB
- Fix illegal regex sequences generating warnings in PHP > 7.1

## v1.6.4
Released on November 19, 2019

> Patterns: 0.14.6-pl

New features:
- Add jsonGetObject snippet for templating JSON output with chunks
- Add ability to center content when stacked on mobile or tablet
- Add circular button option
- Add circular and bordered image options

Fixes and improvements:
- Refactor Global Backgrounds component
- Prevent custom media source elements from being removed on update
- Add ability to search for multiple instances of key in jsonGetValue
- Fix fallback icon in avatar when an article has no author
- Fix memory exhausted issues when rebuilding content with ContentBlocks
- Fix visibility toggles in front-end pattern library
- Limit reverse column order setting to mobile only
- Rename InjectInvertedClasses plugin to ManipulateDOM
- Rename MarkdownMimeType plugin to ProcessMarkdown
- Remove rows from grids that have a reversed column order on mobile
- Remove .md extension from Markdown links and turn them into button if desired
- Add language class to Markdown code blocks that do not specify a language
- Turn Markdown tables into Semantic UI tables

## v1.6.3
Released on October 12, 2019

Hotfix: Forward prefix to setBoxTypeTheme snippet

## v1.6.2
Released on October 10, 2019

New features:
- Integrate visual regression tests (with BackstopJS)
- Add snippet for creating static HTML file of resource
- Add responsive options to main layouts and overviews

Fixes and improvements:
- Fix incorrect syntax in Google webfont requests
- Define exact aspect ratio for Overview images
- Add inverted class to logo in vertical menu
- Fix image URLs and display size in Markdown output
- Mute rogue path output in manager for Redactor too
- Remove http:// in Youtube embed placeholder URL
- Update resourceTVInputOptions to respect possible context settings
- Fix broken avatar image in compact article overviews
- Fix incorrect path in CSS to global backgrounds SVG
- Correctly retrieve (possible) context setting for FormBlocks container ID
- Correctly retrieve (possible) context setting for CB and TV options
- Load full off-canvas navigation if main menu is not a dropdown menu

## v1.6.1
Released on July 16, 2019

New features:
- Allow credits to be added to an image or icon
- Add Free variant to Overview images (no fixed aspect ratio)
- Add Commento as commenting option

Fixes and improvements:
- Isolate content images and increase the distance from element below
- Show top level parent in vertical sub navigation
- Add alignment option to all Overview CBs
- Add text_size, show_subtitle and show_rating options to Testimonial overviews
- Make overviewRowImageBasic template more basic
- Improve sorting in Overviews (reverse sort direction, alphabetic sort order)
- Add basic icon chunk
- Add tertiary button style (Fomantic UI feature)
- Add option to place button on new line
- Fix issue with rogue 0 output from getImageDimensions breaking SUI build
- Fix quirk where TVs couldn't be rendered in layouts anymore
- Prevent leaking of data from srcset placeholder in overview images
- Allow theme additions to global backgrounds
- Return after a setBoxType override was found
- Lower minimum width for all image TVs
- Apply img_quality configuration setting to all images
- Only load certain assets (CSS/JS) when they are needed
- Small caching optimizations in Overview templates
- Rename and refactor Knowledge Base into Notes
- Tickets integration is now deprecated
- Change base_url for Icon media source, so they work in manager previews
- Add access policy for developers
- Include custom lexicon entries in extract
- Replace wildcard filter for project IDs with regex search in Gitify configs

## v1.6.0
Released on April 15, 2019

New features:
- Backyard resources are now part of this repository

Fixes and improvements:
- Prevent MIGXdb fields with default value of NULL from being set to 0
- Allow otherwise duplicate TV category names to be prefixed with _ in projects
- Hide some CB elements that should only be used by admins
- Add option to embed Google Analytics with gtag.js
- Add option to embed Matomo Analytics
- Fix not being able to set image type in Publication and Portfolio overviews
- Fix binary download types (such as PDFs) not having content
- Fix Global Backgrounds TV not loading its MIGX config from file
- Use nvm-exec to run Gulp from PHP (prevents gulp not found errors)
- Add fullname parameter to Registration template
- Point to correct math validator in Registration template
- Add empty error message div to forms (for SUI front-end validation)
- Allow recipient email TV to be empty in forms (i.e. when using a custom hook)
- Fix inheritance of form label layout settings
- Add label to honeypot fields
- Only load Youtube videos after play button is clicked
- Include all manager top menu items in extract

## v1.5.1
Released on February 11, 2019

New features:
- Add TV input option for selecting Fibonacci numbers
- Add math question anti-spam option to forms
- Load Semantic UI styles inside CB preview containers

Fixes and improvements:
- Rearrange snippet folders and import a few new ones from projects
- Fix Overview headings displayed as regular links being too large
- Fix Registration template not validating password correctly
- Fix Registration template not containing FormBlocks CBs
- Exclude resources with unchecked "Use alias in URI" from breadcrumbs
- Make icons work inside CB chunk previews
- Make check for detecting SeoTab plugin watertight

## v1.5.0
Released on January 21, 2019

New features:
- Add main navigation with dropdown submenus
- Add template with Table of Contents menu (instead of submenu)
- Add template for Downloads
- Add Kanban layout for Content Purpose elements

Fixes and improvements:
- Update status grid to incorporate new / altered TV values
- Add optional anti-spam hook to forms
- Add option to select background for rich text segments too

## v1.4.0
Released on November 15, 2018

New features:
- Add TVs for external links, file attachments and related content
- Add ability to create input options
- Add ability to create crosslinks between resources
- Add re-purpose component, for creating content "flows" inside a central topic
- Add after save hooks for MIGXdb configs
- Add JSON import for input options

Fixes and improvements:
- Add chunk for dynamically generating TV input options from database rows
- Load project timeline through Backyard package and store data in db
- Rearrange TV categories and add rank
- Replace Grunt task for generating GPM config with PHP script
- Make tvToJSON output suitable for use in GPM configs
- Disable CSS background images for Tiled overviews
- Fix sidebar not showing on largest screen on Team and Client pages
- Fix link in Instagram social button

## v1.3.2
Released on October 4, 2018

- Add OpenGraph metadata to head
- Add snippet for clipping characters from start or end of string
- Add plugin for injecting inverted classes into content (requires HtmlPageDom)
- Add options for controlling footer and bottom CTA content
- Include homepage in basic template list, so they also have Overview TVs
- Fix author image in compact article overview template
- Disable Disqus comment count in overviews (was acting buggy)
- Prevent decimals in calculated image dimensions from breaking variables file
- Allow overrides for head and footer chunks in all templates
- Fix issues when using multiple file upload fields in form
- Sort available forms by menuindex in Forms CB

## v1.3.1
Released on September 18, 2018

- Add option to wrap CTAs in segment
- Add size and layout_type settings to Quote CB
- Add titles to button links
- Change message size to generic field size setting and use for Quote
- Add inverted layout type to Accordions
- Fix empty subtitles returning as NULL in Tab headers
- Fix fallback image in Publication overviews
- Always set first value in form dropdown as empty default option
- Better explanations for Label position setting
- Fix caching of Select options (caching of nested tags changed in MODX 2.6)
- Remove hideEmptyTVCategories plugin (hidden by default in MODX 2.6)

## v1.3.0
Released on July 27, 2018

- Shorten element descriptions to 191 characters (for MODX 2.6)
- Add Github to social buttons (and some other small tweaks)
- Load Google Analytics if configuration / context setting is set
- Disable raw code view in pattern examples (this broke in MODX 2.6)
- Add setting to make project hub private (requires Login)

## v1.2.0
Released on June 6, 2018

- Add elements for implementing comments (based on Tickets)
- Add elements for creating a Knowledge Base
- Fix author images in Publication tpls
- Use (optional) portrait image on mobile
- Get rid of hardcoded responsive image dimensions
- Add option to change form size
- Add ability to insert your own submit button
- Use alternative value in dropdown if one is defined in CB
- Display error message under form field
- Add option to disable header
- Add option to disable or override toolbar

## v1.1.0
Released on January 16, 2018

- Add settings to change styling theme
- Add Cards field
- Disable markdown text CB
- Control responsive column behavior in nested layouts
- Change UI of Gallery repeater
- Improvements to Accordion / Tabs field
- Include content types in build config
- Include plugin events in build config
- Exclude CB access policy for context
- Exclude gateway context settings
- Add .gitify for extracting CB edits only
- Add .gitify for installs that load patterns with GPM
- Include Gitify configuration files


## v1.0.0
Released on August 15, 2017

Initial release, after split from Romanesco Soil.