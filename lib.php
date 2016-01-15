<?php

namespace esee;

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


/**
*
*/
class Chain {
	public $minX = null;
	public $maxX = null;
	public $minY = null;
	public $maxY = null;	
	public $p = [];

	public function addPoint($x, $y) {
		$this->p[$y][$x] = 1;
		if(is_null($this->minX) || $this->minX > $x) {
			$this->minX = $x;
		}
		if(is_null($this->maxX) || $this->maxX < $x) {
			$this->maxX = $x;
		}
		if(is_null($this->minY) || $this->minY > $y) {
			$this->minY = $y;
		}
		if(is_null($this->maxY) || $this->maxY < $y) {
			$this->maxY = $y;
		}
	}
}

/**
*
*/
class Cluster {

}


function getValue(&$points, $x, $y) {
	return isset($points[$y][$x]) ? $points[$y][$x] : 0; 
}
