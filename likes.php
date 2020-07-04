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
    } else {
            header("Location: registration.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" type="text/css" href= "assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href= "assets/css/style.css?id041">
</head>
<body class="like_body">
    <?php
        // Get post id 
        if (isset($_GET['post_id'])){
            $post_id = $_GET['post_id'];
        }

        // Get the number of likes
        $like_obj = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id = '$post_id'");
        $like_rows = mysqli_fetch_array($like_obj);
        $total_likes = $like_rows['likes'];
        $added_by = $like_rows['added_by'];

        //Get the details of the post author
        $post_author = mysqli_query($con, "SELECT * FROM amigo WHERE username='$added_by'");
        $author_info = mysqli_fetch_array($post_author);

        //Add like
        if ((isset($_POST['btn_like']))){
            //Add to the total number of likes
            $total_likes++;
            $add_like = mysqli_query($con,"UPDATE posts SET likes ='$total_likes' WHERE id='$post_id'");
            // Add to like database
            $user_likes = mysqli_query($con,"INSERT INTO likes VALUES (NULL,'$user_log','$post_id','Yes')");
            $user_to=$author_info['username'];
            if ($user_log != $user_to){
                $notification = new Notification($con, $user_log);
                $notification->insertNotification($post_id, $user_to, 'like');
            }
        }

        //Minus dislike
        if ((isset($_POST['btn_unlike']))){
            //Add to the total number of likes
            $total_likes--;
            $add_like = mysqli_query($con,"UPDATE posts SET likes ='$total_likes' WHERE id='$post_id'");
            // Remove to like database
            $user_likes = mysqli_query($con,"DELETE FROM likes WHERE username = '$user_log' AND post_id='$post_id'");
        }

        //Check if the user currently likes the post
        $like_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$user_log' AND post_id='$post_id'");
        $num_rows = mysqli_num_rows($like_query);
        //Create the user like form
        if ($num_rows>0){
            echo '<div class = "d-flex justify-content-end">
                    <form class ="form-inline" action="likes.php?post_id=' . $post_id . '" method="POST">
                        <button class="btn btn-primary btn-sm" name="btn_unlike"> Unlike </button>
                        <div style="width:10px"> </div>
                        <div class="like_value">' . $total_likes . ' Likes </div>
                    </form>
                </div>';
        } else {
            echo '<div class = "d-flex justify-content-end"><form class ="form-inline" action="likes.php?post_id=' . $post_id . '" method="POST">
                    <button class="btn btn-primary btn-sm" name="btn_like"> Like </button>
                    <div style="width:10px"> </div>
                    <div class="like_value">' . $total_likes . ' Likes </div></form></div>';
        }

    ?>
</body>
</html>