<?php
	ob_start(); //Turns on output buffering
	session_start();

	$timezone = date_default_timezone_set("America/Los_Angeles");
	// $con = mysqli_connect("localhost","root","","social");
	$con = mysqli_connect("162.241.24.185","apexhris_loam","8v9Xmnb!$1iaDc6Z","apexhris_amigo");
	if (mysqli_connect_errno()){
		echo "failed to myDB" .  mysqli_connect_errno();
	}
?>