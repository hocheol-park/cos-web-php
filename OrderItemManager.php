<?php
	
	class OrderItemManager {
		
		public $dbhandler;
		
		function __construct() {
			$this->dbhandler = new DBHandler();
		}
		
		function getOrderItemList() {
			$orderItemList = $this->dbhandler->query("SELECT oi.*, i.name as itemName FROM OrderItem oi JOIN Item i ON i.id = oi.itemId WHERE oi.status = 'waiting' ORDER BY oi.itemId ASC, oi.orderId ASC", true);
			return $orderItemList;
		}
		
		function changeStatus($orderid, $itemid) {
			
			$this->dbhandler->query("UPDATE OrderItem SET `status`='done' WHERE orderId=".$orderid.", itemId=".$itemid, true);
		}
		
	}	
?>