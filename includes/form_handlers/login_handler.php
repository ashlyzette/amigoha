<?php
	// Check if user clickes login
	if (isset($_POST['login'])){
		$email = filter_var($_POST['log_email'],FILTER_SANITIZE_EMAIL);	//Check email format if correct
		$_SESSION['log_email']=$email;

		// Get password and encrypt to compare with existing database
		$pass = md5($_POST['log_password']);

		// Check database
		$lou_query= mysqli_query($con, "SELECT * FROM amigo WHERE email='$email' AND password ='$pass'");
		$num_rows = mysqli_num_rows($lou_query);

		if ($num_rows ==1){
			//Pass dataset to variable
			$row = mysqli_fetch_array($lou_query);
			$username = $row['username'];

			//Update status of user to online
			$lou_status_query = mysqli_query($con, "SELECT * FROM amigo WHERE email ='$email' AND status='offline'");
			if (mysqli_num_rows($lou_status_query) == 1){
				$lou_status_update = mysqli_query($con, "UPDATE amigo SET status='online' WHERE email='$email'");
			}
			
			//Add username to session
			$_SESSION['username'] = $username;

			//Redirect to index.php
			header("Location: index.php");
		} else {
			array_push($error_array, "Incorrect email and password combination!");
		}
	}

?>