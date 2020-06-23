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
                    return $user_to;
                } else {
                    return $user_from;
                }
            }
        } //end GetRecentUser

        public function SendMyMessage($user_log,$body){
            $myUser = $this->user_obj->getUsername();
            $dateNow= DATE("Y-m-d H:i:s");
            echo $dateNow;
            $message_body = mysqli_query($this->con, "INSERT INTO messages VALUES (NULL,'$myUser','$user_log','$body','$dateNow','no','no','no')");
        }
    }//end of class message
?>