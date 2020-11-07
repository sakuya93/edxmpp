$(document).ready(function () {
    var type = location.search
    if(type == '?type=1'){
        $('#hint_text').text("此帳號已在其他地方登入，如有疑慮請聯絡客服或更改密碼");
        $('#hint_window').modal();
        $('#hint_window').on('hidden.bs.modal', function () {
            window.location = "home";
        });
    }else if(type == '?type=2'){
        $('#hint_text').text("此帳號已被封鎖，如有疑慮請聯絡課服");
        $('#hint_window').modal();
        $('#hint_window').on('hidden.bs.modal', function () {
            window.location = "home";
        });
    }
    $('#a-recommend').hover(function () {
        $('#a-recommend').css("cursor", "pointer");
    });

    $(".close").click(function () { //重置推薦教師畫面
        var page = '#recommend_text_page' + step;
        $(page).css("display", "none");
        step = 2;
        $('#recommend_text_page1').css("display", "unset");
    });
});

function openRecommendWindow() {
    $('#recommend_text_page1').css("display", "unset");
}

function startChooese() {
    $('#recommend_text_page1').css("display", "none");
    $('#recommend_text_page2').css("display", "unset");
}

var step = 2;

function nextPage() {
    var page = '#recommend_text_page' + step;
    $(page).css("display", "none");
    step++;
    page = '#recommend_text_page' + step;
    $(page).css("display", "unset");

}