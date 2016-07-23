<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee;

/**
 * Description of Chain
 *
 * @author vench
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
		$this->op[] = [$x, $y];
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
