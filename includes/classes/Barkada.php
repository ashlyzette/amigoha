<?php
    class Barkada{
        private $user_obj;
        private $con;

        public function __construct($con, $user){
            $this->con = $con;
            $this->user_obj = new User($con,$user);
        }

        public function getTotalBarkada(){
            $user_to = $this->user_obj->getUsername();
            $lou_query = mysqli_query($this->con, "SELECT user_to FROM friendRequests WHERE user_to ='$user_to' AND status='pending'");
            return mysqli_num_rows($lou_query);
        }

        public function getAmigoDropdown($data,$limit){
            $page = $data['page'];
            $str="";
            $chatters=array();
            $user_log = $this->user_obj->getUsername();

            if ($page == 1 ){
                $start =0;
            } else {
                $start = ($page -1) * $limit ;
            }

            // $set_view_query = mysqli_query($this->con, "UPDATE friendRequests SET viewed = 'yes' WHERE user_to = '$user_log'");

            $num_iterations =0; //Number of messages checked
            $count = 1; // Number of messages posted

            // Get all amigo request
            $get_amigos_obj = mysqli_query($this->con,"SELECT * FROM friendRequests WHERE user_to='$user_log' AND status='pending' ORDER BY ID DESC");
            while ($amigos = mysqli_fetch_array($get_amigos_obj)){
                $user_to = $amigos['user_to'];
                $user_from = $amigos['user_from'];

                if ($num_iterations++ < $start){
                    continue;
                }

                if ($count > $limit){
                    break;
                } else {
                     $count++;
                }
                    
                $is_unread_obj = mysqli_query($this->con,"SELECT viewed FROM friendRequests WHERE user_to ='$user_log' and status='pending' and viewed != 'yes' ORDER BY ID DESC");
                $row = mysqli_fetch_array ($is_unread_obj);
                $amigo_viewed = (isset($row['viewed']) && $row['viewed'] != 'yes') ? "message_not_viewed" : "message_viewed";
                    
                //Get new amigo profile
                $lou_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM amigo WHERE username ='$user_from'");
                $row = mysqli_fetch_array($lou_query);
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $profile_pic = $row['profile_pic'];

                //Get the time it was sent
                $date_time_now = Date('Y-m-d H:i:s');
                $start_date = new DateTime($amigos['request_date']); 	// time of chat
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
                    
                    $str .=  "<div class ='dropdown-item " . $amigo_viewed . "'><a href='$user_from'>
                                <img class='search_header user_profile px-2' src = '$profile_pic' width='60'>
                                 $first_name $last_name $time_message
                                <form class='form-inline' action method='POST'>
                                    <button class='btn btn-primary btn-sm mr-1' name ='btnAccept'>Accept</button>
                                    <button class='btn btn-danger btn-sm ml-1' name = 'btnDecline'>Decline</button>
                                    <input type = 'hidden' name='friendname' value ='$user_from'>
                                </form>
                                </a></div> <div class='dropdown-divider'></div>";
            }//End of while
            if ($count > $limit){
                $str .= "<input type='hidden' class ='ShowNextAmigo' value ='" . ($page + 1) . "'>
                            <input type='hidden' class = 'NoMoreAmigo' value='false'>";  
            } else {
                $str .= "<input type='hidden' class = 'NoMoreAmigo' value='true'> <span id='EndOfMessage'> End of amigo request </span>";  
            }
            return $str;
        }

        public function getAmigoSearch($query,$limit){
            //Predict what they are using
            $str="";
            $user_log = $this->user_obj->getUsername();
            $friendReturn = mysqli_query($this->con, "SELECT * FROM amigo WHERE username LIKE '%$query%' AND account ='active' LIMIT " . $limit);
            $num_rows = mysqli_num_rows($friendReturn);
            if ($num_rows == 0){
                $friendReturn = mysqli_query($this->con, "SELECT * FROM amigo WHERE first_name LIKE '%$query%' AND account ='active' LIMIT " . $limit);
                $num_rows = mysqli_num_rows($friendReturn);
                if ($num_rows == 0){
                    $friendReturn = mysqli_query($this->con, "SELECT * FROM amigo WHERE last_name LIKE '%$query%' AND account ='active' LIMIT " . $limit);
                    if ($num_rows == 0){
                        return "<div class='text-danger text-center no_search_found'> No amigo with such name...</div><hr/>";
                    }
                }
            } 
            if($query != ""){
                while ($row = mysqli_fetch_array($friendReturn)){
                    $thisuser = new User($this->con, $user_log);
                    $username = $row['username'];

                    //Get number of mutual friends
                    if ($username != $user_log){
                        $mutual_friends = $thisuser->GetMutualFriends($row['username']);
                        //Check if searched friend is a friend

                        if ($thisuser->myFriend($username)){
                            $friend_button="";
                        } else if ($thisuser->SentFriendRequest($user_log,$username)) {
                            $friend_button=" <form action='' method='POST'>
                                            <button class='btn btn-warning btn-sm ml-0' name='search_friend_withdraw'> Withdraw Friend Request </button>
                                            <input type='hidden' name='username' value=".$row['username'].">
                                            </form>";
                        } else if($thisuser->RequestFriend($username,$user_log)) {
                            $friend_button=" <form class = 'form-inline' action='' method='POST'>
                                            <button class='btn btn-primary btn-sm mr-1' name='search_friend_accept'> Accept </button>
                                            <button class='btn btn-danger btn-sm ml-1' name='search_friend_decline'> Decline </button>
                                            <input type='hidden' name='username' value=".$row['username'].">
                                            </form>";
                        }else{
                          $friend_button=" <form  action='' method='POST'>
                                            <button class='btn btn-primary btn-sm ml-0' name='search_friend_request'> Add Friend </button>
                                            <input type='hidden' name='username' value=".$row['username'].">
                                            </form>";
                        }
                        
                        $str.= "<div class='search_card card dropdown-item search_result'>
                                    <div class='row no-gutters'>
                                        <a href='".$row['username']."'>
                                            <div class = 'col-md-4'>
                                                <img class ='profile_image py-2' src='" . $row['profile_pic'] . "' width='40'>
                                            </div>
                                            <div class='col-md-8'>
                                                <h6 class ='search_header card-body'>"
                                                    . $row['first_name'] . " " . $row['last_name'] . 
                                                "</h6>
                                                </a>
                                                <span class ='mutual_friends card-text'>
                                                    $mutual_friends
                                                </span>
                                                $friend_button
                                            </div>
                                    </div>
                                </div><hr/ class='search_line'>";
                    } //end of if
                } // end of while
                return $str;
            } // end of if
        }

        public function getCovidData(){
            $covid_json = file_get_contents("https://covid19api.io/api/v1/AllReports");
            $covid_array = json_decode($covid_json,true);
        
            $str ="<table class='table table-sm covid_table'><tbody>
                    <tr>
                        <th class='text-left' scope='col'>Total Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalCases'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewCases'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Total Deaths</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalDeaths'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Deaths</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewDeaths'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Total Recovered</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalRecovered'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Recovered</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewRecovered'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Active Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['ActiveCases'] ."</td>
                    </tr>
                    <tbody></table>";
                return $str;
            }
    } //end off barkada class
?>