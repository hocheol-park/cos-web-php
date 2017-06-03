<?php
	
	class Order {
		
		public $userId;
		public $order;
		
		function __construct($uid) {
			$this->userId = $uid;
			$this->setOrderInfo();
		}
		
		function setOrderInfo() {
			$dbhandler = new DBHandler();
			$orderinfo = $dbhandler->where(array("userId" => $this->userId))->get("OrderInfo");
			
			$this->order = array();
			if($dbhandler->num_rows() > 0) {
				foreach($orderinfo as $od) {
					$orderItem = new OrderItem($od['orderId']);
					$od['orderItem'] = $orderItem->getOrderItem();
					$this->order[] = $od;
				}
			}
		}
		
		function getOrderInfo() {
			return $this->order();
		}
	}
	
?>