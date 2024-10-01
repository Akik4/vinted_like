FROM ubuntu

workdir /data

VOLUME /data

run apt-get update -y
run apt-get install curl -y
run apt-get install php php-curl php-mysql -y
run apt-get install php-common libapache2-mod-php php-cli php-xml php-zip unzip -y
run curl -sS https://getcomposer.org/installer -o composer-setup.php
run php composer-setup.php --install-dir=/usr/local/bin --filename=composer
run curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
run apt install symfony-cli -y
run git config --global user.email "dupontstef80@gmail.com"
copy php.ini /etc/php/8.3/cli/php.ini
#run symfony check:requirements

run symfony new symfony --webapp
run cd /data/symfony
run composer install

workdir /data/symfony

expose 8000:8000

cmd ["symfony", "server:start"]
