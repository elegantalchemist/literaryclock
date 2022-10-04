## Converting Quotes to Images

This contains a Python script to convert the list of quotes into png images. Although the file appears as a CSV and opens as one, it looks really awful in excel as the commas in the quotes are preserved so it breaks them into columns. I recommend opening and editing it in an actual text editor like Visual Studio Code.

The format is quite simple if you want to add new quotes or edit existing ones

* 24h time|time reference|literary quote with time reference inside it|Book Title|Author

Written as an example

* 16:15|quarter past four|At a quarter past four he stumbled home drunk|Foo-Book Title|Bar-Author

## Using the script
This requires [Python](https://apps.microsoft.com/store/search/python) 3.6 or higher, and the [Pillow](https://pillow.readthedocs.io/en/latest/installation.html#basic-installation) imaging library (`pip3 install pillow`)[^1].

Run the script with `python3 quote_to_image.py`, make sure the csv file and fonts are in the same folder.

If you prefer to generate images without the author and title in them, you can open quote_to_image.py in a text editor and change the line that says "include_metadata" to "False". These will be saved to /images/nometadata/ by default.

Fonts can also be changed in this manner, simply add a new truetype font to this folder and edit the appropriate entry.

If you only want to generate x images (say, for testing how a font or a quote looks), you can pass a number as an argument to the script — `python3 quote_to_image.py 5` will only process the first 5 lines in the csv file (excluding the header).

[^1]: Python is preinstalled on virtually every Linux distribution and MacOS, in which case you only need to install Pillow.

## Using PHP
The older setup for this section was the PHP file enclosed here. Same thing - you need to fonts and the csv file in the same folder and just run the PHP file. But be warned you need to activate Imagick and GP extensions and double check font locations, csv file name inside the PHP file etc. The PHP file also needs the /images/nometadata or whatever folders created for it as it doesn't have permission to do it by itself. 
