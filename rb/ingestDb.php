<?php

require 'rb.php';

R::setup();
$shop = R::dispense( 'shop' );
$shop->name = 'Dhingra Grocers';
$shop->latitude = '28.541369';
$shop->longitude = '77.249780';
$id = R::store( $shop );

?>
