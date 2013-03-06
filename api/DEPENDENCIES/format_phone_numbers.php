<?php

//input:: 
//  phone number

//output::
//  formatted phone number

function standardizePhone($phone){
    
    $number = trim(preg_replace('#[^0-9]#s', '', strval($phone)));

    $length = strlen($number);

    if($length <10) {
        
        $number = "Error! Too Few Numbers: ".$number;

    }elseif($length > 11) {

		$number = "Error! Too Many Numbers: ".$number;

    }elseif($length == 10) {

    // Assume US International code
        $number = "1".$number;

    }

    return $number;
} 

?>