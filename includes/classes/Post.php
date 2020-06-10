<?php
class Post{
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con,$user);
	}

	public function submitPost($body, $user_to){
		//Sanitize the post by removing tags
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($this->con,$body);

		// check if included enter in the post
		$body = str_replace('\r\n', '\n', $body);
		$body = nl2br($body); //Looks for line breaks and replace it with an html tag

		// Delete all spaces
		$check_empty = preg_replace('/\s+/','',$body);

		if ($check_empty != ""){
			// Current date and time
			$date_added = Date("Y-m-d H:i:s");
			//Get username from user.php class
			$added_by = $this->user_obj->getUsername();

			//If user is not on own profile, user_to is none
			if ($user_to == $added_by){
				$user_to = "none";
			}

			//Insert post to database
			$lou_query = mysqli_query($this->con,"INSERT INTO posts VALUES (NULL,'$body','$added_by','$date_added','$user_to','no','no','0')");
			$returned_id = mysqli_insert_id($this->con);

			//Insert Notification

			//Update post count for user
			$num_posts = $this->user_obj->getNumposts();
			$num_posts ++;
			$udpate_query = mysqli_query($this->con,"UPDATE amigo SET num_posts='$num_posts' WHERE username = '$added_by'");
		}
	}

	public function loadPostsFriends(){
		// $page = $data['page'];
		// $userLoggedIn = $this->user_obj->getUsername();

	// 	if ($page == 1){
	// 		$start = 0;
	// 	} else {
	// 		$start = ($page-1) * $limit;
	// 	}


		$str="";
		$data_query = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted ='no' ORDER BY id DESC");
		
		if(mysqli_num_rows($data_query) > 0){
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$post = $row['post'];
				$date_added = $row['date_added'];
				$user_to = $row['user_to'];
				$likes = $row['likes'];

				if ($user_to == 'none'){
					$user_to = "";
				} else {
					$user_to_obj = new User($con,$user_to);
					$user_to_name -> $user_to_obj ->getFirstAndLastName;
					$user_to = "to <a href ='" . $user_to . "'>" . $user_to_name ."</a>";
				}

				// if($num_iterations++ < $start){
				// 	continue;
				// }

				// //Once 10 post have been loaded then break
				// if ($count>$limit){
				// 	break;
				// } else {
				// 	$count++;
				// }



				// Get the details of added by
				$added_by = $row['added_by'];
				$query = mysqli_query($this->con,"SELECT first_name, last_name, profile_pic FROM amigo WHERE username = '$added_by'");
				$added_by_row = mysqli_fetch_array($query);
				$first_name = $added_by_row['first_name'];
				$last_name = $added_by_row['last_name'];
				$profile_pics = $added_by_row['profile_pic'];

				// //Time frame
				$date_time_now = Date("Y-m-d H:i:s");
				$start_date = new DateTime($date_added); 	// time of post
				$end_date = new DateTime($date_time_now ); 	//today's date
				$interval = $start_date->diff($end_date);

				if ($interval->y>=1){
					if ($interval->y == 1){
						$time_message = $interval->y . " year ago";
					} else {
						$time_message = $interval->y . " years ago";
					}
				} else if($interval->m>=1){
					if ($interval->m == 1){
						$time_message = $interval->m . " month ago";
					} else {
						$time_message = $interval->m . " months ago";
					}
				} else if($interval->d>=1){
					if ($interval->d == 1){
						$time_message = $interval->d . " day ago";
					} else {
						$time_message = $interval->d . " days ago";
					}
				} else if($interval->h>=1){
					if ($interval->h == 1){
						$time_message = $interval->h . " hour ago";
					} else {
						$time_message = $interval->h . " hours ago";
					}
				} else if($interval->i >= 1){
					if ($interval->i == 1){
						$time_message = $interval->i . " minute ago";
					} else {
						$time_message = $interval->i . " minutes ago";
					}
				} else {
					$time_message ="Just now";
				}

				$str .= "<div class='status_post ml-2'>
							<div class='post_profile_pic'>
								<img class='px-1 py-3' src = '$profile_pics' width='60'>
							</div>
							<div class='posted_by mt-2'>
								<a class = 'card-title' href='$added_by'> $first_name $last_name </a> 
							</div>
							<div class-text id='time_message'>
								added $time_message
							</div>
							<div id='post_body'>
								<p class ='class-text'>
									$post
								</p>
							</div>
						</div>";
				}//End of While
				// if($count > $limit){
				// 	$str .="<input type='hidden' class='nextpage' value ='" . ($page + 1) . "'>
				// 				<input type='hidden' class='noMorePosts' value ='false'>";
				// } else {
				// 	$str .="<input typ='hidden' class='noMorePosts' value ='false'><p style='text-align:center;'>No more posts to show! </p>";
				// }
			} 
			echo $str;
		}//End of If
	}
?>