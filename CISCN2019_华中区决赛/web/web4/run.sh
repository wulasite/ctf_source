#!/bin/bash

VOLUME_HOME="/var/lib/mysql"

sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
    -e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" /etc/php5/apache2/php.ini
if [[ ! -d $VOLUME_HOME/mysql ]]; then
    echo "=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo "=> Installing MySQL ..."
    mysql_install_db > /dev/null 2>&1
    echo "=> Done!"  
    /create_mysql_admin_user.sh
else
    echo "=> Using an existing volume of MySQL"
fi

mv /tmp/* /app && \
chown -R ciscn:www-data /app && \
chmod -R 750 /app && \
rm -r /app/.git

/etc/init.d/ssh start && \
find /var/lib/mysql -type f -exec touch {} \; && \
service mysql start

service ssh start
sleep 3

mysql < /var/www/admin.sql && \
rm -f /var/www/admin.sql

mysql -uroot -e "CREATE USER 'dog'@'localhost' IDENTIFIED BY '123456';"
mysql -uroot -e "grant select on *.* to dog@"localhost";"
mysql -uroot -e "set password for root@localhost = password('xoawaskd');"

echo > ~/.bash_history && \
history -c
exec supervisord -n
