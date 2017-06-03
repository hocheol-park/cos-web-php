<?php
	
	class OrderItemManager {
		
		public $dbhandler;
		
		function __construct() {
			$this->dbhandler = new DBHandler();
		}
		
		function getOrderItemList() {
			$orderItemList = $this->dbhandler->query("SELECT * FROM OrderItem WHERE status = 'waiting' ORDER BY itemId ASC, orderId ASC", true);
			return $orderItemList;
		}
		
	}	
?>