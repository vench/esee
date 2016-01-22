<?php

namespace esee;

require_once './lib.php';
 
 

 

function optimize($points) {
	return $points;
	 
}
 



// end functions


/**/
$fileName = "x1.png";

$img = imagecreatefrompng($fileName);
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
}/*
/*
$points = [
	[0,0,0,0,0,0,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,0,0,0,0,0,0],
];*/
/*
$points = [
	[0,0,0,0,0,0,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,1,1,0,1,1,0],
	[0,1,1,1,1,1,0],
	[0,1,1,1,1,1,0],
	[0,0,0,0,0,0,0],
];*//*
$points = [
	[0,0,0,1,0,0,0],
	[0,1,1,1,1,1,0],
	[0,1,1,0,1,1,0],
	[1,1,0,0,0,1,1],
	[0,1,1,0,1,1,0],
	[0,1,1,1,1,1,0],
	[0,0,0,1,0,0,0],
];


*//*
$points = [
	[0,0,0,1,0,0,0],
	[0,0,0,1,0,0,0],
	[0,0,0,1,0,0,0],
	[0,1,1,1,1,1,0],
	[0,1,1,0,1,1,0],
	[1,1,0,0,0,1,1],
	[0,1,1,0,1,1,0],
	[0,1,1,1,1,1,0],
	[0,0,0,1,0,0,0],
];
$points = [
	[0,0,0,1,0,0,0,0],
	[0,1,1,1,1,1,0,0],
	[0,1,1,0,1,1,0,0],
	[1,1,0,0,0,1,1,1],
	[0,1,1,0,1,1,0,0],
	[0,1,1,1,1,1,0,0],
	[0,0,0,1,0,0,0,0],
];
$points = [
	[0,0,0,1,1,1,0,0],
 	[0,0,0,0,1,0,0,0],
	[0,0,0,0,1,0,0,0],
	[0,0,0,0,1,0,0,0],
];*/


echo "\n";
$ar = [];
$chainBuilder = new ChainBuilder($points);
foreach($points as $y =>$point) {
	foreach($point as $x => $v) {
		if($v == 1){
			$c = $chainBuilder->makeChain2($x, $y);
			if(is_null($c)) {
				continue;
			} 
			optimize($c);			
			array_push($ar, $c); 
		
			Helper::view2($c); 
			echo "\n";
		}
	}
}

echo "\n";
echo sizeof($ar);
echo "\n";



