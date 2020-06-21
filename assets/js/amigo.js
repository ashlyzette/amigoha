$(document).ready(function(){
    
    var PostToWall = document.querySelector("#PostToWall");
    
    if (PostToWall) PostToWall.addEventListener("click", PostIt);

    function PostIt(){
        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_post_to_wall.php",
            data: $('form.post_to_wall').serialize(),
            success: function(msg){
                $("#PostWall").modal('hide');
                location.reload();
            },
            error: function(){
                alert('Unable to post message to wall');
            }
        });
    };

});