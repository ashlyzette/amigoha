$(document).ready(function(){
    
    var PostToWall = document.querySelector("#PostToWall");
    var covid_country = document.querySelector("#country_dropdown");
    var share_image = document.querySelector("#share_image");
    var save_album = document.querySelector("save_album");
    
    if (PostToWall) PostToWall.addEventListener("click", PostIt);
    if (covid_country) covid_country.addEventListener("change", LoadCovidData);
    if (share_image) share_image.addEventListener("change", ShareImage);
    if (save_album) save_album.addEventListener("click" ,SaveAlbum);

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

function ShareImage(){
    var share = document.querySelector("#share_image");
    var err_message = document.querySelector("#error_message");
    fileCount = this.files.length;
    if(fileCount){        
        for (var i=0;i<fileCount;i++){
            var file = this.files[i];
            var file_name = file['name'];
            var image_type = file_name.split('.');
            var type = image_type[1];
            type= type.toLowerCase();
            if (type != 'jpg' && type != 'jpeg' && type != 'png' ){
                var err = "Invalid file, please use jpg, jpeg, or png files only.";
            } else {
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#error_message').html(err); 
                    if (fileCount === 1){
                        $('.shared_images').html($('#image_handler').attr('src', e.target.result));
                    } else{
                        $($.parseHTML('<img class=\'image_handlers\'>')).attr('src',e.target.result).appendTo('.shared_images');
                    }
                    console.log(e.target.result);
                }
                reader.readAsDataURL(this.files[i]);
            }
        }
    }           
    $('#image_modal').modal();
}

function SaveAlbum(){
    fileCount = this.files.length;
    if(fileCount){   
        //Save Post and get the post id
        var body = $('#caption').val();
        var post_type = 'image';
        var user = $('#user').val();
        $.ajax({
            url:"includes/handlers/ajax_create_album.php",
            type: "POST",
            data: "body=" + body + "&user=" + user + "&post_type=" + post_type + "&save=" + 'post',
            cache: false,

            success: function(data){
                var post_id = data;
            }
        });
    
        //Save the image files individually       
        for (var i=0;i<fileCount;i++){
            var file = this.files[i];
            var file_name = file['name'];    

            var reader = new FileReader();
            reader.onload = function(image){
                $('.shared_images').html($('#image_handler').attr('src', image.target.result));
            }
            reader.readAsDataURL(this.files[i]);
        } // end for satement
    } // End if (fileCount)
}

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