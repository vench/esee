<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace esee\provider;

/**
 * Description of ProviderFile
 *
 * @author vench
 */
class ProviderFile extends ProviderArray {
    
    /**
     *
     * @var resource 
     */
    private $fd;
    
    public function __construct($fileName) {
        $this->fd = fopen($fileName, 'a+');
    }
    
    /**
     * 
     * @param \esee\model\Char $char
     */
    public function addChar(\esee\model\Char $char) {
        parent::addChar($char);
        $strChar = "{$char->char}:{$char->x}:{$char->y}\n";
        $offset = ftell($this->fd);
        fwrite($this->fd, $strChar);
        fseek($this->fd, $offset);
    }
    
    /**
     * 
     * @param float $x
     * @param float $y
     * @return \esee\model\Char
     */
    public function findByXY($x, $y) {
        $result = parent::findByXY($x, $y);
        if(is_null($result)) {
            while(!feof($this->fd)) {
                $strChar = fgets($this->fd); 
                $exp = explode(':', $strChar);
                if(sizeof($exp) != 3) {
                    continue;
                }  print_r($exp);
                list($char, $fx, $fy) = $exp;
                $c = new \esee\model\Char();
                $c->init($char, $fx, $fy);
                parent::addChar($c);
                if($c->equalsXY($x, $y)) {
                    $result = $c;
                    break;
                }
            }
        }  exit();
        return $result;
    }
    
    public function __destruct() {
        fclose($this->fd);
    }
}
