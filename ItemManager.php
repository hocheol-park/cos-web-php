<?php
	
	class ItemManager {
		
		public $dbhandler;
		
		function __construct() {
			$this->dbhandler = new DBHandler();
		}
		
		function addItem($name, $price, $desc) {
			
			$newItem = array(
					"name" => $name,
					"price" => $price,
					"description" => $desc
				);
			$this->dbhandler->insert('Item', $newItem);
			
			return $this->dbhandler->insert_id();
		}
		
		function deleteItem($id) {
			
			$this->dbhandler->where('id', $id)->delete('Item');
		}
		
	}	
?>