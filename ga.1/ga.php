<?php

$dir = dirname(__FILE__);
require $dir . '/../s.1/lib.php';
 


$fileName = "x1.png";
$fileName = "cap/55.png";
$fileName = "o.png";

$img =imagecreatefrompng($fileName);;// imagecreatefromjpeg($fileName);//
$width = imagesx($img);
$height = imagesy($img);

echo $width . ' - ' . $height;
echo "\n";

$diff = 16777215 / 2;
$points = [];
 
for($y = 0; $y < $height; $y ++) {
	$points[$y] = [];
	for($x = 0; $x < $width; $x ++) {	
		$v =  imagecolorat($img, $x, $y);
		$points[$y][$x] = $v < $diff ?  1 : 0; 		 	
	} 
}

function comparehash($a, $b) {
	$diff = 0;	
	for($i = 0; $i < strlen($a); $i ++){
		if($a[$i] != $b[$i]) $diff += abs($a[$i] - $b[$i]);
	}
	return $diff;
}


function containt($c1, $c2) {
 	 	
	return false;
}


$fileData = [];
$result = [];
$lastX = '';
$lastC = null;
$chainBuilder = new \esee\ChainBuilder($points);

foreach($points as $y =>$point) {
	foreach($point as $x => $v) {
		if($v == 1){

			
			$c = $chainBuilder->makeChain2($x, $y);
			if(is_null($c)) {
				continue;
			} 

			if(!is_null($lastC) && containt($lastC, $c)) {
				 continue;
				
			}
			$lastC = $c;

			$str = join('',$c->path);
			//echo $str . "\n";

			$length = strlen($str);
			if($length < 2) continue;
			$file = $dir.'/data/'.$length.'.json';
			if(file_exists($file)) {


				if(!isset($fileData[$length])) {
					$fileData[$length] = json_decode(file_get_contents($file));
				}
				$cont = false;
				$data = $fileData[$length];

				//
				foreach($data as $item) {
					if($str == $item[0]) { 
						echo '0 - ' . $item[1] . "\n";
						\esee\Helper::view2($c); 
						//exit();
						$result[$c->minX . '_' . $c->minY] = $item[1];
						$cont = true;
						break;
					}
				}

				if($cont) {
					continue;
				}

				$diffX = -1;
				$search = null;
				foreach($data as $item) {
					$diff = comparehash($str, $item[0]);
					if($diffX == -1 || $diff < $diffX) {
						$diffX = $diff;
						$search =  $item;
					}
				}
				echo $diffX . ' - ' . $search[1] . "\n";
				$result[$c->minX ] = $search[1];
				 
				\esee\Helper::view2($c);
				$min = 200;
				if($c->minX - $lastX > $min) {
					$result[$c->minX + ($c->minY / 2) + 1] = ' ';
				}
				$lastX = $c->minX ;
			}
		}
	}
}



echo join('',$result) . "\n";
