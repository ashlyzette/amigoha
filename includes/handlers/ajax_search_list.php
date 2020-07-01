<?php
	include("../../config/config.php");
	include("../classes/User.php");
	include("../classes/Barkada.php");

	$query = $_POST['query'];
	$user_log = $_POST['user_log'];
	
	$limit = 3;
    $barkada = new Barkada($con,$user_log);
	$search_result = $barkada->getAmigoSearch($query,$limit);
	$search_result .= "<div class = 'see_all_results'><a href='search.php?amigo=" . $query . "'>See All Results</a>";
	echo $search_result;
?>