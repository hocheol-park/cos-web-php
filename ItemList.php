<?php
	
	class ItemList {
		
		public $itemList;
		
		function __construct($dbhandler) {
			$this->itemList = $dbhandler->get("Item");
		}
		
		function getItemList() {
			return $this->itemList;
		}
	}
	
?>