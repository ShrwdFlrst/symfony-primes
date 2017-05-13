FROM php:7.1.5-cli

#RUN sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /etc/php/7.0/fpm/pool.d/www.conf
ENV APP_DIR /var/www
ADD . ${APP_DIR}
WORKDIR ${APP_DIR}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y
RUN apt-get install -y git

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN cd ${APP_DIR}
