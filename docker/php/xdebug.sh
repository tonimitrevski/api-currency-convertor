#!/bin/sh
if [ "$DISABLE_XDEBUG" == true ]; then
    echo "X-debug is disabled"
else
    apk add --no-cache --virtual .build-deps g++ make autoconf yaml-dev
    pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug
fi
