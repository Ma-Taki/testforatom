FROM php:7-fpm
RUN apt update
RUN apt install -y git vim unzip cron libzip-dev libfreetype6-dev libjpeg-dev libpng-dev zlib1g-dev
RUN apt clean
#下記以降はうまくいかないので手動実行
#RUN docker-php-ext-install pdo_mysql mysqli mbstring curl exif zip iconv
#bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl json ldap mbstring mcrypt mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets spl standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip
#gdは下記でインストールしないと「JPEG Support」が反映されない
#RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
#RUN docker-php-ext-install -j$(nproc) gd
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN chmod 777 /var/www/laravel/storage/logs /var/www/laravel/storage/framework/cache /var/www/laravel/storage/framework/sessions /var/www/laravel/storage/framework/views
#初回はvendorライブラリをインストールのに下記の実行が必要
#RUN cd /var/www/laravel && composer install
#/etc/cron.d/lessanのパーミッションを644に変更できない時はcronインストール後にcrontab -eで下記を設定する
#0 0 * * * php /var/www/laravel/artisan コマンド名