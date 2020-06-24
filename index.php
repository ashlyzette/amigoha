<!DOCTYPE html>
<!-- Start of Header -->
<?php
	include ("includes/standards/header.php");
	
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
	<div class = "w-25 mt-3 leftBox">
		<?php include ("includes/standards/leftcolumn.php") ?>
		<div class="profleLinks">
			Profile Links
		</div>
	</div>
	<div class = "w-75 mt-3 rightBox">
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
		<!-- Load boostrap loader or spinner -->
		<div class ="posts_area"></div>
		<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
	<?php include ("includes/handlers/cont_pages.php") ?>
<div>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->