#!/bin/bash

# PACKAGE
# ==============================================================================
#
# Instruct GPM (Git Package Management) to build a new release package.
# Make sure you update the package version and changelog upfront!


# Config
# ==============================================================================

# exit if variable was not passed
# variables are set in parent script
set -u

# exit on any type of error
set -e


# Execute
# ==============================================================================

# safety first
cd "$basePath" && "${gitifyCmd}" backup

# build EarthBrain GPM package
if [[ "$packageEarthBrain" ]] || [[ "$packageAll" ]]
then
  echo -e "${BOLD}Building EarthBrain GPM package...${NORMAL}"
  cd "$earthBrainPath/_build/"
  php merge-json.php
  php "$basePath/packages/gpm/cli/bin/gpm" package:build --pkg=earthbrain
fi

# build ForestBrain GPM package
if [[ "$packageForestBrain" ]] || [[ "$packageAll" ]]
then
  echo -e "${BOLD}Building ForestBrain GPM package...${NORMAL}"
  cd "$forestBrainPath/_build/"
  php merge-json.php
  php "$basePath/packages/gpm/cli/bin/gpm" package:build --pkg=forestbrain
fi
