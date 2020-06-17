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

	public function myFriend($username_to_check){
		$usernameComma = "," . $username_to_check . ",";
		if(strstr($this->user['friends'], $usernameComma)){
			return true;
		} else {
			return false;
		}

	}

	public function isClosed(){
		if ($this->user['account'] == 'closed'){
			return true;
		} else {
			return false;
		}
	}

	public function RemoveFriend($unFriend){
		//Remove friends from current user list
		$userlogged = $this->user['username'];
		$friendsList = $this->user['friends'];
		$removeFriend = $unFriend . ",";
		$newFriendsList = str_replace($removeFriend,"",$friendsList);
		$updateFriendsList = mysqli_query($this->con, "UPDATE amigo SET friends = '$newFriendsList' WHERE username ='$userlogged'");
	}

	public function SentFriendRequest($sent_by,$sent_to){
		$request=mysqli_query($this->con, "SELECT * FROM friendRequests WHERE from_user = '$sent_by' AND to_user = '$sent_to'");
		if (mysqli_num_rows($request) > 0){
			return true;
		} else {
			return false;
		}
	}

	public function RequestFriend($sent_by,$sent_to){
		$friend_request=mysqli_query($this->con, "SELECT * FROM friendRequests WHERE from_user = '$sent_by' AND to_user = '$sent_to'");
		if (mysqli_num_rows($friend_request)>0){
			return true;
		} else {
			return false;
		}
	}

	public function FriendRequest($requestBy, $requestTo){
		$nowDate = Date("Y-m-d H:i:s");
		$friend_request = mysqli_query ($this->con, "INSERT INTO friendRequests VALUES(NULL,'$requestBy','$requestTo','$nowDate','$nowDate','pending')");
	}

	public function FriendApprove($NewFriend){
		$userlogged = $this->user['username'];
		$friendsList = $this->user['friends'];
		$newFriendsList = $friendsList . $NewFriend . ",";
		$updateFriendsList = mysqli_query($this->con, "UPDATE amigo SET friends = '$newFriendsList' WHERE username ='$userlogged'");
		
		//Update friendship data
		$nowDate = Date("Y-m-d H:i:s");
		$updateFriendShipDate = mysqli_query($this->con,"UPDATE friendRequests SET friendship_date = '$nowDate', status='approved' WHERE from_user = '$NewFriend' AND to_user= '$userlogged'");
	}
}
?>