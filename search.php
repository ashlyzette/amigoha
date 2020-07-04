<?php
    include ("includes/standards/header.php");
    
    $barkada_obj = new Barkada($con,$user_log);

    //Get the name of the user you will send the message or set a new message
    if (isset($_GET['amigo'])){
        $friend_search = $_GET['amigo'];
    } else {
        $friend_search = "";
    }

    if (isset($_GET['type'])){
        $type = $_GET['type'];
    }

    //Friend Request
	if (isset($_POST['search_friend_request'])){
		$profile_obj= new User($con, $_POST['username']);
		$profile_obj->FriendRequest($user_log,$_POST['username']);
	}

    //Accept Friend Request
	if (isset($_POST['search_friend_accept'])){
        $username = $_POST['username'];
		$profile_obj= new User($con, $user_log);
		//Check if existing friends
		if (!$profile_obj->myFriend($username)){
			$profile_obj->FriendApprove($username);
			$profile_obj= new User($con, $username);
			$profile_obj->FriendApprove($user_log);
		}
    }
    
    //Decline Friend Request
	if (isset($_POST['search_friend_decline'])){
        $username = $_POST['username'];
		$profile_obj= new User($con, $user_log);
		//Check if existing friends
		if (!$profile_obj->myFriend($username)){
			$profile_obj->FriendDecline($username);
			$profile_obj= new User($con, $username);
			$profile_obj->FriendDecline($user_log);
		}
    }
    
    //Withdraw Friend Request
	if (isset($_POST['search_friend_withdraw'])){
        $username = $_POST['username'];
		$profile_obj= new User($con, $user_log);
		//Check if existing friends
		if (!$profile_obj->myFriend($username)){
			$profile_obj->FriendWithdraw($username);
		}
	}
?>
<div class="container">
    <div class = "w-25 mt-3 leftBox">
		<?php include ("includes/standards/leftcolumn.php") ?>
		<div class="profleLinks">
			Profile Links
		</div>
	</div>
    <div class = "w-75 mt-3 rightBox">
        <div class ="newsfeed">
            <?php
                $limit = 10;
                $barkada = new Barkada($con,$user_log);
                $search_result = "<div><h3> Search Result for ... " . $friend_search . "</h3></div><hr/>";
                $search_result .= $barkada -> getAmigoSearch($friend_search,$limit);
                echo $search_result;
            ?>
        </div>
    </div>
</div>
<?php
    include ("includes/standards/footer.php");
?>