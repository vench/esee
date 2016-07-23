<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee;

/**
 * Description of Helper
 *
 * @author vench
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
        
        /**
         * 
         * @param \esee\Chain $c
         * @return array [x, y]
         */
        public static function avg(Chain $c) {
                $n = count($c->op);
                $x = 0; $y = 0;
                for($i = 0; $i < $n; $i ++) {
                    $v = $c->op[$i];
                    $x += $v[0] - $c->minX;
                    $y += $v[1] - $c->minY;
                }
                return [
                    $x / $n,
                    $y / $n,
                ]; 
        }


}