<?php
	include("../../config/config.php");
    include("../classes/Class_Messages.php");

    $user_to = $_POST['user_to'];
    $user_from = $_POST['user_from'];
    
    $message = new Message($con,$user_from);
    $load = $message->LoadMessages($user_to);
    return $load;
?>