$(document).ready(function(){
    
    var PostToWall = document.querySelector("#PostToWall");
    var covid_country = document.querySelector("#country_dropdown");

    if (covid_country) covid_country.addEventListener("change", LoadCovidData);
    if (PostToWall) PostToWall.addEventListener("click", PostIt);
    $(".iframe_post").css("height","+=200px");

    setInterval(function(){
        LoadCovidData();
    }, 500000);
});

$(document).click(function(e){
    if (e.target.class != "SearchList" && e.target.id != "search_input_text"){
        $('.SearchList').html("");
        $('.SearchListEmptyFooter').html("");
        $('.SearchListEmptyFooter').toggleClass("SearchListEmptyFooter");
        $('.SearchListEmptyFooter').toggleClass("SearchListEmpty");
    }
});



function LoadCovidData(){
    var country = $('#country_dropdown').val();
	$.ajax({
		url:"includes/handlers/ajax_covid.php",
		type: "POST",
		data: "query=" + country,
		cache: false,

		success: function(data){
            $('.covid_data').html(data);
		}
	}); 
}

function getDropDownData(user,type){
    var pageName;
    var x="";
    if (type == 'message') {
        pageName = "ajax_load_messages.php";
        $("span").remove("#unread_message");
    } else if (type == 'notification'){
        pageName = "ajax_load_notifications.php";
        $("span").remove("#unread_notification");
    } else if (type == 'amigo') {
        pageName = "ajax_load_amigo.php";
        $("span").remove("#unapproved_amigo");
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

function getSearchList(value, me_user){
    $.post("includes/handlers/ajax_search_list.php", {query:value, user_log:me_user}, function(data){

        if($(".SearchListEmpty")[0]){
            $(".SearchListEmpty").toggleClass("SearchListEmpty");
            $(".SearchListEmpty").toggleClass("SearchListEmptyFooter");
        }
       
        $('.SearchList').html(data);
     
        if (data==""){
            $('.SearchListEmptyFooter').html("");
            $('.SearchListEmptyFooter').toggleClass("SearchListEmptyFooter");
            $('.SearchListEmptyFooter').toggleClass("SearchListEmpty");
        }
        
    });
}