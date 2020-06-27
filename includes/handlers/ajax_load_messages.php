<?php
	include("../../config/config.php");
	include("../classes/User.php");
    include("../classes/Class_Messages.php");

    $limit = 3;
    $message = new Message($con,$_REQUEST['userLoggedIn']);
    echo $message->getConvosDropdown($_REQUEST,$limit);
?>