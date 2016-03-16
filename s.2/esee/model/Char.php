<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee\model;

/**
 * Description of Char
 *
 * @author vench
 */
class Char {
    
    /**
     *
     * @var float 
     */
    public $x;
    
    /**
     *
     * @var float
     */
    public $y;
    
    /**
     *
     * @var string 
     */
    public $char;
    
    /**
     * 
     * @param string $char
     * @param float $x
     * @param float $y
     */
    public function init($char, $x, $y) {
        $this->char = $char;
        $this->x = $x;
        $this->y = $y;
    }
    
    /**
     * 
     * @param float $x
     * @param float $y
     * @return boolean
     */
    public function equalsXY($x, $y) {
        return $this->x == $x && $this->y == $y;
    }
}
