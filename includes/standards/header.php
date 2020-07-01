<!DOCTYPE html>
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
		//Get user details
		$user = mysqli_query($con, "SELECT * FROM amigo WHERE username ='$user_log'");
		$user = mysqli_fetch_array($user);

		//Get Friend Request Details
		$friend_request = mysqli_query($con, "SELECT * FROM friendRequests WHERE user_to ='$user_log' AND status='pending'");
		$notification = mysqli_num_rows($friend_request);
	} else {
		header("Location: registration.php");
	}

	//Accept Friend Request
	if (isset($_POST['btnAccept'])){
		$profile_obj= new User($con, $user_log);
		$username = $_POST['friendname'];
		$profile_obj->FriendApprove($username);
		$profile_obj= new User($con, $username);
		$profile_obj->FriendApprove($user_log);
	}

	if (isset($_POST['btnDecline'])){
		$profile_obj= new User($con, $user_log);
		$username = $_POST['friendname'];
		$profile_obj->FriendDecline($username);
		$profile_obj= new User($con, $username);
		$profile_obj->FriendDecline($user_log);
	}
?>

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
<body>
	<?php
		//Get total unviewed messages
		$messages = new Message($con,$user_log);
		$total_messages = $messages->getTotalUnread();

		//Get total unviewed notifications
		$notification = new Notification($con, $user_log);
		$total_notification = $notification->getTotalUnread();

		//Get total friend requests
		$amigo = new Barkada($con, $user_log);
		$total_amigo = $amigo->getTotalBarkada();
	?>

	<nav class="navbar navbar-expand-sm navbar-light loginHeader mb-3">
	 	 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="	#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
	  	</button>
		<div class="collapse navbar-collapse dropdown" id="navbarTogglerDemo01">
	    	<a class="navbar-brand text-light" href="index.php"><img  src="assets/images/profile_pics/defaults/amigo_small.png" alt="https://www.flaticon.com/search?word=friend"> Amigo </a>
				<form class="form-inline my-2 my-sm-0" action="search.php" method="GET">
					<input class="form-control mr-sm-2 nav-link" type="search" onkeyup='getSearchList(this.value, "<?php echo $user_log; ?>")' name="amigo" placeholder="Search" id="search_input_text">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
				</form>
				<div class ="SearchList"></div>
				<div class ="SearchListEmpty"></div>
	    	<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
	    		<li class ="nav-item">
	    			<a class ="navbar-brand text-light" href="<?php echo $user_log; ?>"> <?php echo $user['first_name']; ?> </a>
	    		</li>
	      		<li class="nav-item">
	        		<a class="nav-link" href="#" alt ="home"><i class="fas fa-house-user"></i></a>
				</li>
				  <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" role ="button" id="dropdown_messages" data-toggle ="dropdown" href="javascript:void(0)"onclick = "getDropDownData('<?php echo $user_log; ?>', 'message')" alt ="messaged">
						<i class="fas fa-envelope">
						</i>
						<?php 
							if ($total_messages > 0) 
								echo "<span class='notification_badge' id = 'unread_message'> $total_messages </span>";
						?>
					</a>
					<div class= "dropdown-menu dropdown-menu-sm-right" aria-labelledby="navbarDropdown">
						<div class ="dropdown_window"></div>
						<input type ="hidden" id="dropdown_data_type" value="">
					</div>
	      		</li>
	      		<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" role="button" id="dropdown_notifications" data-toggle="dropdown" href="javascript:void(0)" onclick = "getDropDownData('<?php echo $user_log; ?>', 'notification')" alt="notifications">
						<i class="fas fa-bell"></i>
						<?php 
							if ($total_notification > 0) 
								echo "<span class='notification_badge' id = 'unread_notification'> $total_notification </span>";
						?>
					</a>
					<div class= "dropdown-menu dropdown-menu-sm-right" aria-labelledby="navbarDropdown">
						<div class ="dropdown_window"></div>
						<input type ="hidden" id="dropdown_data_type" value="">
					</div>
	      		</li>
	      		<li class="nav-item dropdown">
				  	<a class="nav-link dropdown-toggle" role="button" id="dropdown_amigo_request" data-toggle="dropdown" href="javascript:void(0)" onclick = "getDropDownData('<?php echo $user_log; ?>', 'amigo')" alt="barkada">
						<i class="fas fa-users"></i>
						<?php 
							if ($total_amigo > 0) 
								echo "<span class='notification_badge' id = 'unapproved_amigo'> $total_amigo </span>";
						?>
					</a>
					<div class= "dropdown-menu dropdown-menu-sm-right" aria-labelledby="navbarDropdown">
						<div class ="dropdown_window"></div>
						<input type ="hidden" id="dropdown_data_type" value="">
					</div>
	      		</li>
	      		<li class="nav-item">
		      		<a class="nav-link" href="upload.php"><i class="fas fa-user-cog"></i></a>
		      	</li>
	      		<li class="nav-item">
					<a class="nav-link" href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i></a>
				</li>
	    	</ul>
		</div>
		<?php
		include ("includes/handlers/cont_messages.php");
		include ("includes/handlers/cont_notifications.php");
		include ("includes/handlers/cont_amigo.php");
		?>
	</nav>
