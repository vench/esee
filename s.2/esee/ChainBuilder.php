<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee;

/**
 * Description of ChainBuilder
 *
 * @author vench
 */
class ChainBuilder {

	private static $see = [];

        /**
         *
         * @var Image 
         */
	private $image;
 
 	private $diff;


	public function __construct(Image $image, $diff) {
		$this->image = $image;
		$this->diff = $diff;
	}

	/**
	* @retrn int
	*/
	public  function getValue($x, $y) {
		return $this->image->hasPixel($x, $y) && $this->image->getPixel($x, $y) < $this->diff ? 
			1 : 0;  
	}

	public function hasSee($x, $y)    { 
		return isset(self::$see[$y][$x]);
	}

	function allowPoint($x, $y) {
		return $this->getValue($x,$y) == 1;
	}

	/**
	* @return Chain
	*/
	public  function makeChain($x, $y, $chain = null) {  
		if(isset(self::$see[$y][$x])) {
			return $chain;
		}
		if($this->allowPoint($x,$y)) {
			if(is_null($chain)) {
				 $chain = new Chain(); 
			}
			$chain->addPoint($x, $y);			 
			self::$see[$y][$x] = 1;
			
			$this->makeChain($x + 1, $y, $chain);
			$this->makeChain($x, $y + 1, $chain);
			$this->makeChain($x - 1, $y, $chain);
			$this->makeChain($x, $y - 1, $chain);
			$this->makeChain($x + 1, $y + 1, $chain);
			$this->makeChain($x + 1, $y - 1, $chain);
			$this->makeChain($x - 1, $y - 1, $chain);
			$this->makeChain($x - 1, $y + 1, $chain);		
			
		} 
		return $chain;
	}
}
