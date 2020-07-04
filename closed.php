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
    <div class ="jumbotron text-center mt-5">	
        <h1> This is account has been closed by the user. </h1>
        <h3><a href="index.php"> Go back to your profile </a></h3>
	</div>
</div> 

<?php
	include ("includes/standards/footer.php");
?>