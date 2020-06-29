$(document).ready(function(){
    
    var PostToWall = document.querySelector("#PostToWall");
    if (PostToWall) PostToWall.addEventListener("click", PostIt);
    $(".iframe_post").css("height","+=200px");

});

function getDropDownData(user,type){
    if ($(".dropdown_window").css("height")=="0px"){
        var pageName;
        if (type == 'notification'){
            pageName = "ajax_load_notifications.php";
            $("span").remove("#unread_notification");
        } else if (type == 'message') {
            pageName = "ajax_load_messages.php";
            $("span").remove("#unread_message");
        }

        var ajax_req = $.ajax({
            url: "includes/handlers/" + pageName,
            type: "POST",
            data:  "page=1&userLoggedIn=" + user,
            cache: false,

            success: function(response){
                $(".dropdown_window").html(response);
                $(".dropdown_window").css({"padding" : "0px" ,"height" : "200px"});
                $("#dropdown_data_type").val(type);
            }
        });

    } else {
        $(".dropdown_window").html("");
        $(".dropdown_window").css({"padding" : "0px" ,"height" : "0px"});
    }  
}

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
}

function getFriendsList(value, me_user){
    $.post("includes/handlers/ajax_friends_search.php", {query:value, user_log:me_user}, function(data){
        $(".friendslist").html(data);
    });
}