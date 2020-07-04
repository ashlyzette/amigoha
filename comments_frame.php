<?php
    require 'config/config.php';
    include ("includes/classes/User.php");
    include ("includes/classes/Post.php");
    include ("includes/classes/Class_Notification.php");
        
    if (isset($_SESSION['username'])){
        $user_log = $_SESSION['username'];
        //Get user details
        $user = mysqli_query($con, "SELECT * FROM amigo WHERE username ='$user_log'");
        $user = mysqli_fetch_array($user);
    }
?>
<html lang="en">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href= "assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href= "assets/css/style.css?idakjdf">
     <!-- Fontawesome kit Dependencies  -->
	<script src="https://kit.fontawesome.com/0a18e92247.js"></script>
	<!-- JQuery dependencies  -->
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<!-- Bootbox -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js"></script>
	<!-- Bootstrap -->
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/amigo.js"></script>
</head>
<body>
    <?php
        // Get post id 
        if (isset($_GET['post_id'])){
            $post_id = $_GET['post_id'];
        }

        //Get the details of the posted you commented on
        $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id = '$post_id'");
        $row = mysqli_fetch_array($user_query);
        $user_to = $row['user_to'];
        $post_to = $row['added_by'];

        if (isset($_POST['postComment' . $post_id])){
            $post_body = $_POST['postBody'];
            $post_body = mysqli_escape_string($con,$post_body);
            $date_now = date("Y-m-d H:i:s");
            $posted_by = $user_log;
            $insert_post =mysqli_query($con,"INSERT INTO comments VALUES (NULL, '$post_body', '$posted_by', '$post_to', '$date_now', 'no', '$post_id')");
            
            if ($posted_by != $post_to){
				$notification = new Notification($con, $user_log);
				$notification->insertNotification($post_id, $post_to, 'comment');
            }
            
            if ($user_to != 'none' && $user_to != $user_log){
                $notification = new Notification($con, $user_log);
				$notification->insertNotification($post_id, $user_to, 'profile_comment');
            }

            //Get all people who commented on the post
            $get_commenters = mysqli_query($con, "SELECT posted_to, posted_by, post_id FROM comments WHERE post_id='$post_id'");
            $notify_users= array();

            while ($row = mysqli_fetch_array($get_commenters)){
                if ($row['posted_by'] != $row['posted_to'] && 
                    $row['posted_by'] != $user_log && 
                    $row['posted_by'] != $user_to  && 
                    !in_array($user_to,$notify_users)){
                        $notification = new Notification($con, $user_log);
                        $notification->insertNotification($post_id, $row['posted_by'], 'comment_non_owner');
                        array_push($notify_users, $row['posted_by']);
                }
            }
        }
    ?>
    <!-- Create the form   -->
    <form class="form-inline px-1 py-1" action="comments_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
        <textarea class="form-control col-12 comment_post" name = "postBody"></textarea>
        <button class="btn btn-success btn-sm comment_post ml-auto" name ="postComment<?php echo $post_id; ?>"> Post Comment </button>
    </form>

    <!-- Load comments -->
    <?php
        $lou_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' AND removed='no' ORDER BY id DESC");
        $num_rows = mysqli_num_rows($lou_comments);

        if ($num_rows > 0){
            while ($comment = mysqli_fetch_array($lou_comments)){
                $comment_body = $comment['post_body'];
                $posted_to = $comment['posted_to'];
                $posted_by = $comment['posted_by'];
                $date_added = $comment['date_added'];
                $removed = $comment['removed'];
                $id=$comment['id'];
                $addDeleteButton = "";

                // Check if comment is the same as the user log
                if ($user_log  == $posted_by){
                    $addDeleteButton = "<button class='del_button btn btn-danger btn-sm mr-1 mt-1' id ='comment$id'>X</button>";
                } else {
                    $addDeleteButton = "";
                }

                //Get the time message
                $date_time_now = Date("Y-m-d H:i:s");
                $start_date = new DateTime($date_added); 	// time of post
                $end_date = new DateTime($date_time_now ); 	//today's date
                $interval = $start_date->diff($end_date);

                if ($interval->y>=1){
                    if ($interval->y == 1){
                        $time_message = $interval->y . " year ago";
                    } else {
                        $time_message = $interval->y . " years ago";
                    }
                } else if($interval->m>=1){
                    if ($interval->m == 1){
                        $time_message = $interval->m . " month ago";
                    } else {
                        $time_message = $interval->m . " months ago";
                    }
                } else if($interval->d>=1){
                    if ($interval->d == 1){
                        $time_message = $interval->d . " day ago";
                    } else {
                        $time_message = $interval->d . " days ago";
                    }
                } else if($interval->h>=1){
                    if ($interval->h == 1){
                        $time_message = $interval->h . " hour ago";
                    } else {
                        $time_message = $interval->h . " hours ago";
                    }
                } else if($interval->i >= 1){
                    if ($interval->i == 1){
                        $time_message = $interval->i . " minute ago";
                    } else {
                        $time_message = $interval->i . " minutes ago";
                    }
                } else {
                    $time_message ="Just now";
                }

                $user_obj = new User($con,$posted_by);
                $profile_pic = $user_obj->getProfilePic();
            ?>
                <div class="user_comments ml-2 mr-2">
                    <div class="d-flex justify-content-between">
                        <div class="post_profile_pic">
                            <a class="img_pix" href="<?php echo $posted_by; ?>" target="_parent">
                                <img class='user_profile' src="<?php echo $profile_pic; ?>" width='30'>
                            </a>
                        </div>
                        <?php echo $addDeleteButton; ?>
                    </div>
                    <div> by <a href="<?php echo $posted_by; ?>" target="_parent"> 
                        <?php echo $posted_by; ?> </a>
                    </div>
                    <div class ="past_comment">
                        <?php echo $time_message; ?>
                    </div>
                    <div> 
                        <?php echo $comment_body; ?> 
                    </div>
                    <hr/>
                </div>

                <script>
					$(document).ready(function(){
						$('#comment<?php echo $id; ?>').on('click', function(){
							bootbox.confirm({
								message:"Are you sure you want to delete your comment?",
								buttons: {
									confirm:{
										label:'Yes',
										className: 'btn-success'
									},
									cancel:{
										label:'No',
										className: 'btn-danger'
									}
								}, callback: function(result){
									$.post("includes/form_handlers/delete_comment.php?comment_id=<?php echo $id; ?>",{result:result});
									if (result){
                                        location.reload();
									}
								}
							});
						});
					});
				</script>
            <?
            } // end of while
        } else  {
            ?><div class="mt-3 text-center"> No comments Yet </div><?
        }
    ?>
</body>
</html>