<?php

$filename = 'cv.png';
$filename = 'photo.jpg';

$im = strpos($filename, 'png') !== false ?
	 imagecreatefrompng($filename) : imagecreatefromjpeg($filename);

$width = imagesx($im);
$height = imagesy($im);

function isDark($rgb) {   //return $rgb == 0;// var_dump($rgb);
$r = ($rgb >> 16) & 0xFF;
$g = ($rgb >> 8) & 0xFF;
$b = $rgb & 0xFF;

// var_dump($r, $g, $b);
	return $r < 127 && $g < 127 && $b < 127;// == 0;
}

$directions = [];
$point  = null;

for($y = 0; $y < $height; $y ++) {
	for($x = 0; $x < $width; $x ++) {
		 
		
		$a1 = isDark(imagecolorat($im, $x, $y));
		$a2 = ($width == $x + 1) ? $a1 : isDark(imagecolorat($im, $x + 1, $y));
		$a3 = ($height == $y + 1) ? $a1 : isDark(imagecolorat($im, $x, $y + 1));
		
		if($a1 !=  $a2 || $a1 !=  $a3) {
			echo 1; 
			if(!is_null($point)) {
				$d = '';
				if($point[0] < $x && $point[1] < $y) {
					$d = 'юго-восток';
				} else if($point[0] == $x && $point[1] < $y) {
					$d = 'юг';
				}  else if($point[0] < $x && $point[1] == $y) {
					$d = 'восток';
				} else if($point[0] < $x && $point[1] > $y) {
					$d = 'северо-восток';
				}else if($point[0] == $x && $point[1] > $y) {
					$d = 'север';
				}else if($point[0] > $x && $point[1] > $y) {
					$d = 'северо-запад';
				}else if($point[0] > $x && $point[1] == $y) {
					$d = 'запад';
				} else if($point[0] > $x && $point[1] < $y) {
					$d = 'юго-запад';
				}
				if(sizeof($directions) == 0 || $directions[sizeof($directions) -1] != $d) {
					$directions[] = $d;
				}
				
			}
			$point = [$x, $y];	
		 
		} else {
			echo '.';
		}
	}
	echo "\n";
} 
print_r($directions);

