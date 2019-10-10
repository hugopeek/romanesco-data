# Changelog for the Romanesco Soil data files

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

- [Data] Add OpenGraph metadata to head
- [Content] Add snippet for clipping characters from start or end of string
- [Content] Add plugin for injecting inverted classes into content (requires HtmlPageDom)
- [Content] Add options for controlling footer and bottom CTA content
- [MODX] Include homepage in basic template list, so they also have Overview TVs
- [Overviews] Fix author image in compact article overview template
- [Overviews] Disable Disqus comment count in overviews (was acting buggy)
- [Build] Prevent decimals in calculated image dimensions from breaking variables file
- [Configuration] Allow overrides for head and footer chunks in all templates
- [FormBlocks] Fix issues when using multiple file upload fields in form
- [FormBlocks] Sort available forms by menuindex in Forms CB

## v1.3.1
Released on September 18, 2018

- [ContentBlocks] Add option to wrap CTAs in segment
- [ContentBlocks] Add size and layout_type settings to Quote CB
- [ContentBlocks] Add titles to button links
- [ContentBlocks] Change message size to generic field size setting and use for Quote
- [ContentBlocks] Add inverted layout type to Accordions
- [ContentBlocks] Fix empty subtitles returning as NULL in Tab headers
- [Overviews] Fix fallback image in Publication overviews
- [FormBlocks] Always set first value in form dropdown as empty default option
- [FormBlocks] Better explanations for Label position setting
- [FormBlocks] Fix caching of Select options (caching of nested tags changed in MODX 2.6)
- [MODX] Remove hideEmptyTVCategories plugin (hidden by default in MODX 2.6)

## v1.3.0
Released on July 27, 2018

- [MODX] Shorten element descriptions to 191 characters (for MODX 2.6)
- [Social] Add Github to social buttons (and some other small tweaks)
- [Data] Load Google Analytics if configuration / context setting is set
- [Backyard] Disable raw code view in pattern examples (this broke in MODX 2.6)
- [Backyard] Add setting to make project hub private (requires Login)

## v1.2.0
Released on June 6, 2018

- [Feedback] Add elements for implementing comments (based on Tickets)
- [Information] Add elements for creating a Knowledge Base
- [Overviews] Fix author images in Publication tpls
- [Backgrounds] Use (optional) portrait image on mobile
- [Backgrounds] Get rid of hardcoded responsive image dimensions
- [FormBlocks] Add option to change form size
- [FormBlocks] Add ability to insert your own submit button
- [FormBlocks] Use alternative value in dropdown if one is defined in CB
- [FormBlocks] Display error message under form field
- [Configuration] Add option to disable header
- [Configuration] Add option to disable or override toolbar

## v1.1.0
Released on January 16, 2018

- [Configuration] Add settings to change styling theme
- [ContentBlocks] Add Cards field
- [ContentBlocks] Disable markdown text CB
- [ContentBlocks] Control responsive column behavior in nested layouts
- [ContentBlocks] Change UI of Gallery repeater
- [ContentBlocks] Improvements to Accordion / Tabs field
- [Gitify] Include content types in build config
- [Gitify] Include plugin events in build config
- [Gitify] Exclude CB access policy for context
- [Gitify] Exclude gateway context settings
- [Gitify] Add .gitify for extracting CB edits only
- [Gitify] Add .gitify for installs that load patterns with GPM
- [Gitify] Include Gitify configuration files


## v1.0.0
Released on August 15, 2017

Initial release, after split from Romanesco Soil.