#!/bin/bash

if ! [ -d "/data/symfony" ]; then symfony new symfony --webapp; fi

cd /data/symfony
composer install
symfony server:start
