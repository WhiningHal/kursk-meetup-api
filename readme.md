# kursk-meetup-api
Репозиторий для приложения kursk-meetup-api.

Для запуска серверной части необходимо сделать следующее:

1. Установка пакетов:
sudo -i
apt-get update
apt-get install postgresql
apt-get install apache2
apt-get install php5 libapache2-mod-php5 php5-mcrypt 
apt-get install php5-json php5-pgsql

2. Настройка Apache:
  - предположим, что репозиторий лежит в папке /srv/kursk-meetup-api/
  - убедитесь, что пользователь www-data имеет доступ к файлам в этой папке.

в /etc/apache2/apache2.conf необходимо добавить:
==========================================================
<Directory /srv/kursk-meetup-api>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
==========================================================

Создать файл /etc/apache2/sites-enabled/kursk-meetup-api с содержимым между 
строками из знаков равенства, не включая сами эти строки:

= Начало файла =========================================================
<VirtualHost *:80>
    ServerName DNS-имя сервера
    ServerAdmin адрес-электронной-почты-админа
     <Directory /srv/kursk-meetup-api>
         Order allow,deny
         AllowOverride All
         Allow from all
         Options Indexes FollowSymLinks MultiViews
         Require all granted
     </Directory>
    DocumentRoot /srv/kursk-meetup-api

    LogLevel debug ssl:debug

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
= Конец файла =========================================================

В файл /etc/apache2/mods-enabled/dir.conf найти строки: 

<IfModule mod_dir.c>
    DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
</IfModule>

И изменить строку DirectoryIndex , переместив index.php в начало списка.

При установке я столкнулся с проблемой, из-за которой Apache отказывался 
запускаться: оказалось, что у пользователя www-data нет никаких прав на папку
/etc/apache2/ . Может быть, придётся эти права ему дать:

chown -R www-data:www-data /etc/apache2/
chown -R www-data:www-data /srv/kursk-meetup-api

3. Настройка БД:
sudo -u postgres psql --command="create user dbuser with superuser password 'databAsepa$$word'"
sudo -u postgres psql --command="CREATE DATABASE meetupapi WITH OWNER = dbuser ENCODING = 'UTF8' CONNECTION LIMIT = -1"
sudo -u postgres psql --command="grant all privileges on schema public to dbuser"

4. Установка Laravel:

4.1. Установка composer:

php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar
sudo mv composer.phar /usr/local/bin/composer

4.2. Установка Laravel:

composer global require "laravel/installer"
Добавить папку ~/.composer/vendor/bin в путь поиска исполняемых файлов:
Добавить в файл ~/.profile :
if [ -d "$HOME/.composer/vendor/bin" ] ; then
  PATH="$PATH:$HOME/.composer/vendor/bin"
fi

В папке /srv запустить:

laravel new kursk-meetup-api

5. Настройка Laravel для работы с PostgreSQL.

