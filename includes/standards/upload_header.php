<?php
	require 'config/config.php';
	require 'includes/form_handlers/login_handler.php';
	include ("includes/classes/User.php");
	include ("includes/classes/Post.php");
	include ("includes/classes/Class_Messages.php");
	include ("includes/classes/Class_Notification.php");
	include ("includes/classes/Barkada.php");

	if (isset($_SESSION['username'])){
		$user_log = $_SESSION['username'];
        $profile = new User($con,$user_log);
		//Get user details
		$user = mysqli_query($con, "SELECT * FROM amigo WHERE username ='$user_log'");
		$user = mysqli_fetch_array($user);

		//Get Friend Request Details
		$friend_request = mysqli_query($con, "SELECT * FROM friendRequests WHERE user_to ='$user_log' AND status='pending'");
        $notification = mysqli_num_rows($friend_request);
	} else {
		header("Location: registration.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Auroranian Registration</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Fontawesome kit Dependencies  -->
	<script type="text/javascript" src="https://kit.fontawesome.com/0a18e92247.js"></script>
	<!-- Popperjs  -->
	<script type="text/javascript" href="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.4.2/cjs/popper.min.js"> </script>
	<!-- JQuery dependencies  -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<!-- Bootbox -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js"></script>
	<!-- Bootstrap -->
	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jcrop_bits.js"></script>
	<script type="text/javascript" src="assets/js/jquery.Jcrop.js"></script>
	<script type="text/javascript" src="assets/js/amigo.js"></script>
	
	<link rel="stylesheet" type="text/css" href= "https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href= "assets/css/register_style.css">
	<link rel="stylesheet" type="text/css" href= "assets/css/jquery.Jcrop.css">
	<link rel="stylesheet" type="text/css" href= "assets/css/style.css">
</head>
<body class ="upload_column">
	
