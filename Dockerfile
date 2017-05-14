FROM php:7.1.5-cli

ENV APP_DIR /var/www
RUN mkdir -p ${APP_DIR}
WORKDIR ${APP_DIR}
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y
RUN apt-get install -y git

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY error_logging.ini /usr/local/etc/php/conf.d/

