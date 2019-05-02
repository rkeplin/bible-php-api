FROM fedora:latest

RUN dnf -y update \
 && dnf -y install httpd php php-common php-pdo php-hash php-mbstring php-apc php-gd php-mysqlnd php-json php-dom php-xdebug mysql npm at wget \
 && dnf clean all

RUN wget https://phar.phpunit.de/phpunit-8.0.5.phar
RUN chmod +x phpunit-8.0.5.phar
RUN mv phpunit-8.0.5.phar /usr/local/bin/phpunit

RUN sed -i 's/;date.timezone =/date.timezone = "America\/New_York"/g' /etc/php.ini

RUN echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf
RUN sed -i 's/LogFormat "%h/LogFormat "%{X-Forwarded-For}i - %h/g' /etc/httpd/conf/httpd.conf

RUN mkdir /run/php-fpm
RUN chown -R apache:apache /run/php-fpm

RUN ln -sf /dev/stdout /var/log/httpd/access_log \
    && ln -sf /dev/stderr /var/log/httpd/error_log

EXPOSE 80

COPY .docker/docker-entrypoint.sh /docker-entrypoint.sh
COPY .docker/local.conf /etc/httpd/conf.d/local.conf
COPY .docker/virtualhosts.conf /etc/httpd/conf.d/virtualhosts.conf

WORKDIR /var/www/html

COPY app app
COPY library library
COPY public public

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD /usr/sbin/php-fpm -D; /usr/sbin/httpd -D FOREGROUND
