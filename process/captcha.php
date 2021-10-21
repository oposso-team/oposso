<?php

/**
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2021, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */

require_once('./../config/mainconf.php');
require_once($PATH_classes . "/class.Helper.php");

if (empty($_SESSION['script_access'])) {
	header('Location:' . $URL_home);
	exit();
}

$image = imagecreatetruecolor(200, 50);
 
imageantialias($image, true);
 
$colors = [];
 
$red = rand(125, 175);
$green = rand(125, 175);
$blue = rand(125, 175);
 
for($i = 0; $i < 5; $i++) {
  $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
}
 
imagefill($image, 0, 0, $colors[0]);
 
for($i = 0; $i < 10; $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $line_color);
}
 
$black = imagecolorallocate($image, rand(0, 50), rand(0, 50), rand(0, 50));
$white = imagecolorallocate($image, 255, 255, 255);
$textcolors = [$black, $white];
 
$fonts = [$PATH_layout.'/fonts/open-sans-v23-latin-700.ttf', $PATH_layout.'/fonts/open-sans-v23-latin-700.ttf'];
 
$string_length = 6;
$captcha_string = Helper::createCaptchaString($string_length);
 
for($i = 0; $i < $string_length; $i++) {
  $letter_space = 170/$string_length;
  $initial = 15;
   
  imagettftext($image, 24, rand(-15, 15), $initial + $i*$letter_space, rand(25, 45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
}
 
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);