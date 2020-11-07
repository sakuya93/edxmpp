function openCollapse(id, type) {
    var openItem = $('#open_' + id);
    if (type == 1) { //open
        openItem.removeClass('fa-plus');
        openItem.addClass('fa-minus');
        openItem.attr('onclick', 'openCollapse(' + id + ",0)");
    } else if (type == 0) { //close
        //刪掉class fa-plus
        openItem.removeClass('fa-minus');
        openItem.addClass('fa-plus');
        openItem.attr('onclick', 'openCollapse(' + id + ",1)");
    }
}

/*-----------------------------------RWD-----------------------------------*/
var course_info_block = $(".course_info_block"); //學生基本資訊
var course_detail_block = $(".course_detail_block"); //學生詳細資訊
var tools = $(".tools i"); //展開評價的icon
var scoreBody = $(".score-body"); //評價區的評分
var commentBody = $(".comment-body"); //評價區的評語

var wdth = $(window).width();
/*------------------頁面載入初始化------------------*/
//wdth 小於
if (wdth <= 426) {
    course_info_block.removeClass("col-sm-12");
    course_info_block.addClass("col-sm-6");
    course_info_block.css("margin-left", "0px");

    course_detail_block.addClass("col-sm-6");
    course_detail_block.css("margin-left", "0px");

    tools.css("margin-left", "300px");
    tools.css("margin-top", "30px");

    scoreBody.removeClass("col-sm-6");
    scoreBody.addClass("col-sm-12");
    scoreBody.css("margin-left", "-50px");

    commentBody.css("margin-left", "-50px");

    $('.comment_area').css("width", "135%");
} else if (wdth < 768) {
    course_info_block.removeClass("col-sm-6");
    course_info_block.addClass("col-sm-12");
    course_info_block.css("margin-left", "60px");

    course_detail_block.removeClass("col-sm-6");
    course_detail_block.css("margin-left", "200px");

    tools.css("margin-left", "-470px");
    tools.css("margin-top", "20px");

    scoreBody.removeClass("col-sm-6");
    scoreBody.addClass("col-sm-12");
    scoreBody.css("margin-left", "-30px");

    commentBody.css("margin-left", "-30px");

    $('.comment_area').css("width", "235%");
} else if (wdth <= 992) {
    course_info_block.removeClass("col-sm-6");
    course_info_block.addClass("col-sm-12");
    course_info_block.css("margin-left", "100px");

    course_detail_block.removeClass("col-sm-6");
    course_detail_block.css("margin-left", "-123px");

    tools.css("margin-left", "-570px");
    tools.css("margin-top", "20px");

    scoreBody.removeClass("col-sm-6");
    scoreBody.addClass("col-sm-12");
    scoreBody.css("margin-left", "-30px");

    commentBody.css("margin-left", "-30px");

    $('.comment_area').css("width", "235%");
}

/*------------------RWD------------------*/
$(window).resize(function () {
    var wdth = $(window).width();

    //wdth 小於
    if (wdth <= 426) {
        course_info_block.removeClass("col-sm-12");
        course_info_block.addClass("col-sm-6");
        course_info_block.css("margin-left", "0px");

        course_detail_block.addClass("col-sm-6");
        course_detail_block.css("margin-left", "0px");

        tools.css("margin-left", "300px");
        tools.css("margin-top", "30px");

        scoreBody.removeClass("col-sm-6");
        scoreBody.addClass("col-sm-12");
        scoreBody.css("margin-left", "-50px");

        commentBody.css("margin-left", "-50px");

        $('.comment_area').css("width", "135%");
    } else if (wdth < 768) {
        course_info_block.removeClass("col-sm-6");
        course_info_block.addClass("col-sm-12");
        course_info_block.css("margin-left", "60px");

        course_detail_block.removeClass("col-sm-6");
        course_detail_block.css("margin-left", "200px");

        tools.css("margin-left", "-470px");
        tools.css("margin-top", "20px");

        scoreBody.removeClass("col-sm-6");
        scoreBody.addClass("col-sm-12");
        scoreBody.css("margin-left", "-30px");

        commentBody.css("margin-left", "-30px");

        $('.comment_area').css("width", "235%");
    } else if (wdth <= 992) {
        course_info_block.removeClass("col-sm-6");
        course_info_block.addClass("col-sm-12");
        course_info_block.css("margin-left", "100px");

        course_detail_block.removeClass("col-sm-6");
        course_detail_block.css("margin-left", "-123px");

        tools.css("margin-left", "-570px");
        tools.css("margin-top", "20px");

        scoreBody.removeClass("col-sm-6");
        scoreBody.addClass("col-sm-12");
        scoreBody.css("margin-left", "-30px");

        commentBody.css("margin-left", "-30px");

        $('.comment_area').css("width", "235%");
    }

    //wdth 大於
    if (wdth > 992) {
        course_info_block.removeClass("col-sm-12");
        course_info_block.addClass("col-sm-6");
        course_info_block.css("margin-left", "0px");

        course_detail_block.addClass("col-sm-6");
        course_detail_block.css("margin-left", "0px");

        tools.css("margin-left", "20px");
        tools.css("margin-top", "80px");

        scoreBody.removeClass("col-sm-12");
        scoreBody.addClass("col-sm-6");
        scoreBody.css("margin-left", "0px");

        commentBody.css("margin-left", "0px");

        $('.comment_area').css("width", "160%");
    }
});