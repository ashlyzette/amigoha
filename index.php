<!DOCTYPE html>
<!-- Start of Header -->
<?php
	include ("includes/standards/header.php");
	include ("includes/classes/User.php");
	include ("includes/classes/Post.php");
	// session_destroy();

	// <?php
		// $user_obj =new User($con,$user_log);
		// echo $user_obj->getFirstAndLastName();

	if (isset($_POST['submit'])){
		$post = new Post($con,$user_log);
		$post->submitPost($_POST['post_text'],'none');
		// Refreshes the page
		header("Location: index.php"); 
	}

?>
<!-- End of Header -->
<div class="container">
	<div class = "w-25 mt-5 leftBox">
		<div class ="card mb-3 profileBox">
			<div class="row no-gutters">
				<div class = "col-md-4 px-1 py-2">
					<a href="<?php echo $user_log; ?>">
						<img class="card-img" src = "<?php echo $user['profile_pic'];?>">
					</a>
				</div>
				<div class ="col-md-8">
					<div class ="card-body">
						<h6 class="card-title">
							<a href="<?php echo $user_log; ?>"az> 
								<?php echo $user['username']?>
							</a>
						</h6>
						<div class="card-text">Posts: <?php echo $user['num_posts']?></div>
						<div class="card-text">Likes: <?php echo $user['num_likes']?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="profleLinks">
			Profile Links
		</div>
	</div>
	<div class = "w-75 mt-5 rightBox">
		<div class = "newsfeed">
			<div class="row OnePost">
				<div class="col-12">
					<form action = "index.php" method ="POST">
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
		<?php
			$post = new Post($con,$user_log);
			$post->loadPostsFriends();
		?>
	</div>
<!-- Start of Footer -->

<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->