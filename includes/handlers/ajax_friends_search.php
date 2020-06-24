<?php
	include("../../config/config.php");
	include("../classes/User.php");
	include("../classes/Post.php");
	include("../classes/Class_Messages.php");

	$query = $_POST['query'];
	$user_log = $_POST['user_log'];

	//Predict what they are using
	$friendReturn = mysqli_query($con, "SELECT * FROM amigo WHERE username LIKE '%$query%' AND account ='active' LIMIT 8");
	$num_rows = mysqli_num_rows($friendReturn);
	if ($num_rows == 0){
		$friendReturn = mysqli_query($con, "SELECT * FROM amigo WHERE first_name LIKE '%$query%' AND account ='active' LIMIT 8");
		$num_rows = mysqli_num_rows($friendReturn);
		if ($num_rows == 0){
			$friendReturn = mysqli_query($con, "SELECT * FROM amigo WHERE last_name LIKE '%$query%' AND account ='active' LIMIT 8");
		}
	} 
	if($query != ""){
		echo "<span id='search_result_header'> Search result for " . $query;
		while ($row = mysqli_fetch_array($friendReturn)){
			$thisuser = new User($con, $user_log);
			//Get number of mutual friends
			if ($thisuser != $user_log){
				$mutual_friends = $thisuser->GetMutualFriends($row['username']);
			} else {
				$mutual_friends = "";
			}
			//Check if searched friend is a friend
			if ($thisuser->myFriend($row['username'])){
				echo "<div class='search_card card mb-3'>
						<div class='row no-gutters'>
							<a href='messages.php?amigo=".$row['username']."'>
								<div class = 'col-md-4'>
									<img class ='profile_image py-2' src='" . $row['profile_pic'] . "' width='80'>
								</div>
								<div class='col-md-8'>
									<h6 class ='search_header card-body'>"
										. $row['first_name'] . " " . $row['last_name'] . 
									"</h6>
									</a>
									<span class ='mutual_friends card-text'>
										$mutual_friends
									</span>
								</div>
							
						</div>
					</div>";
			} else {
				echo "<div class='search_card card mb-3'>
						<div class='row no-gutters'>
							<a href='messages.php?amigo=".$row['username']."'>
								<div class = 'col-md-4'>
									<img class ='profile_image py-2' src='" . $row['profile_pic'] . "' width='80'>
								</div>
								<div class='col-md-8'>
									<h6 class ='search_header card-body'>"
										. $row['first_name'] . " " . $row['last_name'] . 
									"</h6>
									</a>
									<span class ='mutual_friends card-text'>
										$mutual_friends
									</span>
									<form action='messages.php' method='POST'>
										<button class='btn btn-primary btn-sm ml-0' name='btnRequest'> Add Friend </button>
										<input type='hidden' name='username' value=".$row['username'].">
									</form>
								</div>
						</div>
					</div>";
			}

			
		} // end of while
	} // end of if
?>