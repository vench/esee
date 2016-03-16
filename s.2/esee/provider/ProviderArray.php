<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee\provider; 

use esee\model\Char;

/**
 * Description of Provider
 *
 * @author vench
 */
class ProviderArray implements IProviderData {
    
    /**
     *
     * @var \esee\model\Char[]
     */
    private $data = [];
 
    public function addChar(Char $char) { 
        $this->data[] = $char;
    }

    /**
     * 
     * @param float $x
     * @param float $y
     * @return \esee\model\Char
     */
    public function findByXY($x, $y) {
       foreach($this->data as $char) {
           if($char->equalsXY($x, $y)) {
               return $char;
           }
       }
       return null;
    }

}
