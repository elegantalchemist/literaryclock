## Converting Quotes to Images

This contains an awesome PHP script to conver the list of quotes into images. Although the file appears as a CSV and opens as one, it looks really awful in excel as the commas in the quotes are preserved so it breaks into columns.

The format is quite simple if you want to add more

* 24h time|time reference|literary quote with time reference inside it|Book Title|Author

Written as an example
* 16:15|quarter past four|At a quarter past four he stumbled home drunk|Foo-Book Title|Bar-Author

## Using the script
Run the script in the same location as the csv quotes file - it's PHP - you'll need the gd extension and image magic extension, check where the fonts are coming from or just dump them in the same folder as the php file like I have here, check the csv file name it will be using. You need to create the image and image/metadata folders for the script to put the images in.

The script produces a folder of images without metadata (author, title) and a folder with the metadata added. I have removed the 'quiz' function which needs this so I take the metadata folder images and use them to replace the standard image folder images.

## Why no images here?
it's more fun to make them yourself and GitHub limits folders to 1000 files so I can't put them all in GitHub without some difficulty anyway.
