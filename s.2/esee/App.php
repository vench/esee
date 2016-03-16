<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee;

/**
 * Description of App
 *
 * @author vench
 */
class App {

    /**
     * 
     * @param string $dir
     */
    public static function autoload($dir = null) {
        if(is_null($dir)) {
            $dir = dirname(__FILE__) . '/../';
        }
        spl_autoload_register(function($className) use (&$dir){  
            if(class_exists($className)) {
                return true;
            }
            $fileName = str_replace('\\', '/', $className) . '.php';
            include_once $dir.$fileName;
        });
    }
}
