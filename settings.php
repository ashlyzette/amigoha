<!DOCTYPE html>
<!-- Start of Header -->
<?php
    include ("includes/standards/header.php");
    require 'includes/form_handlers/add_profile.php';
    
?>
<div class="container settings_page">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a>
                <a class="nav-link" id="v-pills-user-tab" data-toggle="pill" href="#v-pills-user" role="tab" aria-controls="v-pills-user" aria-selected="false">User Information</a>
                <a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Security</a>
            </div>
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
                                    <input type="text" class="form-control" name="middleschool" id="middleschool" value = '<?php echo $middle_school; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="highschool">High School</label>
                                    <input type="text" class="form-control" name="highschool" id="highschool" value = '<?php echo $high_school; ?>'>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="college">College</label>
                                    <input type="text" class="form-control" name="college" id="highschool" value = '<?php echo $college; ?>'>
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
                        <div class="form-group col-md-12">
                            <label for ="oldpassword">Old Password</label>
                            <input type="password" class="form-control" name="old_password" id ="oldpassword">
                        </div>
                        <div class="form-group col-md-12">
                            <label for ="newpassword">New Password</label>
                            <input type="password" class="form-control" name="new_password" id ="newpassword">
                        </div>
                        <div class="form-group col-md-12">
                            <label for ="verifypassword">Verify Password</label>
                            <input type="password" class="form-control" name="verify_password" id ="verifypassword">
                        </div>
                        <div class="form-group col-md-12">
                            <button class="form-control btn btn-primary" id="save">
                            Save Changes
                            </button>
                        </div>
                        <div class="form-group col-md-12">
                            <button class="form-control btn btn-danger" id="save">
                            Close Account
                            </button>
                        </div>
                    <form>  
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include ("includes/standards/footer.php");
?>