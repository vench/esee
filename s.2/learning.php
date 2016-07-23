<?php

namespace esee;


require_once 'esee/App.php';
App::autoload();

$data = [
    ['т', 4.5, 2.7222222222222],
    ['о', 4.5, 4.5],
    ['0', 4, 6],
    ['0', 5.5, 6],
];


$provider = new provider\ProviderFile('data.txt');

foreach($data as $item) {
    $char = new model\Char();
    $char->init($item[0], $item[1], $item[2]);
    $provider->addChar($char);
} 