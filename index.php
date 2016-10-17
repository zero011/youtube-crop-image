<?php

// Get image from youtube url
$url = 'https://i.ytimg.com/vi/zVIhUVid4fA/hqdefault.jpg';
$img = "image.png";
file_put_contents($img, file_get_contents($url));
$orig_image = @file_get_contents($url);
if($orig_image ==  false){
	$orig_image = @file_get_contents($url);
}

// save it locally
file_put_contents($img, $orig_image);
unset($orig_image);

// call crop_image function
crop_image($img);

function crop_image($filename){

		$less_than_hex = hexdec('222222');

		// get image size
		list($width, $height) = getimagesize($filename);

		// calculate y axis start point
        $image_res = imagecreatefromjpeg($filename);
        $y = 0;
        $min_y = -1;
        $start_color = imagecolorat($image_res, 0, 0);
 
        $one_color = true;
        while ($one_color && $y < $height) {
                for ($x = 0; $x < $width; $x++) {
                        $rgb = imagecolorat($image_res, $x, $y);
                        if($rgb > $less_than_hex) $one_color = false;
                }
                $min_y++;
                $y++;
        }
 
        // calculate y axis stop point
        $y = $height - 1;
        $max_y = $height + 1;
        $start_color = imagecolorat($image_res, $width - 1, $y);
        $one_color = true;
        while ($one_color && $y > 0) {
                for ($x = 0; $x < $width; $x++) {
                        $rgb = imagecolorat($image_res, $x, $y);
                        if($rgb > $less_than_hex) $one_color = false;
                }
                $max_y--;
                $y--;
        }

        // calculate x axis start point
        $start_color = imagecolorat($image_res, 0, 0);
        $x = 0;
        $min_x = - 1;
        $one_color = true;
        while ($one_color && $x < $width) {
                for ($y = $min_y; $y < $max_y; $y++) {
                        $rgb = imagecolorat($image_res, $x, $y);
                        if($rgb > $less_than_hex) $one_color = false;
                }
                $min_x++;
                $x++;
        }
 
        // calculate x axis stop point
        $x = $width - 1;
        $start_color = imagecolorat($image_res, $width - 1, $height - 1);
        $max_x = $width + 1;
        $one_color = true;
        while ($one_color && $x > 0) {
                for ($y = $min_y; $y < $max_y; $y++) {
                        $rgb = imagecolorat($image_res, $x, $y);
                        if($rgb > $less_than_hex) $one_color = false;
                }
                $max_x--;
                $x--;
        }

        // calculate new width/height
        $new_height = $max_y - $min_y;
        $new_width = $max_x - $min_x;

        // crop the image
        $gd = imagecreatetruecolor($new_width, $new_height);
        imagecopy($gd, $image_res, 0, 0, $min_x, $min_y, $width, $height);

        // save the new image to a different location
        imagejpeg($gd, 'img/myImage.png', 100);

        // delete the temporary file
        unlink($filename);

}