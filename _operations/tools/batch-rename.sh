#!/bin/bash

directory="/var/www/romanesco/nursery/notes/"
separator=" "

# Convert files and folders to lowercase
find $directory -depth -exec rename 's/(.*)\/([^\/]*)/$1\/\L$2/' {} \;

# Replace separator and possible edge case
find $directory -depth -exec rename -v "s/$separator/-/g" {} \;
find $directory -depth -exec rename -v "s/---/-/g" {} \;

echo "Files and folders renamed."