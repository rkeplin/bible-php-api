#!/bin/bash
set -e

if [ ! -z "${ALLOW_ORIGIN}" ]; then
    ORIGIN_REGEX=$(echo "${ALLOW_ORIGIN}" | sed -e 's/[.]/\\\\./g' -e 's/ *, */|/g')
    sed -i "s#^SetEnvIf Origin \".*\" CORS_ALLOW_ORIGIN=\$0#SetEnvIf Origin \"^(${ORIGIN_REGEX})\$\" CORS_ALLOW_ORIGIN=\$0#" /var/www/html/public/.htaccess
fi

if [ ! -z "${REDIS_SERVER}" ]; then
    sed -i "s|session.save_handler = files|session.save_handler = redis|g" /etc/php.ini
    sed -i "s|;session.save_path = \"/tmp\"|session.save_path = ${REDIS_SERVER}|g" /etc/php.ini

    sed -i "s|php_value\[session.save_handler\] = files|php_value\[session.save_handler\] = redis|g" /etc/php-fpm.d/www.conf
    sed -i "s|php_value\[session.save_path\]    = /var/lib/php/session|php_value\[session.save_path\]    = ${REDIS_SERVER}|g" /etc/php-fpm.d/www.conf
fi

if [ ! -z "${COOKIE_DOMAIN}" ]; then
    sed -i "s|session.cookie_domain =|session.cookie_domain = ${COOKIE_DOMAIN}|g" /etc/php.ini
fi

exec "$@"
