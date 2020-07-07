$(document).ready(function(){
    setInterval(function(){
        LoadChat();
    }, 10000);
});

function LoadChat(){
    var user_to = $('#user_to_chat').val();
    var user_from = $('#user_from_chat').val();
	$.ajax({
		url:"includes/handlers/ajax_chat.php",
		type: "POST",
        data: "user_to=" + user_to + "&user_from=" + user_from,
		cache: false,

		success: function(data){
            $('#load_chat').html(data);
		}
	}); 
}
