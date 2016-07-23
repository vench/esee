<?php

function getHash($img) {
	$w = imagesx($img);
	$h = imagesy($img);

	$maxX = -1;
	$maxY = -1;
	$minX = $w;
	$minY = $h;
	$ha = [];
	for($x = 0; $x < $w; $x ++) {
		for($y = 0; $y < $h; $y ++) {
			$pixel = imagecolorat($img, $x, $y);
			if(16777215 > $pixel) {
				$ha[$x.'_'.$y] = 1; 
				if($x > $maxX) $maxX = $x;
				if($y > $maxY) $maxY = $y;
				if($x < $minX) $minX = $x;
				if($y < $minY) $minY = $y;
			 }
		}
	}
	$str = '';
	for($x = $minX; $x <= $maxX; $x ++) {
		for($y = $minY; $y <= $maxY; $y ++) {
			$str .= isset($ha[$x.'_'.$y]) ? 1 : 0;
		}
	}
	return $str;
} 
