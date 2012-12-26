<?php

//input:: 
//  phone number

//output::
//  formatted phone number

function standardizePhone($phone){
    
    $number = trim(preg_replace('#[^0-9]#s', '', strval($phone)));

    $length = strlen($number);

    if($length <10 || $length > 11) {
        
        $number = "Incorrect Number Input";

    } elseif($length == 10) {

    // Assume US International code
        $number = "1".$number;

    }

    return $number;
} 

?>