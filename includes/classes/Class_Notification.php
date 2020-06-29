<?php
    class Notification{
        private $user_obj;
        private $con;

        public function __construct($con, $user){
            $this->con = $con;
            $this->user_obj = new User($con,$user);
        }

        public function getTotalUnread(){
            $user_log = $this->user_obj->getUsername();
            $query = mysqli_query($this->con, "SELECT *  FROM notifications WHERE viewed = 'no' AND user_to ='$user_log'");
            return mysqli_num_rows($query);
        }

        public function insertNotification($post_id, $user_to, $type){
            $user_log = $this->user_obj->getUsername();
            $user_complete_name = $this->user_obj->getFirstAndLastName();
            $dateNow = date("Y-m-d H:i:s");

            switch ($type){
                case 'comment':
                    $message = $user_log . " commented on your post";
                    break;
                case 'like':
                    $message = $user_log . " liked your post";
                    break;
                case 'newsfeed_post':
                    $message = $user_log . " posted on your newsfeed";
                    break;
                case 'comment_non_owner':
                    $message = $user_log . " commented on a post you commented on";
                    break;
                case 'profile_comment':
                    $message = $user_log . " commented on your profile post";
                    break;
            }
            $link = "post.php?id=" . $post_id;
            $lou_query = mysqli_query($this->con, "INSERT INTO notifications VALUES (NULL,'$user_to','$user_log','$message','$link','$dateNow','no','no')");
        }

        public function getNotificationDropdown($data,$limit){
            $page = $data['page'];
            $str="";
            $notifiers=array();
            $user_log = $this->user_obj->getUsername();
        
            if ($page == 1 ){
                $start =0;
            } else {
                $start = ($page -1) * $limit ;
            }

            $set_view_query = mysqli_query($this->con, "UPDATE notifications SET viewed = 'yes' WHERE user_to = '$user_log'");

            $num_iterations =0; //Number of notifications checked
            $count = 1; // Number of notifications posted

            // Get the latest messages only 
            $get_notification_obj = mysqli_query($this->con,"SELECT * FROM notifications WHERE user_to='$user_log' OR user_from='$user_log' ORDER BY ID DESC");
            while ($notify = mysqli_fetch_array($get_notification_obj)){
                $user_to = $notify['user_to'];
                $user_from = $notify['user_from'];
                $message = $notify['message'];
                $link = $notify['link'];

                $notify_user = ($user_to != $user_log) ? $user_to : $user_from;

                    if ($num_iterations++ < $start){
                        continue;
                    }

                    if ($count > $limit){
                        break;
                    } else {
                        $count++;
                    }

                    $is_unread_obj = mysqli_query($this->con,"SELECT opened FROM notifications WHERE user_to ='$user_log' and user_from='$notify_user' ORDER BY ID DESC");
                    $row = mysqli_fetch_array ($is_unread_obj);
                    
                    $message_viewed = (isset($row['opened']) && $row['opened'] == 'no') ? "message_not_viewed" : "message_viewed";

                    //Get chatmate profile
                    $lou_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM amigo WHERE username ='$notify_user'");
                    $row = mysqli_fetch_array($lou_query);
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $profile_pic = $row['profile_pic'];

                    //Get the time it was sent
                    $date_time_now = Date('Y-m-d H:i:s');
                    $start_date = new DateTime($notify['dateSent']); 	// time of chat
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

                    $time_message = "<span class='time_message'>" . $time_message . "</span>";
                    
            

                    $str .=  "<div class ='dropdown-item " . $message_viewed . "'><a href='$link'>
                                <img class='search_header user_profile px-2' src = '$profile_pic' width='60'>
                                 $first_name $last_name $time_message
                                <div class = 'notification_message text-wrap'>$message</div>
                                </a></div> <div class='dropdown-divider'></div>";
            }//End of while
            if ($count > $limit){
                $str .= "<input type='hidden' class ='ShowNextNotification' value ='" . ($page + 1) . "'>
                            <input type='hidden' class = 'NoMoreNotification' value='false'>";  
            } else {
                $str .= "<input type='hidden' class = 'NoMoreNotification' value='true'> <span id='EndOfMessage'> End of notification </span>";  
            }
            return $str;
        }

    } // end of contsruct
?>