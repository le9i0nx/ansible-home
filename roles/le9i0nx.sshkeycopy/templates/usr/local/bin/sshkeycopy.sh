#!/bin/bash
if [ ! -d /root/.ssh ]; then
    mkdir /root/.ssh
    chmod 0700 /root/.ssh
fi
if [ ! -f /root/.ssh/authorized_keys ]; then
    touch /root/.ssh/authorized_keys
    chmod 0600 /root/.ssh/authorized_keys
fi
wget --quiet -O /tmp/authorized_keys https://le9i0nx.gitlab.io/autoinstall/authorized_keys
if [ -f /tmp/authorized_keys ]; then
    result=$(diff -Nuar /tmp/authorized_keys /root/.ssh/authorized_keys)
    if [ $? -ne 0 ]; then
        if [[ -s /tmp/authorized_keys ]]; then
            cat /tmp/authorized_keys > /root/.ssh/authorized_keys
        fi
    fi
    rm /tmp/authorized_keys
fi
