$(document).ready(function () {
    if (window.location.href.indexOf("/live/") != -1) { //當是直播頁面時
        $('#live_course').addClass("active_course");
        $('#film_course').removeClass("active_course");
        $('#course_type').text("直播課程");
    }
    else if (window.location.href.indexOf("/film/") != -1) {
        $('#film_course').addClass("active_course");
        $('#live_course').removeClass("active_course");
        $('#course_type').text("影片課程");
    }

    //第二頁之後的切換影片或直播或募資課程，都跳到第一頁。
    //取得變數後，當切換課程類型時，用replace的方式就會讓頁面跳到第一頁
    var pathname = window.location.pathname;
    page = pathname.substring(pathname.lastIndexOf("/") + 1);
});

function select_item(type) {
    if (type == 'live') { //當是點擊直播選項時
        if (!$("#live_course").hasClass("active_course")) {
            $('#film_course').removeClass("active_course");
            $('#live_course').addClass("active_course");
            $('#course_type').text("直播課程");

            if (window.location.href.indexOf("fundraisingCourse") != -1) {
                window.location.href = window.location.href.replace("/fundraisingCourse/" + page, "/live/1");
            }
            else{
                if (window.location.href.indexOf("#main") != -1) {
                    window.location.href = window.location.href.replace("/film/" + page, "/live/1");
                }
                else {
                    window.location.href = window.location.href.replace("/film/" + page, "/live/1") + "#main";
                }
            }
        }
    }
    else if (type == 'film') {
        if (!$("#film_course").hasClass("active_course")) {
            $('#live_course').removeClass("active_course");
            $('#film_course').addClass("active_course");
            $('#course_type').text("影片課程");

            if (window.location.href.indexOf("fundraisingCourse") != -1) {
                window.location.href = window.location.href.replace("/fundraisingCourse/" + page, "/film/1");
            }
            else{
                if (window.location.href.indexOf("#main") != -1) {
                    window.location.href = window.location.href.replace("/live/" + page, "/film/1");
                }
                else {
                    window.location.href = window.location.href.replace("/live/" + page, "/film/1") + "#main";
                }
            }
        }
    }
}

function view_fundraising_course() {
    if (window.location.href.indexOf("live") != -1) {
        window.location.href = window.location.href.replace("/live/" + page, "/fundraisingCourse/1");
    }
    else if (window.location.href.indexOf("film") != -1) {
        window.location.href = window.location.href.replace("/film/" + page, "/fundraisingCourse/1");
    }
}

function favorite(l_id, heart) { //加入收藏
    var convey_data = {
        id: l_id
    };

    $.ajax({
        type: "POST",
        url: "../../Course_introduction/favorite",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            if (!res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            }
            if(res['msg'] == '刪除課程收藏成功'){
                $("#" + heart).css("color","gray");
            }else if(res['msg'] == '課程收藏成功'){
                $("#" + heart).css("color","red");
            }
        }
    });
}

function cancel_favorite(l_id) {  //取消收藏
    var convey_data = {
        id: l_id
    };

    $.ajax({
        type: "POST",
        url: "../../Course_introduction/cancel_favorite",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            if (res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            }
        }
    });
}

function undone_memberData() {
    $('#hint_text').text('完成會員基本資料設定，並通過信箱驗證，才可申請成為老師');
    $('#hint_window').modal();
}

function undone_teacherData() {
    $('#hint_text').text('完成成為老師後，並等待審核，才可以管理課程');
    $('#hint_window').modal();
}

/*---------------------------------------------------響應RWD---------------------------------------------------*/
var introduction_block = $(".introduction_block");
var expectation_course = $(".expectation_course");
var course_info = $('.course_info');
var course_detail = $('.course_detail');

var wdth = $(window).width();

if (wdth < 386) {

} else if (wdth < 576) {
} else if (wdth < 768) {
    course_info.removeClass("col-sm-3");
    course_detail.removeClass("col-sm-9");
    course_info.addClass("col-sm-12");
    course_detail.addClass("col-sm-12");
} else if (wdth < 992) {

} else if (wdth > 992) {

}


$(window).resize(function () {
    var wdth = $(window).width();
    if (wdth < 386) {
    } else if (wdth < 576) {
    } else if (wdth < 768) {
        course_info.removeClass("col-sm-3");
        course_detail.removeClass("col-sm-9");
        course_info.addClass("col-sm-12");
        course_detail.addClass("col-sm-12");
    } else if (wdth < 992) {

    }

    if (wdth > 768) {
        course_info.removeClass("col-sm-12");
        course_detail.removeClass("col-sm-12");
        course_info.addClass("col-sm-3");
        course_detail.addClass("col-sm-9");
    }

});