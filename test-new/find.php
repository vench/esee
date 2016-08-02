<?php


define('VIEW_DEBAG', !true);


require './lib.php';


$file = '/home/vench/dev/see/test-new/circl/index.png'; 
$file = '/home/vench/32.jpg';

$s = getDirectionPath($file);
	if(empty($s)) {
		echo "...is empty\n";
		exit();
	}
echo "find tags\n";
$hashTags = getTagHash();
$find = [];
for($i = 0; $i < strlen($s); $i ++){

	$n = MIN_TAG_LENGTH;
	do {
		 
			$p = substr($s, $i, $n ++);
			if(isset($hashTags[$p])) {
				$find[] = $hashTags[$p];
			}
			 	
	} while($i + $n < strlen($s)); 

	
}


//print_r($find);


$sql = 'SELECT o.object_id, sum(g.size) s , count(*) c
	FROM `tag_object_templ` t 
	inner join object_templ o ON (t.object_templ_id = o.id) 
	inner join tag g ON (t.tag_id = g.id)
	WHERE  t.tag_id IN ('.join(',',$find).') 

	GROUP by o.object_id';
$db = getDb(); 

$sth = $db->prepare($sql);
$sth->execute(); 
$res = [];
foreach($sth->fetchAll() as $row) {
	$res[] = [
		'id' => $row['object_id'],
		'count' => $row['c'],
		'sum' => $row['s'],
		'index' => $row['s'] / $row['c'],
		];
}

print_r($res);






echo "\n";
