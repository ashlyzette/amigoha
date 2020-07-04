<?php
    include ("includes/standards/header.php");
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

    //Friend Request
	if (isset($_POST['btnRequest'])){
		$profile_obj= new User($con, $_POST['username']);
		$profile_obj->FriendRequest($user_log,$_POST['username']);
	}
?>
<div class="container">
	<div class = "w-25 mt-3 leftBox">
        <div class ="profileBox">
        <?php
            echo "<div class = 'd-flex justify-content-sm-between'>
                    <div class ='ml-2'>
                        <h5 class='text-success text-center pt-2'> Conversations </h5>
                    </div>
                    <div class = 'new_chat mt-2 mr-auto'>
                        <sup id ='show_chat'> New Chat </sup><a href='messages.php?amigo=new'><i class='fas fa-pen'></i></a>
                    </div>
                </div>
                    <hr/>"; 
            $message_obj->LoadChatMates();
        ?>
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
                            ?>
                            <span>Send message to:</span> 
                            <input class = 'form-control ml-1' type='text' onkeyup='getFriendsList(this.value, "<?php echo $user_log; ?>")' name ='SearchFriends' placeholder='Enter name to search...'>
                        	<div class='col-12 friendslist'></div>

                       <?php } else {
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
    var user_to = '<?php echo $user_to; ?>';
    if (user_to != 'new'){
        var lastMessage = document.querySelector("#last_message");
        lastMessage.scrollTop = lastMessage.scrollHeight;

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    }
</script>
<?php
    include ("includes/standards/footer.php");
?>