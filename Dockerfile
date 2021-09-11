FROM php:8.0-fpm
# Install dependencies
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    unzip \
    zip \ 
	libzip-dev \
    libonig-dev \
	nodejs \
	npm \
    curl

	
	
# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

#install NPM
RUN curl -fsSL https://deb.nodesource.com/setup_15.x | bash -
RUN npm install -g agentkeepalive --save
RUN npm install -g yarn
RUN npm install -g npm

# install app
WORKDIR /data
RUN mkdir /data/app
ADD ./docker-entrypoint.sh /data
RUN mv docker-entrypoint.sh entrypoint.sh && chmod +x entrypoint.sh

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/data
RUN php -r "unlink('composer-setup.php');"
RUN chmod +x composer.phar

# Change WWW-data user and change ownership of folders
ARG WWW_UID=33 #take arg from docker-compose or else 33
ARG WWW_GID=33 #take arg from docker-compose or else 33
RUN usermod -u ${WWW_UID} www-data #change www-data uid to UID
RUN groupmod -g ${WWW_GID} www-data #change www-data gid to GID
RUN chown www-data:www-data /data -R
RUN chown www-data:www-data /var/www -R

# Change current user to www
USER www-data

#Start app
ENTRYPOINT [ "/data/entrypoint.sh" ]
CMD ["run"]