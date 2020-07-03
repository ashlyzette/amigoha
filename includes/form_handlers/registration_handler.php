<?php
	//Variable declarations
	$error_array = array();
	if (isset($_POST['submit'])){
		

		//Assign variables and clean up - check len characters - store session
		$fname = $_POST['reg_fname'];
		$fname = ucfirst(strtolower($fname));
		if (strlen($fname) > 25 || strlen($fname)<2) {
			array_push($error_array,"First name must be between 2 to 25 characters<br/>");
		}
		$_SESSION['reg_fname']= $fname;

		$lname = $_POST['reg_lname'];
		$lname = ucfirst(strtolower($lname));
		if (strlen($lname) > 25 || strlen($lname)<2) {
			array_push($error_array,"Last name must be between 2 to 25 characters<br/>");
		}
		$_SESSION['reg_lname']= $lname;


		$email = $_POST['reg_email'];
		$_SESSION['reg_email']= $email;
		
		$email2 = $_POST['reg_email2'];
		$_SESSION['reg_email2']= $email2;

		//Check if emails are identical - correct email format - already in use
		if ($email == $email2){
			if (filter_var($email,FILTER_VALIDATE_EMAIL)){
				$email = filter_var($email,FILTER_VALIDATE_EMAIL);
				$query = mysqli_query($con, "SELECT email FROM amigo WHERE email = '$email'");
				$numRows = mysqli_num_rows($query);
				if ($numRows > 0){
					array_push($error_array,"Email already in use<br/>");	
				}
			} else {
				array_push($error_array,"Invalid email format<br/>");
			}
		} else {
			array_push($error_array,"Emails do not match<br/>");	
		}

		$username = $_POST['reg_username'];
		$username = str_replace(' ','', $username);
		$_SESSION['reg_username'] = $username;

		//Check if username already exist
		$query = mysqli_query($con, "SELECT username FROM amigo WHERE username = '$username'");
		$numRows = mysqli_num_rows($query);
		if ($numRows>0){
			array_push($error_array,"Username already in use<br/>");

		}

		//Check password length
		$pass = $_POST['reg_password'];
		if (strlen($pass)>25 || strlen($pass<8)){
			array_push($error_array, "Password must be between 8 to 25 characters<br/>");
		}
		$pass2 = $_POST['reg_password2'];
		//Check password if the same
		if ($pass != $pass2){
			array_push($error_array, "Password does not match<br/>");
		} else {
			//Check password requirements
			if (preg_match('/[^A-Za-z0-9]/',$pass)){
				array_push($error_array,"Your password muct only contain english characters of numbers<br/>");
			}
		}
		$pass = md5($pass);

		$gender = $_POST['reg_gender'];
		switch ($gender) {
			case 'm':
				$profilePics = "assets/images/profile_pics/defaults/male.png";
				break;
			case 'f':
				$profilePics = "assets/images/profile_pics/defaults/female.png";
				break;
			case 'l':
				$profilePics = "assets/images/profile_pics/defaults/lgbtqai.png";
				break;
			default:
				# code...
				break;
		}

		//Assign variables manually
		$status = "online";
		$account ="active";
		$sDate = Date("Y-m-d");
		$banner = "assets/images/banners/default.png";

		//If no error save to database
		if (empty($error_array)){
			$query = mysqli_query($con, "INSERT INTO amigo VALUES (NULL,'$fname','$lname','$email','$username','$pass','$gender','$profilePics','$status','$account','0','0',',','$sDate','$banner','$sDate')");
			array_push($error_array,"Succefully registered. Continue to login<br/>");

			//Clear session variables
			$_SESSION['reg_fname']="";
			$_SESSION['reg_lname'] = "";
			$_SESSION['reg_email'] = "";
			$_SESSION['reg_email2'] = "";
			$_SESSION['reg_username'] = "";
			$_SESSION['reg_password'] = "";
			$_SESSION['reg_password2'] = "";
			$_SESSION['reg_gender'] = "";
		}
	}
?>