#!/bin/bash

# PUSH
# ==============================================================================
#
# Push changes to Github / Gitlab.


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in romanesco parent script
set -u

# exit on any type of error
set -e

# let's see if there's gonna be any action
action=


# Execute
# ==============================================================================

if [[ "$pushProject" ]] || [[ "$pushAll" ]]
then
  printf "%sPushing main repository...%s\n" "$BOLD" "$NORMAL"
  cd "$basePath" && git push origin

  if [[ "$pushTags" ]] ; then
    echo "Pushing tags..."
    cd "$basePath" && git push origin --tags
  fi
  action=1
fi

if [[ "$pushTheme" ]] || [[ "$pushAll" ]]
then
  printf "%sPushing Romanesco theme...%s\n" "$BOLD" "$NORMAL"
  cd "$themePath" && git push origin
  action=1
fi

if ! [[ "$action" ]]
then
  printf "%sWhat do you want me to push?%s\n" "$BOLD$MAGENTA" "$NORMAL"
  exit 1
fi
