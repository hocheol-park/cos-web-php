<?php
	
	class SalesLineItem {
		
		public $itemId;
		public $quantity;
		public $item;
		
		function __construct($id, $quan) {
			$this->itemId = $id;
			$this->quantity = $quan;
			$this->item = new Item($this->itemId);
		}
		
		function getItemId() {
			return $this->itemId;
		}
		
		function getQuantity() {
			return $this->quantity;
		}
		
		function getSubTotal() {
			return $this->item->getItemPrice() * $this->quantity;
		}
	}
	
?>