#!/bin/bash

# BUILD
# ==============================================================================
#
# Update your MODX installation with the latest project data, using Gitify.


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in parent script
set -u

# exit on any type of error
set -e


# Execute
# ==============================================================================

# build contexts one by one
if [[ ! "${#buildContexts[@]}" -eq 0 ]]
then
  for context in "${buildContexts[@]}"
  do
    printf "%sBuilding context %s...%s\n" "$BOLD" "$context" "$NORMAL"
    cd "$basePath/_contexts/$context/_gitify"
    ${gitifyCmd} backup
    ${gitifyCmd} build
  done
fi

# build project elements
if [[ "$buildElements" ]] || [[ "$buildAll" ]]
then
  printf "%sBuilding project elements...%s\n" "$BOLD" "$NORMAL"
  cd "$basePath"
  ${gitifyCmd} backup
  ${gitifyCmd} build
fi
