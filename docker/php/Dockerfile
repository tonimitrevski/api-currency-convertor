FROM php:7-fpm-alpine

# Install packages
RUN apk add --update --no-cache \
#      bash \
#      openssh \
      python

# Install awscli
RUN TDIR=`mktemp -d` && \
    cd $TDIR && \
    curl "https://s3.amazonaws.com/aws-cli/awscli-bundle.zip" -o "awscli-bundle.zip" && \
    unzip awscli-bundle.zip && \
    ./awscli-bundle/install -i /usr/local/aws -b /usr/local/bin/aws && \
    rm -rf $TDIR

# Install PHP Extensions from source
RUN apk add --update --no-cache \
      gmp \
      gmp-dev && \
    docker-php-source extract && \
    docker-php-ext-install -j$(nproc) \
      bcmath \
      gmp \
      mysqli \
      opcache \
      pdo_mysql && \
    docker-php-source delete && \
    apk del --no-cache \
      gmp-dev

# Install X-Debug
ARG DISABLE_XDEBUG
COPY ./docker/php/xdebug.sh /xdebug.sh
RUN /bin/sh /xdebug.sh
RUN rm /xdebug.sh
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Bundle app source
COPY . /var/www/html
WORKDIR /var/www/html
VOLUME ["/var/www/html"]

#EXPOSE 22

CMD ["/bin/sh", "/var/www/html/init.sh"]
