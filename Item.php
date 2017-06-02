<?php
	
	class Item {
		
		public $dbhandler;
		public $itemId;
		public $itemName;
		public $itemPrice;
		public $itemDesc;
		
		function __construct($id) {
			$this->dbhandler = new DBHandler();
			$this->itemId = $id;
			$this->getItemFromDB();
		}
		
		function getItemFromDB() {
			$item = $this->dbhandler->where(array("id" => $this->itemId))->get("Item");
			$this->itemName = $item[0]['name'];
			$this->itemPrice = $item[0]['price'];
			$this->itemDesc = $item[0]['description'];
		}
		
		function getItemId() {
			return $this->itemId;
		}
		
		function getItemName() {
			return $this->itemName;
		}
		
		function getItemPrice() {
			return $this->itemPrice;
		}
		
		function getItemDesc() {
			return $this->itemDesc;
		}
	}
	
?>