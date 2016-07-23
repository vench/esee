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
     * @var double 
     */
    public $x;
    
    /**
     *
     * @var double
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
     * @param double $x
     * @param double $y
     */
    public function init($char, $x, $y) {
        $this->char = $char;
        $this->x = $x;
        $this->y = $y;
    }
    
    /**
     * 
     * @param double $x
     * @param double $y
     * @return boolean
     */
    public function equalsXY($x, $y) {
        if($this->x == 3.9508196721311) {
            var_dump((float)$x, (float)$y, (float)$this->x, (float)$this->y, ((float)$this->x) -  ((float)$x),  ((float)$this->y) -  ((float)$y));
            echo $x . ' ' . $y . "\n";
        }
        return $this->x == $x && $this->y == $y;
    }
}
