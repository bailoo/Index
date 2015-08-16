<?php

//echo getShops('1', '2');

function getShops($latitude, $longitude)
{ 
	require_once 'rb.php';
	R::setup();
	$shop = R::load( 'shop', 1);
	return $shop;
}


?>
