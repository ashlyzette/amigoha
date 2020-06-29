<?php
	include("../../config/config.php");
	include("../classes/User.php");
    include("../classes/Barkada.php");

    $limit = 3;
    $message = new Barkada($con,$_REQUEST['userLoggedIn']);
    echo $message->getAmigoDropdown($_REQUEST,$limit);
?>