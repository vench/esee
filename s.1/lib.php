<?php

namespace esee;

/**
*
*/
class Helper {

	/**
	* @param array $points points [[x,y], [...]]
	*/
	public static function view($points) {
		$l = sizeof($points[0]);
		for($y = 0; $y < sizeof($points); $y ++) {
			for($x = 0; $x < $l; $x ++) {	 
				 echo $points[$y][$x] ? '#' : '.';		 	
			} 
			echo "\n";
		}	
	}

	/**
	* @param Chain $c
	*/
	public static function view2(Chain $c) { 
		for($y = 0; $y <= ($c->maxY - $c->minY); $y ++){
			for($x = 0; $x <= ($c->maxX - $c->minX); $x ++){
				echo isset($c->p[$y + $c->minY][$x + $c->minX]) ?  '#' : '.';
			} 
			echo "\n";
		} 
	}


}

/**
*
*/
class ChainBuilder { 

	private static $see = [];


	private $points;

	private $recus = 0;

	private $chain;


	public function __construct($points) {
		$this->points = $points;
	}

	/**
	* @retrn int
	*/
	public  function getValue($x, $y) {
		return isset($this->points[$y][$x]) ? $this->points[$y][$x] : 0; 
	}

	public function hasSee($x, $y)    {
		if(!isset(self::$see[$y][$x])) {
			return true;
		}
		
		if($this->recus < 1 && isset($this->chain->p[$y][$x])) {
			$this->recus ++;
			return true;
		}
		return false;
	}

	function allowPoint($x, $y) {
		return $this->getValue($x,$y) == 1 && ( 
			$this->getValue($x + 1,$y) == 0 ||
			$this->getValue($x,$y + 1) == 0 ||
			$this->getValue($x - 1,$y) == 0 ||
			$this->getValue($x,$y - 1) == 0 ||
			$this->getValue($x + 1,$y + 1) == 0 ||
			$this->getValue($x - 1,$y - 1) == 0 ||
			$this->getValue($x - 1,$y + 1) == 0 ||
			$this->getValue($x + 1,$y - 1) == 0
		);
	}

	/**
	* @return Chain
	*/
	public  function makeChain2($x, $y) {  
		$this->recus  = 0; //?
		$this->chain = null;
		if(isset(self::$see[$y][$x])) {
			return $this->chain;
		}
		if($this->allowPoint($x,$y)) {
			$this->chain = new Chain(); 
			$this->chain->addPoint($x, $y);
			 
			$cx = $x;
			$cy = $y;
			 
			$n = 0;	
			$w = 0; 
			 
			while(true) { 		 
				if($n ++ > 0 && $x == $cx && $y == $cy) {
					break;
				}
				//n 
				if($w < 1 && $this->hasSee($cx,$cy-1) && $this->allowPoint($cx,$cy-1) ) { //echo 'n ';
					$cy = $cy - 1;
					self::$see[$cy][$cx] = 1;				
					$this->chain->addPoint($cx, $cy); 
					$w = 7;  	
					continue;
				}
				//n&o 
				if($w < 2 && $this->hasSee($cx+1,$cy-1) && $this->allowPoint($cx+1,$cy-1) ) { //echo 'n&o '; 
					$cy = $cy - 1;
					$cx = $cx + 1;
					self::$see[$cy][$cx] = 1; 
					$this->chain->addPoint($cx, $cy);
					$w = 0; 
					continue;
				}
				//o
				if($w < 3 && $this->hasSee($cx+1,$cy) && $this->allowPoint($cx+1,$cy) ) {// echo 'o ';
					$cx = $cx + 1;
					self::$see[$cy][$cx] = 1;				
					$this->chain->addPoint($cx, $cy);
					$w = 1;  
					continue;
				}
				//s&o
				if($w < 4 && $this->hasSee($cx+1,$cy+1) && $this->allowPoint($cx+1,$cy+1) ) { //echo 's&o ';
					$cx = $cx + 1;
					$cy = $cy + 1;
					self::$see[$cy][$cx] = 1;					
					$this->chain->addPoint($cx, $cy); 
					$w = 2; 	
					continue;
				}
				//s 
				if($w < 5 && $this->hasSee($cx,$cy+1) && $this->allowPoint($cx,$cy+1) ) { //echo 's ';
					$cy = $cy + 1;
					self::$see[$cy][$cx] = 1; 
					$this->chain->addPoint($cx, $cy); 
					$w = 3; 
					continue;
				}
				//s&w 
				if($w < 6 && $this->hasSee($cx-1,$cy+1) && $this->allowPoint($cx-1,$cy+1) ) { //echo 's&w ';
					$cy = $cy + 1;
					$cx = $cx - 1;
					self::$see[$cy][$cx] = 1; 
					$this->chain->addPoint($cx, $cy); 
					$w = 4; 
					continue;
				}
				//w
				if($w < 7 && $this->hasSee($cx-1,$cy) && $this->allowPoint($cx-1,$cy) ) { //echo 'w ';
					$cx = $cx - 1;
					self::$see[$cy][$cx] = 1; 
					$this->chain->addPoint($cx, $cy); 
					$w =5; 
					continue;
				}
				//n&w
				if($w < 8 && $this->hasSee($cx-1,$cy-1) && $this->allowPoint($cx-1,$cy-1) ) {//echo 'n&w '; 
					$cx = $cx - 1;
					$cy = $cy - 1;
					self::$see[$cy][$cx] = 1;				
					$this->chain->addPoint($cx, $cy); 
					$w = 6; 
					continue;
				}
				 
				if($w > 0) {
					$w = 0; 
					continue;
				} 
				break;
			} 
			self::$see[$y][$x] = 1;
		}
	

		return $this->chain;
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

	/**
	* @var array points [[x,y], [...]]
	*/
	public $p = [];

	/**
	* @var array optimize points [[x,y], [...]]
	*/
	public $op = [];

	/**
	* @param int $x
	* @param int $y
	*/
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





