## Converting Quotes to Images

This contains an awesome PHP script to conver the list of quotes into images. Although the file appears as a CSV and opens as one, it looks really awful in excel as the commas in the quotes are preserved so it breaks into columns. Excel also breaks it if you try and edit it. I recommend opening and editing in something like Visual Studio Code.

The format is quite simple if you want to add more

* 24h time|time reference|literary quote with time reference inside it|Book Title|Author

Written as an example
* 16:15|quarter past four|At a quarter past four he stumbled home drunk|Foo-Book Title|Bar-Author

## Using the script
Run the script in the same location as the csv quotes file - it's PHP - you'll need the gd extension and image magic extension, check where the fonts are coming from or just dump them in the same folder as the php file like I have here, check the csv file name it will be using. You need to create the image and image/nometadata folders for the script to put the images in, the PHP script can't create them itself.

The script produces a standard folder of images with metadata (author, title) and a folder without the metadata added. The code used to have a 'quiz' function which I have removed so you only need the standard images with metadata now.

## Images here
If the whole PHP thing isn't your bag, the zip file here (split into 5 pieces) contains the images and nometadata folders zipped up already - you can unzip to where they belong /mnt/us/timelit/images/nometadata
