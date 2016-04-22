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

Для меня работало следующее:
Добавить в /etc/hosts:
==========================================================
127.0.0.1       bololike.ru
195.234.3.17    bololike.ru
==========================================================
При установке Apache пользователь почему-то создался со странным домашним каталогом.
Пришлось создать ему папку /home/www-data
sudo -i
service apache2 stop
cd /home
mkdir www-data
chown -R www-data:www-data www-data/
usermod -d /home/www-data www-data
service apache2 start

в /etc/apache2/apache2.conf необходимо добавить:
==========================================================
<Directory /srv/kursk-meetup-api>
    Options FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
<Directory /srv/kursk-meetup-api/public>
    Options FollowSymLinks
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
    DocumentRoot /srv/kursk-meetup-api/public
    <Directory /srv/kursk-meetup-api/public>
         Order allow,deny
         AllowOverride All
         Allow from all
         Options FollowSymLinks MultiViews
         Require all granted
    </Directory>
    <Location "/">
         AllowOverride All
         Allow from all
         Options FollowSymLinks MultiViews
         Require all granted
    </Location>
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

3. Настройка БД:
sudo service postgresql start
sudo -u postgres psql --command="create user dbuser with superuser password 'databAsepa$$word'"
sudo -u postgres psql --command="CREATE DATABASE meetupapi WITH OWNER = dbuser TEMPLATE = template0 ENCODING = 'UTF8' CONNECTION LIMIT = -1"
sudo -u postgres psql --dbname=meetupapi --command="grant all privileges on schema public to dbuser"

=== Изменить файл /etc/postgresql/9.3/main/pg_hba.conf  : ===
local   all dbuser password
host    all all 127.0.0.1/32 password
host    all all ::1/128 md5
=============================================================

4. Установка Laravel:

4.1. Установка composer:

php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

Не забыть обновить SSL-сертификаты:
update-ca-certificates

php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer

4.2. Установка Laravel:

Добавить папку ~/.composer/vendor/bin в путь поиска исполняемых файлов:
Добавить в файл ~/.profile :
if [ -d "$HOME/.composer/vendor/bin" ] ; then
  PATH="$PATH:$HOME/.composer/vendor/bin"
fi

composer global require "laravel/installer"

В папке /srv запустить:

laravel new kursk-meetup-api

chown -R www-data:www-data /srv/kursk-meetup-api

5. Настройка Laravel для работы с PostgreSQL.

5.1. Отредактировать файл /srv/kursk-meetup-api/config/database.php :

    'default' => 'pgsql',
...
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => 'localhost',
            'port' => '5432',
            'database' => 'meetupapi',
            'username' => 'dbuser',
            'password' => 'databAsepa$$word',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ],
...

5.2. Положить в каталог сайта (/srv/kursk-meetup-api) файлы из репозитория,
     затем выполнить 
php artisan migrate

Для заполнения БД тестовыми данными выполнить
php artisan db:seed

