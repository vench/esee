<?php

$dir = dirname(__FILE__);
require $dir . '/../s.1/lib.php';

$fontDir = '/usr/share/fonts/truetype/tlwg/';//$dir.'/fonts/';


$fonts = array_filter (scandir($fontDir), function($file) use(&$fontDir) { 
	return $file != '.' && $file != '..' && strpos($file, '.ttf') !== false;}); 
$chars = array_merge(range('a', 'z'), range('A', 'Z'), [0,1,2,3,4,5,6,7,8,9]);
 


$hashs = [];



function getPoints($img,  $width, $height) {
	$points = []; 
	$diff = 16777215 / 2;	
	for($y = 0; $y < $height; $y ++) {
		$points[$y] = [];
		for($x = 0; $x < $width; $x ++) {	
			$v =  imagecolorat($img, $x, $y);
			$points[$y][$x] = $v < $diff ?  1 : 0; 		 	
		} 
	}
	return $points;
}

function getFirst($points) {
	foreach($points as $y => $row) {
		foreach($row as $x => $v) {
			if($v == 1) {
				return [$x, $y];
			}
		}
	}
	return [0,0];
}

$c = new \esee\ChainBuilder([]);
 
foreach($fonts as $font) {
	foreach($chars as $char) {//6,7,8,
		$size = [9,10,11,12,13,14,15,16,18,20,22,24];
		foreach($size as $s) {
			$im = imagecreatetruecolor(40, 40);	
			$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
			$black = imagecolorallocate($im, 0x00, 0x00, 0x00); 
			imagefill($im, 0, 0, $white);  //echo $fontDir .  $font . "\n"; exit();
			imagefttext($im, $s, 0, 5, 30, $black, $fontDir .  $font, $char);
			
			$points = getPoints($im, 40, 40); 
			list($x, $y) = getFirst($points);
			$c->reset($points);
			$chain = $c->makeChain2($x, $y);
			//\esee\Helper::view2($chain);
			//print_r($chain->path);	//  exit();
			if(is_null($chain)) {  
				//echo $char . ' '.  $font . "\n";	
				//\esee\Helper::view($points); if($i > 3) exit();
				continue;
			}
			$hash = join('',$chain->path);

			$hashs[strlen($hash)][] = [$hash, $char];
			//imagepng($im, './images/'.md5($font). '_' .$char. '_' . $s . '.png');
			imagedestroy($im);
		}
	}
}
 
foreach($hashs as $length => $data) {

	$file = $dir.'/data/'.$length.'.json';
	if(file_exists($file )) {
		$dataOld = json_decode( file_get_contents($file ) );
		$data = array_merge($data , $dataOld);
	}

	file_put_contents($file , json_encode( $data));
}
