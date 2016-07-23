<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee\provider;

use esee\model\Char;

/**
 *
 * @author vench
 */
interface IProviderData {

    /**
     * 
     * @param float $x
     * @param float $y
     * @return Char Description
     */
    function findByXY($x, $y);
    
    /**
     * 
     * @param Char $char
     */
    function addChar(Char $char);
}
