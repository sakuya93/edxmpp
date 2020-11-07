$(document).ready(function () {
    /*目標進度條 start*/
    var config = {
        value: fundraisingCourse[0]['expectedNumber'] - fundraisingCourse[0]['remainingNumber'],
        max: fundraisingCourse[0]['expectedNumber'],
        create: function (e, ui) {
            $(".ui-progressbar-value").html($("#progress_bar").progressbar("value") * 2 + "%");
            var css = {"line-height": "24px", "padding": "5px 0 0 5px"};
            $(".ui-progressbar-value").css(css);
        }
    };
    $("#progress_bar").progressbar(config);
    /*目標進度條 end*/

    /*計算募資總剩餘時間 start*/
    var date = new Date();
    var m = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1); //月
    var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); //日
    var today_time = date.getFullYear() + "/" + m + "/" + day + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds(); //今天日期
    var end_time = fundraisingCourse[0]['endTime'].replace(/-/g, "/"); //結束時間

    var date1 = new Date(today_time); //開始時間
    var date2 = new Date(end_time); //結束時間
    var date3 = date1.getTime() - date2.getTime(); //時間差幾毫秒

    //計算差幾天
    var day = date3 / (24 * 3600 * 1000) > 0 ? 0 : Math.floor(Math.abs(date3 / (24 * 3600 * 1000)));

    //計算差幾小時
    var leave1 = date3 % (24 * 3600 * 1000);
    var hour = leave1 / (3600 * 1000) > 0 ? 0 : Math.floor(Math.abs(leave1 / (3600 * 1000)));

    //計算差幾分鐘
    var leave2 = leave1 % (3600 * 1000);
    var minute = leave2 / (60 * 1000) > 0 ? 0 : Math.floor(Math.abs(leave2 / (60 * 1000)));

    //計算差幾秒
    var leave3 = leave2 % (60 * 1000);
    var second = leave3 / 1000 > 0 ? 0 : Math.round(Math.abs(leave3 / 1000));

    $('#day').text(day + "天");
    $('#hour').text(hour + "小時");
    $('#minute').text(minute + "分鐘");
    $('#second').text(second + "秒");

    /*計算募資總剩餘時間 end*/

    /*募資時間到數監控事件*/
    var interval = setInterval(function () {
        var day = parseInt($('#day').text());
        var hour = parseInt($('#hour').text());
        var minute = parseInt($('#minute').text());
        var second = parseInt($('#second').text());

        if (second > 0) { //秒數大於0的時候 每一秒 秒都 -1
            $('#second').text(second - 1 + "秒");
        }
        else { //秒數等於 0
            if (minute > 0) {
                $('#second').text(59 + "秒");
                $('#minute').text(minute - 1 + "分鐘");
            }
            else {
                if (hour > 0) {
                    $('#second').text(59 + "秒");
                    $('#hour').text(hour - 1 + "小時");
                    $('#minute').text(59 + "分鐘");
                }
                else {
                    if (day > 0) {
                        $('#second').text(59 + "秒");
                        $('#day').text(day - 1 + "天");
                        $('#hour').text(23 + "小時");
                        $('#minute').text(59 + "分鐘");
                    }
                    else {
                        // $('#hint_text').text('募資結束囉!');
                        // $('#hint_window').modal();
                        console.log("募資結束囉!");
                        clearInterval(interval);
                    }
                }
            }
        }
    }, 1000);

    //初始載入留言區訊息方法
    load_message_area(0, "initial"); //第0筆資料,初始型態

    //如果未登入的話把留言區的輸入框都刪除
    if (user_photo == "") {
        $('.user_area').remove();
    }
});

function load_FilmCourse(film_data) {
    var convey_data = {
        film_url: film_data
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "../fundraising_course_fun/get_mp4_video_url",
        data: convey_data,
        success: function (Jdata_url) {
            Jdata_url = decodeURIComponent(Jdata_url); //因為後端傳回的影片網址是編碼後的所以要解碼回來
            var video_player = document.getElementById("video_player");
            var video_player_src = document.getElementById("video_player_src");

            video_player_src.src = Jdata_url;
            video_player.load();
        },
        error: function (e) {
            $('#hint_text').text('影片連結發生不明錯誤，所以加載失敗!');
            $('#hint_window').modal();
        }
    });
}

/*有興趣按鈕*/
function interested() {
    var convey_data = {
        id: $('#fc_id').text()
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "../fundraising_course_fun/fundraisingCourseInterested",
        data: convey_data,
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            if (res['status']) {
                /*底下做一些參加完成後的呈現變動*/
                //更新目前人數
                $('.fundraising_goals .currently').text("目前" + ((fundraisingCourse[0]['expectedNumber'] - fundraisingCourse[0]['remainingNumber']) + 1) + "人");
                $('.interested_btn').addClass('not_interested_btn'); //按鈕呈現無法點擊的鼠標
                $('.interested_btn').removeClass('btn-success'); //刪除原先綠色樣式的按鈕
                $('.interested_btn').addClass('btn-danger'); //添加紅色樣式的按鈕
                $('.interested_btn').text("已參加"); //更改文字
                $('.interested_btn').prop('onclick', null).off('click'); //刪除click事件
            }

            $("#hint_window").on("hidden.bs.modal", function () {
                window.location.reload();
            });
        }
    });
}

/*留言討論區 start*/
var is_data_max = false;
var message_index = 0; //初始資料筆數
var timer;
$(window).scroll(function () { //偵測頁面卷軸是否在最底下(是的話取得舊留言)
    window.clearTimeout(timer);
    timer = setTimeout(function () {
        var area = document.body;
        var sh = $("body").height();
        var al = $(window).scrollTop() + $(window).outerHeight();

        if (al >= sh) {
            if (!is_data_max) {
                message_index++;

                var scrollHeight = $('.message_discuss_area').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                $('.more_loading_icon').css('display', 'block');

                setTimeout(function () {
                    load_message_area(message_index, "loading"); //第n筆資料,載入型態
                    $(window).scrollTop(scrollHeight, 200);
                }, 700);
            }
        }
    }, 700);
})

/*初始載入留言區*/
function load_message_area(m_index, type) {
    var convey_data = {
        id: fc_id,
        index: m_index
    };

    //取得留言
    $.ajax({
        type: "POST",
        url: "../fundraising_course_fun/getMessage",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            console.log(res);
            if (res.length == 0) is_data_max = true; //已達最舊資料

            var message_html = "";

            for (var i = 0, count = ($('.discuss_area').length + 1); i < res.length; i++, count++) {
                var photo = res[i]['admin_message'] == 0 ? "student/photo/" + res[i]['photo'] : "share/admin.jpg";
                var name = res[i]['admin_message'] == 0 ? res[i]['name'] : "管理員";
                var identity = res[i]['admin_message'] == 0 ? res[i]['identity'] : "";

                //看有沒有登入 沒有的入的話就不形成回覆輸入區
                var reply_input_area = "";
                if (user_photo != "") {
                    reply_input_area = "<p class=\"score_area\" id=\"score_area_" + count + "\">" +
                        "<span onclick=\"add_reply_area(" + count + ")\">回覆</span>" +
                        "</p>";
                }

                //判斷dashboard導向位置
                var url_dashboard = "../dashboard/" + res[i]["memberID"] + "/1"
                if (res[i]['identity'] == "老師") {
                    url_dashboard = url_dashboard.replace("dashboard", "teacher_page");
                } else if (res[i]['admin_message'] == "1") {
                    url_dashboard = "#";
                }

                message_html += "<div class=\"discuss_area row\">" +
                    "<span class='messageID' id='" + res[i]['messageID'] + "' style='display: none;'></span>" +
                    "                <div class=\"avatar_area\">" +
                    "                    <a href='" + url_dashboard + "'><img class=\"avatar\"" +
                    "                         src=\"../resource/image/" + photo + "\"" +
                    "                         alt=\"\">" +
                    "                    </a>" +
                    "                </div>" +
                    "                <div class=\"message_information_area col-sm-10\">" +
                    "                    <p id=\"commenter_" + count + "\">" + identity + " " + name + " " + res[i]['date'] + "</p>" +
                    "                    <p class=\"message_text\" id=\"message_area_" + count + "\">" + res[i]['message'] + "</p>" +
                    reply_input_area +
                    "                    <!--回覆區-->" +
                    "                    <div class=\"user_area reply_input_area\" id=\"user_message_discuss_" + count + "\">" +
                    "                        <img class=\"avatar\"" +
                    "                             src=\"../" + user_photo + "\"" +
                    "                             alt=\"\">" +
                    "                        <textarea rows=\"1\" class=\"message_input_area\" id=\"message_input_area_" + count + "\"  placeholder=\"輸入公開回覆...\"></textarea>" +
                    "                        <button type=\"button\" class=\"btn btn-outline-primary message_btn\" id=\"message_btn_" + count + "\">回覆</button>" +
                    "                        <button type=\"button\" class=\"btn btn-outline-primary cancel_message_btn\"" +
                    "                                onclick=\"Cancel_reply(" + count + ")\">取消" +
                    "                        </button>" +
                    "                    </div>" +
                    "                    <p class=\"reply_area\">" +
                    "                    <p class=\"more_reply\" id=\"more_reply_" + count + "\" onclick=\"more_reply('view'," + count + "," + 0 + ")\">查看更多回覆</p>" +
                    "                    <div class=\"more_message_discuss_area\" id=\"more_message_discuss_area_" + count + "\">" +
                    // 中間部分為更多回覆區 ;點了更多回覆才會去載入
                    "                    </div>" +
                    "                    </p>" +
                    "                </div>" +
                    "            </div>";
            }

            if (type == "initial") { //初始載入
                $(message_html).prependTo('#old_message_area');
                load_message_input_area(); //初始載入輸入框的觸發事件
            }
            else if (type == "loading") { //舊資料載入
                $('#old_message_area').append(message_html);
                $('.more_loading_icon').css('display', 'none');
            }
        }
    });
}

//留言輸入區
function load_message_input_area() {
    $(".message_input_area").each(function (index) {
        $(".message_input_area")[index].addEventListener('keydown', function (e) {
            if (event.keyCode == 13 && event.shiftKey) { //當按下enter換行時
                $(this).height($(this).height() + 29.6); //29.6 為一行高度
            } else if (e.keyCode == 8) { //當按下到回鍵時
                //取下當下刪除的的那個字元來判斷是否為換行字符
                var current_character = $(this).focus()[0].value.charAt($(this).focus()[0].selectionStart - 1);
                if (current_character == "\n" && $(this).height() > 29.6) { //游標在第一個的時候刪除;29.6是一行的高度(最小不能刪到一行以下)
                    $(this).height($(this).height() - 29.6);
                }
            }
            else if (event.keyCode == 13) {
                e.preventDefault(); //停止案下enter換行的動作;改成 shift + enter 換行
                var message_input = $(this).val();
                var message_index = this.id.substr(-1);

                if (message_index == 0) //0是公開留言的ID
                    add_public_message(message_input, $(".message_input_area")[message_index]); //呼叫新增留言方法
                else { //否則就是回覆
                    add_public_reply(message_input, $(".message_input_area")[message_index]); //呼叫新增回覆方法
                }
            }
        });

        $(this).focusin(function () { //取得焦點時
            var message_index = this.id.substr(-1);

            $('#user_message_discuss_' + message_index + ' .message_btn').css("visibility", "visible");
            $('#user_message_discuss_' + message_index + ' .cancel_message_btn').css("visibility", "visible");
            $('#user_message_discuss_' + message_index + ' textarea').animate({
                'borderWidth': '2px'
            }, 100);
        });
        $(this).focusout(function () { //失去焦點時
            var message_index = this.id.substr(-1);
            $('#user_message_discuss_' + message_index + ' textarea').animate({
                'borderWidth': '1px'
            }, 100);
        });

        $('.message_btn').unbind('click').bind('click', function () {
            var message_index = this.id.substr(-1);
            var message_input = $(".message_input_area")[message_index]['value'];

            if (this['innerText'] == "留言") {
                add_public_message(message_input, $(".message_input_area")[message_index]); //呼叫新增留言方法
            }
            else { //否則就是回覆
                add_public_reply(message_input, $(".message_input_area")[message_index]); //呼叫新增回覆方法
            }
        });
    });
}

//新增公開留言區
function add_public_message(value, t) {
    if (value != "") {
        value = value.replace(/\n|\r\n/g, "<br>");

        var message_html = "";

        var index = $('.discuss_area').length + 1;
        var date = new Date();
        var m = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1); //月
        var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); //日
        var hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours(); //時
        var minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(); //分
        var seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds(); //秒
        var now_time = date.getFullYear() + "/" + m + "/" + day + " " + hours + ":" + minutes + ":" + seconds; //今天日期

        var identity_temp = identity == "管理員" ? "" : identity;

        message_html += "<div class=\"discuss_area row\">" +
            "                <div class=\"avatar_area\">" +
            "                    <img class=\"avatar\"" +
            "                         src=\"../" + user_photo + "\"" +
            "                         alt=\"\">" +
            "                </div>" +
            "                <div class=\"message_information_area col-sm-10\">" +
            "                    <p id=\"commenter_" + index + "\">" + identity_temp + " " + user_name + " " + now_time + "</p>" +
            "                    <p class=\"message_text\" id=\"message_area_" + index + "\">" + value + "</p>" +
            "                    <p class=\"score_area\" id=\"score_area_" + index + "\">" +
            "                        <span onclick=\"\" style='color: grey'>回覆</span>" +
            "                    </p>" +
            "                </div>" +
            "            </div>";

        $(message_html).prependTo('#new_message_area');

        $(t).val(""); //傳送後清空輸入框
        $(t).height(29.6); //傳送後將行數恢復成單行
        /*以上是呈現部分*/

        var identity_status = 0; //0為學生留言 1為老師留言
        if (identity == "老師") identity_status = 1;

        /*以下是後端處理部分*/
        var convey_data = {
            id: fc_id,
            status: identity_status, //先預設都是學生留言
            replay: '',
            message: value
        };

        $.ajax({
            type: "POST",
            url: "../fundraising_course_fun/addFundraisingMessage",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_Title').text("提示");

                if (!res['status']) {
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                }
            }
        });
    }
}

//新增公開回覆區
var is_read_reply = []; //看是否讀取過回覆了
function add_public_reply(value, t) {
    if (value != "") {
        value = value.replace(/\n|\r\n/g, "<br>");

        var message_html = "";

        var reply_index = t.id.substr(-1);

        var identity_temp = identity == "管理員" ? "" : identity;
        var date = new Date();
        var m = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1); //月
        var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); //日
        var hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours(); //時
        var minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(); //分
        var seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds(); //秒
        var now_time = date.getFullYear() + "/" + m + "/" + day + " " + hours + ":" + minutes + ":" + seconds; //今天日期

        message_html += "<!--一個回覆者-->" +
            "<div class=\"avatar_area\">" +
            "<img class=\"avatar\"" +
            "src=\"../" + user_photo + "\"" +
            "alt=\"\">" +
            "</div>" +
            "<div class=\"message_information_area col-sm-10\">" +
            "    <p class=\"more_commenter\">" + identity_temp + " " + user_name + " " + now_time + "</p>" +
            "    <p class=\"message_text\">" + value + "</p>" +
            "</div>";

        //查看詳細回覆區塊顯示的時候或者是已經已讀過初始回覆了才去顯示
        $(message_html).prependTo('#more_message_discuss_area_' + reply_index);

        more_reply("auto_view", reply_index, 0);

        $(t).val(""); //傳送後清空輸入框
        $(t).height(29.6); //傳送後將行數恢復成單行
        /*以上是呈現部分*/

        var identity_status = 0; //0為學生留言 1為老師留言
        if (identity == "老師") identity_status = 1;

        /*以下是後端處理部分*/
        var convey_data = {
            id: fc_id,
            status: identity_status, //先預設都是學生留言
            replay: $('.messageID')[reply_index - 1]['id'],
            message: value
        };

        $.ajax({
            type: "POST",
            url: "../fundraising_course_fun/addFundraisingMessage",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_Title').text("提示");

                if (!res['status']) {
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                }
            }
        });
    }
}

//取消留言
function Cancel_message(index) {
    $('#user_message_discuss_' + index + ' .message_btn, #user_message_discuss_' + index + ' .cancel_message_btn').css('visibility', 'hidden');
}

//新增回覆區塊
function add_reply_area(index) {
    $('#user_message_discuss_' + index).css('display', 'block');
}

//取消回覆區塊
function Cancel_reply(index) {
    $('#user_message_discuss_' + index).css('display', 'none');
    $('#user_message_discuss_' + index + ' textarea').val(""); //清空回覆框文字
}


//更多回覆區
function more_reply(type, index, data_count) {
    if (typeof is_read_reply[index] == 'undefined') is_read_reply[index] = false;
    var count = index;
    if ($('#more_message_discuss_area_' + index).is(':hidden') | type == 'more' | type == 'auto_view') {
        $('#more_reply_' + index).text("隱藏所有回覆");
        $('#more_message_discuss_area_' + index).css('display', 'block');

        if (is_read_reply[index] == false | type == 'more') {
            var convey_data = {
                id: $('.messageID')[index - 1]['id'],
                id2: fc_id,
                index: data_count
            };

            //取得回覆
            $.ajax({
                type: "POST",
                url: "../fundraising_course_fun/getMessageReply",
                data: convey_data,
                dataType: 'json',
                success: function (res) {
                    var reply_message_html = "";

                    for (var i = 0; i < res.length; i++) {
                        var photo = res[i]['admin_message'] == 0 ? "student/photo/" + res[i]['photo'] : "share/admin.jpg";
                        var name = res[i]['admin_message'] == 0 ? res[i]['name'] : "管理員";
                        var identity = res[i]['admin_message'] == 0 ? res[i]['identity'] : "";

                        reply_message_html += "<!--一個回覆者-->" +
                            "                        <div class=\"avatar_area\">" +
                            "                            <img class=\"avatar\"" +
                            "                                 src=\"../resource/image/" + photo + "\"" +
                            "                                 alt=\"\">" +
                            "                        </div>" +
                            "                        <div class=\"message_information_area col-sm-10\">" +
                            "                            <p>" + identity + " " + name + " " + res[i]['date'] + "</p>" +
                            "                            <p class=\"message_text\">" + res[i]['message'] + "</p>" +
                            "                        </div>";
                    }

                    $('#view_more_reply_' + count).remove(); //刪除顯示更多回覆的按鈕

                    if (res.length == 5) { //如果資料筆數等於五的話代表還有可能有下次的資料
                        reply_message_html += " <p class=\"more_reply\" id=\"view_more_reply_" + count + "\" onclick=\"more_reply('more'," + count + "," + (data_count + 1) + ")\">顯示更多回覆</p>";
                    }

                    is_read_reply[count] = true;

                    $('#more_message_discuss_area_' + count).append(reply_message_html);
                }
            });
        }
    }
    else {
        $('#more_reply_' + index).text("查看更多回覆");
        $('#more_message_discuss_area_' + index).css('display', 'none');
    }
}

/*留言討論區 end*/