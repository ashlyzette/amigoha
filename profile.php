<?php
	include ("includes/standards/header.php");

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

	//Withdraw Friend Request
	if (isset($_POST['btnWithdraw'])){
		$profile_obj= new User($con, $user_log);
		//Check if existing friends
		if (!$profile_obj->myFriend($username)){
			$profile_obj->FriendWithdraw($username);
		}
	}

	//User sends a message
    if (isset($_POST['SendMessage'])){
    	//Check if there is a message
    	if (isset($_POST['myMessage'])){
            //Sanitize the message to allow single quotes
            $body = $_POST['myMessage'];
            $body = mysqli_real_escape_string($con, $body);        
			$message_obj = new Message($con,$username);
			$message_obj->SendMyMessage($user_log,$body);
            unset($_POST);
    	}
	}
	
?>
<!-- End of Header -->
<div class="container">
	<div class ="jumbotron">	
		<img class="img_header" src = '<?php echo $user['header_img'] ?>'>
	</div>
	<div class = "row">
		<div class = "col-md-3 col-sm-12 mt-3 pr-md-1 pr-sm-3">
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
				<button type="button" class="btn btn-primary btn-block btn-sm mt-2" data-toggle="modal" data-target="#PostWall">
					Post to <?php echo $username . '\'s'; ?> wall page
				</button>
				<a type = "button" class ="btn btn-primary btn-block btn-sm mt-1" href="messages.php?amigo=<?php echo $username; ?>"> Send a message </a>
				<div class = "userBox mt-2 text-center">
					<?php
						$friends_obj = new User($con, $user_log);
						$commonfriends = $friends_obj->GetMutualFriends($username);
						echo $commonfriends;
					?>
				</div>
			</div>
		</div>
		<div class ="col-md-9 col-sm-12 profile_feed">
			<div class = "nav_tab_box mt-3">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link active" id="home-tab" data-toggle="tab" href="#user_posts" role="tab" aria-controls="user_posts" aria-selected="true">Home</a>
						<a class="nav-item nav-link" id="profile-tab" data-toggle="tab" href="#user_profile" role="tab" aria-controls="user_profile" aria-selected="false">Profile</a>
						<a class="nav-item nav-link" id="messaging-tab" data-toggle="tab" href="#user_messages" role="tab" aria-controls="user_messages" aria-selected="false">Messages</a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="user_posts" role="tabpanel" aria-labelledby="home-tab">
						<div class ="posts_area"></div>
						<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
							<span class="sr-only">Loading...</span>
						</div>
						<?php include ("includes/handlers/cont_newsfeed.php") ?>
					</div>
					<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="profile-tab">
						<div class="messaging_profile_box">This is the profile page</div>	
					</div>
					<div class="tab-pane fade" id="user_messages" role="tabpanel" aria-labelledby="messaging-tab">
						<div class=" col-12 MessageHeader">
							<?php
								$user_to = $username;
								$profile_obj= new User($con, $username);
								$user_to_obj = new Message($con, $username);
									echo "<h5> You and <a href ='$user_to'>" . $profile_obj->getFirstAndLastName() . "</a></h5>";
									echo "<div class = 'container messaging_profile_box mr-4 border rounded mb-2'>";
									echo "<div class = 'mt-2 last_message'>" . $user_to_obj->LoadMessages($user_log);  
									echo "</div></div>";
							?>
						</div>
						<div class="col-12 MessageBody">
							<form class='form-inline' action="" method="POST">
								<?php
									if ($user_to == 'new'){
										?>
										<span>Send message to:</span> 
										<input class = 'form-control ml-1' type='text' onkeyup='getFriendsList(this.value, "<?php echo $user_log; ?>")' name ='SearchFriends' placeholder='Enter name to search...'>
										<div class='col-12 friendslist'></div>
								<?php } else {
										echo "<textarea class='form-control col-12' name='myMessage' placeholder='Write your message...'></textarea>";
										echo "<div class='d-flex ml-auto'><button class='btn btn-primary btn-sm mt-1 send_button' name='SendMessage'> Send </button></div>";
									}
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="PostWall" tabindex="-1" role="dialog" aria-labelledby="PostWalllLabel" aria-hidden="true">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="PostWallLabel" name ="Post_to">Post to <?php echo $username . '\'s'; ?> wall</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
					<form class = "post_to_wall" action="" method="POST">
						<textarea class = "form-control col-md-12" name="txtPostToWall"></textarea>
						<input type="hidden" name="from_user" value ="<?php echo $user_log; ?>">
						<input type="hidden" name="to_user" value ="<?php echo $username; ?>">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" name="PostToWall" id="PostToWall">Post to Wall</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="assets/js/profile.js"></script>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->