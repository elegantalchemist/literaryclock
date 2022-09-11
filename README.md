# literaryclock
Repurposing a Kindle into a clock which tells time entirely through real book quotes. Every time is from a real book quote and the current file runs to slightly over 2300+ times for the 1440 minutes of a day. Every quote has the time preserved in it's original written format which means times can vary from 24h clock (1315h) to written format (twenty and seven past three).

![Kindle CLock Displaying the time at 6:51](https://github.com/elegantalchemist/literaryclock/blob/main/images/kindle%20clock%20showing%20time%206-51.jpg?raw=true)

## Materials
* **Kindle 3 Keyboard** (I think this can be run on multiple models but I haven't explored this yet - I have touch and non--touch to try out)
* **Computer connection** for use with SSH and transferring files

## Build Instructions
The overview is fairly simple. Jailbreak the kindle, install launchpad, install USBNetwork, install Python. Use something like USB transfer to transfer all the files to the right place and then SSH into the kindle to set a cronjob. The SSH is the hardest part by far but it's only needed for a very small part.

* **WARNING** None of this is what the kindle is designed to do and it's not hard to get it wrong and brick the Kindle. Do not proceed unless you are comfortable with this risk.
* **Step One** - jailbreak the kindle and install appropriate software
* **Jailbreak the kindle** Connect the kindle to USB, extract and copy over the jailbreak install file for the correct kindle model. Disconnect from USB, Menu -> Settings -> Update. When you reconnect to USB it will now have a linkjail folder.
* **Install Launchapd** Same as before, copy over the appropriate launchpad files, update, restart. It will now have a launchpad folder
* **Install usbNetwork** Same as before, copy over the appropriate usbnetwork files, update, restart. It will now have a usbnet folder.
* **Install Python** Same as before, copy over the appropriate python files, update, restart. Can't tell if this one works though.

* **Step Two** - install the scripts for this project
* Connect the Kindle to USB and you will see the storage on your computer available. This is /mnt/us in the linux filesystem so it's easier to copy and paste here over USb than trying to use rsync or SSH or whatever.
*Copy and paste over the utils folder.
*Copy and paste over the timelit folder into /mnt/us so there now exists /mnt/us/timelit/ which contains the scripts.
*In the timelit -> conf folder rename the mac-address-here.conf file to your mac address lower case with hyphen replacing dots
*Copy and paste over the utils folder into /mnt/us so there now exists /mnt/us/utils which contains other utility scripts
*Copy the startclock.conf file to the /mnt/us/launchpad folder (this provides the key combo to enable SSH as well as the clock)



## Credits
* The original project instructables by tjaap - https://www.instructables.com/Literary-Clock-Made-From-E-reader/
* Updated and modified scripts for running it by knobunc - https://github.com/knobunc/kindle-clock
* Hugely expanded list of quotes from JohannesNE - https://github.com/JohannesNE/literature-clock
* Original project ideas and crowdsourced quotes - the Guardian - http://litclock.mohawkhq.com/

## NSFW Warning
A number of the literary quotes contain NSFW language. I have little to no interest in filtering them out and they remain here unredacted. If you wanted to you could do a ctrl+f search and replace for common profanity through the quotes.
