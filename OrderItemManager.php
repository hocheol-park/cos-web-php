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
			
			// OrderItem status changed
			$this->dbhandler->where(array('orderId'=>$orderid, 'itemId'=>$itemid))->update('OrderItem', array('status' => 'done'));
			
			// Check whether it still has 'waiting' status
			$orderItems = $this->dbhandler->where(array('orderId'=>$orderid, 'status'=>'waiting'))->get('OrderItem');
			
			// If it still has waiting then change to progressing
			if( $this->dbhandler->num_rows() > 0 ) {
				$this->dbhandler->where(array('orderId'=>$orderid))->update('OrderInfo', array('status' => 'making'));
			}
			// If it is the last order item
			else {
				$this->dbhandler->where(array('orderId'=>$orderid))->update('OrderInfo', array('status' => 'done'));
				
				$orderinfo = $this->dbhandler->where(array('orderId'=>$orderid))->get('OrderInfo');
				$userid = $orderinfo[0]['userId'];
				$orderDesc = $orderinfo[0]['description'];
				$user = $this->dbhandler->where(array('userId'=>$userid))->get('User');
				
				// SEND PUSH
				$FCM = new GoogleFcm($user[0]['fcmToken'], array("title"=>"Order Complete", "message"=>"Your order [".$orderDesc."] is completed. \nPlease pick up your drinks."));
				$FCM->send();
			}
		}
		
	}	
?>