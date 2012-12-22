<?php

$password = $_GET['password'];

$salt = 11;

$hash = hash('sha256', $salt . $password);

for ($i = 0; $i < 10000; $i++)
{
	$hash = hash('sha256', $hash);
}

echo $hash;


?>