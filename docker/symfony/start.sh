#!/bin/bash

if ! [ -d "/data/symfony" ]; then cd .. &&  symfony new symfony --webapp && cd symfony; fi

composer install
symfony server:start
