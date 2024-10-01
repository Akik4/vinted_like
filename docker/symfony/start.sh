#!/bin/bash

if ! [ -d "/data/symfony" ]; then symfony new symfony --webapp; fi

composer install
cd symfony && symfony server:start
