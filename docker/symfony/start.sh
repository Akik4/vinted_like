#!/bin/bash

if ! [ -d "/data/symfony" ]; then symfony new symfony --webapp; fi

cd symfony
composer install
symfony server:start
