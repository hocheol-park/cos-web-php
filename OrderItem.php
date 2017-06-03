<?php
	
	class OrderItem {
		
		public $orderId;
		public $itemId;
		public $itemName;
		public $amount;
		public $status;
		
		function __construct($oid) {
			$this->orderId = $oid;
			$this->setOrderItem();
		}
		
		function setOrderItem() {
			$dbhandler = new DBHandler();
			$orderitem = $dbhandler->query("SELECT o.itemId, o.amount, o.status, i.name FROM OrderItem o JOIN Item i ON i.id = o.itemId WHERE o.orderId = ".$this->orderId);
			
			if($dbhandler->num_rows() > 0) {
				$this->itemId = $orderitem[0]['itemId'];
				$this->itemName = $orderitem[0]['name'];
				$this->amount = $orderitem[0]['amount'];
				$this->status = $orderitem[0]['status'];
			}
		}
		
		function getOrderItem() {
			return array(
				"itemId" => $this->itemId, 
				"itemName"=> $this->itemName, 
				"amount" => $this->amount, 
				"status" => $this->status
			);
		}
	}
	
?>