<?php
	ob_start(); //Turns on output buffering
	session_start();

	$timezone = date_default_timezone_set("America/Los_Angeles");
	$con = mysqli_connect("localhost","root","","social");
	if (mysqli_connect_errno()){
		echo "failed to myDB" .  mysqli_connect_errno();
	}
?>