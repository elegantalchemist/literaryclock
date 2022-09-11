#!/bin/sh

# This ensures that we don't boot into the clock

_FUNCTIONS=/etc/rc.d/functions
[ -f ${_FUNCTIONS} ] && . ${_FUNCTIONS}

case "$1" in

        start)
            rm -f /mnt/us/timelit/clockisticking
        ;;

        stop)
            rm -f /mnt/us/timelit/clockisticking
        ;;

        restart)
            # Do nothing
        ;;

        *)
            msg "Usage: $0 {start|stop|restart}" W >&2
            exit 1
        ;;
esac

exit 0