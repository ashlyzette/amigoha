<?php
    require 'config/config.php';
    include ("includes/classes/User.php");
    include ("includes/classes/Post.php");
        
    if (isset($_SESSION['username'])){
        $user_log = $_SESSION['username'];
        //Get user details
        $user = mysqli_query($con, "SELECT * FROM amigo WHERE username ='$user_log'");
        $user = mysqli_fetch_array($user);
    } else {
            header("Location: registration.php");
    }
?>
<html lang="en">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href= "assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href= "assets/css/style.css?id041804">
</head>
<body>
    <?php
        // Get post id 
        if (isset($_GET['post_id'])){
            $post_id = $_GET['post_id'];
        }
        $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id = '$post_id'");
        $row = mysqli_fetch_array($user_query);

        $post_to = $row['added_by'];

        if (isset($_POST['postComment' . $post_id])){
            $post_body = $_POST['postBody'];
            $post_body = mysqli_escape_string($con,$post_body);
            $date_now = date("Y-m-d H:i:s");
            $posted_by = $user_log;
            $insert_post =mysqli_query($con,"INSERT INTO comments VALUES (NULL, '$post_body', '$posted_by', '$post_to', '$date_now', 'no', '$post_id')");
        }
    ?>
    <!-- Create the form   -->
    <form class="form-inline px-1 py-1" action="comments_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
        <textarea class="form-control col-12 comment_post" name = "postBody"></textarea>
        <button class="btn btn-success btn-sm comment_post ml-auto" name ="postComment<?php echo $post_id; ?>"> Post Comment </button>
    </form>

    <!-- Load comments -->
    <?php
        $lou_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id DESC");
        $num_rows = mysqli_num_rows($lou_comments);

        if ($num_rows > 0){
            while ($comment = mysqli_fetch_array($lou_comments)){
                $comment_body = $comment['post_body'];
                $posted_to = $comment['posted_to'];
                $posted_by = $comment['posted_by'];
                $date_added = $comment['date_added'];
                $removed = $comment['removed'];

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
                    <div class="post_profile_pic">
                        <a class="img_pix" href="<?php echo $posted_by; ?>" target="_parent">
                            <img src="<?php echo $profile_pic; ?>" width='30'>
                        </a>
                    </div>
                    <div> by <a href="<?php echo $posted_by; ?>" target="_parent"> <?php echo $posted_by ?> </a></div>
                    <div class ="past_comment"><?php echo $time_message ?></div>
                    <div> <?php echo $comment_body ?> </div>
                    <hr/>
                </div>
            <?
            } // end of while
        } else  {
            ?><div class="mt-3 text-center"> No comments Yet </div><?
        }

    ?>
</body>
</html>