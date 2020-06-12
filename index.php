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

		<!-- Load boostrap loader or spinner -->
		<div class ="posts_area"></div>
		<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
	<script>
	var userLoggedIn = '<?php echo $user_log; ?>';
		$(document).ready(function(){
			$('#loading').show();  //calls the boostrap spinner

			//Call ajax request for loading first 10 pages as set on the limit variable
			$.ajax({
				url:"includes/handlers/ajax_load_posts.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn,
				cache: false,

				success: function(data){
					$('#loading').hide();
					$('.posts_area').html(data);
				}
			}); // end of ajax document

			$(window).scroll(function(){
				var height = $('.posts_area').height(); //div containing the post -> '.post_area'
				var scroll_top = $(this).scrollTop(); //current top of the displayed page
				var page = $('.posts_area').find('.nextPage').val();
				var noMorePosts = $('.posts_area').find('.noMorePosts').val();

				if ((document.body.scrollHeight == window.pageYOffset + window.innerHeight) && noMorePosts == 'false') {
					$('#loading').show();  //calls the boostrap spinner
					

					var ajaxReq = $.ajax({
						url: "includes/handlers/ajax_load_posts.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
						cache: false,
						
						success: function(response){
							$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
							$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage
							
							$('#loading').hide();
							$('.posts_area').append(response);
						}
					}); //end of ajax document
				} 
			}); //end of window scroll function
		});//end of document
	</script>
<div>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->