<?php
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $fname = "";
        $lname = "";
        $email = "";
        $gender = "";
        $birthday = "";
        $id = "";
        $street = "";
        $apartment = "";
        $city = "";
        $country = "";
        $state = "";
        $zip = "";
        $contact_number = "";
        $elementary="";
        $middle_school="";
        $high_school="";
        $college="";

        //Get the user information
        $profile = mysqli_query($con,"SELECT * FROM amigo WHERE username = '$username'");
        if ($row = mysqli_fetch_array($profile)){
            $fname = $row['first_name'];
            $lname = $row['last_name'];
            $email = $row['email'];
            $gender = $row['gender'];
            $id = $row['ID'];
            if ($row['birthday']){
                $birth = date($row['birthday']);
                $birthday = date('Y-m-d', strtotime('$birth'));
            }
        }
        $contact = mysqli_query($con,"SELECT * FROM contact WHERE amigo_id = '$id'");
        if ($add = mysqli_fetch_array($contact)){
            $street = $add['street'];
            $apartment = $add['apartment'];
            $city = $add['city'];
            $country = $add['country'];
            $state = $add['states'];
            $zip = $add['zip'];
            $contact_number = $add['contact_number'];
        }
    } //End of if(isset)

    if (isset($_POST['save_profile'])){
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $birthday = $_POST['birthday'];
        $street = $_POST['street'];
        $apartment = $_POST['apartment'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $contact_number = $_POST['contact'];
        $elementary = $_POST['elementary'];
        $middle_school = $_POST['middleschool'];
        $high_school = $_POST['highschool'];
        $college= $_POST['college'];
        $birth = date('Y-m-d', strtotime($birthday));
        $profile = mysqli_query($con, "UPDATE amigo SET first_name = '$fname',last_name = '$lname',email = '$email',gender = '$gender',birthday= '$birth' WHERE ID = '$id'");
        //Check if address is added in the amigo profile
        $profile = mysqli_query($con, "SELECT amigo_id FROM contact WHERE amigo_id ='$id'");
        if(mysqli_num_rows($profile)>0){
            $profile = mysqli_query($con, "UPDATE contact SET 
                                    street='$street',
                                    apartment='$apartment',
                                    city='$city',
                                    country='$country',
                                    states = '$state',
                                    zip='$zip',
                                    contact_number='$contact_number',
                                    elementary='$elementary',
                                    middle_school='$middle_school',
                                    high_school='$high_school',
                                    college='$college'
                                    WHERE amigo_id='$id'");
        } else {
            $profile = mysqli_query($con, "INSERT INTO contact VALUES 
                                    (NULL,
                                    '$id',
                                    '$street',
                                    '$apartment',
                                    '$city',
                                    '$country',
                                    '$state',
                                    '$zip',
                                    '$contact_number',
                                    '$elementary',
                                    '$middle_school',
                                    '$high_school',
                                    '$college'
                                    )");
        }
    } // End of $_POST
?>