FROM debian:jessie

# Install php and stuff
RUN apt-get update && apt-get install -y \
        php5-fpm \
        php5-common \
        php5-dev \
        php5-intl \
        php5-mcrypt \
        php5-curl \
        php5-imagick \
    && rm -rf /var/lib/apt/lists/*

RUN sed -i "s/listen = .*/listen = 0.0.0.0:9000/" /etc/php5/fpm/pool.d/www.conf

EXPOSE 9000
ENTRYPOINT ["php5-fpm","-F"]
