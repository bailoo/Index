<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->config('debug', true);

$app->get('/', function() use($app) {
	$app->response->setStatus(200);
	echo "MyLocalShoppr API <br/>\n";
	echo "<br/>\n";	
	echo "/getshops/:latitude/:longitude <br/>\n";
	echo "<br/>\n";	
	echo "/getcat/:shopid <br/>\n";
	echo "<br/>\n";	
});

$app->get('/getshops/:latitude/:longitude/', function ($latitude, $longitude) {
	
	//require_once 'libShop.php';
	//$st_results = getShops($latitude, $longitude);

	$st_results = array('shopid' => 1, 'name' => 'Dhingra Grocers', 'latitude' => '28.541369N', 'longitude' => '77.249780E');
	echo json_encode($st_results);

});

$app->get('/getcat/:shopid/', function ($shopId) {
	
	//require_once 'libShop.php';
	//$st_results = getCat($shopId);

	$st_results = array('prodid' => 1, 'name' => 'Water 20L Jar', 'price' => 50);
	echo json_encode($st_results);

});

$app->run();



?>
