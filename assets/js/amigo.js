var element = document.getElementById("comment_section");

element.addEventListener("click", showComments);

function showComments(){
    if (element.style.display == "block"){
        element.style.display = "none";
    } else {
        element.style.display ="block";
    }
}
