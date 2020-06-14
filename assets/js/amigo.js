$( document ).ready(function(){
    var element = document.getElementById("comment_section");

    element.addEventListener("click", showComments);

    function showComments(){
        var clicked = $(event.target);
        if(!clicked.is("a")){
            if (element.style.display == "block"){
                element.style.display = "none";
            } else {
                element.style.display ="block";
            }
        }
    }
});
