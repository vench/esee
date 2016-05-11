<?php

namespace esee;
require dirname(__FILE__) . '/../s.1/lib.php';
require 'func.php';


$fileName = "t.png";
//$fileName = "cap/so.png";

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
		if($a[$i] != $b[$i]) $diff ++;
	}
	return $diff;
}


function makeChain($points, $x, $y, $chain = null) {
	static $see = [];
	if(isset($see[$x][$y])) {
		return $chain;
	}
	$see[$x][$y] = 1;
	if(isset($points[$y][$x]) && $points[$y][$x] == 1) {
		if(is_null($chain)) {
			$chain = new Chain();
		}
		$chain->addPoint($x, $y);


		makeChain($points, $x+1, $y, $chain);
		makeChain($points, $x+1, $y+1, $chain);
		makeChain($points, $x, $y+1, $chain);
		makeChain($points, $x-1, $y, $chain);
		makeChain($points, $x-1, $y-1, $chain);
		makeChain($points, $x, $y-1, $chain); 
		makeChain($points, $x+1, $y-1, $chain);
		makeChain($points, $x-1, $y+1, $chain);
	}

	return $chain;
}


//
$ar = [];
 
$fileData = [];
$result = [];
foreach($points as $y =>$point) {
	foreach($point as $x => $v) {
		if($v == 1){
			$c = makeChain($points, $x, $y);
			if(is_null($c)) {
				continue;
			} 
			
			$str = '';
			for($x = 0; $x <= ($c->maxX - $c->minX); $x ++){
				for($y = 0; $y <= ($c->maxY - $c->minY); $y ++){ 
					$str .= isset($c->p[$y + $c->minY][$x + $c->minX]) ?  '1' : '0';
				}  
			} 
			//echo $str;	
			$length = strlen($str);
			$file = './data/'.$length.'.json';
			if(file_exists($file)) {
				if(!isset($fileData[$length])) {
					$fileData[$length] = json_decode(file_get_contents($file));
				}
				$cont = false;
				$data = $fileData[$length];
				foreach($data as $item) {
					if($str == $item[0]) {
						//print_r($item);
						echo '0 - ' . $item[1] . "\n";
						Helper::view2($c); 
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
				$result[$c->minX . '_' . $c->minY] = $search[1];
				 
				Helper::view2($c);
				
			}
			//array_push($ar, $c); 		
			//Helper::view2($c); 
			//echo "\n";
		}
	}
}	


echo "\n";
ksort($result);
echo join(' ', $result);

echo "\n";
