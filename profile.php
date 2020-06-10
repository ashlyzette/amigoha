<!DOCTYPE html>
<!-- Start of Header -->
<?php
	include ("includes/standards/header.php");
	// session_destroy();
?>
<!-- End of Header -->
<div class="container">
	<div class = "w-75 mt-5 rightBox">
			<div class = "newsfeed">
				<div class="row">
					<div class="col-12">
						<form action = "index.php" method ="POST">
							<div class="form-group">
								<textarea class="form-control" name="post_text" placeholder="Share something..."></textarea>
							</div>
							<input type="button" class ="btn btn-primary btn-sm" name="post_submit" value ="POST">
						</form>
					</div>
					<div class = "col-12 userPosts">

					</div>
				</div>
			</div>
	</div>
</div>
<!-- Start of Footer -->
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->