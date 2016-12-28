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
    
    echo $str;
    echo PHP_EOL;
    echo $title;
    echo PHP_EOL;
    
    
    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////

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

    var_dump($h, $w);
    //exit();
   

    $node = $lib->findByTitle($title, $w, $h);
    if(is_null($node)) {
            $node = new Node();
            $node->title = $title;
            $node->weights = $weights;
            $node->learn = 1;
            $node->width = $w;
            $node->height = $h;
    } else {
           $node = Node::updateWeight($weights, $node);
    }


    for($i = 0; $i < 1; $i ++) {
            $node = Node::updateWeight($weights, $node);
    }

    $lib->saveNode($node);
} 