<?php
// Read the source PNG image
$sourceImagePath = 'path_to_your_original_image.png';
$image = imagecreatefrompng($sourceImagePath);

// Create a blank image with JPG format
$convertedImage = imagecreatetruecolor(imagesx($image), imagesy($image));
$white = imagecolorallocate($convertedImage, 255, 255, 255);
imagefill($convertedImage, 0, 0, $white);

// Copy the PNG image onto the blank image to convert
imagecopy($convertedImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

// Capture output and encode to base64
ob_start();
imagejpeg($convertedImage, null, 100); // 100 is the image quality
$base64Image = base64_encode(ob_get_clean());

imagedestroy($image);
imagedestroy($convertedImage);

// Echo the base64-encoded image data
echo $base64Image;
?>
