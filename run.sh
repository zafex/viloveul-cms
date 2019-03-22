#!/usr/bin/env sh
set -e

/etc/init.d/mysql start
 
mysql -uroot -e 'CREATE DATABASE IF NOT EXISTS viloveul;'
mysql -uroot -e "CREATE USER 'dev'@'%' IDENTIFIED BY 'viloveul';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON * . * TO 'dev'@'%';"
mysql -uroot -e "FLUSH PRIVILEGES;"

php viloveulc cms:install
php viloveulc cms:admin --email=$ADMIN_EMAIL --password=$ADMIN_PASSWORD

/etc/init.d/mysql stop

exec /usr/bin/supervisord -c /www/supervisor.conf