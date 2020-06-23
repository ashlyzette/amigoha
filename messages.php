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

    //User sends a message
    if (isset($_POST['SendMessage'])){
    	//Check if there is a message
    	if (isset($_POST['myMessage'])){
            //Sanitize the message to allow single quotes
            $body = $_POST['myMessage'];
            $body = mysqli_real_escape_string($con, $body);        
            $message_obj->SendMyMessage($user_to,$body);
            unset($_POST);
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
                        echo "<div class = 'container messaging_box mr-4 border rounded mb-2' id='last_message'>";
                        echo $message_obj->LoadMessages($user_to);
                        echo "</div>";
                    }
                ?>
            </div>
            <div class="col-12 MessageBody">
                <form action="" method="POST">
                    <?php
                        if ($user_to == 'new'){
                        	echo "<input class = 'form-control' type='text' name ='txtSendTo' placeholder='Enter name'>";
                        	echo "<div class='d-flex justify-content-end'><button class = 'btn btn-primary btn-sm'> Send </button></div>";

                        } else {
                            echo "<textarea class='form-control' name='myMessage' placeholder='Write your message...'></textarea>";
                            echo "<div class='d-flex justify-content-end'><button class='btn btn-primary btn-sm mt-1 send_button' name='SendMessage'> Send </button></div>";
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    //Initialize message display to the latest message
    var lastMessage = document.querySelector("#last_message");
    lastMessage.scrollTop = lastMessage.scrollHeight;

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
    include ("includes/standards/footer.php");
?>