<?php
class User{
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$user_details_query = mysqli_query($con,"SELECT * FROM amigo WHERE username ='$user'");
		$this->user=mysqli_fetch_array($user_details_query);
	}

	public function getFirstAndLastName(){
		// $username = $this->user['username'];
		// $query = mysqli_query($this->con,"SELECT first_name,last_name FROM amigo WHERE username='$username'");
		// $row = mysqli_fetch_array($query);
		// return $row['first_name'] . " " . $row['last_name'];
		return $this->user['first_name'] . " " . $this->user['last_name'];
	}

	public function getUsername(){
		return $this->user['username'];
	}

	public function getProfilePic(){
		return $this->user['profile_pic'];
	}

	public function getNumposts(){
		return $this->user['num_posts'];
	}

	public function isFriend($username_to_check){
		$usernameComma = "," . $username_to_check . ",";

		if(strstr($this->user['friends'], $usernameComma) || $username_to_check == $this->user['username']){
			return true;
		} else {
			return false;
		}

	}
}
?>