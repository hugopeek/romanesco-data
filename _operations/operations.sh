#!/bin/bash

# ROMANESCO OPERATIONS
# ==============================================================================
#
# A collection of commands for updating and maintaining a Romanesco project.
# @todo: Use _contexts folder for extracting / building various parts separately.

# FUNCTIONS
# ==============================================================================

# return useful error on failure
function clarify() {
  exitCode="$?"
  # ignore exit code 0
  if [[ $exitCode -gt 0 ]] ; then
    echo "\"${last_command}\" command failed with exit code $exitCode."
  fi
}

# show list of contexts if requested
function listContexts() {
  if [[ "$2" == "--list-contexts" ]] ; then
    printf "%sAvailable contexts:%s\n" "$BOLD" "$NORMAL"
    for context in "${contexts[@]}"
    do
      echo "  $context"
    done
    exit
  fi
}


# CONFIG
# ==============================================================================

# determine absolute path of this file and chdir to it
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # loop until the file is no longer a symlink
  DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  # if $SOURCE was a relative symlink, we need to resolve it relative to the
  # path where the symlink file was located
  [[ "$SOURCE" != /* ]] && SOURCE="$DIR/$SOURCE"
done
DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
cd "$DIR"

# exit on any type of error
set -e

# keep track of the last executed command
trap 'last_command=$current_command; current_command=$BASH_COMMAND' DEBUG

# clarify errors on exit
trap clarify EXIT


# VARIABLES
# ==============================================================================

version="2.0.0"
operationsPath="${DIR%/}"

# styling
BLACK="$(tput setaf 0)"
RED="$(tput setaf 1)" #negative
GREEN="$(tput setaf 2)" #positive
YELLOW="$(tput setaf 3)" #warning
BLUE="$(tput setaf 4)" #variable
MAGENTA="$(tput setaf 5)" #dialogue
CYAN="$(tput setaf 6)" #info
WHITE="$(tput setaf 7)"
BOLD="$(tput bold)"
NORMAL="$(tput sgr0)"
NC="$NORMAL" #backward compatibility

# local defaults
localUser=
phpUser=
phpVersion=
wwwPath=
gitifyCmd=
modxVersion=
defaultUser=
defaultEmail=
gpmPath=
packagesPath=
installPath=

# load local variables from file
source "${operationsPath}/config.sh"

# paths
basePath="${installPath}"
dataPath="${basePath}/_romanesco"
themePath="${basePath}/assets/semantic/src/themes/romanesco"

# list of active contexts with a domain name attached
contextsDomain=(
  "web"
)

# list of contexts containing actual content
contexts=(
  "${contextsDomain[@]}"
  "global"
)

# dev contexts
contextsDev=(
  "hub"
)

# available flags
extractFlag=
buildFlag=
packageFlag=
generateFlag=
commitFlag=
testFlag=
pushFlag=
updateFlag=
deployFlag=

while [[ "$1" ]]
do case $1 in
  -v | --version)
    echo "$version"
    exit 0
    ;;
  -s | --no-syntax)
    echo "No syntax highlighting please."
    RED=
    GREEN=
    YELLOW=
    NC=
    BOLD=
    NORMAL=
    ;;
  -h | --help)
    source "$basePath/_operations/run/00-help.sh"
    exit 0
    ;;
  extract)
    extractFlag=1
    extractContexts=()
    extractElements=
    extractAll=
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi

      # contexts
      listContexts "$@"
      for context in "${contexts[@]}"
      do
        if [[ $context == "$2" ]]
        then
          extractContexts+=("$2")
        fi
      done

      # other subjects
      case $2 in
        content)
          extractContexts+=("${contexts[@]}")
          ;;
        elements)
          extractElements=1
          ;;
        hub)
          extractContexts+=("$2")
          ;;
        all)
          extractAll=1
          extractContexts+=("${contexts[@]}" "${contextsDev[@]}")
          extractElements=1
          ;;
      esac
      shift
    done
    ;;
  build)
    buildFlag=1
    buildContexts=()
    buildElements=
    buildAll=
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi

      # contexts
      listContexts "$@"
      for context in "${contexts[@]}"
      do
        if [[ $context == "$2" ]]
        then
          buildContexts+=("$2")
        fi
      done

      # other subjects
      case $2 in
        content)
          buildContexts+=("${contexts[@]}")
          ;;
        elements)
          buildElements=1
          ;;
        hub)
          buildContexts+=("$2")
          ;;
        all)
          buildAll=1
          buildContexts+=("${contexts[@]}" "${contextsDev[@]}")
          buildElements=1
          ;;
      esac
      shift
    done
    ;;
  package)
    packageFlag=1
    packageEarthBrain=
    packageForestBrain=
    packageAll=
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi
      case $2 in
        earthbrain)
          packageEarthBrain=1
          ;;
        forestbrain)
          packageForestBrain=1
          ;;
        all)
          packageAll=1
          ;;
      esac
      shift
    done
    ;;
  generate)
    generateFlag=1
    generateTasks=()
    generateAll=
    generateFor=()
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi
      case $2 in
        css)
          generateTasks+=("css")
          ;;
        js)
          generateTasks+=("javascript")
          ;;
        assets)
          generateTasks+=("assets")
          ;;
        all)
          generateAll=1
          generateTasks+=("all")
          ;;
      esac
      shift
      if [[ "$2" == "for" ]]
      then
        while [[ "$2" ]]
        do
          # contexts
          for context in "${contexts[@]}"
          do
            if [[ $context == "$3" ]]
            then
              generateFor+=("$3")
            fi
          done

          # all
          if [[ "$3" == "all" ]]
          then
            generateFor+=("${contextsDomain[@]}")
          fi
          shift
        done
      else
        echo -e "%sProvide at least one context!%s"
        exit 0
      fi
    done
    ;;
  update)
    updateFlag=1
    updatePatterns=
    updateBackyard=
    updateTheme=
    updateMODX=
    updatePackages=
    npmFlag=
    defaultsFlag=
    while [[ "$2" ]]; do
      # move to next task
      if [[ "$2" == "AND" ]]; then break; fi
      case $2 in
      patterns)
        updatePatterns=1
        ;;
      backyard)
        updateBackyard=1
        ;;
      theme)
        updateTheme=1
        ;;
      modx)
        updateMODX=1
        ;;
      packages)
        updatePackages=1
        ;;
      -n | --npm)
        npmFlag=1
        ;;
      -d | --defaults)
        defaultsFlag=1
        ;;
      '' | *)
        printf "%sComputer says no.%s\n" "$RED" "$NORMAL"
        exit 0
        ;;
      esac
      shift
    done
    ;;
  commit)
    commitFlag=1
    commitProject=
    commitAll=
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi
      case $2 in
        project)
          commitProject=1
          ;;
        all)
          commitAll=1
          ;;
      esac
      shift
    done
    ;;
  push)
    pushFlag=1
    pushProject=
    pushTheme=
    pushAll=
    pushTags=
    while [[ "$2" ]]
    do
      # move to next task
      if [[ "$2" == "AND" ]] ; then break ; fi
      case $2 in
        project)
          pushProject=1
          ;;
        theme)
          pushTheme=1
          ;;
        all)
          pushAll=1
          ;;
        --tags)
          pushTags=1
          ;;
      esac
      shift
    done
    ;;
  esac
  shift
done

# nested repositories
installPathData="$installPath/_romanesco"
installPathTheme="$installPath/assets/semantic/src/themes/romanesco"

# defaults source folder
defaultsPath="$installPathData/_defaults"

# Gitify command
if ! [ "$gitifyCmd" ]
then
  gitifyCmd="$operationsPath/vendor/bin/gitify"
fi

# GPM packages
gpmPath="${gpmPath%/}"
gpmRepos=(
  "$cbHeadingImagePath"
  "$romanescoBackyardPath"
  "$htmlPageDomPath"
  "$mailBlocksPath"
)
gpmProjects=(
  "cbheadingimage"
  "romanesco-backyard"
  "htmlpagedom"
  "mailblocks"
)
gpmPackages=()


# CHECKS
# ==============================================================================

# double check if installation path is defined
if ! [ "$installPath" ]
then
  printf "%sPlease define an installation path.%s\n" "$BOLD" "$NORMAL"
  printf "%sAbort.%s\n" "$RED" "$NORMAL"
  exit 0
fi


# TASKS
# ==============================================================================

if [ "$extractFlag" ]
then
  echo "Extracting data..."
  source "$basePath/_operations/run/10-extract.sh"
fi

if [ "$buildFlag" ]
then
  echo "Building data..."
  source "$basePath/_operations/run/20-build.sh"
fi

if [ "$packageFlag" ]
then
  echo "Building GPM packages..."
  source "$basePath/_operations/run/30-package.sh"
fi

if [ "$generateFlag" ]
then
  echo "Generating assets for selected contexts..."
  source "$basePath/_operations/run/40-generate.sh"
fi

if [ "$updateFlag" ]
then
  echo "Updating Romanesco..."
  source "$basePath/_operations/run/50-update.sh"
  printf "%sRomanesco successfully updated!%s\n" "$GREEN" "$NORMAL"
fi

if [ "$commitFlag" ]
then
  echo "Committing changes..."
  source "$basePath/_operations/run/60-commit.sh"
fi

if [ "$pushFlag" ]
then
  echo "Pushing changes to Gitlab..."
  source "$basePath/_operations/run/70-push.sh"
fi

echo "Time to let it grow."
exit 0