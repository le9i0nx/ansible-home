#!/bin/bash
TMP_DIR=/tmp/blacklist
mkdir -p $TMP_DIR/list
cd $TMP_DIR/list
wget -N https://antifilter.download/list/ipsum.lst https://antifilter.download/list/subnet.lst
old=$(cat $TMP_DIR/md5.txt);
new=$(cat $TMP_DIR/list/*.lst | md5sum | head -c 32);
if [ "$old" != "$new" ]
then
    cat $TMP_DIR/list/ipsum.lst | sed 's_.*_route & reject;_' > /etc/bird/ipsum.txt
    cat $TMP_DIR/list/subnet.lst | sed 's_.*_route & reject;_' > /etc/bird/subnet.txt
    /usr/sbin/birdc configure;
    logger "RKN list reconfigured";
    echo $new > $TMP_DIR/md5.txt;
fi
