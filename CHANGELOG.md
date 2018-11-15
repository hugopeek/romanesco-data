# Changelog for the Romanesco Soil data files

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