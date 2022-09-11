#!/bin/sh

# This sets the clock by ntp

NTPSERVER=time.google.com
TIMEOUT=30

killall ntpdate > /dev/null 2>&1

ping -c 1 "$NTPSERVER" > /dev/null 2>&1 || ( echo "Can't reach $NTPSERVER"; exit 1 )

ntpdate "$NTPSERVER" & pid=$!

count=1
while [ $count -lt $TIMEOUT ]; do
    if [ ! -e "/proc/$pid" ]; then
        break
    fi
    sleep 1
done

if [ -e "/proc/$pid" ]; then
    kill $pid
    echo "Time update from $NTPSERVER took too long"
    exit 1
fi

exit