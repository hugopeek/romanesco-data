#!/bin/bash

# ROMANESCO OPERATIONS
# ==============================================================================
#
# A collection of commands for updating and maintaining a Romanesco project.


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

version="1.0"
operationsPath="${DIR%/}"

# styling
RED="\033[0;31m"
GREEN="\033[1;32m"
YELLOW="\033[0;33m"
NC='\033[0m' #no color
BOLD=$(tput bold)
NORMAL=$(tput sgr0)

# local defaults
localUser=
phpUser=
phpVersion=
wwwPath=
gitifyPath=
modxVersion=
defaultUser=
defaultEmail=
gpmPath=
packagesPath=
installPath=

# load local variables from file
source "${operationsPath}/config.sh"

# command line defaults
updateFlag=

# collect command line arguments
while [[ "$1" ]]; do
  case $1 in
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
    source "${operationsPath}/run/00-help.sh"
    exit 0
    ;;
  update)
    updateFlag=1
    updateRomanesco=
    updateBackyard=
    npmFlag=
    defaultsFlag=
    while [[ "$2" ]]; do
      # move to next task
      if [[ "$2" == "and" ]]; then break; fi
      if [[ "$2" == "for" ]]; then break; fi
      case $2 in
      romanesco)
        updateRomanesco=1
        ;;
      backyard)
        updateBackyard=1
        ;;
      -n | --npm)
        npmFlag=1
        ;;
      -d | --defaults)
        defaultsFlag=1
        ;;
      '' | *)
        printf "${RED}Computer says no.${NC}\n"
        exit 0
        ;;
      esac
      shift
    done
    ;;
  esac
  shift
done

# flags for additional safety / control
updateMODX=1
updatePackages=1

# nested repositories
installPathData="$installPath/_romanesco"
installPathTheme="$installPath/assets/semantic/src/themes/romanesco"

# defaults source folder
defaultsPath="$installPathData/_defaults"

# Gitify command
gitifyCmd="$gitifyPath/Gitify"

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
  printf "${BOLD}Please define an installation path.${NORMAL}\n"
  printf "${RED}Abort.${NC}\n"
  exit 0
fi


# TASKS
# ==============================================================================

if [ "$updateFlag" ]
then
  echo "Updating Romanesco..."
  source "${operationsPath}/run/10-update.sh"
  printf "${GREEN}Romanesco successfully updated!${NORMAL}\n"
fi

echo "Bye"
exit