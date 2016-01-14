#! /bin/sh
# инициализация grive -a
# переходим в папку с файлами
cd /home/le9i0nx/crypt/
if ! pidof grive
then
	date_backup=`date +%F--%H-%M`
	screen -Dm -A -S grive bash -c "grive 2> /home/le9i0nx/backup_grive/grive_$date_backup.txt"
fi
# чистим компромант(файлы от ака google)
cd /tmp/
rm `ls ./ | grep changes | grep xml`

