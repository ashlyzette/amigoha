$(document).ready(function(){
    var user_to = $('#user_to_chat').val()
    if (user_to != 'new'){
        var lastMessage = document.querySelector("#last_message");
        lastMessage.scrollTop = lastMessage.scrollHeight;

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    }
})