<?php
    include ("includes/standards/header.php");
	// session_destroy();

	if (isset($_POST['submit'])){
		$post = new Post($con,$user_log);
		$post->submitPost($_POST['post_text'],'none');
		// Refreshes the page
		header("Location: index.php"); 
    }
?>
<!-- End of Header -->
<div class="container">
    <div class="row">
        <div class = "col-md-3 col-sm-12 mt-3">
            <?php include ("includes/standards/leftcolumn.php") ?>
            <div class="profleLinks">
                Profile Links
            </div>
        </div>
        <div class = "col-md-9 col-sm-12 mt-3">
            <div class = "newsfeed">
                <div class="row OnePost">
                    <div class="col-12">
                        <h5 id="friend_request"> Friend Requests</h5>
                    </div>
                </div>
                <?php
                    $friend_request = mysqli_query($con, "SELECT * FROM friendRequests WHERE user_to ='$user_log' AND status='pending'");
                    $total_requests = mysqli_num_rows($friend_request);
                    if ($total_requests==0){
                        echo "You do not have any pending friend request!";
                    } else {
                        if ($total_requests == 1){
                            echo "You have " . $total_requests . " pending friend request!";
                        } else {
                            echo "You have " . $total_requests . " pending friend requests!";
                        }
                        while ($friend = mysqli_fetch_array($friend_request)){
                            $newFriend = $friend['user_from'];
                            $dateRequest = date_create($friend['request_date']);
                            $dateRequest = date_format($dateRequest,'Y-M-d');
                            $newFriend_obj = mysqli_query($con,"SELECT first_name, last_name, profile_pic FROM amigo WHERE username ='$newFriend'");
                            $friend_info = mysqli_fetch_array($newFriend_obj);
                            $first_name = $friend_info['first_name'];
                            $last_name = $friend_info['last_name'];
                            $profile_pics = $friend_info['profile_pic'];
                            if (isset($_POST['btnAccept' . $newFriend])){
                                $profile_obj= new User($con, $user_log);
                                    //Check if existing friends
                                if (!$profile_obj->myFriend($newFriend)){
                                    $profile_obj->FriendApprove($newFriend);
                                    $profile_obj= new User($con, $newFriend);
                                    $profile_obj->FriendApprove($user_log);
                                    header("Location: requests.php"); 
                                }
                            }
                            
                            if (isset($_POST['btnDecline' . $newFriend])){
                                $profile_obj= new User($con, $user_log);
                                $profile_obj->FriendDecline($newFriend);
                                header("Location: requests.php"); 
                            }
                            ?>
                                <div class='status_post ml-2'>
                                    <div class='post_profile_pic'>
                                        <img class='px-1 py-2' src = '<?php echo $profile_pics; ?>' width='100'>
                                    </div>
                                    <div class='posted_by mt-2'>
                                        <a class='card-title' href='<?php echo $newFriend; ?>'> <?php echo $first_name . " " . $last_name; ?></a> 
                                    </div>
                                    <div class='time_message mt-2'>
                                        Requested <?php echo $dateRequest; ?>
                                    </div>
                                    <div id='post_body'>
                                        <p class ='card-text' id='post_body'>
                                            <form class='form-inline pb-3' action='requests.php' method='POST'>
                                                <button class='form-control btn btn-primary btn-sm mr-1' name='btnAccept<?php echo $newFriend; ?>'> Accept </button>
                                                <button class='form-control btn btn-danger btn-sm ml-1' name='btnDecline<?php echo $newFriend; ?>'> Decline </button>
                                            </form>
                                        </p>
                                    </div>
                                </div>
                            <?php
                        }
                    }
                ?>
                            

            </div>
        </div>
    </div>
<div>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->