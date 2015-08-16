<?php

/* Require Slim and plugins */
require 'Slim/Slim.php';
require 'plugins/NotORM.php';

/* Register autoloader and instantiate Slim */
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

/* Database Configuration */
$dbhost   = 'localhost';
$dbuser   = 'mls';
$dbpass   = 'neon04$MLS';
$dbname   = 'mls';
$dbmethod = 'mysql:dbname=';

$dsn = $dbmethod.$dbname;
$pdo = new PDO($dsn, $dbuser, $dbpass);
$db = new NotORM($pdo);

/* Routes */

// Home route
$app->get('/', function(){
	echo "MyLocalShoppr API <br/>\n";
	echo "<br/>\n";	
	echo "/shop <br/>\n";
	echo "<br/>\n";	
	echo "/shop/:id <br/>\n";
	echo "<br/>\n";	
	echo "/localshops/:latitude/:longitude <br/>\n";
	echo "<br/>\n";	
	echo "/getcat/:shopid <br/>\n";
	echo "<br/>\n";	
	echo "/getorders/:shopid <br/>\n";
	echo "<br/>\n";	
});

// Get all shops
$app->get('/shop', function() use($app, $db){
    $shops = array();
    foreach ($db->shops() as $shop) {
        $shops[]  = array(
            'id' => $shop['id'],
            'name' => $shop['name'],
            'latitude' => $shop['latitude'],
            'longitude' => $shop['longitude']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($shops, JSON_FORCE_OBJECT);
});

// Get a single shop
$app->get('/shop/:id', function($id) use ($app, $db) {
    $app->response()->header("Content-Type", "application/json");
    $shop = $db->shops()->where('id', $id);
    if($data = $shop->fetch()){
        echo json_encode(array(
            'id' => $data['id'],
            'name' => $data['name'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude']
        ));
    }
    else{
        echo json_encode(array(
            'status' => false,
            'message' => "Shop ID $id does not exist"
        ));
    }
});

// Add a new shop
$app->post('/shop', function() use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $shop = $app->request()->post();
    $result = $db->shops->insert($shop);
    echo json_encode(array('id' => $result['id']));
});

// Update a shop
$app->put('/shop/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $shop = $db->shops()->where("id", $id);
    if ($shop->fetch()) {
        $post = $app->request()->put();
        $result = $shop->update($post);
        echo json_encode(array(
            "status" => (bool)$result,
            "message" => "Shop updated successfully"
            ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Shop id $id does not exist"
        ));
    }
});

// Remove a shop
$app->delete('/shop/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $shop = $db->shops()->where('id', $id);
    if($shop->fetch()){
        $result = $shop->delete();
        echo json_encode(array(
            "status" => true,
            "message" => "Shop deleted successfully"
        ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Shop id $id does not exist"
        ));
    }
});

// Get local shops
$app->get('/localshops', function() use($app, $db){
    $shops = array();
    foreach ($db->shops() as $shop) {
        $shops[]  = array(
            'id' => $shop['id'],
            'name' => $shop['name'],
            'latitude' => $shop['latitude'],
            'longitude' => $shop['longitude']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($shops, JSON_FORCE_OBJECT);
});

/* Run the application */
$app->run();
