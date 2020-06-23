<?php
    class Message{
        private $user_obj;
        private $con;

        public function __construct($con, $user){
            $this->con = $con;
            $this->user_obj = new User($con,$user);
        } // end of contsruct
    
        public function GetRecentUser(){
            $myUser = $this->user_obj->getUsername();
            $lou_query = mysqli_query($this->con, "SELECT user_to,user_from FROM messages WHERE user_to='$myUser' OR user_from='$myUser' ORDER BY id DESC LIMIT 1");
            if (mysqli_num_rows($lou_query)==0){
                return false;
            } else {
                //Return the user you are messaging with
                $data = mysqli_fetch_array($lou_query);
                $user_to = $data['user_to'];
                $user_from = $data['user_from'];
                if ($user_to == $myUser){
                    return $user_from;
                } else {
                    return $user_to;
                }
            }
        } //end GetRecentUser

        public function SendMyMessage($user_to,$body){
            $myUser = $this->user_obj->getUsername();
            $dateNow= DATE("Y-m-d H:i:s");
            $message_body = mysqli_query($this->con, "INSERT INTO messages VALUES (NULL,'$user_to','$myUser','$body','$dateNow','no','no','no','$dateNow')");
        }

        public function LoadMessages($user_to){
            $div = "";
            $user_log = $this->user_obj->getUsername();
            $dateOpened =Date("Y-m-d H:i:s");
            // change opened and viewed flag to yes
            $update_obj = mysqli_query($this->con, "UPDATE messages SET opened='yes', viewed ='yes',dateOpened='$dateOpened' WHERE user_to = '$user_to and user_from ='$user_log'");
            // get all messages from the sender
            $all_messages_obj = mysqli_query($this->con,"SELECT * FROM messages WHERE (user_to='$user_to' AND user_from = '$user_log') OR (user_to='$user_log' AND user_from = '$user_to')");
            $div="";
            while($message=mysqli_fetch_array($all_messages_obj)){
                $dateSent = $message['dateSent'];
                $user_to = $message['user_to'];
                $user_from = $message['user_from'];
                $message_body = $message['message'];
                $title = ($user_to == $user_log) ? "<div class='d-flex justify-content-start mt-2'><span class='user_message'>" : "<div class = 'd-flex justify-content-end'><span class='friend_message'>";

                //Get the time it was sent
                $date_time_now = Date('Y-m-d H:i:s');
				$start_date = new DateTime($dateSent); 	// time of post
				$end_date = new DateTime($date_time_now ); 	//today's date
				$interval = $start_date->diff($end_date);

				if ($interval->y>=1){
					if ($interval->y == 1){
						$time_message = $interval->y . ' year ago';
					} else {
						$time_message = $interval->y . ' years ago';
					}
				} else if($interval->m>=1){
					if ($interval->m == 1){
						$time_message = $interval->m . ' month ago';
					} else {
		    			$time_message = $interval->m . ' months ago';
					}
				} else if($interval->d>=1){
					if ($interval->d == 1){
						$time_message = $interval->d . ' day ago';
					} else {
						$time_message = $interval->d . ' days ago';
					}
				} else if($interval->h>=1){
					if ($interval->h == 1){
						$time_message = $interval->h . ' hour ago';
					} else {
						$time_message = $interval->h . ' hours ago';
					}
				} else if($interval->i >= 1){
					if ($interval->i == 1){
						$time_message = $interval->i . ' minute ago';
					} else {
						$time_message = $interval->i . ' minutes ago';
					}
				} else {
					$time_message ='Just now';
                }
                $time = ($user_to == $user_log) ? "<div class='d-flex justify-content-start time_message'>" . $time_message . "</div>" : "<div class = 'd-flex justify-content-end time_message'>" . $time_message . "</div>";
                $div .= $title . $message_body . "</span></div>" . $time ;

            }//End while loop
            return $div;
        }
    }//end of class message
?>