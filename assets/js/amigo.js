
    var element = document.querySelector(".status_post");

    element.addEventListener("click", showComments);

    function showComments(){
        var clicked = $(Event.target);
        console.log(clicked);
        if(!clicked.is("a")){
            if (element.style.display == "block"){
                element.style.display = "none";
            } else {
                element.style.display ="block";
            }
        }
    }