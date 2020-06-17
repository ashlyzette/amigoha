<!DOCTYPE html>
<!-- Start of Header -->
<?php
	include ("includes/standards/header.php");
	include ("includes/classes/User.php");
	include ("includes/classes/Post.php");

	// session_destroy();
	if(isset($_GET['profile_username'])){
		$username = $_GET['profile_username'];
		$user_details = mysqli_query($con,"SELECT * FROM amigo WHERE username = '$username'");
		$user = mysqli_fetch_array($user_details);
	}

	//Remove Friend
	if (isset($_POST['btnUnfriend'])){
		//Remove friend list from profile user
		$profile_obj= new User($con, $username);
		$profile_obj->RemoveFriend($user_log);
		//Remove friend list from logged user
		$profile_obj= new User($con, $user_log);
		$profile_obj->RemoveFriend($username);
	}

	//Friend Request
	if (isset($_POST['btnRequest'])){
		$profile_obj= new User($con, $username);
		$profile_obj->FriendRequest($user_log,$username);
	}

	//Accept Friend Request
	if (isset($_POST['btnAccept'])){
		$profile_obj= new User($con, $user_log);
		//Check if existing friends
		if (!$profile_obj->myFriend($username)){
			$profile_obj->FriendApprove($username);
			$profile_obj= new User($con, $username);
			$profile_obj->FriendApprove($user_log);
		}
	}

?>
<!-- End of Header -->
<div class="container">
	<div class ="jumbotron">	
		<img class="img_header" src = '<?php echo $user['header_img'] ?>'>
	</div>
	<div class = "w-25 mt-3 leftBox">
		<?php include ("includes/standards/leftcolumn.php"); ?>
		<div class = "userBox mt-2">
			<form class ="text-center mt-2" action="<?php echo $username; ?>" method="POST">
				<?php
					$profile_obj= new User($con, $username);
					if ($user_log != $username){
						if ($profile_obj->isClosed()){
							//Check if profile account is closed
							header("Location: closed.php");
						} else if ($profile_obj->myFriend($user_log)){
							// check user friends if profile visited is a friend
							echo '<input type="submit" class="btn btn-danger btn-sm btn-block" name="btnUnfriend" value="Unfriend">';
						} else if ($profile_obj->SentFriendRequest($user_log, $username)){
							// check if you sent a friend request
							echo '<input type="submit" class="btn btn-warning btn-sm btn-block" name="btnWithdraw" value="Withdraw Friend Request">';	
						} else if ($profile_obj->RequestFriend($username,$user_log)){
							// check if profile visited sent a friend request and needs your respond
							echo '<input type="submit" class="btn btn-warning btn-sm btn-block" name="btnAccept" value="Accept Friend Request">';
						} else {
							// Chcek if profile is not a friend and no pending request
							echo '<input type="submit" class="btn btn-success btn-sm btn-block" name="btnRequest" value="Friend Request">';
						}	
					}
				?>
			</form>
		</div>
	</div>
	<div class = "w-75 mt-3 rightBox">
			<div class = "newsfeed">
				<div class="row">
					<div class="col-12">
						<form action = "index.php" method ="POST">
							<div class="form-group">
								<textarea class="form-control" name="post_text" placeholder="Share something..."></textarea>
							</div>
							<input type="button" class ="btn btn-primary btn-sm" name="post_submit" value ="POST">
						</form>
					</div>
					<div class = "col-12 userPosts">

					</div>
				</div>
			</div>
	</div>
</div>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->