<?php


define('VIEW_DEBAG', !true);


require './lib.php';

$dirFiles = '/home/vench/dev/see/test-new/sq/'; ////circl/';
$idFetch = OBJ_ID_SQ;

$sacn = scandir($dirFiles);

$hashObj = getHashTObjects($idFetch );
$hashTags = getTagHash(); 

foreach($sacn as $file) {
	if($file == '.' || $file == '..') {
		continue;
	}
	echo $file . "\n";
	$sing = getDirectionPath($dirFiles.$file);
	if(empty($sing)) {
		echo "...is empty\n";
		continue;
	}

	if(in_array($sing, $hashObj)) {
		echo "...isset\n";
		continue;
	} 

	$id = registerTObject($idFetch, $sing);
	echo "\n";
	echo $sing, '-', $id;
	echo "\n";

	//find tags
	$tags = findTags($sing, $hashObj);
	//add new object to hash
	$hashObj[$id] = $sing;


	if(!empty($tags)) {
		echo "... add ". count($tags) ." tags\n";
		foreach($tags as $tag) {

			list($idref, $tagData) = $tag;
			$tagId = (!isset($hashTags[$tagData])) ? 
				insertTag($tagData) : $hashTags[$tagData];
			$hashTags[$tagData] = $tagId;

			insertTagToTempl($idref, $tagId);
			insertTagToTempl($id, $tagId);
			 		
		}
	}

}
