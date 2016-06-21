FROM php:7.0-cli

COPY ./config/php.ini /usr/local/etc/php/

# Install modules
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install bcmath

# Install PhantomJS
RUN apt-get install -y libfreetype6 libfontconfig bzip2
RUN apt-get install -y nodejs-legacy
RUN apt-get install -y npm
RUN npm install -g phantomjs-prebuilt

EXPOSE 4444

WORKDIR /usr/src/myapp/applications/console

# Add the console `bin` folder to the PATH environment variable
ENV PATH /usr/src/myapp/applications/console/bin:$PATH
