<?php
	include ("includes/standards/header.php");

	//Get the trending words
	$trends = new POST($con,$user_log);
	$trend = $trends->loadTrendingWords();

	if (isset($_POST['submit'])){
		$post_type = 'post';
		$post = new Post($con,$user_log);
		$post->submitPost($_POST['post_text'],'none','post');
		header("Location: index.php"); 
	}

	if(isset($_POST['save_album'])){
		$success = false;
		$post_type = 'image';
		$post = new Post($con,$user_log);
		$post_id = $post->submitPost($_POST['caption'],'none',$post_type);

        $total_images = count($_FILES['upload']['name']);

        for ($i=0; $i<$total_images; $i++){
			$image_name = $_FILES['upload']['name'][$i];
            $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
            $target_dir = "assets/images/memories/";
			$image_dir = $target_dir . uniqid() . basename($image_name);
			
			//Move files to folder memories
            if (move_uploaded_file($_FILES['upload']['tmp_name'][$i],$image_dir)){
				$success = true;
				$album = $_POST['album_name'];
				$image_post = mysqli_query($con, "INSERT INTO images VALUES (NULL,'$post_id','$album','$image_dir')");
            }
		}
		header("Location: index.php"); 
	}
?>

<!-- End of Header -->
<div class="container">
	<div class="row">
		<div class = "col-md-3 col-sm-12 px-md-0 pl-sm-3 pr-sm-4 mt-3">
			<div class="profileBox">
				<div class ="card profile_set">
					<div class="row no-gutters">
						<div class = "col-md-4 px-1 py-2">
							<a href="<?php echo $user['username']; ?>">
								<img class="card-img ml-2 mt-2 pr-3" src = "<?php echo $user['profile_pic'];?>">
							</a>
						</div>
						<div class ="col-md-8">
							<div class ="card-body">
								<h6 class="card-title">
									<a href="<?php echo $user['username'] ?>"az> 
										<?php echo $user['username']?>
									</a>
								</h6>
								<div class="card-text">Posts: <?php echo $user['num_posts']?></div>
									<?php 
										$total_friends = (substr_count($user['friends'],',')) - 1;
									?>
								<div class="card-text">Friends: <?php echo $total_friends?></div>
							</div>
						</div>
					</div>	
				</div>
				<div class="mt-4 text-center">
					<span>Live Covid -19 Tracker </span>
					<div class = "covid_table">
						<span id ="covid_header">as of 
							<?php
								$now_date = Date("Y M d");
								echo $now_date;
							?>
						</span>
						<div class = "covid_data">
							<?php 
								$covid_data = new Covid($con);
								$covid = $covid_data->getCovidData();
								echo $covid;
							?>
						</div>
						<form class = "mt-2">
							<span> Select Country <br/> arranged by highest cases </span>
							<select class = "form-control form-control-sm"  id= "country_dropdown">
								<?php 
									$country_list = $covid_data->getCovidCountries(); 
									echo $country_list;
								?>
							</select>
						</form>
					</div>
					<!-- Display trending words -->
					<div class = "mt-4 mb-2"> TRENDING NOW </div>
					<div id = "trends">
						<input type = "hidden" name = "trendsetter" id="trendsetter" value = '" <?php echo $trend; ?>"'>
					</div>
				</div>
			</div>
		</div>
		<div class = "col-md-9 col-sm-12 mt-3">
			<div class = "newsfeed">
				<div class="row OnePost">
					<div class="col-12">
						<form action = "index.php" method ="POST" enctype="multipart/form-data">
							<div class = "image_upload d-flex justify-content-end">
								<label class = "image_text" for ="share_image">
									<span id='share_image_text'> Share Memory </span> <i class="fas fa-image"></i>
								</label>
								<input type = "file" name="upload[]" id ="share_image" multiple/>
								<!-- Button trigger modal -->
								<button type="submit" class="btn btn-sm modal_button" name="show_modal" id ="img_button" data-toggle="modal" data-target="#image_modal">
								Launch demo modal
								</button>
								<!-- Load Image Modal Form  -->
								<div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Share Memories</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
												<div class="modal-body">
													<div id ="error_message"></div>
													<input class = "form-control mb-3" type= "text" id="album_name" name="album_name" placeholder="Enter album name...">
													<textarea class = "form-control" id="caption" name="caption" placeholder = "Say something...">
													</textarea>
													<div class = "shared_images">
														<img class = "image_handlers" id ="image_handler">
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" id = "close_modal" data-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary" name= "save_album" id= "save_album" >Share Memories</button>
												</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<textarea class="form-control" name="post_text" placeholder="Share something..."></textarea>
							</div>
							<button class ="btn btn-primary btn-sm" name="submit">
								Post
							</button>
						</form>
					</div>
				</div>
			</div>

			<!-- Load boostrap loader or spinner -->
			<div class ="posts_area"></div>
			<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<?php include ("includes/handlers/cont_pages.php") ?>
	</div>
<div>
<!-- Start of Footer -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/plugins/wordCloud.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src = "assets/js/trends.js"></script>
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->