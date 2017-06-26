#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
EXE=$DIR/../vendor/bin/phpunit
clear
$EXE -c $DIR/../phpunit.xml --colors=always --columns max
