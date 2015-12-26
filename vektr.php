<?php


function view($points) {
	$l = sizeof($points[0]);
	for($y = 0; $y < sizeof($points); $y ++) {
		for($x = 0; $x < $l; $x ++) {	 
			 echo $points[$y][$x] ? '#' : '.';		 	
		} 
		echo "\n";
	}	
}

function view2($points) {
	$xMin = 999999;
	$yMin = 999999;	
	$xMax = 0;
	$yMax = 0;

	//print_r($points);
	
	foreach($points as $y=>$point) {
		$yMin = min($yMin, $y);
		$yMax = max($yMax, $y);
		foreach($point as $x =>$p) {
			$xMin = min($xMin, $x);
			$xMax = max($xMax, $x);
		}
	}
//var_dump($xMin, $xMax);
	for($y = 0; $y <= ($yMax - $yMin); $y ++){
		for($x = 0; $x <= ($xMax - $xMin); $x ++){
			echo isset($points[$y + $yMin][$x + $xMin]) ?  '#' : '.';
		}

		echo "\n";
	}

}

function getValue(&$points, $x, $y) {
	return isset($points[$y][$x]) ? $points[$y][$x] : 0; 
}

function inChain($x, $y, $x1, $y1, &$points) {

	if(getValue($points,$x,$y) != 1 || 
		getValue($points,$x1,$y1) != 1) {
		return false;
	}
	if($x == $x1 && abs($y - $y1) == 1 && (
		getValue($points,$x - 1,$y1) == 0 ||
		getValue($points,$x - 1,$y ) == 0 ||
		getValue($points,$x + 1,$y1) == 0 ||
		getValue($points,$x + 1,$y ) == 0
		) && (
1
	)) {
		return true;
	} 
	if($y == $y1 && abs($x - $x1) == 1 && (
		getValue($points,$x, $y - 1) == 0 ||
		getValue($points,$x1, $y + 1) == 0 ||
		getValue($points,$x, $y - 1) == 0 ||
		getValue($points,$x1, $y + 1) == 0
		) && (
		1  
)) {
		return true;
	}
	if(abs($y - $y1) == 1 && abs($x - $x1) == 1 && ( 
		getValue($points,$x1, $y) == 0 ||
		getValue($points,$x, $y1) == 0 	 
		)) {
		return true;
	}
	
	return false;
}

function makeChain(&$points, $x, $y, &$chain = null) { 
	static $see = [];

	if(isset($see[$y][$x])) {
		return $chain;
	}/*
	if(getValue($points,$x,$y) == 1 && ( 
		getValue($points,$x + 1,$y) == 0 ||
		getValue($points,$x,$y + 1) == 0 ||
		getValue($points,$x - 1,$y) == 0 ||
		getValue($points,$x,$y - 1) == 0 ||
		getValue($points,$x + 1,$y + 1) == 0 ||
		getValue($points,$x - 1,$y - 1) == 0 ||
		getValue($points,$x - 1,$y + 1) == 0 ||
		getValue($points,$x + 1,$y - 1) == 0
	)) {

		if(is_null($chain)) {
			$chain = [];
		}
		$chain[$y][$x] = 1;

		
		
		makeChain($points, $x + 1, $y, $chain);	
		makeChain($points, $x - 1, $y, $chain);
		makeChain($points, $x, $y + 1, $chain);
		makeChain($points, $x, $y - 1, $chain);
		makeChain($points, $x + 1, $y + 1, $chain);
		makeChain($points, $x - 1, $y - 1, $chain);
		makeChain($points, $x + 1, $y - 1, $chain);
		makeChain($points, $x - 1, $y + 1, $chain);
	}*/


	if(getValue($points,$x,$y) == 1 && ( 
		getValue($points,$x + 1,$y) == 0 ||
		getValue($points,$x,$y + 1) == 0 ||
		getValue($points,$x - 1,$y) == 0 ||
		getValue($points,$x,$y - 1) == 0 ||
		getValue($points,$x + 1,$y + 1) == 0 ||
		getValue($points,$x - 1,$y - 1) == 0 ||
		getValue($points,$x - 1,$y + 1) == 0 ||
		getValue($points,$x + 1,$y - 1) == 0/**/
	)) {
//echo $x . ' ' .$y . ' x' . "\n";
		if(is_null($chain)) {
			$chain = [];
		}
		$chain[$y][$x] = 1;
		$see[$y][$x] = 1;
		//$lx = $x; $ly = $y;

		if(inChain($x, $y, $x + 1, $y, $points)) {
			makeChain($points, $x + 1, $y, $chain);	
		} else {
			//$see[$y][$x + 1] = 1;
		}
		if(inChain($x, $y, $x , $y + 1, $points)) {
			makeChain($points, $x , $y + 1, $chain);	
		} else {
			///$see[$y + 1][$x] = 1;
		}
		if(inChain($x, $y, $x -1, $y, $points)) {
			makeChain($points, $x - 1, $y, $chain);	
		} else {
			//$see[$y][$x-1] = 1;
		}
		if(inChain($x, $y, $x, $y-1, $points)) {
			makeChain($points, $x, $y-1, $chain);	
		} else {
			//$see[$y-1][$x] = 1;
		}
		if(inChain($x, $y, $x+1, $y+1, $points)) {
			makeChain($points, $x+1, $y+1, $chain);	
		} else {
			//$see[$y +1][$x+1] = 1;
		}
		if(inChain($x, $y, $x-1, $y-1, $points)) {
			makeChain($points, $x-1, $y-1, $chain);	
		} else {
			//$see[$y-1][$x-1] = 1;
		}
		if(inChain($x, $y, $x+1, $y-1, $points)) {
			makeChain($points, $x-1, $y-1, $chain);	
		} else {
			//$see[$y-1][$x+1] = 1;
		}
		if(inChain($x, $y, $x-1, $y+1, $points)) {
			makeChain($points, $x-1, $y-1, $chain);	
		} else {
			//$see[$y+1][$x-1] = 1;
		} /*/*
		makeChain($points, $x + 1, $y, $chain);	
		makeChain($points, $x - 1, $y, $chain);
		makeChain($points, $x, $y + 1, $chain);
		makeChain($points, $x, $y - 1, $chain);
		makeChain($points, $x + 1, $y + 1, $chain);
		makeChain($points, $x - 1, $y - 1, $chain);
		makeChain($points, $x + 1, $y - 1, $chain);
		makeChain($points, $x - 1, $y + 1, $chain);*/
	}
//$see[$y][$x] = 1;

	

	return $chain;
}

function optimize($points) {
	return $points;
	 
}

function allowPoint(&$points, $x, $y) {
	return getValue($points,$x,$y) == 1 && ( 
		getValue($points,$x + 1,$y) == 0 ||
		getValue($points,$x,$y + 1) == 0 ||
		getValue($points,$x - 1,$y) == 0 ||
		getValue($points,$x,$y - 1) == 0 ||
		getValue($points,$x + 1,$y + 1) == 0 ||
		getValue($points,$x - 1,$y - 1) == 0 ||
		getValue($points,$x - 1,$y + 1) == 0 ||
		getValue($points,$x + 1,$y - 1) == 0
	);
}


function makeChain2(&$points, $x, $y, &$chain = null) { 
	static $see = [];

	if(isset($see[$y][$x])) {
		return $chain;
	}
	if(allowPoint($points,$x,$y)) {
		if(is_null($chain)) {
			$chain = [];
		}
		$chain[$y][$x] = 1;
		

		//$points = [];
		$cx = $x;
		$cy = $y;
		$n = 0;	
		$w = 0;
		while(true) { 
		 
			if($n ++ > 0 && $x == $cx && $y == $cy) break;
		//$see = [];
			//n 
			if($w < 1 && !isset($see[$cy-1][$cx]) && allowPoint($points,$cx,$cy-1) ) {
				$see[$cy-1][$cx] = 1;
				$cy = $cy - 1;
				$chain[$cy][$cx] = 1; echo 'n ';
				$w = 7; 
				continue;
			}
			//n&o 
			if($w < 2 && !isset($see[$cy-1][$cx+1]) && allowPoint($points,$cx+1,$cy-1) ) {
				$see[$cy-1][$cx+1] = 1;
				$cy = $cy - 1;
				$cx = $cx + 1;
				$chain[$cy][$cx] = 1; echo 'n&o '; 
				$w = 0;
				continue;
			}
			//o
			if($w < 3 && !isset($see[$cy][$cx+1]) && allowPoint($points,$cx+1,$cy) ) {
				$see[$cy][$cx+1] = 1;
				$cx = $cx + 1;
				$chain[$cy][$cx] = 1; echo 'o ';
				$w = 1;
				continue;
			}
			//s&o
			if($w< 4&& !isset($see[$cy+1][$cx+1]) && allowPoint($points,$cx+1,$cy+1) ) {
				$see[$cy+1][$cx+1] = 1;
				$cx = $cx + 1;
				$cy = $cy + 1;	
				$chain[$cy][$cx] = 1; echo 's&o ';
				$w = 2;	
				continue;
			}
			//s 
			if($w <5 && !isset($see[$cy+1][$cx]) && allowPoint($points,$cx,$cy+1) ) {
				$see[$cy+1][$cx] = 1;
				$cy = $cy + 1;
				$chain[$cy][$cx] = 1; echo 's ';
				$w = 3;
				continue;
			}
			//s&w 
			if($w <6 && !isset($see[$cy+1][$cx-1]) && allowPoint($points,$cx-1,$cy+1) ) {
				$see[$cy+1][$cx-1] = 1;
				$cy = $cy + 1;
				$cx = $cx - 1;
				$chain[$cy][$cx] = 1; echo 's&w ';
				$w = 4;
				continue;
			}
			//w
			if($w<7 && !isset($see[$cy][$cx-1]) && allowPoint($points,$cx-1,$cy) ) {
				$see[$cy][$cx-1] = 1;
				$cx = $cx - 1;
				$chain[$cy][$cx] = 1; echo 'w ';
				$w =5;
				continue;
			}
			//n&w
			if($w <8 && !isset($see[$cy-1][$cx-1]) && allowPoint($points,$cx-1,$cy-1) ) {
				$see[$cy-1][$cx-1] = 1;
				$cx = $cx - 1;
				$cy = $cy - 1;
				$chain[$cy][$cx] = 1; echo 'n&w ';
				$w = 6;
				continue;
			}
			echo "\n";
			if($w > 0) {
				$w = 0;
				continue;
			}
			break;
		}
echo "\n";
		$see[$y][$x] = 1;
	}
	

	return $chain;
}

// end functions



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
*/


echo "\n";

foreach($points as $y =>$point) {
	foreach($point as $x => $v) {
		if($v == 1){
			$c = makeChain2($points, $x, $y);
			if(is_null($c)) {
				continue;
			}
			//print_r($c);
			 
			view2($c);/*
			//print_r($c);
			foreach($c as $p) {
				list($x1,$y1) = $p;
				echo str_repeat('x', $x1 - 303);
				echo str_repeat('y', $y1 - 190);
				echo "\n";
			}*/
		}
	}
}


