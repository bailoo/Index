<?php

require ('./vendor/autoload.php');

/* Require Slim and plugins */
require 'plugins/NotORM.php';

/* Register autoloader and instantiate Slim */
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \CorsSlim\CorsSlim());

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
	echo "/shops <br/>\n";
	echo "<br/>\n";	
	echo "/shops/:id <br/>\n";
	echo "<br/>\n";	
	echo "/localshops/:latitude/:longitude <br/>\n";
	echo "<br/>\n";	
	echo "/products/:shopid <br/>\n";
	echo "<br/>\n";	
	echo "/orders/:shopid <br/>\n";
	echo "<br/>\n";	
});

/******************
**  Shops	 **
*******************/

// Get all shops
$app->get('/shops', function() use($app, $db){
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
    echo json_encode($shops);
});

// Get a single shop
$app->get('/shops/:id', function($id) use ($app, $db) {
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
    echo json_encode($shops);
});

// Add a new shop
$app->post('/shop', function() use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $shop = $app->request()->post();
    $result = $db->shops->insert($shop);
    echo json_encode(array('id' => $result['id']));
});

// Update a shop
$app->put('/shops/:id', function($id) use($app, $db){
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
$app->delete('/shops/:id', function($id) use($app, $db){
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

/******************
**  Products	 **
*******************/

// Get all products of a shop
$app->get('/products/:shopid/', function ($shopId) use($app, $db) {
    $prods = array();
    foreach ($db->products() as $prod) {
        $prods[]  = array(
            'id' => $prod['id'],
            'category' => $prod['cid'],	/* category id */
            'name' => $prod['name'],
            'price' => $prod['price'],
            'photo' => $prod['photo']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($prods);
});

// Add a new product
$app->post('/product', function() use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $app->request()->post();
    $result = $db->cat->insert($prod);
    echo json_encode(array('id' => $result['id']));
});

// Update a product
$app->put('/products/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $db->cat()->where("id", $id);
    if ($prod->fetch()) {
        $post = $app->request()->put();
        $result = $prod->update($post);
        echo json_encode(array(
            "status" => (bool)$result,
            "message" => "Product updated successfully"
            ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Product id $id does not exist"
        ));
    }
});

// Remove a product 
$app->delete('/products/:id', function($id) use($app, $db){
    $app->response()->header("Content-Type", "application/json");
    $prod = $db->cat()->where('id', $id);
    if($prod->fetch()){
        $result = $prod->delete();
        echo json_encode(array(
            "status" => true,
            "message" => "Product deleted successfully"
        ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Product id $id does not exist"
        ));
    }
});

/*****************
**  Orders	**
******************/

// Get all orders of a shop
$app->get('/orders/:shopid/', function ($shopId) use($app, $db) {
    $shops = array();
    foreach ($db->orders()->where('sid', $shopId) as $order) {

	$_shop = $db->shops()->where('id', $order['sid'])->fetch();	/* shop ID */
	$shop = $_shop['name'];

	$_user = $db->users()->where('id', $order['uid'])->fetch();	/* buyer ID */
	$buyer = $_user['name']; 
	$mobile = $_user['mobile'];

	$_prod = $db->products()->where('id', $order['pid'])->fetch();	/* shop ID */
	$prod = $_prod['name'];

        $prods[]  = array(
            'id' => $order['id'],			/* order ID */
            'shop' => $shop,
            'status' => $order['status'],
            'buyer' => $buyer,
            'mobile' => $mobile,
            'delivery address' => $order['address'],
            'delivery time' => $order['dtime'],
            'products' => $prod,
            'qty' => $order['qty'],
            'time' => $order['time']
        );
    }
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($prods);
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
            "message" => "Order updated successfully"
            ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Order id $id does not exist"
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
            "message" => "Order deleted successfully"
        ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "message" => "Order id $id does not exist"
        ));
    }
});

/* Run the application */
$app->run();
