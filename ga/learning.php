<?php


//learning
require 'func.php';
 
//'/sites/club/club.tehnosila.ru/vendor/imagine/imagine/tests/Imagine/Fixtures/font/'; //
$fontDir = '/usr/share/fonts/truetype/freefont/'; //'/usr/share/fonts/truetype/tlwg/'; //
$fontDir = './fonts/';


$fonts = array_filter (scandir($fontDir), function($file) use(&$fontDir) { 
	return $file != '.' && $file != '..' && strpos($file, '.ttf') !== false;}); 
 
 

  
//$chars = range('a', 'z') + range('A', 'Z') + range('0', '9') + range('а', 'я')  + range('А', 'Я');
$chars = array_merge(range('a', 'z'), range('A', 'Z'), [0,1,2,3,4,5,6,7,8,9]);
 /*
foreach (range(chr(192),chr(255)) as $v) 
  $chars[] = iconv('CP1251','UTF-8',$v); 
 */

$hashs = [];

foreach($fonts as $font) {
	foreach($chars as $char) {
		$size = [6,7,8,9,10,11,12,13,14,15,16,18,20,22,24];
		foreach($size as $s) {
			$im = imagecreatetruecolor(40, 40);	
			$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
			$black = imagecolorallocate($im, 0x00, 0x00, 0x00); 
			imagefill($im, 0, 0, $white);  //echo $fontDir .  $font . "\n"; exit();
			imagefttext($im, $s, 0, 5, 30, $black, $fontDir .  $font, $char);
			
			$hash = getHash($im); 
			$hashs[strlen($hash)][] = [$hash, $char];
			//imagepng($im, './images/'.md5($font). '_' .$char. '_' . $s . '.png');
			imagedestroy($im);
		}
	}
}
 
foreach($hashs as $length => $data) {

	$file = './data/'.$length.'.json';
	if(file_exists($file )) {
		$dataOld = json_decode( file_get_contents($file ) );
		$data += $dataOld;
	}

	file_put_contents($file , json_encode( $data));
}


