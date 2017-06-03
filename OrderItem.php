<?php
	
	class OrderItem {
		
		public $orderId;
		public $orderItems;
		
		function __construct($oid) {
			$this->orderId = $oid;
			$this->setOrderItem();
		}
		
		function setOrderItem() {
			$dbhandler = new DBHandler();
			$orderitem = $dbhandler->query("SELECT o.itemId, o.amount, o.status, i.name FROM OrderItem o JOIN Item i ON i.id = o.itemId WHERE o.orderId = ".$this->orderId, true);
			
			$this->orderItems = array();
			if($dbhandler->num_rows() > 0) {
				foreach($orderitem as $oi) {
					$this->orderItems[] = array(
							"itemId" => $oi['itemId'],
							"itemName" => $oi['name'],
							"amount" => $oi['amount'],
							"status" => $oi['status']
					);	
				}
			}
		}
		
		function getOrderItem() {
			return $this->orderItems;
		}
	}
	
?>