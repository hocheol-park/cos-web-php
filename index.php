<?php
	
	// COMMON FUNCTIONS
	function returnJson($code, $data, $msg) {
		$result = array(
				"code" => $code,
				"data" => $data,
				"msg" => $msg
			);
		echo json_encode($result);
	}
	
	// CLASSES
	include_once('DBHandler.php');
	include_once('ItemList.php');
	include_once('User.php');
	include_once('Sale.php');
	include_once('SalesLineItem.php');
	include_once('Item.php');
	
	// MAIN CONTROLLER
	class MainController {
		
		public $dbhandler;
		public $userId;
		public $userNum;
		
		function __construct($mode, $uid, $unum) {
			$this->dbhandler = new DBHandler();
			$this->setUser($uid, $unum);
			$this->doFunction($mode);
		}
		
		function setUser($uid, $unum) {
			//$this->user = new User($uid, $unum);
			$this->userId = $uid;
			$this->userNum = $unum;
		}
		
		function doFunction($mode) {
			
			if($mode == "initset") {
				$this->setInitData();
			}
			else if($mode == "getmenu") {
				$this->getMenu();
			}
			else if($mode == "makesale") {
				$this->makeNewSale();
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
		
		function getMenu() {
			$itemList = new itemList($this->dbhandler);
			returnJson(200, array("list"=>$itemList->getItemList()), "");
		}
		
		function makeNewSale() {
			$user = new User($this->userId, $this->userNum);
			$sale = new Sale($user->getUserId());
			
			$saledata = json_decode($_GET['data']);
			foreach($saledata as $sd) {
				$sale->addLineItem($sd->id, $sd->quant);
			}
			$sale->endSale();
		}
		
	}
	
	// Declare MainController Object
	
	// Param1 mode : the function which is requested from Android
	// Param2 uid : device id from Android
	// Param3 unum : phone number from Android
	
	$mode = isset($_GET['mode']) ? $_GET['mode'] : "";
	$uid = isset($_GET['uid']) ? $_GET['uid'] : "";
	$unum = isset($_GET['unum']) ? $_GET['unum'] : "";
	
	$mainController = new MainController($mode, $uid, $unum);
	
?>