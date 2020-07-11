<?php
    include("../../config/config.php");
    include("../classes/Post.php");

    $type = $_POST['post_type'];
    $post =new Post($con,$_POST['user']);
    $post_id = $post->submitPost($_POST['body'],'none',$type);
    echo $post_id;
?>