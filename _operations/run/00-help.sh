#!/bin/bash

# exit if variable was not passed
set -u

# exit on any type of error
set -e

# USAGE
# ==============================================================================

echo "Usage: ./operations [TASK] [SUBJECT] [--ARGS]"
echo "Example: ./operations update romanesco --npm"
echo ""
echo "Available tasks, subjects and options:"
echo "    update"
printf "${BOLD}"
echo "      romanesco         update Romanesco for this project"
printf "${NORMAL}"
echo "      backyard          update Backyard resources in project hub"
echo "      -n|--npm          update frontend dependencies with npm"
echo "      -d|--defaults     update defaults from Romanesco Soil (DANGEROUS!!)"
