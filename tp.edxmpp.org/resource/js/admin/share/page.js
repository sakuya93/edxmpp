$(document).ready(function () {
    activeSideBarButton();
});

function activeSideBarButton() { //檢查側邊攔的按紐現在是哪個頁面，並給予按紐 class:active
    var pathname = window.location.pathname;
    var page = pathname.substring(pathname.lastIndexOf("/") + 1);
    var match_text = "javascript:location.href='" + page + "'";

    var sideBarBtns = document.getElementsByClassName("side_bar-btn");
    for (var i = 0; i<sideBarBtns.length;i++){
        if($(sideBarBtns[i]).attr("onclick") == match_text){
            $(sideBarBtns[i]).addClass("active");
            break;
        }
    }
}