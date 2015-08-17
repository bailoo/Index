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
	echo "/product/:shopid <br/>\n";
	echo "<br/>\n";	
	echo "/orders/:shopid <br/>\n";
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

// Get local shops
$app->get('/localshop', function() use($app, $db){
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

// Get all products of a shop
$app->get('/product/:shopid/', function ($shopId) use($app, $db) {
    $prods = array();
    foreach ($db->cat() as $prod) {
        $prods[]  = array(
            'id' => $prod['id'],
            'cid' => $prod['cid'],
            'name' => $prod['name'],
            'price' => $prod['price'],
            'photo' => $prod['photo']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($prods, JSON_FORCE_OBJECT);
});

// Add a new product
$app->post('/product', function() use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $app->request()->post();
    $result = $db->cat->insert($prod);
    echo json_encode(array('id' => $result['id']));
});

// Update a product
$app->put('/product/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $db->cat()->where("id", $id);
    if ($prod->fetch()) {
        $post = $app->request()->put();
        $result = $prod->update($post);
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

// Remove a product 
$app->delete('/product/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $db->cat()->where('id', $id);
    if($prod->fetch()){
        $result = $prod->delete();
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

// Get all orders of a shop
$app->get('/orders/:shopid/', function ($shopId) use($app, $db) {
    $shops = array();
    foreach ($db->orders() as $order) {
        $prods[]  = array(
            'id' => $order['id'],
            'pid' => $order['pid'],
            'qty' => $order['qty'],
            'time' => $order['time']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($prods, JSON_FORCE_OBJECT);
});

// Add a new order
$app->post('/orders', function() use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $order = $app->request()->post();
    $result = $db->order->insert($order);
    echo json_encode(array('id' => $result['id']));
});

// Update an order
$app->put('/orders/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $order = $db->order()->where("id", $id);
    if ($order->fetch()) {
        $post = $app->request()->put();
        $result = $order->update($post);
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

// Remove an order 
$app->delete('/orders/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $order = $db->order()->where('id', $id);
    if($order->fetch()){
        $result = $order->delete();
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

/* Run the application */
$app->run();
