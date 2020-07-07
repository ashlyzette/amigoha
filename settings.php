<?php
    include ("includes/standards/header.php");
    include ("includes/form_handlers/add_profile.php");
    
    $error_array =array();
    if (isset($_POST['change_password'])){
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $verify_password = $_POST['verify_password'];

        //Check if password match
        if ($new_password != $verify_password){
            array_push($error_array,"Password does not match");
        } else {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT,['cost' =>12]);
            $old_hash = password_hash($old_password, PASSWORD_DEFAULT,['cost' =>12]);
        }
        //check if old password matches the password in database
        $lou_query= mysqli_query($con, "SELECT password FROM amigo WHERE username='$user_log'");
        $db_password = mysqli_fetch_array($lou_query);
        $db_password = $db_password['password'];
        if (md5($old_password) != $db_password){
            //check password using hash
            $verify = password_verify($old_password,$db_password);
            if (!$verify){
                array_push($error_array,"Incorrect password");
            }
        } 

        //Save new password if no error
        if (empty($error_array)){
            $update_password = mysqli_query($con,"UPDATE amigo SET password = '$new_hash' WHERE username='$user_log'");
            array_push($error_array,"Password updated");
        }
    }
?>
<div class="container settings_page">
    <div class="row">
        <div class="col-3">
            <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <li><a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a></li>
                <li><a class="nav-link" id="v-pills-user-tab" data-toggle="pill" href="#v-pills-user" role="tab" aria-controls="v-pills-user" aria-selected="false">User Information</a></li>
                <li><a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Security</a></li>
            </ul>
        </div>
        <div class="col-9 settings_background">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <iframe class='iframe_profile embed-responsive' src='upload.php' id='comment_iframe' style='$iframe_height'></iframe>
                </div>

                <div class="tab-pane fade" id="v-pills-user" role="tabpanel" aria-labelledby="v-pills-user-tab">
                    <div class = "col-sm-10 ml-auto mr-auto"> <h3 class = "text-right"> Edit User Information </h3>
                        <h4 class = "mb-4"> Personal <hr /> </h4> 
                        <form class = "mt-2" action="" method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value = '<?php echo $fname; ?>' required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value = '<?php echo $lname; ?>' required>
                                </div>
                                <div class = "form-group col-md-6">
                                    <div class="col-md-6">Gender</div>
                                    <?php 
                                        if ($gender=='m'){
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='m' checked>
                                                <label for='male'>Male</label>";
                                        } else {
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='m'>
                                                <label for='male'>Male</label>"; 
                                        }

                                        if ($gender =='f'){
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='f' checked>
                                                <label for='female'>Female</label>";
                                        } else {
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='f'>
                                                <label for='female'>Female</label>";
                                        }
                                        
                                        if ($gender == 'l'){
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='l' checked>
                                                <label for='lgbtqai'>LGBTQAI</label>";
                                        } else {
                                            echo "<input class = 'mt-3 ml-2' type='radio' name='gender' value ='l'>
                                                <label for='lgbtqai'>LGBTQAI</label>";
                                        }
                                    ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="birthday">Birthday</label>
                                    <input type="date" class="form-control" name="birthday" id="birthday" value = '<?php echo $birthday; ?>' required>
                                    
                                </div>
                                
                                <div class="form-group col-md-12 mt-5">
                                    <h4> Contact <hr/> </h4>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value = '<?php echo $email; ?>'>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="number">Contact Number</label>
                                    <input type="text" class="form-control" name="contact" id="number" value = '<?php echo $contact_number; ?>'>
                                </div>

                                <div class="form-group col-md-12 mt-5">
                                    <h4> School <hr/></h4>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="elementary">Elementary</label>
                                    <input type="text" class="form-control" name="elementary" id="elementary" value = '<?php echo $elementary; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="middleschool">Middle School</label>
                                    <input type="text" class="form-control" name="middle_school" id="middleschool" value = '<?php echo $middle_school; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="highschool">High School</label>
                                    <input type="text" class="form-control" name="high_school" id="highschool" value = '<?php echo $high_school; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="college">College</label>
                                    <input type="text" class="form-control" name="college" id="college" value = '<?php echo $college; ?>'>
                                </div>


                                <div class="form-group col-md-12 mt-5">
                                    <h4> Address <hr/></h4>
                                </div>
                                <div class="form-group col-md-10">
                                    <label for="street">Street</label>
                                    <input type="text" class="form-control" name="street" id="street" value = '<?php echo $street; ?>'>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="apartment">House #</label>
                                    <input type="text" class="form-control" name="apartment" id="apartment" value = '<?php echo $apartment; ?>'>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="city">City / Town</label>
                                    <input type="text" class="form-control" name="city" id="city" value = '<?php echo $city; ?>'>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="state">State / Province</label>
                                    <input type="text" class="form-control" name="state" id="state" value = '<?php echo $state; ?>'>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="zip">Postal</label>
                                    <input type="text" class="form-control" name="zip" id="zip" value = '<?php echo $zip; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        <?php
                                            $country_json = file_get_contents("assets/json/countries.json");
                                            $json = json_decode($country_json,true);
                                            foreach ($json as $key => $val){
                                                if ($val['name'] == $country){
                                                    echo "<option selected>" . $val['name'] . "</option>";
                                                } else {
                                                    echo "<option>" . $val['name'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <button class="form-control btn btn-primary" name="save_profile" id="save">
                                    Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                    <div class = "col-sm-10 ml-auto mr-auto"> <h3 class= "text-right"> Edit Security Settings </h3> <hr />  
                    <form action ="" method="POST">
                        <?php 
                            if (in_array("Password updated", $error_array)){
                                echo "<span class ='entry_success'>Password successfully saved!</span>";
                            }
                        ?>
                        <div class="form-group col-md-12">
                            <label for ="oldpassword">Old Password</label>
                            <input type="password" class="form-control" name="old_password" id ="oldpassword">
                            <?php 
                                if (in_array("Incorrect password", $error_array)){
                                    echo "<span class ='required_field'>Incorrect password</span>";
                                }
                            ?>
                        </div>
                        <div class="form-group col-md-12">
                            <label for ="newpassword">New Password</label>
                            <input type="password" class="form-control" name="new_password" id ="newpassword">
                        </div>
                        <div class="form-group col-md-12">
                            <label for ="verifypassword">Verify Password</label>
                            <input type="password" class="form-control" name="verify_password" id ="verifypassword">
                            <?php 
                                if (in_array("Password does not match", $error_array)){
                                    echo "<span class ='required_field'>Password does not match</span>";
                                }
                            ?>
                        </div>
                        <div class="form-group col-md-12">
                            <button class="form-control btn btn-primary" name = "change_password" id="save_password">
                            Save Changes
                            </button>
                        </div>
                    </form>
                    <form action ="" method="POST">
                        <div class="form-group col-md-12">
                            <button class="form-control btn btn-danger" id="close">
                            Close Account
                            </button>
                        </div>
                    <form>  
                </div>
            </div>
            <script>
                $('#v-pills-tab a').click(function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                    console.log ($(this));
                });

                // store the currently selected tab in the hash value
                $("ul.nav-pills > li > a").on("shown.bs.tab", function(e) {
                    var id = $(e.target).attr("href").substr(1);
                    window.location.hash = id;
                });

                // on load of the page: switch to the currently selected tab
                var hash = window.location.hash;
                $('#v-pills-tab a[href="' + hash + '"]').tab('show');
            </script>
        </div>
    </div>
</div>
<?php
    include ("includes/standards/footer.php");
?>