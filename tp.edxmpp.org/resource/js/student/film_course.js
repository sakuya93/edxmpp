var video_parameter = {course_introduction: ""}; //初始載入所有課程影片參數

function load_FilmCourse(film_data) {
    $('#course_type').text(film_data[0]['type']); //更改麵包屑上面的課程類型
    $('#course_name').text(film_data[0]['name']); //更改麵包屑上面的課程名稱
    $('#film_name').text(film_data[0]['experienceFilmName']); //更改大標題(影片名稱);會對應點選的影片隨著改變
    $('#course_information_text').text(film_data.length - 1 + "段單元影片"); //課程單元數;減一是因為體驗影片不算在一個單元

    video_parameter['course_introduction'] = film_data[0]['experienceFilm'];
    load_course_video(film_data[0]['id2'], 0); //初始載入體驗影片

    //影片鎖起or開放
    var icon_content = "";
    if (teacher_status == 0 | teacher_status == 2) {
        $('.buy_course_btn').text("無法購買");
        $('.buy_course_btn').attr('disabled', true); //設成禁用按鈕
        $('.buy_course_btn').css('opacity', "1"); //背景不要透明
        $('.buy_course_btn').css('cursor', "not-allowed"); //不允許滑鼠指標樣式
    } else if (buy_status == 0) { //未購買時
        icon_content = "<i class=\"fa fa-lock\"></i>&nbsp;&nbsp;";
    } else if (buy_status == 1) { //已購買時
        icon_content = "<i class=\"fa fa-play-circle\"></i>&nbsp;&nbsp;";
        $('.buy_course_btn').text("已購買");
        $('.buy_course_btn').attr('disabled', true); //設成禁用按鈕
        $('.buy_course_btn').css('opacity', "1"); //背景不要透明
        $('.buy_course_btn').css('cursor', "not-allowed"); //不允許滑鼠指標樣式
        $('.buy_course_btn').css('backgroundColor', "#007bff"); //背景顏色
        $('.buy_course_btn').css('borderColor', "#007bff"); //邊框顏色
    } else if (buy_status == 2) {
        icon_content = "<i class=\"fa fa-play-circle\"></i>&nbsp;&nbsp;";
        $('.buy_course_btn').text("我的課程");
        $('.buy_course_btn').attr('disabled', true); //設成禁用按鈕
        $('.buy_course_btn').css('opacity', "1"); //背景不要透明
        $('.buy_course_btn').css('cursor', "not-allowed"); //不允許滑鼠指標樣式
        $('.buy_course_btn').css('backgroundColor', "#28a745"); //背景顏色
        $('.buy_course_btn').css('borderColor', "#28a745"); //邊框顏色
    } else if (buy_status == 3) {
        icon_content = "<i class=\"fa fa-lock\"></i>&nbsp;&nbsp;";
        $('.buy_course_btn').text("請先登入");
        $('.buy_course_btn').attr('onclick', "window.location = '../home'"); //跳轉回首頁
    }

    var course_unit_content = "";

    if (film_data[1] != undefined) { //當有系列影片時
        var chapter_temp = [];
        var chapter_arr_num = 1;
        var chapter_html = "";
        for (var i = 1; i < film_data.length; i++) {
            if (chapter_temp.indexOf(film_data[i]['unitName']) == -1) { //未創建此章節時
                chapter_temp[chapter_arr_num] = film_data[i]['unitName'];

                chapter_html = "<div id=\"chapter_" + chapter_arr_num + "\">" +
                    "<div class=\"chapter row\" onclick=\"chapter_display_change(" + chapter_arr_num + ")\">  <!--章節-->" +
                    "<span>" +
                    "<i class=\"fa fa-list\"></i>" +
                    "<span id=\"unit_title_1\">第 " + chapter_arr_num + " 章 " + film_data[i]['unitName'] + " 章節</span>" +
                    "</span>" +
                    "<i class=\"fa fa-chevron-up col-sm-3 arrow" + chapter_arr_num + "\" style=\"color: white\"></i>" +
                    "</div>" +
                    "</div>"
                $('#course_select_body').append(chapter_html);

                chapter_arr_num++;
            }

            var chapter_count = chapter_temp.indexOf(film_data[i]['unitName']); //獲取是第幾章節
            video_parameter['unit' + chapter_count + '_' + i] = film_data[i]['actualMovie'];

            course_unit_content =
                "<div class=\"unit_card chapter" + chapter_count + " unit" + chapter_count + "_" + i + " row\">" +
                "<span class=\"unit_name col-sm-12\">" +
                icon_content +
                film_data[i]['actualMovieName'] + "</span>" +
                "</div>";

            $('#chapter_' + chapter_count).append(course_unit_content);
        }

        $(".unit_card").unbind("click").bind("click", function (e) { //點選單元按鈕事件
            if (buy_status == 1 | buy_status == 2) { //已購買或自己課程可正常瀏覽
                $(".unit_card").removeClass("select");
                $(this).addClass("select");

                if (e.currentTarget.classList[2] != "row") { //若是點擊單元的話去抓取對應的名稱
                    $('#film_name').text(film_data[e.currentTarget.classList[2].substr(-1)]['actualMovieName']); //先抓取是單元幾再去抓對應的影片名稱
                    load_course_video(film_data[0]['id2'], e.currentTarget.classList[2].substr(e.currentTarget.classList[2].indexOf("_") + 1)); //傳送課程id、第幾個影片
                } else { //若不是點擊單元的話，也就是點擊體驗影片
                    $('#film_name').text(film_data[0]['experienceFilmName']);
                    load_course_video(film_data[0]['id2'], 0); //傳送課程id、第幾個影片
                }
            } else if (buy_status == 3) { //遊客登入
                $('#hint_text').text('請先登入後購買才可進行觀看!!');
                $('#hint_window').modal();
            } else {
                $('#hint_text').text('請購買課程!');
                $('#hint_window').modal();
            }
        });
    }

    //初始載入留言區訊息方法
    load_message_area(0, "initial"); //第0筆資料,初始型態

    //如果未登入的話把留言區的輸入框都刪除
    if (user_photo == "") {
        $('.user_area').remove();
    }
}

function f12_right_off() { //關閉f12和右鍵功能
    document.oncontextmenu = new Function("return false;")
    document.onkeydown = document.onkeyup = document.onkeypress = function () {
        if (window.event.keyCode == 123) {
            window.event.returnValue = false;
            return (false);
        }
    }
}

function chapter_display_change(chapter) { //章節收起功能
    $('.chapter' + chapter).slideToggle(800);

    if ($(".arrow" + chapter).hasClass("reversal")) {
        $(".arrow" + chapter).removeClass("reversal");
    } else {
        $(".arrow" + chapter).addClass("reversal");
    }
}

function load_course_video(id, index) { //讀取影片
    var convey_data = {
        id: id,
        index: eval(index)
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "../filmCourses/getVideoUrl",
        data: convey_data,
        success: function (Jdata_url) {
            Jdata_url = decodeURIComponent(Jdata_url); //因為後端傳回的影片網址是編碼後的所以要解碼回來
            var video_player = document.getElementById("video_player");
            var video_player_src = document.getElementById("video_player_src");

            video_player_src.src = Jdata_url;
            video_player.load();
            if (index != 0) {
                console.log("開始觀看");
                setInterval(function () { //每5分鐘觸發一次
                    var convey_data1 = {
                        id: id,
                        index: index
                    };

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "../filmCourses/addVideoWatchHistory",
                        data: convey_data1,
                        success: function (Jdata) {
                            console.log("時間到，輸入觀看紀錄");
                        }
                    });
                }, 60000 * 5000);
            }
        },
        error: function (e) {
            // $('#hint_text').text('影片連結發生不明錯誤，所以加載失敗!');
            // $('#hint_window').modal();
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
        id: film_id,
        index: m_index
    };

    //取得留言
    $.ajax({
        type: "POST",
        url: "../filmCourses/getMessage",
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
            id: film_id,
            status: identity_status,
            replay: '',
            message: value
        };

        $.ajax({
            type: "POST",
            url: "../filmCourses/addFilmMessage",
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
            id: film_id,
            status: identity_status,
            replay: $('.messageID')[reply_index - 1]['id'],
            message: value
        };

        $.ajax({
            type: "POST",
            url: "../filmCourses/addFilmMessage",
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
                id2: film_id,
                index: data_count
            };

            //取得回覆
            $.ajax({
                type: "POST",
                url: "../filmCourses/getMessageReply",
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
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios1" value="4" >' +
            '                        <label class="form-check-label" for="exampleRadios1">' +
            '                            老師未依照正確時間上課' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios2" value="5">' +
            '                        <label class="form-check-label" for="exampleRadios2">' +
            '                            老師無故提早下課' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios3" value="6" >' +
            '                        <label class="form-check-label" for="exampleRadios3">' +
            '                            老師不認真上課' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios4" value="7" >' +
            '                        <label class="form-check-label" for="exampleRadios4">' +
            '                            老師實際上課內容與課程介紹不符' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios5" value="8" >' +
            '                        <label class="form-check-label" for="exampleRadios5">' +
            '                            老師課程搜尋關鍵詞用與課程無關' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="form-check mb-2">' +
            '                        <input class="form-check-input report-chkbox" type="radio" name="exampleRadios" id="exampleRadios6" value="9">' +
            '                        <label class="form-check-label" for="exampleRadios6">' +
            '                            上課老師與課程老師不同人' +
            '                        </label>' +
            '                    </div>' +
            '                    <div class="mb-3">' +
            '                        <label for="validationTextarea">其他(需要詳細描述情形才需填寫)</label>' +
            '                        <textarea class="form-control" id="reportContent" placeholder="檢舉內容"></textarea>' +
            '                    </div>' +
            '                </form >';

        $("#reportUser_confirm").attr("onclick", "confirmReportUser()");
        $("#report_user_Title").text("檢舉 " + data['t_name']);
        $("#report_user_content").html(content);
    } else {
        var content = "<p>請登入後檢舉</p>";
        $("#report_user_content").html(content);
        $("#report_user_window").modal("show");
    }
}

function confirmReportUser() {

    var option_items = document.getElementsByClassName("report-chkbox"); //檢舉選項
    for (var i = 0; i < option_items.length; i++) {
        if (option_items[i].checked) {
            var option = $(option_items[i]).val();
        }
    }
    var content = $("#reportContent").val() == null ? "無填寫檢舉內容" : $("#reportContent").val(); //檢舉內容

    var convey_data = {
        course_type: 1,
        id: data[0]['id2'],
        reported: data[0]['id1'],
        option: option,
        content: content
    };


    $.ajax({
        type: "POST",
        url: "../filmCourses/ClassReport",
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