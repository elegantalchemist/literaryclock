# literaryclock
Repurposing a Kindle into a clock which tells time entirely through real book quotes. A fascinating conversation piece as well as a beautiful and functional clock.

Non-destructive, the clock can be exited and the Kindle used as normal at any point.

Every time is from a real book quote and the current selection of quotes runs to slightly over 2300+ times for the 1440 minutes of a day. Every quote has the time preserved in it's original written format which means times can vary from 24h clock (1315h) to written format (twenty and seven past three). There are still a number of times still missing and some quotes are vague enough to be used to fill in some gaps.

<p align="center">
<img src="https://github.com/elegantalchemist/literaryclock/blob/main/images/literaryclockrunning.jpg" height="600">
</p>

## Materials
* **Kindle 3 Keyboard** (I think this can be run on multiple models but I haven't explored this yet - I have touch and non--touch to try out)
* **Computer connection** for use with SSH and transferring files

## Build Overview
The overview is fairly simple. Jailbreak the kindle, install launchpad, install USBNetwork, install Python. Use something like USB transfer to transfer all the files to the right place and then SSH into the kindle to set a cronjob.

That's it. Copy some files, then one CronJob + copy a file via SSH. I've tried to provide very detailed steps below - it might look more daunting before you get started.

The SSH is the hardest part by far but it's only needed for a small part

* **WARNING** None of this is what the kindle is designed to do and it's not hard to get it wrong and brick the Kindle. Do not proceed unless you are comfortable with this risk.

## **Step 1 - Make Some Images**
* Run the quote_to_image PHP script to generate your images in the 'images' and 'nometadata' folders. This assumes you have the gd and imagick extensions available and activated and the appropriate fonts in the same folder as the script. The script is designed to run in the same folder as the quotes csv file. There are various things you can do at this point - change fonts, link the files in different ways etc.
* The end result is you should have two folders each containing 2,300+ images. These two folders can be copied into the timelit folder so they run like .../timelit/images/nometadata.
* When it comes to copy the timelit folder across this can be done in one step, scripts and images all together.
* You'll need to install PHP and enable the extensions gd & imagick - this is OS dependent.
* If this is a problem for you (because nobody got time for PHP), I have included a zipped folder with all the images and nometadata copies also.

## **Step 2** - Jailbreak the kindle and install appropriate software - see the sources folder for these files
* **Optional but useful** Update the kindle amazon firmware to the newest, this helps with time and date setting in the background. Firmwares available here pay attention to your serial number https://www.amazon.com/gp/help/customer/display.html?nodeId=GX3VVAQS4DYDE5KE
* **Jailbreak the kindle** Connect the kindle to USB, extract and copy over the jailbreak install file (directly to the root of the visible USB storage section, not into any folders) for the correct kindle model. Disconnect from USB, Menu -> Settings -> Menu -> Update. When you reconnect to USB it will now have a linkjail folder.
* **Install Launchapd** Same as before, copy over the appropriate launchpad files, update, restart. It will now have a launchpad folder
* **Install usbNetwork** Same as before, copy over the appropriate usbnetwork files, update, restart. It will now have a usbnet folder.
* **Install Python** Same as before, copy over the appropriate python files, update, restart. It will now have a python folder.


## **Step 3** - Install the scripts for this project
* Connect the Kindle to USB and you will see the storage on your computer available. This is /mnt/us/ in the linux filesystem so it's easier to copy and paste here over USB than trying to use rsync or SSH or whatever.
* Copy and paste over the timelit folder into /mnt/us so there now exists /mnt/us/timelit/ which contains the scripts, plus the images in their appropriate place /mnt/us/timelit/images/nometadata
* In the timelit -> conf folder rename the mac-address-here.conf file to your mac address lower case with hyphen replacing dots
* In the timelit -> conf folder, edit the mac-address-here.conf file as required to show time in the correct timezone. Ex: GMT0BST -> AST4ADT. Timezone formats can be referenced from here: https://mm.icann.org/pipermail/tz/2001-April/011541.html
* Copy and paste over the utils folder into /mnt/us/ so there now exists /mnt/us/utils which contains other utility scripts
* Copy the startClock.ini file to the existing /mnt/us/launchpad folder (this provides the key combo to enable SSH as well as the clock)
* Activate SSH over wifi by editing 'config' file in /mnt/us/usbnet/etc to turn 'allow ssh over wifi' to true. You can also update the kindle's local IP address expectations here, although I'm not sure if this is necessary for SSH over WiFi.
* All of the above can be done over USB in windows (and presumably linux) as the 'external' storage visible when you plug in just like a USB stick is /mnt/us/
* Restart the kindle (settings -> menu -> settings -> restart). This is needed to get the key combinations activated in launchpad. A soft reboot after an update won't do the same as a full restart - you need a full restart to get this implemented.
* Now if you select Shift, then N on the keypad (press shift, let go, press n) the message 'success' should pop up and networking is turned on to allow SSH connections. If this doesn't work then the tedious way is to use the kindle search function and search for ';debugOn' then '~usbNetwork' then ';debugOff' (without quotes but with tilde/semicolon) and this will turn SSH access on.
* SSH into your kindle - I prefer PuTTY (get the IP of the Kindle from your router (default will try to be 192.168.2.2, see /mnt/us/usbnet/etc/config), user is root, password can be found using the serial number here: https://www.sven.de/kindle/?# or use the python script included in sources here - may need to try all 4 to get one which works)
* Mount the root storage as read-write, then edit the crontab to add a cronjob, something like the below instruction set

```
mntroot rw
nano /etc/crontab/root
(add the below cronjob to the top of the crontab)
* * * * * /bin/sh /mnt/us/timelit/timelit.sh
ctrl+x then yes to save

(then the following functions to add the cleanup clockisticking file function)
cp /mnt/us/utils/clean-clock.sh /etc/init.d/clean-clock
cd /etc/rcS.d
ln -s ../init.d/clean-clock S77clean-clock
mntroot ro
reboot
```

* This should be total extent of SSH/terminal needed - adding timelit.sh to a one minute cronjob and copy the clean-clock file to init.d
* Once the kindle has rebooted and given a minute to be fully running, shift (and release) and press C should start the clock.
* This project disables the metadata function so no buttons at all should affect the clock running. If the battery runs out, charging it will put it right back in clock mode.
* Shift (and release) and C while the clock is running will reboot the kindle out of clock mode and back to normal mode - perfectly usable as a normal kindle with books all kept where they are - this whole project is non-destructive to your Kindle.

## Uninstall
* All of the source files also have 'uninstall' variants to remove them from the Kindle if you wished to take it right back to the start.
* Delete all the folders and files you created yourself (but not the ones created by the updates like usbnet, python etc)
* Copy across the uninstall variants of each update one at a time and apply as an update, python, usbnet, launchapd, jailbreak.

## Credits
* The original project instructables by tjaap - https://www.instructables.com/Literary-Clock-Made-From-E-reader/
* Updated and modified scripts for running it by knobunc - https://github.com/knobunc/kindle-clock
* Hugely expanded list of quotes from JohannesNE - https://github.com/JohannesNE/literature-clock
* Original project ideas and crowdsourced quotes - the Guardian - http://litclock.mohawkhq.com/

## NSFW Warning
A number of the literary quotes contain NSFW language. I have little to no interest in filtering them out and they remain here unredacted. If you wanted to you could do a ctrl+f search and replace for common profanity through the quotes.
