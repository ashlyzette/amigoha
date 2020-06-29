<script>
	var userLoggedIn = '<?php echo $user['username']; ?>';
	$(document).ready(function(){
		$('.dropdown_window').scroll(function(){
			var message_height = $('.dropdown_window').innerHeight(); //div containing the post -> '.post_area'
			var scroll_top = $('.dropdown_window')[1].scrollTop; //current top of the displayed page
			var page = $('.dropdown_window').find('.ShowNextNotification').val();
            var noMoreNotification = $('.dropdown_window').find('.NoMoreNotification').val();
			
			console.log(scroll_top);
			console.log(message_height);
			console.log($('.dropdown_window')[1].scrollHeight);
			console.log(noMoreNotification);

			if ((scroll_top + message_height >=$('.dropdown_window')[1].scrollHeight) && noMoreNotification == 'false') {
                
                var pageName; //holds the name of the page to send ajax request
                var type = $('#dropdown_data_type').val();

                if (type == 'notification'){
                    pageName = "ajax_load_notifications.php";
                } else if (type == 'message') {
                    pageName = "ajax_load_messages.php"
                }
				
				var ajaxReq = $.ajax({
					url: "includes/handlers/" + pageName,
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache: false,
						
					success: function(response){
						$('.dropdown_window').find('.ShowNextNotification').remove(); //Removes current .nextpage
						$('.dropdown_window').find('.NoMoreNotification').remove(); //Removes current .nextpage
						$('.dropdown_window').append(response);
					}
				}); //end of ajax document
			} 	
		}); //end of window scroll function
	});//end of document
</script>