<?php
	
	class User {
		
		public $userId;
		public $phoneNumber;
		public $dbhandler;
		
		function __construct($uid, $unum) {
			$this->dbhandler = new DBHandler();
			$this->userId = $uid;
			$this->phoneNumber = $unum;
			$user = $this->dbhandler->where(array("userId" => $this->userId))->get("User");
			
			if(!$user) {
				$this->createUser();
			}
		}
		
		function createUser() {
			try {
				$this->dbhandler->insert('User', array('userId' => $this->userId, 'phoneNumber' => $this->phoneNumber));
			} catch(Exception $e) {
				returnJson(400, '', 'Caught exception: ', $e->getMessage());
			}
		}
		
		function getUserId() {
			return $this->userId;
		}
		
		function getUserPhone() {
			return $this->phoneNumber;
		}
	}
	
?>