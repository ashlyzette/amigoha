<?php
    include ("includes/standards/header.php");

    //Get the post id
    if(isset($_GET['id'])){
        $post_id = $_GET['id'];
    } else {
        $post_id = 0;
    }
?>
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
                <div class ="posts_area col-12"></div>
                    <?php 
                        // include ("includes/handlers/cont_pages.php") 
                        $post_me = new POST($con,$user_log);
                        $post_me->LoadPost($post_id);
                    ?>
			</div>
		</div>
		<!-- Load boostrap loader or spinner -->
<div>
<?php
    include ("includes/standards/footer.php");
?>