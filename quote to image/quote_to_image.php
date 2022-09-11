<?php

// this script turns quotes from books into images for use in a Kindle clock.
// Originally by Jaap Meijers, 2018, modified by ElegantAlchemist, 2022.


error_reporting(E_ALL);
ini_set("display_errors", 1);

$imagenumber = 0;
$previoustime = 0;

// pad naar font file
putenv('GDFONTPATH=c:\\Windows\\Fonts');
$font_path = './bookerly.ttf';
$font_path_bold = './bookerlybold.ttf';
$creditFont = './baskervilleboldbt.ttf';


// get the quotes (including title and author) from a CSV file, 
// and create unique images for them, one without and one with title and author
$row = 1;
if (($handle = fopen("litclock_annotated_expanded.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
        $num = count($data);
        $row++;
        $time = $data[0];
        $timestring = trim($data[1]);
        $quote = $data[2];
        $quote = trim(preg_replace('/\s+/', ' ', $quote));
        $title = trim($data[3]);
        $author = trim($data[4]);

        // if ($row < 10)
        TurnQuoteIntoImage($time, $quote, $timestring, $title, $author);

    }
    fclose($handle);
}



function TurnQuoteIntoImage($time, $quote, $timestring, $title, $author) {

    global $font_path;
    global $font_path_bold;
    global $creditFont;

    //image dimensions
    $width = 600;
    $height = 800;

    //text margin
    $margin = 26;

    // first, find the timestring to be highlighted in the quote
    // determine the position of the timestring in the quote (so after how many words it appears)
    $timestringStarts = count(explode(' ', stristr($quote, $timestring, true)))-1;
    // how many words long the timestring is
    $timestring_wordcount = count(explode(' ', $timestring))-1;

    // divide text in an array of words, based on spaces
    $quote_array = explode(' ', $quote);

    $time = substr($time, 0, 2).substr($time, 3, 2);

    // font size to start with looking for a fit. a long quote of 125 words or 700 characters gives us a font size of 23, so 18 is a safe start.
    $font_size = 18;

    ///// QUOTE /////
    // find the font size (recursively) for an optimal fit of the text in the bounding box
    // and create the image.
    list($png_image) = fitText($quote_array, $width, $height, $font_size, $timestringStarts, $timestring_wordcount, $margin);


    // serial number for when there is more than one quote for a certain minute 
    global $imagenumber;
    global $previoustime;
    if ($time == $previoustime) {
        $imagenumber++;
    } else {
        $imagenumber = 0;
    }
    $previoustime = $time;

    print "Image for " . $time .'_'. $imagenumber . "\n";

    // Save the image (commented out to remove the No Metadata images)
    imagepng($png_image, 'images/quote_'.$time.'_'.$imagenumber.'.png');


    ///// METADATA /////
    // create another version, with title and author in the image

    
    // define text color
    $grey = imagecolorallocate($png_image, 125, 125, 125);
    $black = imagecolorallocate($png_image, 0, 0, 0);

    $dash = "—";

    $credits = $title . ", " . $author;
    $creditFont_size = 18;

    // if the metadata are longer than 45 characters, replace a space by a newline from the end,
    // just as long the paragraph is getting smaller. stop when the box gets wider again.
    list($metawidth, $metaheight, $metaleft, $metatop) = measureSizeOfTextbox($creditFont_size, $creditFont, $dash . $credits);
    
    if ( $metawidth > 500 ) {

        $newCredits = array();

        $creditsArray = explode(" ", $credits);
        
        $i = 1;

        while ( True ) {

            // cut the metadata in two lines
            $tmp0 = implode(" ", array_slice($creditsArray, 0, count($creditsArray)-$i));
            $tmp1 = implode(" ", array_slice($creditsArray, 0-$i));

            // once the second line is (almost) longer than the first line, stop
            if ( strlen($tmp1)+5 > strlen($tmp0) ) {
                break;
            } else { 
                // if the second line is still shorter than the first, save it to a new string, but continue to look at a new fit.
                $newCredits[0] = $tmp0;
                $newCredits[1] = $tmp1;
            }

            $i++;

        }

        list($textWidth1, $textheight1) = measureSizeOfTextbox($creditFont_size, $creditFont, $dash . $newCredits[0]);
        list($textWidth2, $textheight2) = measureSizeOfTextbox($creditFont_size, $creditFont, $newCredits[1]);

        $metadataX1 = $width-($textWidth1+$margin);
        $metadataX2 = $width-($textWidth2+$margin);
        $metadataY = $height-$margin;

        imagettftext($png_image, $creditFont_size, 0, $metadataX1, $metadataY-($textheight1*1.1), $black, $creditFont, $dash . $newCredits[0]);
        imagettftext($png_image, $creditFont_size, 0, $metadataX2, $metadataY, $black, $creditFont, $newCredits[1]);
        
    } else {

        // position of single line metadata
        $metadataX = ($width-$metaleft)-$margin;
        $metadataY = $height-$margin;

        imagettftext($png_image, $creditFont_size, 0, $metadataX, $metadataY, $black, $creditFont, $dash . $credits);

    }

    // Save the image with metadata
    imagepng($png_image, 'images/metadata/quote_'.$time.'_'.$imagenumber.'.png');

    // Free up memory
    imagedestroy($png_image);

    // convert the image we made to greyscale
    $im = new Imagick();
    $im->readImage('images/quote_'.$time.'_'.$imagenumber.'.png');
    $im->setImageType(Imagick::IMGTYPE_GRAYSCALE);
    unlink('images/quote_'.$time.'_'.$imagenumber.'.png');
    $im->writeImage('images/quote_'.$time.'_'.$imagenumber.'.png');

    // convert the image we made to greyscale 
    $im = new Imagick();
    $im->readImage('images/metadata/quote_'.$time.'_'.$imagenumber.'.png');
    $im->setImageType(Imagick::IMGTYPE_GRAYSCALE);
    unlink('images/metadata/quote_'.$time.'_'.$imagenumber.'.png');
    $im->writeImage('images/metadata/quote_'.$time.'_'.$imagenumber.'.png');

}


function fitText($quote_array, $width, $height, $font_size, $timestringStarts, $timestring_wordcount, $margin) {

    global $font_path_bold;
    global $font_path;

    // create image
    $png_image = imagecreate($width, $height)
        or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($png_image, 255, 255, 255);

    // define text color
    $grey = imagecolorallocate($png_image, 125, 125, 125);
    $black = imagecolorallocate($png_image, 0, 0, 0);

    $timeLocation = 0;
    $lineWidth = 0;

    // variable to hold the x and y position of words
    $position = array($margin,$margin+$font_size);

    // echo "try " . $font_size . ", ";

    foreach($quote_array as $key => $word) {

        # change the look of the text if it is part of the time string
        if ( in_array($key, range($timestringStarts, $timestringStarts+$timestring_wordcount)) ) {
            $font = $font_path_bold;
            $textcolor = $black;
        } else {
            $font = $font_path;
            $textcolor = $grey;
        }

        // measure the word's width
        list($textwidth, $textheight) = measureSizeOfTextbox($font_size, $font, $word . " ");

        //// write every word to image, and record its position for the next word ////

        // if one word exceeds the width of the image (this sometimes happens when the quote is very short),
        // then stop trying to make the font size even bigger.
        if ( $textwidth > ($width - $margin) ) {
            return False;
        }

        // if the line plus the extra word is too wide for the specified width, then write the word one the next line. 
        if ( ($position[0] + $textwidth) >= ($width - $margin) ) {
            
            # 'carriage return':
            # reset x to the beginning of the line and push y down a line 
            $position[0] = $margin;
            $position[1] = $position[1] + round($font_size*1.618); // 'golden ratio' line height

            # write the word to the image
            imagettftext($png_image, $font_size, 0, $position[0], $position[1], $textcolor, $font, $word);
           
        // if the line isn't too long, just add it.
        } else {

            # write the word to the image
            imagettftext($png_image, $font_size, 0, $position[0], $position[1], $textcolor, $font, $word);

        }
        
        # add the word's width
        $position[0] += $textwidth;

    }

    // if the height of the whole text is smaller than the height of the image, then call this same function again
    $paragraphHeight = $position[1];
    if ( $paragraphHeight < $height-100 ) { // leaving room for the credits below
        $result = fitText($quote_array, $width, $height, $font_size+1, $timestringStarts, $timestring_wordcount, $margin);
        if ( $result !== False ) {
            list($png_image, $paragraphHeight, $font_size, $timeLocation) = $result;
        };
    } else {
        // if this call to fitText returned a paragraph that is in fact higher than the height of the image,
        // then return without those values
        return False;
    }

    return array($png_image, $paragraphHeight, $font_size, $timeLocation);

}

function measureSizeOfTextbox($font_size, $font_path, $text) {

    $box = imagettfbbox($font_size, 0, $font_path, $text);

    $min_x = min( array($box[0], $box[2], $box[4], $box[6]) );
    $max_x = max( array($box[0], $box[2], $box[4], $box[6]) );
    $min_y = min( array($box[1], $box[3], $box[5], $box[7]) );
    $max_y = max( array($box[1], $box[3], $box[5], $box[7]) );

    $width  = ( $max_x - $min_x );
    $height = ( $max_y - $min_y );
    $left   = abs( $min_x ) + $width;
    $top    = abs( $min_y ) + $height;

    return array($width, $height, $left, $top);

}


?>
