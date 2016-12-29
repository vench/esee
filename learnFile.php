<?php

require './lib.php';

$dataFile = './data.txt';
$dataStr = file_get_contents($dataFile);

if(empty($dataStr)) {
    throw new \Exception("Empty file");
}

$list = explode(';', $dataStr); 
$lib = LibChars::getInstance();

foreach($list as $item) {
    list($str, $title) = explode('^', $item);
    
    echo $str, PHP_EOL, "Char: {$title}", PHP_EOL;
     

    $w = 0; 
    $h = 0;

    $weights = [];

    for($i = 0; $i < strlen($str); $i++) {
            $c = substr($str, $i, 1);
            if($c != '.' && $c != '#') {
                    if(!empty($weights)) {
                        $h ++;
                    }
                    continue;
            }
             
            $weights[] = $c == '#' ? 1 : 0;
    }

    $w = count($weights) /  $h;

    echo "Width: {$w}; Height: {$w}", PHP_EOL;
    
    $node = $lib->findByTitle($title, $w, $h);
    if(is_null($node)) {
            $node = new Node();
            $node->title = $title;
            $node->weights = $weights;
            $node->learn = 1;
            $node->width = $w;
            $node->height = $h;
    }   

    $node = Node::updateWeight($weights, $node); 
    $lib->saveNode($node);
} 