# literaryclock
Repurposing a Kindle into a clock which tells time entirely through real book quotes. Every time is from a real book quote and the current file runs to slightly over 2300+ times for the 1440 minutes of a day. Every quote has the time preserved in it's original written format which means times can vary from 24h clock (1315h) to written format (twenty and seven past three).

![Kindle CLock Displaying the time at 6:51](https://github.com/elegantalchemist/literaryclock/blob/main/images/kindle%20clock%20showing%20time%206-51.jpg?raw=true)

#Materials
* **Kindle 3 Keyboard (I think this can be run on multiple models but I haven't explored this yet - I have touch and non--touch to try out)
* **Computer connection for use with SSH and transferring files

#Build Instructions
The overview is fairly simple. Jailbreak the kindle, install launchpad, install USBNetwork, install Python. Use something like USB transfer to transfer all the files to the right place and then SSH into the kindle to set a cronjob. The SSH is the hardest part by far but it's only needed for a very small part.

