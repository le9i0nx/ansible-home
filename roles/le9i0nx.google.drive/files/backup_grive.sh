#! /bin/sh
if ! pidof backup_grive
then
	date_backup=`date +%F--%H-%M`
	rsync --archive --one-file-system --exclude "/.griv*" --exclude "/.stversion*" --verbose  ~/crypt/  --delete ~/backup_grive/latest/ > ~/backup_grive/backup_$date_backup.txt
	lines=`cat ~/backup_grive/backup_$date_backup.txt| wc -l`
	if [ "$lines" -gt "4" ]
	then
		cp --archive --link ~/backup_grive/latest/ ~/backup_grive/$date_backup/
	else
		rm ~/backup_grive/backup_$date_backup.txt
	fi
fi

