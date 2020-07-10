<?php
    include("../../config/config.php");
	include("../classes/User.php");
    include("../classes/Post.php");

    if ($_POST['save']==='post'){
        $type = $_POST['post_type'];
        $post =new Post($con,$_POST['user']);
        $post->submitPost($_POST['body'],$_POST['user'],$type);
    }
?>