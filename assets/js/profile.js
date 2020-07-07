$(document).ready(function(){
    //Display latet message
    var lastMessage = document.querySelector(".last_message");
	lastMessage.scrollTop = lastMessage.scrollHeight;
	
    if ( window.history.replaceState ) {
         window.history.replaceState( null, null, window.location.href );
    }
})
