#!/bin/bash

# EXTRACT
# ==============================================================================
#
# Extract project data from your MODX installation, using Gitify.


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in parent script
set -u

# exit on any type of error
set -e


# Execute
# ==============================================================================

# extract contexts one by one
if [[ ! "${#extractContexts[@]}" -eq 0 ]]
then
  for context in "${extractContexts[@]}"
  do
    printf "%sExtracting %s...%s\n" "$BOLD" "$context" "$NORMAL"
    cd "$basePath/_contexts/$context/_gitify"
    ${gitifyCmd} extract --no-packages
  done
fi

# extract project elements
if [[ "$extractElements" ]] || [[ "$extractAll" ]]
then
  printf "%sExtracting project elements...%s\n" "$BOLD" "$NORMAL"
  cd "$basePath"
  ${gitifyCmd} extract --no-packages
fi
