#!/bin/bash

# exit if variable was not passed
# variables are set in romanesco parent script
set -u

# exit on any type of error
set -e


# EXECUTE
# ==============================================================================

# pull latest changes from repositories
cd "$installPathData" && git pull origin master
cd "$installPathTheme" && git pull origin master

# go to project root
cd "$installPath"

# backup and extract current state of project
printf "${BOLD}Extracting project data...${NORMAL}\n"
$gitifyCmd backup "$(date +'%Y-%m-%dT%H%M%S')"_INSTALLER
$gitifyCmd extract --no-packages

# commit changes, if there are any
if [[ -n $(cd ${installPath} && git status -s) ]]
then
  printf "${BOLD}Committing latest project edits...${NORMAL}\n"
  git add -A
  git commit -m "INSTALLER: Extract project edits"
fi

# update MODX
if [ "$updateMODX" ]
then
  printf "${BOLD}Updating MODX...${NORMAL}\n"
  $gitifyCmd modx:upgrade ${modxVersion}
fi

# update packages
if [ "$updatePackages" ]
then
  printf "${BOLD}Updating installed extras...${NORMAL}\n"
  $gitifyCmd package:install --all
fi

# update Backyard
if [ "$updateBackyard" ]
then
  printf "${BOLD}Updating Backyard resources...${NORMAL}\n"

  # preserve current resource IDs and parent IDs
  tmpFile="$(mktemp)"
  printf "${BOLD}Generating temporary patch file: $tmpFile${NORMAL}\n"
  cd "$installPath"
  php "${operationsPath}/tools/preserve_ids.php" build _data/content ${tmpFile}

  # copy Backyard resources to data folder
  rsync -av "$installPath/_romanesco/_backyard/update/content" "$installPath/_data/"

  # apply old resource IDs and parent IDs to upgraded resources
  php "${operationsPath}/tools/preserve_ids.php" apply _data/content ${tmpFile}

  # build with --no-cleanup flag, to ensure no data gets erased
  $gitifyCmd build --no-cleanup content
fi

# update Romanesco
if [ "$updateRomanesco" ]
then
  # check if local Romanesco dependencies were updated
  if [ "$gpmPath" ]
  then
    echo "Checking dependencies..."

    # clone / update source repositories
    i="0"
    for repository in "${gpmRepos[@]}"
    do
      project=${gpmProjects[$i]}
      if ! [ -d "$gpmPath/$project" ] ; then
        git clone "$repository" "$gpmPath/$project"
      else
        cd "$gpmPath/$project" && git pull
      fi
      i=$(($i+1))
    done

    # grab the latest package versions
    for project in "${gpmProjects[@]}"
    do
      pkgFolder="$gpmPath/$project/_packages"
      pkgVersion=$(ls -v ${pkgFolder} | tail -n 1)
      gpmPackages+=("${pkgFolder}/$pkgVersion")
    done
  fi

  printf "${BOLD}Updating dependencies...${NORMAL}\n"
  cd "$installPath"
  $gitifyCmd package:install --local

  # build
  printf "${BOLD}Updating Romanesco elements...${NORMAL}\n"
  cd "$installPathData/_gitify/build/romanesco"
  $gitifyCmd build
fi

# update default settings
if [ "$defaultsFlag" ] && [ "$defaultsPath" ]
then
  printf "${BOLD}Updating default settings...${NORMAL}\n"

  # import updated defaults
  rm -rf "$installPath/_defaults"
  rsync -av "$defaultsPath"/ "$installPath/_defaults"

  # check for changes and commit
  if [ -n "$(cd ${installPath} && git diff --exit-code)" ] ; then
    cd "$installPath"
    git add -A
    git commit -m "INSTALLER: Import latest default settings"
  fi

  # check if any of the default settings were changed inside the project
  # to do this, copy the defaults to the _data folder first and list the differences with git
  rsync -a "$installPath/_defaults/" "$installPath/_data"

  gitDiff="$(cd ${installPath} && git diff --no-ext-diff | grep --count -e 'value:')"
  gitDiffList="$(cd ${installPath} && git diff --no-ext-diff --name-only -i -G 'value:' | while read line ; do echo $line ; done)"

  # we have a list of changes now, but we'll reset them again to preserve project values
  # this will leave new settings alone, as they are untracked files
  cd "$installPath"
  git reset --hard HEAD

  # prevent project values from being overwritten
  if [[ "$gitDiff" -ne 0 ]]
  then
    printf "${YELLOW}Preventing project values from being overridden...${NC}\n"
    echo "Protecting values in:"

    # insert project values into _defaults files
    for line in $gitDiffList
    do
      cd "$installPath"
      sed -i '/value/d' "${line//_data/_defaults}" #delete existing
      sed -n '/value/p' "$line" >> "${line//_data/_defaults}" #write new
      echo "$line"
    done

    echo "Project values secured."
  fi

  # mix unique project settings with updated defaults
  rsync -aP --ignore-existing "$installPath/_data/cg_groups/" "$installPath/_defaults/cg_groups"
  rsync -aP --ignore-existing "$installPath/_data/cg_settings/" "$installPath/_defaults/cg_settings"
  rsync -aP --ignore-existing "$installPath/_data/content_type/" "$installPath/_defaults/content_type"
  rsync -aP --ignore-existing "$installPath/_data/system_settings/" "$installPath/_defaults/system_settings"

  # build & commit
  cd "$installPathData/_gitify/build/defaults"
  $gitifyCmd build
  cd "$installPath"
  git add -A
  git commit -m "INSTALLER: Update Romanesco default settings"
fi

# run NPM updates
if [ "$npmFlag" ]
then
  printf "${BOLD}Updating Romanesco styling theme...${NORMAL}\n"

  rsync -a "${gitPathSoil//.git/}semantic.json" "$installPath"
  cd "$installPath"
  npm update
  echo "NPM dependencies successfully updated."

  gulp build
  gulp minify
  git add -A
  git commit -m "INSTALLER: Update Romanesco styling theme"
  echo "Theme files successfully updated."
fi
