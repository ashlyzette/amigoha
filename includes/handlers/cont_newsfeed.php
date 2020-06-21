<script>
    var userLoggedIn = '<?php echo $user['username']; ?>';
    var UserProfile = '<?php echo $username; ?>';
		$(document).ready(function(){
			$('#loading').show();  //calls the boostrap spinner

			//Call ajax request for loading first 10 pages as set on the limit variable
			$.ajax({
				url:"includes/handlers/ajax_load_user_newsfeed.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn + "&UserProfile=" + UserProfile,
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
						url: "includes/handlers/ajax_load_user_newsfeed.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&UserProfile=" + UserProfile,
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