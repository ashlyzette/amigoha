<?php
	include("../../config/config.php");
	include("../classes/User.php");
    include("../classes/Post.php");
    
    if(isset($_POST['txtPostToWall'])){
        $type = 'post';
        $post =new Post($con,$_POST['from_user']);
        $post->submitPost($_POST['txtPostToWall'],$_POST['to_user'],$type);
    }

?>