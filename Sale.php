<?php
	
	class Sale {
		
		public $salesLineItem;
		public $total;
		public $userId;
		public $orderId;
		
		function __construct($uid) {
			$this->total = 0;
			$this->userId = $uid;
		}
		
		function addLineItem($id, $quan) {
			$this->salesLineItem[] = new SalesLineItem($id, $quan);
		}
		
		function getTotal() {
			foreach($this->salesLineItem as $sli) {
				$this->total += $sli->getSubTotal();
			}
			
			return $this->total;
		}
		
		function endSale() {
			$dbhandler = new DBHandler();
			$description = $this->salesLineItem[0]->item->getItemName();
			if(count($this->salesLineItem) > 1) {
				$description .= " and ".(count($this->salesLineItem)-1)." more items in addition";
			}
			$orderInfo = array(
					'description' => $description,
					'price' => $this->getTotal(),
					'userId' => $this->userId
				);
				
			$dbhandler->insert('OrderInfo', $orderInfo);
			$this->orderId = $dbhandler->insert_id();
			
			$this->makeOrderItem($dbhandler);
		}
		
		function makeOrderItem($dbhandler) {
			
			foreach($this->salesLineItem as $sli) {
				$orderItem = array(
						'orderId' => $this->orderId,
						'itemId' => $sli->getItemId(),
						'amount' => $sli->getQuantity()
					);
				
				try {
					$dbhandler->insert('OrderItem', $orderItem);
				} catch (Exception $e) {
					echo 'Caught exception: ', $e->getMessage();
					
					$dbhandler->where('orderId', $this->orderId)->delete('OrderInfo');
					break;
				}	

			}
		}
		
		function getOrderId() {
			return $this->orderId;
		}
	}
	
?>