<?php


require './lib.php';

$str = "##.####..
########.
###...###
##.....##
##.....##
##.....##
##.....##
###...###
########.
##.####..
##.......
##.......
##.......";



$title = 'р';


/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

$w = 0; 
$h = 1;

$weights = [];

for($i = 0; $i < strlen($str); $i++) {
        $c = substr($str, $i, 1);
        if($c != '.' && $c != '#') {
                $h ++;
                continue;
        }
        $weights[] = $c == '#' ? 1 : 0;
}

$w = count($weights) /  $h;

var_dump($h, $w);
//exit();
$lib = LibChars::getInstance();

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

 
