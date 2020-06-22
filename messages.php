<?php
    include ("includes/standards/header.php");
    include ("includes/classes/User.php");
    include ("includes/classes/Post.php");
    include ("includes/classes/Class_Messages.php");

    $message_obj = new Message($con,$user_log);

    //Get the name of the user you will send the message or set a new message
    if (isset($_GET['amigo'])){
        $user_to = $_GET['amigo'];
    } else {
        $user_to = $message_obj->GetRecentUser();
        if ($user_to == false){
            $user_to = 'new';
        } 
    }

    //Get the friend user details
    if ($user_to != 'new'){
        $user_to_obj = new User($con,$user_to);
    } 
?>
<div class="container">
	<div class = "w-25 mt-3 leftBox">
		<?php include ("includes/standards/leftcolumn.php") ?>
		<div class="profleLinks">
			Profile Links
		</div>
    </div>
    <div class = "w-75 mt-3 rightBox">
        <div class ="newsfeed">
            <div class=" col-12 MessageHeader">
                <?php
                    if ($user_to != 'new'){
                        echo "<h5> You and <a href ='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h5>";
                    }
                ?>
            </div>
            <div class="col-12 MessageBody">
                <form action="messages.php" method="POST">
                    <?php
                        if ($user_to == 'new'){

                        } else {
                            echo "<textarea class='form-control' name='myMessage' placeholder='Write your message...'></textarea>";
                            echo "<div class='d-flex justify-content-end'><input class='btn btn-primary btn-sm mt-1' type='input' value='Send'></div>";
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    include ("includes/standards/footer.php");
?>