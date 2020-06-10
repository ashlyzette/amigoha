<!DOCTYPE html>
<?php
	require 'config/config.php';
	require 'includes/form_handlers/login_handler.php';

	if (isset($_SESSION['username'])){
		$user_log = $_SESSION['username'];
		//Get user details
		$user = mysqli_query($con, "SELECT * FROM amigo WHERE username ='$user_log'");
		$user = mysqli_fetch_array($user);
	} else {
		header("Location: registration.php");
	}
?>

<html>
<head>
	<title>Auroranian Registration</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href= "assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href= "assets/css/register_style.css">
	<link rel="stylesheet" type="text/css" href= "assets/css/style.css">
	<!-- font awesome -->
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light loginHeader">
	 	 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="	#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		</div>
	  	</button>
		 <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
	    	<a class="navbar-brand text-light" href="#"><img  src="assets/images/profile_pics/defaults/amigo_small.png" alt="https://www.flaticon.com/search?word=friend"> Amigo </a>
	    	<form class="form-inline my-2 my-lg-0">
	      		<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
	      		<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
	    	</form>
	    	<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
	    		<li class ="nav-item">
	    			<a class ="navbar-brand text-light" href="<?php echo $user_log; ?>"> <?php echo $user['first_name']; ?> </a>
	    		</li>
	      		<li class="nav-item">
	        		<a class="nav-link" href="#"><i class="fas fa-house-user"></i></a>
	      		</li>
	      		<li class="nav-item">
	        		<a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
	      		</li>
	      		<li class="nav-item">
	        		<a class="nav-link" href="#"><i class="fas fa-users"></i></a>
	      		</li>
	      		<li class="nav-item">
		      		<a class="nav-link" href="#"><i class="fas fa-user-cog"></i></a>
		      	</li>
	      		<li class="nav-item">
					<a class="nav-link" href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i></a>
				</li>
	    	</ul>
	    	
	  	</div>
	</nav>