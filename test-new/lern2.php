<?php


$dir = dirname(__FILE__);
$fontDir = '/home/vench/dev/see/ga.1/fonts/';//$dir.'/fonts/';


$fonts = array_filter (scandir($fontDir), function($file) use(&$fontDir) { 
	return $file != '.' && $file != '..' && strpos($file, '.ttf') !== false;}); 
$chars = array_merge(range('a', 'z'), range('A', 'Z'), [0,1,2,3,4,5,6,7,8,9]);
$size = [9,10,11,12,13,14,15,16,18,20,22,24];

foreach($fonts as $font) {
	foreach($chars as $char) { 

		$charDir = $dir. '/' . $char.'/';
		if(!is_dir($charDir)) {
			 mkdir($charDir, 0777);
		}
		foreach($size as $s) {

			$im = imagecreatetruecolor($s+$s, $s+$s);	
			$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
			$black = imagecolorallocate($im, 0x00, 0x00, 0x00); 
			imagefill($im, 0, 0, $white);
			imagefttext($im, $s, 0, $s-2, $s+2, $black, $fontDir .  $font, $char); 
			imagepng($im, $charDir.md5($font.$char. $s).'.png');
			imagedestroy($im);
		}

	}
}
