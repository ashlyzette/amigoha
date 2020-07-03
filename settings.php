<!DOCTYPE html>
<!-- Start of Header -->
<?php
	include ("includes/standards/header.php");
?>
<div class="container settings_page">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a>
                <a class="nav-link" id="v-pills-user-tab" data-toggle="pill" href="#v-pills-user" role="tab" aria-controls="v-pills-user" aria-selected="false">User Information</a>
                <a class="nav-link" id="v-pills-notification-tab" data-toggle="pill" href="#v-pills-notification" role="tab" aria-controls="v-pills-notification" aria-selected="false">Notifications</a>
                <a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Security</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <iframe class='iframe_profile embed-responsive' src='upload.php' id='comment_iframe' style='$iframe_height'></iframe>
                </div>
                <div class="tab-pane fade" id="v-pills-user" role="tabpanel" aria-labelledby="v-pills-user-tab">
                    <div class = "col-sm-8"> <h3> Edit User Information </h3> <hr>    
                    <?php include ("user.php"); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-notification" role="tabpanel" aria-labelledby="v-pills-notifiction-tab">...</div>
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">...</div>
            </div>
        </div>
    </div>
</div>
<?php
    include ("includes/standards/footer.php");
?>