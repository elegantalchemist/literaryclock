#!/bin/sh

BASEDIR="/mnt/us/timelit"

# See what image is shown at the moment
current=$(cat "$BASEDIR/clockisticking" 2>/dev/null)

# Only if a filename is in the clockisticking file, then continue
if [ -n "$current" ]; then
    if [ -f "$BASEDIR/showsource" ]; then
		# Going from showing source to not:
		# Remove the indicator and then use the unmodified name
        rm "$BASEDIR/showsource"
		
		currentCredit="$current"
	else	
		# Going from no source to showing it:
		# Make the indicator and then permute the name
        touch "$BASEDIR/showsource"
		
        # Find the matching image with metadata
		currentCredit=$(echo $current | sed 's/.png//')_credits.png
		currentCredit=$(echo $currentCredit | sed 's/images/images\/metadata/')
	fi

    # Show the resulting image
	eips -g $currentCredit

    # If there's an upgrade status file, print it
    if [ -f "$BASEDIR/../updatestatus" ]; then
        eips 0 39 "`cat $BASEDIR/../updatestatus`"
    fi
fi

# start waiting for new keystrokes
/usr/bin/waitforkey 104 191 && sh "$BASEDIR/showMetadata.sh" &
