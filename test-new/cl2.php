<?php



$s1 = '01010000111111010101111110111011110011101';
$s2 = '0101010111010100001110011111010111111101';
$s3 = '01101110101000000111101111000000010000000011';
$a = [$s1, $s2, $s3];

foreach($a as $k=>$s) {
	echo $s . "\n\n";		
	for($i = 0; $i < strlen($s); $i ++){
		$n = 3;
		$findOk;
		$find = [];
		do {
			$findOk = $find;
			$find = [];
			$p = substr($s, $i, $n ++);
			foreach($a as $k1=>$s1) {
				if($k1 != $k && strpos($s1, $p) !== false) {
					$find[$k1]=$p;
				}
			}
			 	
		} while(!empty($find) && $i + $n < strlen($s));
		//TODO save p
		print_r($findOk); //exit();
	}	
	//exit();
}




/*
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

$linies = [];
$line = []; 
$last = null;


for($y = 0; $y < $height; $y ++) {
	for($x = 0; $x < $width; $x ++) {
		 
		
		$a = isDark(imagecolorat($im, $x, $y)) ? 1 : 0;
		if(is_null($last) || $last != $a)  {
			$line[] = $a;
			$last = $a;
			if(sizeof($line) > 2) {
				$linies[] = $line;
				$line = [];
			} 
		}
	}
	 
} 

foreach($linies as $line) {
	echo join('', $line) . "_";
}
 
*/
