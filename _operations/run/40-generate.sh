#!/bin/bash

# GENERATE
# ==============================================================================
#
# Generate frontend assets (CSS, JS, images, etc) and place them in appropriate
# dist folders.


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in parent script
set -u

# exit on any type of error
set -e

# define path to gulpfile
gulpfilePath="$basePath/assets/components/romanescobackyard/js/gulp/generate-multicontext-css.js"
distPath="$basePath/assets/dist"

# Execute
# ==============================================================================

# build Semantic UI assets
if [[ ! "${#generateFor[@]}" -eq 0 ]]
then
  for context in "${generateFor[@]}"
  do
    for task in "${generateTasks[@]}"
    do
      printf "%sBuilding %s for %s...%s\n" "$BOLD" "$task" "$context" "$NORMAL"
      gulp build-context --gulpfile "$gulpfilePath" --key "$context" --task "$task" --dist "$distPath/$context"
    done
  done
fi