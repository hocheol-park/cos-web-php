<?php
	
	// COMMON FUNCTIONS
	function returnJson($code, $data, $msg) {
		$result = array(
				"code" => $code,
				"data" => $data,
				"msg" => $msg
			);
		echo json_encode($result);
		exit;
	}
	
	// CLASSES
	include_once('DBHandler.php');
	include_once('ItemList.php');
	include_once('User.php');
	include_once('Sale.php');
	include_once('SalesLineItem.php');
	include_once('Item.php');
	include_once('ItemManager.php');
	include_once('Order.php');
	include_once('OrderItem.php');
	include_once('OrderItemManager.php');
	
	// MAIN CONTROLLER
	class MainController {
		
		public $dbhandler;
		public $userId;
		
		function __construct($mode, $uid) {
			$this->dbhandler = new DBHandler();
			$this->userId = $uid;
			$this->doFunction($mode);
		}
		
		function doFunction($mode) {
			
			if($mode == "initset") {
				$this->setInitData();
			}
			else if($mode == "updateuser") {
				$this->updateUser();
			}
			else if($mode == "getmenu") {
				$this->getMenu();
			}
			else if($mode == "makesale") {
				$this->makeNewSale();
			}
			else if($mode == "myorder") {
				$this->myOrder();
			}
			else if($mode == "orderlist") {
				$this->orderList();
			}
			else if($mode == "additem") {
				$this->makeNewItem();
			}
		}
		
		function setInitData() {
			
			try {
				/* USER SET in mysql */
				
				// mysql > grant all privileges on cos.* to dev@localhost identified by 'qwer1234';
				// mysql > exit;
				// bash > mysql -u dev -p qwer1234
				// mysql > CREATE DATABASE cos;
				// mysql > use cos;
				// mysql > show tables;
				
				// CREATE TABLE Item
				$createItem  = "CREATE TABLE Item (";
				$createItem .= "id INT(11) unsigned NOT NULL AUTO_INCREMENT, ";
				$createItem .= "name VARCHAR(32) NOT NULL, ";
				$createItem .= "price INT(8) unsigned NOT NULL, ";
				$createItem .= "description VARCHAR(200) NOT NULL, ";
				$createItem .= "PRIMARY KEY (id) );";
				
				$this->dbhandler->query($createItem);
				
				$itemRow = "INSERT INTO Item (name, price, description) VALUES ('Americano', 1000, 'This is Americano. The price is 1,000 won.');";
				
				$this->dbhandler->query($itemRow);
				
				$itemRow = "INSERT INTO Item (name, price, description) VALUES ('Cafe Latte', 2000, 'This is Cafe Latte. The price is 2,000 won.');";
				
				$this->dbhandler->query($itemRow);
				
				$itemRow = "INSERT INTO Item (name, price, description) VALUES ('Cafe Mocca', 2500, 'This is Cafe Mocca. The price is 2,500 won.');";
				
				$this->dbhandler->query($itemRow);
				
				// CREATE TABLE User
				$createUser  = "CREATE TABLE User (";
				$createUser .= "userId VARCHAR(30) NOT NULL, ";
				$createUser .= "phoneNumber VARCHAR(15) NOT NULL, ";
				$createUser .= "fcmToken VARCHAR(50), ";
				$createUser .= "regDate DATETIME NULL DEFAULT CURRENT_TIMESTAMP, ";
				$createUser .= "PRIMARY KEY (userId) );";
				
				$this->dbhandler->query($createUser);
				
				// CREATE TABLE OrderInfo
				$createOrder  = "CREATE TABLE OrderInfo (";
				$createOrder .= "orderId INT(11) unsigned NOT NULL AUTO_INCREMENT, ";
				$createOrder .= "userId VARCHAR(30) NOT NULL, ";
				$createOrder .= "description VARCHAR(50) NOT NULL, ";
				$createOrder .= "price INT(8) unsigned NOT NULL, ";
				$createOrder .= "date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
				$createOrder .= "status ENUM('waiting','making','done') DEFAULT 'waiting', ";
				$createOrder .= "PRIMARY KEY (orderId) );";
				
				$this->dbhandler->query($createOrder);
				
				// CREATE TABLE OrderItem
				$createOrderItem  = "CREATE TABLE OrderItem (";
				$createOrderItem .= "orderId INT(11) unsigned NOT NULL, ";
				$createOrderItem .= "itemId INT(11) unsigned NOT NULL, ";
				$createOrderItem .= "amount INT(3) unsigned NOT NULL, ";
				$createOrderItem .= "status ENUM('waiting','done') DEFAULT 'waiting' )";
				
				$this->dbhandler->query($createOrderItem);
				
			} catch(Exception $e){
				echo 'Caught exception: ', $e->getMessage();
			}
		}
		
		function updateUser() {
			$user = new User($this->userId);
			
			$unum = isset($_GET['unum']) ? $_GET['unum'] : "";
			$token = isset($_GET['token']) ? $_GET['token'] : "";
			
			$user->updateUser($this->userId, $unum, $token);
			
			returnJson(200, "", "update success");
		}
		
		function getMenu() {
			$itemList = new itemList($this->dbhandler);
			returnJson(200, array("list"=>$itemList->getItemList()), "");
		}
		
		function makeNewSale() {
			$user = new User($this->userId);
			$sale = new Sale($user->getUserId());
			
			$data = isset($_GET['data']) ? $_GET['data'] : (isset($_POST['data']) ? $_POST['data'] : "");
			
			$saledata = json_decode($data);
			foreach($saledata as $sd) {
				$sale->addLineItem($sd->id, $sd->quant);
			}
			$sale->endSale();
			returnJson(200, "", "Order success. Your orderId is '".$sale->getOrderId()."'");
		}
		
		function myOrder() {
			$user = new User($this->userId);
			$order = new Order($user->getUserId());
			
			returnJson(200, $order->getOrderInfo(), "Get List");
		}
		
		function orderList() {
			$oim = new OrderItemManager();
			returnJson(200, $oim->getOrderItemList(), "Get Order List");
		}
		
		function makeNewItem() {
			$name = isset($_GET['name']) ? $_GET['name'] : "";
			$price = isset($_GET['price']) ? $_GET['price'] : "";
			$desc = isset($_GET['desc']) ? $_GET['desc'] : "";
			
			$itemManager = new ItemManager();
			$newItemId = $itemManager->addItem($name, $price, $desc);
			returnJson(200, "", "Add new item. Id : ".$newItemId);
		}
		
	}
	
	// Declare MainController Object
	
	// Param1 mode : the function which is requested from Android
	// Param2 uid : device id from Android
	// Param3 unum : phone number from Android
	
	$mode = isset($_GET['mode']) ? $_GET['mode'] : (isset($_POST['mode']) ? $_POST['mode'] : "");
	$uid = isset($_GET['uid']) ? $_GET['uid'] : (isset($_POST['uid']) ? $_POST['uid'] : "");
	
	$mainController = new MainController($mode, $uid);
	
?>