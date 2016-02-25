<?php

namespace esee;

require_once './lib.php';

var_dump( hex2bin(md5('csa')) );

echo strcasecmp('-<<<|<><><**><><*><<<><><|<><><|<><<<|<|<*','-<*');
echo "\n";
echo strcasecmp('a','a');
echo "\n";
echo strcasecmp('2cccccsasaa','a');
echo "\n";
exit();
 
/**
* @param array $map  int[y][x]
*/ 
function rastToVector($map) {
	$ret = [];
	//var_dump($map); exit();
	$see = [];
	foreach($map as $y => $line){
		foreach($line as $x => $value) {
			//echo $value ? '#' : '0'; 
			if(!isset($see[$y][$x]) && $value == 1) {
				
				//get point
				$p = [$x, $y, $x, $y]; 
				$see[$y][$x] = 1;
				
				$i = 1;
				//xy right
				while(!isset($see[$y + $i][$x + $i]) && isset($map[$y + $i][$x + $i]) && $map[$y + $i][$x + $i] == 1) {
					$see[$y +  $i][$x + $i] = 1;	 
					$p[2] = $x + $i;
					$p[3] = $y + $i ++;
				}
				if($i > 1) {
					$see[$y][$x] = 1;
					$ret[] = $p; 
					continue;
				}
				$i = 1;
				//xy left
				while(!isset($see[$y + $i][$x - $i]) && isset($map[$y + $i][$x - $i]) && $map[$y + $i][$x - $i] == 1) {
					$see[$y +  $i][$x - $i] = 1;	 
					$p[2] = $x - $i;
					$p[3] = $y + $i ++;  
				}
				if($i > 1) {
					$see[$y][$x] = 1;	
					$ret[] = $p; 
					continue;
				}
				/**/

				
				$i = 1;
				//x
				while(!isset($see[$y][$x + $i]) && isset($map[$y][$x + $i]) && $map[$y][$x + $i] == 1) {
					$see[$y][$x + $i] = 1;
					$p[2] = $x + $i ++; 
				} 
				if($i > 1) {
					$ret[] = $p;
					continue;
				}
				$i = 1;
				//y
				while(!isset($see[$y + $i][$x]) && isset($map[$y + $i][$x]) && $map[$y + $i][$x] == 1) {
					$see[$y +  $i][$x] = 1;
					$p[3] = $y + $i ++;
				}
				if($i > 1) {
					$ret[] = $p;
					continue;
				}

				$ret[] = $p; /**/
				
			}
		}
		//echo "\n"; 
	}

	foreach($map as $y => $line){
		foreach($line as $x => $value) {
			if(!isset($see[$y][$x]) && $value == 1) {
				
				//get point
				$p = [$x, $y, $x, $y]; 
				$see[$y][$x] = 1;
				
				$i = 1;
				//x
				while(!isset($see[$y][$x + $i]) && isset($map[$y][$x + $i]) && $map[$y][$x + $i] == 1) {
					$see[$y][$x + $i] = 1;
					$p[2] = $x + $i ++; 
				} 
				if($i > 1) {
					$ret[] = $p;
					continue;
				}
				$i = 1;
				//y
				while(!isset($see[$y + $i][$x]) && isset($map[$y + $i][$x]) && $map[$y + $i][$x] == 1) {
					$see[$y +  $i][$x] = 1;
					$p[3] = $y + $i ++;
				}
				if($i > 1) {
					$ret[] = $p;
					continue;
				}

				$ret[] = $p;  
			}
		}
	}

	 

	return $ret;
}

 

function optimize(Chain $claster) { exit();
	$p = $claster->p;
	$see = [];	
	foreach($p as $y => $row) {
		foreach($row as $x => $val) {
			if(isset($see[$y][$x])) {
				continue;
			}
			if(isset($p[$y][$x+1])) {
				
			}
			if(isset($p[$y][$x+1])) {
				
			}
		}
	}
	
	return $claster; 
}
 



// end functions


/**/
$fileName = "x1.jpg";
//$fileName = "cap/1.jpg";

$img = imagecreatefromjpeg($fileName);//imagecreatefrompng($fileName);;//
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


echo "\n";
$ar = [];
$hash = [];
$words = require 'dic.php';

$chainBuilder = new ChainBuilder($points);
foreach($points as $y =>$point) {
	foreach($point as $x => $v) {
		if($v == 1){
			$c = $chainBuilder->makeChain2($x, $y);
			if(is_null($c)) {
				continue;
			} 
					
			array_push($ar, $c); 		
			Helper::view2($c); 
			echo "\n";
			
			//optimize($c);	
			$mat = rastToVector($c->p);
			//print_r( $mat );


			$key = '';	
			foreach($mat as $m) {				
				if($m[0] == $m[2] && $m[1] == $m[3]) {
					$key .= '*';
				}
				if($m[0] == $m[2] && $m[1] > $m[3]) {
					$key .= '|>';
				}
				if($m[0] == $m[2] && $m[1] < $m[3]) {
					$key .= '|<';
				}
				if($m[0] > $m[2] && $m[1] == $m[3]) {
					$key .= '->';
				}
				if($m[0] < $m[2] && $m[1] == $m[3]) {
					$key .= '-<';
				}
				if($m[0] < $m[2] && $m[1] < $m[3]) {
					$key .= '<<';
				}
				if($m[0] > $m[2] && $m[1] > $m[3]) {
					$key .= '>>';
				}
				if($m[0] > $m[2] && $m[1] < $m[3]) {
					$key .= '><';
				}
				if($m[0] < $m[2] && $m[1] > $m[3]) {
					$key .= '<>';
				}
			}
			if($key == '*') {
				continue;
			}
			
			//echo $key;
			//echo "\n";
			$keyh = md5($key);
			

			if(isset($words[$keyh])) { 
				echo ' - ' .$words[$keyh]; echo  "\n";  
			} else {
				echo $keyh; echo  "\n";
				exit();
			}
			echo  "\n"; 
			if(!isset($hash[$key]) ) $hash[$key] = 0;
		 //exit;
			$hash[$key] ++;
		}
	}
}

echo "\n";
echo sizeof($ar);
echo "\n";
asort($hash);


print_r($hash);

echo "\n";

