var bd_sidebar = $(".bd-sidebar");

window.bd_sidebar_isShow = 0
$(".toggle-nav").click(function () {
    if (window.bd_sidebar_isShow == 0) {
        bd_sidebar.css("left", "0");
        window.bd_sidebar_isShow = 1;
    } else if (window.bd_sidebar_isShow == 1) {
        bd_sidebar.css("left", "-14rem");
        window.bd_sidebar_isShow = 0;
    }
})

$(document).ready(function () {
    var pathname = window.location.pathname;
    // var userID = pathname.substring(pathname.indexOf("dashboard/") + 10, pathname.lastIndexOf("/"));
    // $("#userInfo").append("<li>身分ID: "+ userID +"</li>")


    let identify = $("#identify").text();
    if (identify == "teacher") {
        var str = "linear-gradient(to right, #ff5858, #fb5d81)"; // 老師
        $("#navbarSupportedContent").css("background", str);
    } else if (identify == "admin") {
        var str = "linear-gradient(to right, #ff5858, #093145)"; // 管理員
        $("#navbarSupportedContent").css("background", str);
    } else {
        $("#navbarSupportedContent").css("background", "#fff");
    }

});

//近一個月日曆形成
// $( "#calendar" ).fullCalendar({
//     // 參數設定[註1]
//     header: { // 頂部排版
//         left: "title", //放置標題
//         center: "",
//         right: ""
//     },
//     height:400,
//     dayClick: function (date, allDay, jsEvent, view) {
//         alert("您所點擊的是天");
//     },
//     eventClick: function (date, event, view) {
//         alert("您所點擊的是事件");
//     },
//     events: [ // 事件
//         { // 事件
//             title: "1",
//             start: "2020-01-13"
//         },
//         { // 事件
//             title: "1",
//             start: "2020-01-07"
//         },
//         { // 事件
//             title: "2",
//             start: "2020-01-15"
//         }
//     ]
// });

// 直播評語
function commentBuild(data) {
    if (data.length == 0) {
        var HTMLcomment = "<div class=\"box col-sm-12\">" +
            "                <div class=\"cb-header\">課程評論</div>" +
            "                <ul class=\"cb-info\">" +
            "                    <li>目前尚無直播課程評論</li>" +
            "                </ul>" +
            "            </div>";
        $('#comment').append(HTMLcomment);
    } else {
        for (i = 0; i < data.length; i++) {
            var HTMLcomment = "<div class=\"box col-sm-12\">" +
                "                <div class=\"cb-header\">直播名稱: " + data[i]['liveName'] + "</div>" +
                "                <ul class=\"cb-info\">" +
                "                    <li>課程類別: " + data[i]['type'] + "</li>" +
                "                    <li>老師姓名: " + data[i]['teacherName'] + "</li>" +
                "                    <li>老師評語: " + data[i]['comment'] + "</li>" +
                "                    <li>老師評分: " + data[i]['score'] + "</li>" +
                "                </ul>" +
                "            </div>";
            $('#comment').append(HTMLcomment);
        }
    }


}

// 擁有的影片課程
function filmBuild(data) {
    if (data.length == 0) {
        var HTMLfilm_course = "<ul class=\"cb-info\">" +
            "                    <li>目前尚無擁有影片課程</li>" +
            "                </ul>";
        $('#film_course').append(HTMLfilm_course);
    } else {
        for (i = 0; i < data.length; i++) {
            var HTMLfilm_course = "<ul class=\"cb-info\">" +
                "                    <li>課程名稱: " + data[i]['filmName'] + "</li>" +
                "                    <li>老師姓名: " + data[i]['teacherName'] + "</li>" +
                "                </ul>";
            $('#film_course').append(HTMLfilm_course);
        }
    }
}

function askForReportUser() {
    var report_user_content = '<p>請如實檢舉，如發現為不實檢舉可能會影響到您的會員資格。</p>';
    $("#reportUser_confirm").css("display", "block");
    $("#reportUser_confirm").attr("onclick", "reportUser()");
    $("#report_user_Title").text("提示");
    $("#report_user_content").html(report_user_content);
    $("#report_user_window").modal("show");
}

function reportUser() {
    if (isLogin) {
        var content = '                <form>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios1" value="1" >' +
            '                        <label class="form-check-label" for="exampleRadios1">' +
            '                            帶有傷害或人身攻擊的言論' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios2" value="2">' +
            '                        <label class="form-check-label" for="exampleRadios2">' +
            '                            學生擾亂上課秩序' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios3" value="3" >' +
            '                        <label class="form-check-label" for="exampleRadios3">' +
            '                            學生發布不實消息' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="mb-3">' +
            '                        <label for="validationTextarea">其他(需要詳細描述情形才需填寫)</label>' +
            '                        <textarea class="form-control" id="reportContent" placeholder="檢舉內容"></textarea>' +
            '                    </div>' +
            '                </form >';

        $("#reportUser_confirm").attr("onclick", "confirmReportUser()");
        $("#report_user_Title").text("檢舉 " + userName);
        $("#report_user_content").html(content);
    } else {
        var content = "<p>請登入後檢舉</p>";
        $("#report_user_content").html(content);
        $("#report_user_window").modal("show");
    }
}

function confirmReportUser() {
    var url = location.pathname;
    var match_text = "dashboard";
    var id = url.substring(url.indexOf(match_text) + match_text.length + 1, url.lastIndexOf("/"))
    var option_items = document.getElementsByClassName("report-chkbox"); //檢舉選項
    for (var i = 0; i < option_items.length; i++) {
        if (option_items[i].checked) {
            var option = $(option_items[i]).val();
        }
    }
    var content = $("#reportContent").val() == null ? "無填寫檢舉內容" : $("#reportContent").val(); //檢舉內容

    var convey_data = {
        reported: id,
        option: option,
        content: content
    };

    console.log(convey_data);

    $.ajax({
        type: "POST",
        url: "../../dashboard/MemberReport",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var report_user_content = '<p>' + res['msg'] + '</p>';
            $("#reportUser_confirm").css("display", "none");
            $("#reportUser_confirm").attr("onclick", "");
            $("#report_user_Title").text("提示");
            $("#report_user_content").html(report_user_content);
        },
        error: function (res) {
            console.log(res);
        }
    });
}