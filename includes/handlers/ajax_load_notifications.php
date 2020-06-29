<?php
	include("../../config/config.php");
	include("../classes/User.php");
    include("../classes/Class_Notification.php");

    $limit = 5;
    $message = new Notification($con,$_REQUEST['userLoggedIn']);
    echo $message->getNotificationDropdown($_REQUEST,$limit);
?>