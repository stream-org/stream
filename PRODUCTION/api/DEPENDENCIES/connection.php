<?php

// Connects to your Database 

//////////
// IMPORTANT!!! If connecting to Suman's hostGator server remotely, host is "174.130.60.130". 
// else, you are hosting this file on Suman's hostGator server so the host is simply "localhost"
//////////

 mysql_connect("174.120.60.130", "suman", "ninjas1158!") or die(mysql_error()); 
 mysql_select_db("suman_Stream") or die(mysql_error()); 


?>