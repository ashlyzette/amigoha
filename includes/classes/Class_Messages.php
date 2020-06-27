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
            $user_log = $this->user_obj->getUsername();
            $dateNow= DATE("Y-m-d H:i:s");
            $message_body = mysqli_query($this->con, "INSERT INTO messages VALUES (NULL,'$user_to','$user_log','$body','$dateNow','no','no','no','$dateNow')");
        } //End SendMyMessages

        public function LoadMessages($user_to){
            $str = "";
            $user_log = $this->user_obj->getUsername();
            $dateOpened =Date("Y-m-d H:i:s");
            // change opened and viewed flag to yes
            $update_obj = mysqli_query($this->con, "UPDATE messages SET opened='yes', viewed ='yes',dateOpened='$dateOpened' WHERE user_to = '$user_to and user_from ='$user_log'");
            // get all messages from the sender
            $all_messages_obj = mysqli_query($this->con,"SELECT * FROM messages WHERE (user_to='$user_to' AND user_from = '$user_log') OR (user_to='$user_log' AND user_from = '$user_to')");
            while($message=mysqli_fetch_array($all_messages_obj)){
                $dateSent = $message['dateSent'];
                $user_to = $message['user_to'];
                $user_from = $message['user_from'];
                $message_body = $message['message'];
                $title = ($user_to == $user_log) ? "<div class='d-flex justify-content-start mt-2'><span class='user_message'>" : "<div class = 'd-flex justify-content-end mt-2'><span class='friend_message'>";

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
                $str .= $title . $message_body . "</span></div>" . $time ;

            }//End while loop
            return $str;
        } //End LeadMessages

        public function LoadChatMates(){
            $str="";
            $chatters=array();
            $user_log = $this->user_obj->getUsername();
            // Get the latest messages only 
            $get_chatmates_obj = mysqli_query($this->con,"SELECT user_to, user_from FROM messages WHERE user_to='$user_log' OR user_from='$user_log' ORDER BY ID DESC");
            while ($chatmates = mysqli_fetch_array($get_chatmates_obj)){
                $user_to = $chatmates['user_to'];
                $user_from = $chatmates['user_from'];

                $chatmate = ($user_to != $user_log) ? $user_to : $user_from;

                //Check if friend is already added
                if (!in_array($chatmate,$chatters)){
                    //add to array
                    array_push($chatters,$chatmate);

                    //Get chatmate profile
                    $lou_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM amigo WHERE username ='$chatmate'");
                    $row = mysqli_fetch_array($lou_query);
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $profile_pic = $row['profile_pic'];

                    //Get the latest message
                    $latest_chat_obj = mysqli_query($this->con,"SELECT * FROM messages WHERE (user_to='$chatmate' and user_from='$user_log') OR (user_to='$user_log' and user_from='$chatmate') ORDER BY ID DESC Limit 1");
                    $chat = mysqli_fetch_array($latest_chat_obj);
                    $message = $chat['message'];
                    //Get the time it was sent
                    $date_time_now = Date('Y-m-d H:i:s');
                    $start_date = new DateTime($chat['dateSent']); 	// time of chat
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


                    $time_message = ($user_to != $user_log) ? "<span class='time_message'> you sent " . $time_message . "</span>" : "<span class='time_message'> sent to you " . $time_message . "</span>";
                    
                    //Allow 12 characters to display only
                    $message_dot = (strlen($message)>=20) ? "..." : "";
                    $message_split = str_split($message,20);
                    $message = $message_split[0] . $message_dot;

                    
                    $str .=  "<div> <a href='messages.php?amigo=$chatmate'>
                                <img class='search_header user_profile px-2 py-1' src = '$profile_pic' width='80'>
                                 $first_name $last_name </a>
                                <div>$time_message</div>
                                <div>$message</div>
                                <hr/>
                            </div>";
                }//end of if array
            }//End of while
            echo $str;
        }

        public function getConvosDropdown($data,$limit){
            $page = $data['page'];
            $str="";
            $chatters=array();
            $user_log = $this->user_obj->getUsername();

            if ($page == 1 ){
                $start =0;
            } else {
                $start = ($page -1) * $limit ;
            }

            $set_view_query = mysqli_query($this->con, "UPDATE messages SET viewed = 'yes' WHERE user_to = '$user_log'");

            $num_iterations =0; //Number of messages checked
            $count = 1; // Number of messages posted

            // Get the latest messages only 
            $get_chatmates_obj = mysqli_query($this->con,"SELECT user_to, user_from FROM messages WHERE user_to='$user_log' OR user_from='$user_log' ORDER BY ID DESC");
            while ($chatmates = mysqli_fetch_array($get_chatmates_obj)){
                $user_to = $chatmates['user_to'];
                $user_from = $chatmates['user_from'];

                $chatmate = ($user_to != $user_log) ? $user_to : $user_from;

                //Check if friend is already added
                if (!in_array($chatmate,$chatters)){
                    //add to array
                    array_push($chatters,$chatmate);

                    if ($num_iterations++ < $start){
                        continue;
                    }

                    if ($count > $limit){
                        break;
                    } else {
                        $count++;
                    }

                    $is_unread_obj = mysqli_query($this->con,"SELECT opened FROM messages WHERE user_to ='$user_log' and user_from='$chatmate' ORDER BY ID DESC");
                    $row = mysqli_fetch_array ($is_unread_obj);
                    $message_viewed = (isset($row['opened']) && $row['opened'] == 'no') ? "message_not_viewed" : "message_viewed";

                    //Get chatmate profile
                    $lou_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM amigo WHERE username ='$chatmate'");
                    $row = mysqli_fetch_array($lou_query);
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $profile_pic = $row['profile_pic'];

                    //Get the latest message
                    $latest_chat_obj = mysqli_query($this->con,"SELECT * FROM messages WHERE (user_to='$chatmate' and user_from='$user_log') OR (user_to='$user_log' and user_from='$chatmate') ORDER BY ID DESC Limit 1");
                    $chat = mysqli_fetch_array($latest_chat_obj);
                    $message = $chat['message'];
                    //Get the time it was sent
                    $date_time_now = Date('Y-m-d H:i:s');
                    $start_date = new DateTime($chat['dateSent']); 	// time of chat
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

                    $time_message = ($user_to != $user_log) ? "<span class='time_message'> you sent " . $time_message . "</span>" : "<span class='time_message'> sent to you " . $time_message . "</span>";
                    
                    //Allow 12 characters to display only
                    $message_dot = (strlen($message)>=20) ? "..." : "";
                    $message_split = str_split($message,20);
                    $message = $message_split[0] . $message_dot;

                    
                    $str .=  "<div class ='dropdown-item " . $message_viewed . "'><a href='messages.php?amigo=$chatmate'>
                                <img class='search_header user_profile px-2' src = '$profile_pic' width='60'>
                                 $first_name $last_name $time_message
                                <div>$message</div>
                                </a></div> <div class='dropdown-divider'></div>";
                }//end of if array
            }//End of while
            if ($count > $limit){
                $str .= "<input type='hidden' class ='ShowNextMessages' value ='" . ($page + 1) . "'>
                            <input type='hidden' class = 'NoMoreMessages' value='false'>";  
            } else {
                $str .= "<input type='hidden' class = 'NoMoreMessages' value='true'> <span id='EndOfMessage'> End of messages </span>";  
            }
            return $str;
        }
    }//end of class message
?>