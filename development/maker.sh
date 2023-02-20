#!/bin/bash

# Usage example:
# Need composer install on the main project before
# Omit extension .json in the arg
# sh maker.sh Donatarias11
# sh maker.sh ConsumoDeCombustibles11 

nameFile=$1
mainPath=$(pwd)
dirPath=$(dirname $mainPath)
if [ -z "$nameFile" ]
then
  echo 'Need name of the file as arg'
else
  # echo $dirPath
  echo "\nStart ElementsMaker $nameFile"
  echo rm -rf $dirPath/src/CfdiUtils/Elements/$nameFile
  mkdir -p $dirPath/src/CfdiUtils/Elements/$nameFile
  php $dirPath/development/bin/elements-maker.php $dirPath/development/ElementsMaker/specifications/$nameFile.json $dirPath/src/CfdiUtils/Elements/$nameFile
  echo composer dev:fix-style
  # vendor/bin/php-cs-fixer fix --verbose
  # bin/phpcbf --colors -sp src/ tests/
  echo "End ElementsMaker $nameFile\n"
fi
