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
        $sendTo = $user_to_obj->getUsername();
    } 

    //User sends a message
    if (isset($_POST['SendMessage'])){
    	//Check if there is a message
    	echo "Clicked";
    	if (isset($_POST['myMessage'])){
    		echo "Checked";
    		//Sanitize the message to allow single quotes
    		$body = mysqli_real_escape_string($_POST['myMessage']);
    		$sendMessage = new Message($con,$sendTo);
    		$sendMessage->SendMyMessage($user_log,$body);
    	}

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
                        	echo "<input class = 'form-control' type='text' name ='txtSendTo' placeholder='Enter name'>";
                        	echo "<div class='d-flex justify-content-end'><input type='button' class = 'btn btn-primary btn-sm' value ='Send'></div>";

                        } else {
                            echo "<textarea class='form-control' name='myMessage' placeholder='Write your message...'></textarea>";
                            echo "<div class='d-flex justify-content-end'><button class='btn btn-primary btn-sm mt-1' type='input' name='SendMessage'>Send</button></div>";
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