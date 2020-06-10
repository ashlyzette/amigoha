<?php
	include("../../config/config.php");
	include("../classes/User.php");
	include("../classes/Post.php");

	$limit =10; //indicates the number of posts to be loaded

	$posts = new Post($con, $_REQUEST['$user_log']);
	$posts->loadPostsFriends($_REQUEST, $limit);
?>