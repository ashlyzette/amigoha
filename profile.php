<!DOCTYPE html>
<!-- Start of Header -->
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
			<button type="button" class="btn btn-primary btn-block btn-sm mt-2" data-toggle="modal" data-target="#PostWall">
				Post to <?php echo $username . '\'s'; ?> wall
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
	<div class = "nav_tab_box mt-3">
		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#userPosts" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
				<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
				<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Messages</a>
			</div>
		</nav>
		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="userPosts" role="tabpanel" aria-labelledby="nav-home-tab">
				<div class ="posts_area"></div>
				<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<?php include ("includes/handlers/cont_newsfeed.php") ?>
			</div>
			<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
				This is the profile page
			</div>
			<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
				<div class=" col-12 MessageHeader">
					<?php
						$user_to_obj = new User($con,$username);
						$user_to = $$user_to_obj->GetRecentUser();
						if ($user_to == false){
							$user_to = 'new';
						} else {
							$user_to = $username;
						}
						if ($user_to != 'new'){
							echo "<h5> You and <a href ='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h5>";
							echo "<div class = 'container messaging_box mr-4 border rounded mb-2' id='last_message'>";
							echo $message_obj->LoadMessages($user_to);
							echo "</div>";
						}
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
								echo "<textarea class='form-control' name='myMessage' placeholder='Write your message...'></textarea>";
								echo "<div class='d-flex justify-content-end'><button class='btn btn-primary btn-sm mt-1 send_button' name='SendMessage'> Send </button></div>";
							}
						?>
					</form>
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
					<form class = "post_to_wall" action method="POST">
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
<script>
    //Initialize message display to the latest message
    var user_to = '<?php echo $user_to; ?>';
    if (user_to != 'new'){
        var lastMessage = document.querySelector("#last_message");
        lastMessage.scrollTop = lastMessage.scrollHeight;

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    }
</script>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->