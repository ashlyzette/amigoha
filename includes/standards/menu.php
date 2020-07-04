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
		      		<a class="nav-link" href="settings.php"><i class="fas fa-user-cog"></i></a>
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