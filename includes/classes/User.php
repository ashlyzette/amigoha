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
		$request=mysqli_query($this->con, "SELECT * FROM friendRequests WHERE user_from = '$sent_by' AND user_to = '$sent_to' AND status = 'pending'");
		if (mysqli_num_rows($request) > 0){
			return true;
		} else {
			return false;
		}
	}

	public function RequestFriend($sent_by,$sent_to){
		$friend_request=mysqli_query($this->con, "SELECT * FROM friendRequests WHERE user_from = '$sent_by' AND user_to = '$sent_to' AND status = 'pending'");
		if (mysqli_num_rows($friend_request)>0){
			return true;
		} else {
			return false;
		}
	}

	public function FriendRequest($requestBy, $requestTo){
		$nowDate = Date("Y-m-d H:i:s");
		$friend_request = mysqli_query ($this->con, "INSERT INTO friendRequests VALUES(NULL,'$requestBy','$requestTo','$nowDate','$nowDate','pending','no')");
	}

	public function FriendApprove($newFriend){
		$userlogged = $this->user['username'];
		$friendsList = $this->user['friends'];
		$newFriendsList = $friendsList . $newFriend . ",";
		$updateFriendsList = mysqli_query($this->con, "UPDATE amigo SET friends = '$newFriendsList' WHERE username ='$userlogged'");
		
		//Update friendship data
		$nowDate = Date("Y-m-d H:i:s");
		$updateFriendShipDate = mysqli_query($this->con,"UPDATE friendRequests SET friendship_date = '$nowDate', status='approved' WHERE user_from = '$newFriend' AND user_to= '$userlogged'");
	}

	public function FriendDecline($newFriend){
		$userlogged = $this->user['username'];
		$nowDate = Date("Y-m-d H:i:s");
		$updateFriendShipDate = mysqli_query($this->con,"UPDATE friendRequests SET friendship_date = '$nowDate', status='declined' WHERE user_from = '$newFriend' AND user_to= '$userlogged'");
	}

	public function GetMutualFriends($FriendName){
		//Get your friends list
		$commonFriends =0;
		$your_list = $this->user['friends'];
		$your_friends = explode(",",$your_list);

		//Get your friend's friends list
		$friends_list = mysqli_query($this->con,"SELECT friends FROM amigo WHERE username='$FriendName'");
		$your_friends_list = mysqli_fetch_array($friends_list);
		$friends = explode(",",$your_friends_list['friends']);
		$commonFriends =0;
		$div="";
		foreach($your_friends as $x){
			foreach($friends as $y){
				if($x==$y && $x!=""){
					$commonFriends++;
					$query = mysqli_query($this->con, "SELECT username, profile_pic FROM amigo WHERE username='$y'");
					$row = mysqli_fetch_array($query);
					$myName = $row['username'];
					$myPix = $row['profile_pic'];
					$div .= "<a class='mr-1' href='$myName'><img class='user_profile' src='$myPix' width='30'></a>";
					break;
				}
			}
		}
		$div = "<span class='text-center'>" . $commonFriends . " mutal friends</span><div class='form-inline'>" . $div ."</div>";
		return $div;
	}
}
?>