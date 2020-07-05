<?php
    include("../../config/config.php");
	include("../classes/Corona.php");
    
    $country = $_POST['query'];
    $covid_data = new Covid($con);
    $covid =  $covid_data->getCovidDataCountry($country);
    echo $covid;
?>