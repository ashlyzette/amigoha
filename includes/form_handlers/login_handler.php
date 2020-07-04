<?php
	$error_array = array();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	if (isset($_POST['login'])){
		$email = filter_var($_POST['log_email'],FILTER_SANITIZE_EMAIL);
		$_SESSION['log_email'] = $email;
		$pass = md5($_POST['log_password']);
		$lou_query= mysqli_query($con, "SELECT * FROM amigo WHERE email='$email' AND password ='$pass'");
		$num_rows = mysqli_num_rows($lou_query);
		if ($num_rows ==1){
			$row = mysqli_fetch_array($lou_query);
			$username = $row['username'];
			$_SESSION['username'] = $username;
			header("Location: http://amigoha.com",true,301);
			exit();
		} else {
			array_push($error_array, "Incorrect email and password combination!");
		}
	}
?>