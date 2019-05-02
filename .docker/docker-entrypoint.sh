#!/bin/bash
set -e

if [ ! -z "${ALLOW_ORIGIN}" ]; then
    sed -i "s|Access-Control-Allow-Origin \"\*\"|Access-Control-Allow-Origin \"${ALLOW_ORIGIN}\"|g" /var/www/html/public/.htaccess
fi

exec "$@"
