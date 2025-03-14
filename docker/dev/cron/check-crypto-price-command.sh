#!/usr/bin/bash
while true
do
  /usr/local/bin/php /var/www/html/bin/console app:crypto-current-price >> /var/log/cron.log 2>&1
  sleep 20
done