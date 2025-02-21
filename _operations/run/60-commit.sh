#!/bin/bash

# COMMIT
# ==============================================================================
#
# Commit changes to Git.


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in parent script
set -u

# exit on any type of error
set -e

# let's see if there's gonna be any action
action=


# Execute
# ==============================================================================

if [[ "$commitProject" ]] || [[ "$commitAll" ]]
then
  printf "%sCommitting changes in main repository...%s\n" "$BOLD" "$NORMAL"
  cd "$basePath" && git add -A
  cd "$basePath" && git commit -m "ROMANESCO: Extract data" 2>&1 || true
  action=1
fi

if ! [[ "$action" ]]
then
  printf "%sWhat do you want me to commit?%s\n" "$BOLD$MAGENTA" "$NORMAL"
  exit 1
fi
