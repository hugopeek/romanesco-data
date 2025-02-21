#!/bin/bash

# exit if variable was not passed
set -u

# exit on any type of error
set -e

# USAGE
# ==============================================================================

cat <<EOF
CLI version ${version}

${BOLD}Usage:${NORMAL}
  ${GREEN}Usage: ./romanesco [TASK] [SUBJECT] [--OPTION]${NORMAL}

${BOLD}Examples:${NORMAL}
  ${CYAN}./romanesco update patterns${NORMAL}

  You can define multiple subjects per task:
  ${CYAN}./romanesco build elements global hub${NORMAL}

  You can also chain tasks together with AND:
  ${CYAN}./romanesco extract all AND commit project${NORMAL}

${BOLD}Contexts:${NORMAL}
${BLUE}$(for context in "${contextsDomain[@]}"; do echo "  $context"; done)${NORMAL}

${BOLD}Subjects:${NORMAL}
  [CONTEXT]                       Content and settings from given context(s)
  global                          Backgrounds, footers, forms, CTAs and tags
  content                         All contexts + global + project data
  elements                        Templates, TVs, chunks, snippets, plugins, etc
  hub                             Everything inside the Project Hub
  all                             Everything

${BOLD}Tasks and options:${NORMAL}

  ${BOLD}extract${NORMAL}
    [SUBJECT]                     Extract data from given subject(s) with Gitify

  ${BOLD}build${NORMAL}
    [SUBJECT]                     Build data from given subject(s) with Gitify

  ${BOLD}generate${NORMAL}
    css                           gulp build-css
    js                            gulp build-javascript
    assets                        gulp build-assets
    all                           gulp build
    ${BOLD}for${NORMAL} [CONTEXT]

  ${BOLD}update${NORMAL}
    patterns                      Update Romanesco pattern library
    backyard                      Update Backyard resources in project hub
    theme                         Update Fomantic UI Romanesco theme
    modx                          Upgrade MODX to version ${BLUE}${modxVersion}${NORMAL}
    packages                      Update all packages listed in .gitify
    all                           Update all of the above
    -n|--npm                      + run npm update ${YELLOW}(One does not simply...)${NORMAL}
    -d|--defaults                 + import Romanesco Soil updates ${RED}(RISKY!)${NORMAL}

  ${BOLD}commit${NORMAL}
    project                       Main repository
    all                           Everything

  ${BOLD}push${NORMAL}
    project                       Main repository
    theme                         Romanesco styling theme
    all                           Everything
    --tags                        + tags
EOF
