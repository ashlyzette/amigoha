<?php
class Post{
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con,$user);
	} // end of contsruct

	public function submitPost($body, $user_to, $post_type){
		//Sanitize the post by removing tags
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($this->con,$body);
		$isVideo =false;
		$stop_word="";
		// check if included enter in the post
		$body = str_replace('\r\n', '<br/>', $body);
		$body = nl2br("$body"); //Looks for line breaks and replace it with an break tag

		// Delete all spaces
		$check_empty = preg_replace('/\s+/','',$body);

		if ($check_empty != ""){
			$body_array = preg_split("/\s+/", $body);
			foreach($body_array as $key => $value){
				if (strpos($value,"www.youtube.com/watch?v=") !== false){
					$isVideo = true;
					$value = preg_replace("!watch\?v=!", "embed/",$value);
					$value = "<iframe class=\'youtube\' width=\'420\' height=\'315\' src=\'" . $value . "\' allowfullscreen></iframe>";
					$body_array[$key] = $value;
				}
			}
			$body = implode(" ",$body_array);

			// Current date and time
			$date_added = Date("Y-m-d H:i:s");
			//Get username from user.php class
			$added_by = $this->user_obj->getUsername();

			//If user is not on own profile, user_to is none
			if ($user_to == $added_by){
				$user_to = "none";
			}

			//Fetch stopwords.json
			$stop_words = file_get_contents("assets/json/stopwords.json");
			$json = json_decode($stop_words,true);
			// $stop_words = str_replace('"','',$stop_words);
			// $stop_words = array($stop_words);
			$trend_word = preg_replace("/[^a-zA-Z 0-9]+/","",$body);
			$trend_word = preg_split('/[\s,]+/', $trend_word);

			//Check body if it is a youtube video that is shared, if not calculate trend words
			if ($isVideo === false){
            	foreach($json as $stop_word){
					foreach($trend_word as $key => $trend){
						if (strtolower($stop_word) === strtolower($trend)){
							$trend_word[$key]="";
						}
					}
				}
				//loop trend_word and add or update database
				foreach($trend_word as $key => $value){
					if ($value!="") 
						$this->getTrendWords($value);
				}
			}

			//Check posted article is a link
			
			//Add total post
			$num_posts = $this->user_obj->getNumposts();
			$num_posts ++;
			$udpate_query = mysqli_query($this->con,"UPDATE amigo SET num_posts='$num_posts' WHERE username = '$added_by'");
			
			//Insert post to database
			$lou_query = mysqli_query($this->con,"INSERT INTO posts VALUES (NULL,'$body','$added_by','$date_added','$user_to','no','no','0','$post_type')");
			$post_id = mysqli_insert_id($this->con);
			
			//Insert Notification
			if ($user_to != 'none'){
				$notification = new Notification($this->con, $user_log);
				$notification->insertNotification($post_id, $user_to, 'newsfeed_post');
			}
			
			return $post_id;
		}
	} //end of submit post
	
	public function getTrendWords($trend){
		$trend = strtolower($trend);
		$trending = mysqli_query($this->con,"SELECT * FROM trends WHERE word = '$trend'");
		if (mysqli_num_rows($trending) > 0){
			$update_trend = mysqli_query($this->con,"UPDATE trends SET hits = hits + 1 WHERE word = '$trend'");
		}else {
			$add_trend = mysqli_query($this->con, "INSERT INTO trends VALUES (NULL,'$trend','1')");
		}
	}

	public function loadTrendingWords(){
		$trend = "";
		$trending_obj = mysqli_query($this->con,"SELECT * FROM trends");
		while ($trending = mysqli_fetch_array($trending_obj)){
			for ($i=0; $i<$trending['hits']; $i++){
				$trend .= $trending['word'] . " ";
			}
		}
		return $trend;
	}

	public function loadPostsFriends($data, $limit){
		$page = $data['page'];
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1){
			$start=0;
		} else {
			$start= ($page-1) * $limit;
		}

		// Get data of posts
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
				$iframe_height="";
				$post_type = $row['post_type'];
				
				if (strpos($post,"www.youtube.com") !== false){
					$post = "<div class='embed-responsive embed-responsive-16by9 pl-2 pr-2'>" . $post . "</div>";
				}

				if ($user_to == 'none'){
					$user_to = "";
				} else {
					$user_to_obj = new User($this->con,$user_to);
					$user_to_name= $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href ='" . $user_to . "'>" . $user_to_name ."</a>";
				}
				$added_by = $row['added_by'];

				//Check if account is closed - to be added			

				//Check if post is in album or not
				if ($post_type === 'image'){
					$carousel ="<div id = 'album_carousel' class = 'carousel slide' data-ride='carousel'>
									<div class = 'carousel-inner'>";
					$image_obj = mysqli_query($this->con, "SELECT * FROM images WHERE post_id = '$id'");
					$i=0;
					while ($image_row = mysqli_fetch_array($image_obj)){
						$album_name = $image_row['album_name'];
						$memory = $image_row['image_source'];
						if ($i==0){
							$carousel .= "<div class = 'carousel-item active'>
										<img src = '$memory' class ='d-block w-100'> 
									  </div>";
						} else {
							$carousel .= "<div class = 'carousel-item'>
										<img src = '$memory' class ='d-block w-100'> 
									  </div>";
						}
						$i++;
					}
					$carousel .= "</div>
									<a class = 'carousel-control-pref' href='#album_carousel' role='button' data-slide ='prev'>
										<span class='carousel-control-prev-icon' aria-hidden='true'></span>
										<span class='sr-only'>Previous</span>
									</a>
									<a class='carousel-control-next' href='#album_carousel' role='button' data-slide='next'>
										<span class='carousel-control-next-icon' aria-hidden='true'></span>
										<span class='sr-only'>Next</span>
									</a>
								</div>";		
					$post = "<div class = 'card mt-4 ml-md-5 ml-sm-3 memory_show'> 
								$carousel		
								<div class = 'card-body'>
									<h5 class = 'card-title'> $album_name </h5>
									<p class = 'card-text'> $post </p>
								</div>
							</div>";
				}

				//Check if account is a friend, do not load if not friend
				$friend_obj= new User($this->con, $userLoggedIn);
				if ($friend_obj->isFriend($added_by)){

					if ($num_iterations++ < $start){
						continue;
					}

					//Once 10 posts has been loaded then break
					if ($count > $limit){
						break;
					} else {
						$count++;
					}

					if ($userLoggedIn == $added_by){
						$addDeleteButton = "<button class='del_button btn btn-danger btn-sm mr-1 mt-1' id ='post$id'>X</button>";
					} else {
						$addDeleteButton = "";
					}
					// Get the details of added by
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

					?>
					<script>
						function toggle<?php echo $id; ?>(){
							var clicked = $(event.target);
							if (!clicked.is("a")){
								var element = document.getElementById("toggleComment<?php echo $id; ?>");
								if (element.style.display == "block"){
									element.style.display = "none";
								} else {
									element.style.display ="block";
								}
							}
						}
					</script>
					<?

					// check the number of comments  
					$comments_query = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id = $id and removed='no'");
					$total_comments = mysqli_num_rows($comments_query);
					
					if ($total_comments==0){
						$comment = "0 comments";
						$myDisplay = "Display:none";
					} else if ($total_comments==1){
						$comment = 1 . " comment"; 
						$myDisplay = "Display:block";
						$iframe_height= "height:150px";
					} else {
						$comment = $total_comments . " comments";
						$myDisplay = "Display:block";
						$iframe_height= "height:300px";
					}

					?>
					<script>
						$(document).ready(function(){
							$('#post<?php echo $id; ?>').on('click', function(){
								bootbox.confirm({
									message:"Are you sure you want to delete this post?",
									buttons: {
										confirm:{
											label:'Yes',
											className: 'btn-success'
										},
										cancel:{
											label:'No',
											className: 'btn-danger'
										}
									}, callback: function(result){
										$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>",{result:result});
										if (result){
											location.reload();
										}
									}
								});
							});
						});
					</script>
				<?php

					$str .= "<div class='status_post' onClick='javascript:toggle$id()'>
								<div class='post_profile_pic'>
									<img class='px-1 py-2' src = '$profile_pics' width='50'>
								</div>
								<div class='d-flex posted_by form-inline justify-content-lg-between'>
									<div><a class = 'card-title' href='$added_by'> $first_name $last_name </a> $user_to</div>
									$addDeleteButton
								</div>
								<div class='time_message'>
									posted $time_message
								</div>
								<div id='post_body'>
									<p class ='class-text ml-3'>
										$post
									</p>
								</div>
								<div class='d-flex justify-content-between'>
									<div class = 'class-text ml-3 mt-2'>
										$comment
									</div>
									<div>
										<iframe class='iframe_like' src='likes.php?post_id=$id' style='border:0; height:33px;'> 
										</iframe>
									</div>
								</div>
								<div class='d-flex post_comment justify-content-between' id='toggleComment$id' style ='$myDisplay'>
									<iframe class='iframe_post' src='comments_frame.php?post_id=$id' id='comment_iframe' style='$iframe_height'>
									</iframe>
								</div>
							</div>";
				}//End if
			} //end while loop
				if($count > $limit){
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
								<input type='hidden' class='noMorePosts' value='false'>";
				} else { 
					$str .= "<input type='hidden' class='noMorePosts' value='true'>
							<p style='text-align: center;'> End of posts... </p>";
				}
		}//if(mysqli_num_rows($data_query) > 0)
		echo $str;
	} //end of class post

	public function loadFriendNewsfeed($data, $limit){
		$page = $data['page'];
		$UserProfile = $data['UserProfile'];
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1){
			$start=0;
		} else {
			$start= ($page-1) * $limit;
		}

		// Get data of posts
		$str="";
		$data_query = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted ='no' AND ((added_by='$UserProfile' AND user_to='none') OR user_to = '$UserProfile') ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0){
			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$post = $row['post'];
				$date_added = $row['date_added'];
				$user_to = $row['user_to'];
				$likes = $row['likes'];
				$iframe_height="";
				$added_by = $row['added_by'];

				//Check if account is closed - to be added			

				if ($user_to == 'none'){
					$user_to = "";
				} else {
					$user_to_obj = new User($this->con,$user_to);
					$user_to_name= $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href ='" . $user_to . "'>" . "You </a>";
				}

					if ($num_iterations++ < $start){
						continue;
					}

					//Once 10 posts has been loaded then break
					if ($count > $limit){
						break;
					} else {
						$count++;
					}

					if ($userLoggedIn == $added_by){
						$addDeleteButton = "<button class='del_button btn btn-danger btn-sm mr-1 mt-1' id ='post$id'>X</button>";
					} else {
						$addDeleteButton = "";
					}
					// Get the details of added by
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

					?>
					<script>
						function toggle<?php echo $id; ?>(){
							var clicked = $(event.target);
							if (!clicked.is("a")){
								var element = document.getElementById("toggleComment<?php echo $id; ?>");
								if (element.style.display == "block"){
									element.style.display = "none";
								} else {
									element.style.display ="block";
								}
							}
						}
					</script>
					<?

					// check the number of comments  
					$comments_query = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id = $id and removed='no'");
					$total_comments = mysqli_num_rows($comments_query);
					
					if ($total_comments==0){
						$comment = "0 comments";
						$myDisplay = "Display:none";
					} else if ($total_comments==1){
						$comment = 1 . " comment"; 
						$myDisplay = "Display:block";
						$iframe_height= "height:150px";
					} else {
						$comment = $total_comments . " comments";
						$myDisplay = "Display:block";
						$iframe_height= "height:300px";
					}

					?>
					<script>
						$(document).ready(function(){
							$('#post<?php echo $id; ?>').on('click', function(){
								bootbox.confirm({
									message:"Are you sure you want to delete this post?",
									buttons: {
										confirm:{
											label:'Yes',
											className: 'btn-success'
										},
										cancel:{
											label:'No',
											className: 'btn-danger'
										}
									}, callback: function(result){
										$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>",{result:result});
										if (result){
											location.reload();
										}
									}
								});
							});
						});
					</script>
				<?php

					$str .= "<div class='status_post ml-2' onClick='javascript:toggle$id()'>
								<div class='post_profile_pic'>
									<img class='px-1 py-2' src = '$profile_pics' width='50'>
								</div>
								<div class='d-flex posted_by mt-2 form-inline justify-content-lg-between'>
									<div><a class = 'card-title' href='$added_by'> $first_name $last_name </a> $user_to</div>
									$addDeleteButton
								</div>
								<div class='time_message mt-2'>
									posted $time_message
								</div>
								<div id='post_body'>
									<p class ='class-text ml-3'>
										$post
									</p>
								</div>
								<div class='d-flex justify-content-between'>
									<div class = 'class-text ml-3 mt-2'>
										$comment
									</div>
									<div>
										<iframe class='iframe_like' src='likes.php?post_id=$id' style='border:0; height:33px;'> 
										</iframe>
									</div>
								</div>
								<div class='d-flex post_comment justify-content-between' id='toggleComment$id' style ='$myDisplay'>
									<iframe class='iframe_post' src='comments_frame.php?post_id=$id' id='comment_iframe' style='$iframe_height'>
									</iframe>
								</div>
							</div>";
			} //end while loop
				if($count > $limit){
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
								<input type='hidden' class='noMorePosts' value='false'>";
				} else { 
					$str .= "<input type='hidden' class='noMorePosts' value='true'>
							<p style='text-align: center;'> End of posts... </p>";
				}
		}//if(mysqli_num_rows($data_query) > 0)
		echo $str;
	} //end of class post

	public function loadPost($post_id){
		$userLoggedIn = $this->user_obj->getUsername();

		// Get data of posts
		$str="";
		$data_query = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted ='no' and id ='$post_id'");

		if(mysqli_num_rows($data_query) > 0){
		
			while($row = mysqli_fetch_array($data_query)){
				$id = $row['id'];
				$post = $row['post'];
				$date_added = $row['date_added'];
				$user_to = $row['user_to'];
				$likes = $row['likes'];
				$iframe_height="";

				if ($user_to == 'none'){
					$user_to = "";
				} else {
					$user_to_obj = new User($this->con,$user_to);
					$user_to_name= $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href ='" . $user_to . "'>" . $user_to_name ."</a>";
				}
				$added_by = $row['added_by'];
			
				//Check if account is closed - to be added			

				//Check if account is a friend, do not load if not friend
				$friend_obj= new User($this->con, $userLoggedIn);
				if ($friend_obj->isFriend($added_by)){

					if ($userLoggedIn == $added_by){
						$addDeleteButton = "<button class='del_button btn btn-danger btn-sm mr-1 mt-1' id ='post$id'>X</button>";
					} else {
						$addDeleteButton = "";
					}
					// Get the details of added by
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

					?>
					<script>
						function toggle<?php echo $id; ?>(){
							var clicked = $(event.target);
							if (!clicked.is("a")){
								var element = document.getElementById("toggleComment<?php echo $id; ?>");
								if (element.style.display == "block"){
									element.style.display = "none";
								} else {
									element.style.display ="block";
								}
							}
						}
					</script>
					<?

					// check the number of comments  
					$comments_query = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id = $id and removed='no'");
					$total_comments = mysqli_num_rows($comments_query);
					
					if ($total_comments==0){
						$comment = "0 comments";
						$myDisplay = "Display:none";
					} else if ($total_comments==1){
						$comment = 1 . " comment"; 
						$myDisplay = "Display:block";
						$iframe_height= "height:150px";
					} else {
						$comment = $total_comments . " comments";
						$myDisplay = "Display:block";
						$iframe_height= "height:300px";
					}

					?>
					<script>
						$(document).ready(function(){
							$('#post<?php echo $id; ?>').on('click', function(){
								bootbox.confirm({
									message:"Are you sure you want to delete this post?",
									buttons: {
										confirm:{
											label:'Yes',
											className: 'btn-success'
										},
										cancel:{
											label:'No',
											className: 'btn-danger'
										}
									}, callback: function(result){
										$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>",{result:result});
										if (result){
											location.reload();
										}
									}
								});
							});
						});
					</script>
				<?php

					$str .= "<div class='status_single_post ml-2' onClick='javascript:toggle$id()'>
								<div class='post_profile_pic'>
									<img class='px-1 py-2' src = '$profile_pics' width='50'>
								</div>
								<div class='d-flex posted_by mt-2 form-inline justify-content-lg-between'>
									<div><a class = 'card-title' href='$added_by'> $first_name $last_name </a> $user_to</div>
									$addDeleteButton
								</div>
								<div class='time_message mt-2'>
									posted $time_message
								</div>
								<div id='post_body'>
									<p class ='class-text ml-3'>
										$post
									</p>
								</div>
								<div class='d-flex justify-content-between'>
									<div class = 'class-text ml-3 mt-2'>
										$comment
									</div>
									<div>
										<iframe class='iframe_like' src='likes.php?post_id=$id' style='border:0; height:33px;'> 
										</iframe>
									</div>
								</div>
								<div class='d-flex post_comment justify-content-between' id='toggleComment$id' style ='$myDisplay'>
									<iframe class='iframe_post' src='comments_frame.php?post_id=$id' id='comment_iframe' style='$iframe_height'>
									</iframe>
								</div>
							</div>";
				}//End if
			} //end while loop
		}//if(mysqli_num_rows($data_query) > 0)
		echo $str;
	}

	public function UploadHeader($user,$image){
		$image_upload = mysqli_query($this->con, "UPDATE amigo SET header_img = '$image' WHERE username = '$user'");
	}
}
?>