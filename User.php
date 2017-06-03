<?php
	
	class User {
		
		public $userId;
		public $phoneNumber;
		public $fcmToken;
		public $dbhandler;
		public $isRegister;
		
		function __construct($uid) {
			$this->dbhandler = new DBHandler();
			$this->userId = $uid;
			
			$user = $this->dbhandler->where(array("userId" => $this->userId))->get("User");
			
			if($this->dbhandler->num_rows() != 0) {
				$this->phoneNumber = $user[0]['phoneNumber'];
				$this->fcmToken = $user[0]['fcmToken'];
				
				$this->isRegister = true;
			}
			else {
				$this->isRegister = false;
			}
		}
		
		function createUser($uid, $unum, $token) {
			try {
				$this->dbhandler->insert('User', array('userId' => $uid, 'phoneNumber' => $unum, 'fcmToken' => $token));
				$this->phoneNumber = $unum;
				$this->fcmToken = $token;
			} catch(Exception $e) {
				returnJson(400, '', 'Caught exception: ', $e->getMessage());
			}
		}
		
		function updateUser($uid, $unum, $token) {
			if($this->isRegister === false) {
				$this->createUser($uid, $unum, $token);
			}
			else {
				try {
					$this->dbhandler->where('userId', $uid)->update('User', array('phoneNumber' => $unum, 'fcmToken' => $token));
					$this->phoneNumber = $unum;
					$this->fcmToken = $token;
				} catch(Exception $e) {
					returnJson(400, '', 'Caught exception: ', $e->getMessage());
				}
			}
		}
		
		function getUserId() {
			return $this->userId;
		}
		
		function getUserPhone() {
			return $this->phoneNumber;
		}
		
		function getFCMToken() {
			return $this->fcmToken;
		}
	}
	
?>