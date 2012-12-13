<?php

$sentence = 'hey,you,over,there';

$sentArray = explode(',', $sentence);

//echo $sentArray;

for ($i=0; $i < count($sentArray) ; $i++) { 
	echo $sentArray[$i];
}

?>