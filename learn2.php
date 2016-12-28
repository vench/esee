<?php

require './lib.php';

$chars = array_merge(range(0,9), range('a','z'), range('A','Z'), range(chr(192), chr(255)));
$chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
$chars = range(chr(192), chr(255));
$chars = array_map(function($v){ return iconv('CP1251','UTF-8',$v); }, $chars);


$baseDir = '/home/vench/old/dev/see/test-new/';


$lib = LibChars::getInstance();

foreach($chars as $char) {
        $dir = $baseDir. $char. '/';
        if(!is_dir($dir)) {
                continue;
        }
        
        $scan = scandir($dir);
        
        echo $dir, ": ", count($scan), "\n";
        
        foreach($scan as $file) {
                if($file == '.' || $file == '..') {
                        continue;
                }
                
                $r = new ImageReader( $dir . $file );  
                $s = new Symbol();
                $s->x1 = 0;
                $s->y1 = 0;
                $s->x2 = $r->getWidth();
                $s->y2 = $r->getHeight();
                $s->trim($r);
                $weights = $s->getPointsList($r);
                
                ImageHelper::viewSymbol($s, $r); 
                
                $w = $s->x2 - $s->x1;
                $h = $s->y2 - $s->y1;
                $node = $lib->findByTitle($char, $w, $h);
                if(is_null($node)) {
                        $node = new Node();
                        $node->title = $char;
                        $node->weights = $weights;
	                $node->learn = 1;
	                $node->width = $w;
	                $node->height = $h;
                } else {
                       $node = Node::updateWeight($weights, $node);
                }


                $lib->saveNode($node);
                echo "\n";
                //exit();      
        } 
}
