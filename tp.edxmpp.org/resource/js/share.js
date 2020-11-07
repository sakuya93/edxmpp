/*切換管理員身分*/
function switchIdentity() {
    $.ajax({
        url: "https://ajcode.tk/teaching_platform_dev/student/switchIdentity",
        type: "POST",
        datatype: 'json',
        success: function (res) {

        },
    });
}

var chatroom_continually_updated_type = 0; //訊息視窗關閉後不再繼續讀取訊息 1為打開
var index = 1;
$(document).ready(function () {
    let identify = $("#identify").text();
    if (identify == "teacher") {
        var str = "linear-gradient(to right, #ff5858, #fb5d81)"; // 老師
        $("header .navbar.navbar-expand-lg.navbar-light").css("background", str);
        $(".nav-item.option .nav-link").css("color", "#fff");
        $(".option").css("background", "unset");
    } else if (identify == "admin") {
        var str = "linear-gradient(to right, #ff5858, #093145)"; // 管理員
        $("header .navbar.navbar-expand-lg.navbar-light").css("background", str);
        $(".nav-item.option .nav-link").css("color", "#fff");
        $(".option").css("background", "unset");
    } else {
        $("header .navbar.navbar-expand-lg.navbar-light").css("background", "#fff");
    }

    //目前時間
    var mydate = new Date();
    //初始更新
    var now_date = mydate.toLocaleTimeString().toString();
    $('#showbox').text(now_date.substring(0, now_date.lastIndexOf(":")));
    //接下來的每一次更新
    var millisecond = 60000 - (mydate.getSeconds() * 1000); //以毫秒計算相差下一分還有多久
    now_date_event(millisecond);

    $(".quick-search,body").click(function () {
        if ($(".search").hasClass("show")) {
            $(".dropdown-item").css("font-weight", "unset");
            $(".dropdown-item").css("color", "unset");
            $(".menu").css("display", "none");
            $("#main_menu").css("display", "none");
        }
    });

    /*點擊其他地方使元素消失 開始*/
    $("body").click(function (e) {
        if (e.target.id != "notify_icon") {
            $(".notify_container").css("display", "none");
        }
        if (e.target.id != "contact_icon") {
            $(".contact_container").css("display", "none");
        }
        if (e.target.id != "personal_icon") {
            $("#personal_div").css("display", "none");
        }
    });
    /*點擊其他地方使元素消失 結束*/

    /*通知 開始*/
    $("#notify_icon").click(function () {
        $(".notify_container").slideToggle();
    })
    /*通知結束*/

    /*訊息 開始*/
    $("#contact_icon").click(function () {
        $(".contact_container").slideToggle();
    })
    /*訊息 結束*/


    /*右側個人資訊欄 #personal_div 開始*/
    $("#personal_icon").click(function () {
        $("#personal_div").slideToggle();
    })

    $("#close_btn").click(function () {
        $("#personal_div").css("display", "none");
    })
    /*右側個人資訊欄 #personal_div 結束*/


    /*----------------- 顯示/隱藏 效果開始 -----------------*/

    $('.quick-search').click(function (e) {
        if ($('#main_menu').is(':hidden')) {
            $('#main_menu').show();
        } else {
            $('.search.dropdown.sd-rwd .menu').hide();
            $('#main_menu').hide();
        }
    })

    /*----------------- 顯示/隱藏 效果結束 -----------------*/

    /*聊天訊息icon顯示多少人傳送新訊息給你 開始*/
    if ($('.contact__inner').length > 99) { //新訊息超過99封的時候
        $('#new_message_hint').text("99+");
        $('#new_message_hint').css('visibility', 'visible');
    } else if ($('.contact__inner').length > 0) { //如有人傳送新訊息給你時
        $('#new_message_hint').text($('.contact__inner').length);
        $('#new_message_hint').css('visibility', 'visible');
    } else {
        $('#new_message_hint').css('visibility', 'hidden'); //沒有新訊息則隱藏提示
    }
    /*聊天訊息icon顯示多少人傳送新訊息給你 結束*/

    /*通知訊息icon顯示多少則新通知 開始*/
    if ($('.notice-course__inner').length > 99) { //新通知超過99則的時候
        $('#new_notify_hint').text("99+");
        $('#new_notify_hint').css('visibility', 'visible');
    } else if ($('.notice-course__inner').length > 0) { //如有新通知時
        $('#new_notify_hint').text($('.notice-course__inner').length);
        $('#new_notify_hint').css('visibility', 'visible');
    } else {
        $('#new_notify_hint').css('visibility', 'hidden'); //沒有新通知則隱藏提示
    }
    /*通知訊息icon顯示多少則新通知 結束*/

    notice_load_more(); //舊通知載入事件
    contact_load_more(); //舊訊息載入事件
});

/*載入更多通知 start*/
function notice_load_more() {
    var is_data_max = false;
    var message_index = 1; //初始資料筆數
    var timer;

    $(".notice_area").scroll(function () { //偵測頁面卷軸是否在最底下(是的話取得舊通知)
        window.clearTimeout(timer);
        timer = setTimeout(function () {
            var area = document.getElementById('notice_area');
            var sh = area.scrollHeight;
            var al = area.scrollTop + area.offsetHeight;

            if (al >= sh) {
                if (!is_data_max) {
                    var convey_data = {
                        index: message_index
                    };

                    //取得留言
                    $.ajax({
                        type: "POST",
                        url: "https://ajcode.tk/teaching_platform_dev/student/get_old_Notice",
                        data: convey_data,
                        dataType: 'json',
                        success: function (res) {
                            if (res.length == 0) is_data_max = true; //已達最舊資料
                            console.log(res);
                            var HTML = "";
                            for (var i = 0, j = $(".notice_area div").length; i < res.length; i++, j++) {
                                HTML = "";

                                if (res[i]['actual_date'] < $('#notify_actual_date' + ($(".notice_area div").length - 1)).text()) {
                                    HTML = "	<!--	鈴鐺Menu內部	--> " +
                                        "<div class=\"" + res[i]['haveRead'] + "d-flex\" id=\"notify_detail" + j + "\" onclick=\"notify_detail(" + j + "," + res[i]['id'] + ");\">" +
                                        "<img class=\"course__inner-img\" alt=\"course image\" src=\"" + res[i]['photo'] + "\">" +
                                        "<span class=\"course__inner-content row\">" +
                                        "<span class=\"col-sm-12\">" + res[i]['messageTitle'] + "</span>" +
                                        "<span class=\"col-sm-12\" id=\"notify_date" + j + "\">" + res[i]['date'] + "</span>" +
                                        "<span id=\"notify_actual_date" + j + "\" style=\"display: none\">" + res[i]['actual_date'] + "</span>" +
                                        "</span>" +
                                        "</div>";
                                } else {
                                    j--;
                                }

                                $(".notice_area").append(HTML);
                            }

                            message_index++;
                        }
                    });
                }
            }
        }, 300);
    })
}

/*載入更多通知 end*/

/*載入更多訊息 start*/
function contact_load_more() {
    var is_data_max = false;
    var message_index = 2; //初始資料筆數
    var timer;

    $(".contact_area").scroll(function () { //偵測頁面卷軸是否在最底下(是的話取得舊訊息)
        window.clearTimeout(timer);
        timer = setTimeout(function () {
            var area = document.getElementById('contact_area');
            var sh = area.scrollHeight;
            var al = area.scrollTop + area.offsetHeight;

            if (al >= sh) {
                if (!is_data_max) {
                    var convey_data = {
                        index: message_index
                    };

                    //取得留言
                    $.ajax({
                        type: "POST",
                        url: "https://ajcode.tk/teaching_platform_dev/student/get_old_Contact",
                        data: convey_data,
                        dataType: 'json',
                        success: function (res) {
                            if (res.length == 0) is_data_max = true; //已達最舊資料
                            console.log(res);
                            var HTML = "";
                            for (var i = 0, j = $(".contact_area div").length; i < res.length; i++, j++) {
                                HTML = "";

                                if (res[i]['actual_date'] < $('#contact_actual_date' + ($(".contact_area div").length - 1)).text()) {
                                    HTML = "	<!--	訊息Menu內部	--> " +
                                        "<div class=\"" + res[i]['haveRead'] + "d-flex\" id=\"contact_detail" + j + "\" onclick=\"contact_detail('" + j + "','" + res[i]['identity'] + "','" + res[i]['id'] + "','" + res[i]['name'] + "','" + res[i]['id2'] + "','" + res[i]['id3'] + "');\">" +
                                        "<img class=\"contact__inner-img\" alt=\"sender image\" src=\"https://ajcode.tk/teaching_platform_dev/resource/image/student/photo/" + res[i]['photo'] + "?value=" + Math.random() + "\">" +
                                        "<span class=\"contact__inner-content row\">" +
                                        "<span class=\"col-sm-12\">" + res[i]['identity'] + " " + res[i]['name'] + "</span>" +
                                        "<span class=\"col-sm-12\">" + res[i]['message'] + "</span>" +
                                        "<span class=\"col-sm-12\" style='color: #929292'>" + res[i]['date'] + "</span>" +
                                        "<span class=\"col-sm-12\" id=\"contact_actual_date" + j + "\" style='display: none'>" + res[i]['actual_date'] + "</span>" +
                                        "</span>" +
                                        "</div>";
                                } else {
                                    j--;
                                }

                                $(".contact_area").append(HTML);
                            }

                            message_index++;
                        }
                    });
                }
            }
        }, 300);
    })
}

/*載入更多訊息 end*/

/*搜尋課程 start*/
var search_text = "";

//按下放大鏡圖示搜尋
function search_course(url) {
    search_text = $('#search_text').val();
    if (search_text != "") {
        window.location = url + "Course_introduction/live/1?s=" + $('#search_text').val() + "&c=TWD";
    }
}

//按下enter搜尋
$("#search_text").keypress(function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    search_text = $('#search_text').val();
    if (code == 13) {
        if (search_text != "") {
            //因為不知道此頁面是否有帶參數，所以這邊將網址前半段固定部分取下來再去加上要搜尋的關鍵詞
            var url = window.location.href;
            //最後面indexof是要對照base_url 網域 / 後面的名稱
            var jump_url = url.substr(0, url.indexOf('/', url.indexOf('teaching_platform_dev')));

            window.location = jump_url + "/Course_introduction/live/1?s=" + $('#search_text').val() + "&c=TWD";
        }
    }
});

/*搜尋課程 end*/

String.prototype.format = function () {
    a = this;
    for (k in arguments) {
        a = a.replace("{" + k + "}", arguments[k])
    }
    return a
}

function undone_memberData() {
    $('#hint_text').text('完成會員基本資料設定，並通過信箱驗證，才可申請成為老師');
    $('#hint_window').modal();
}

function undone_teacherData() {
    $('#hint_text').text('完成成為老師後，並等待審核，才可以管理課程');
    $('#hint_window').modal();
}

function now_date_event(millisecond) {
    //目前時間監控事件
    setTimeout(function () {
        var mydate = new Date();
        var now_date = mydate.toLocaleTimeString().toString();
        $('#showbox').text(now_date.substring(0, now_date.lastIndexOf(":")));

        now_date_event(60000); //接下來的每一次都是60秒後才更新時間
    }, millisecond);
}


function notify_detail(div_num, id) {
    $(".content-body").html("");
    var convey_data = {
        id: id
    };

    $.ajax({
        type: 'POST',
        url: 'https://ajcode.tk/teaching_platform_dev/student/getNoticeDetail',
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);

            if (!$('#notify_detail' + div_num).hasClass("notice-course__inner_haveRead")) { //還沒已讀的時候
                $('#notify_detail' + div_num).removeClass("notice-course__inner");
                $('#notify_detail' + div_num).addClass("notice-course__inner_haveRead");

                if ($('.notice-course__inner').length > 99) { //新通知超過99則的時候
                    $('#new_notify_hint').text("99+");
                    $('#new_notify_hint').css('visibility', 'visible');
                } else if ($('.notice-course__inner').length > 0) { //如有新通知時
                    $('#new_notify_hint').text($('.notice-course__inner').length);
                    $('#new_notify_hint').css('visibility', 'visible');
                } else {
                    $('#new_notify_hint').css('visibility', 'hidden'); //沒有新通知則隱藏提示
                }
            }

            res['messageTitle'] = res['messageTitle'] == null ? "無標題" : res['messageTitle'];

            $("#notice_Title").text(res['messageTitle']);
            $(".content-body").append(res['message']);
            $(".content-time").text("通知時間: " + res['date']);
            $("#notice_window").modal();
            $('#notify_detail' + id).css("backgroundColor", "#ffffff");
        },
    });
}

function contact_detail(div_num, identity, id, chat_object, id2, id3) { //打開聊天訊息視窗
    chatroom_continually_updated_type = 1; //持續讀取訊息
    //清空訊息框及隱藏
    $('.message-box').css("display", "none");
    $('.message_area').html("");

    if (Notification.permission != "denied") { //當通知權限為預設的時候 詢問
        Notification.requestPermission(function () {
        });
    }

    var convey_data = {};

    convey_data[0] = {id: id, index: index};

    /*舊資料載入觸發方法 start*/
    $('.message_area').scroll(function () {
        var window_scrollTop = $('.message_area').scrollTop();

        if (window_scrollTop == 0) { //捲軸滑到最上面時
            index++;
            convey_data[0] = {id: id, index: index};
            $.ajax({
                type: "POST",
                url: "https://ajcode.tk/teaching_platform_dev/student/getContactDetail",
                data: convey_data,
                dataType: 'json',
                async: true,//設定是否非同步，預設為非同步
                success: function (res) {
                    console.log(res);
                    if (res[0].length != 0) { //如果有抓到值才做
                        var message_html = "<div class='old_" + index + "'>";
                        for (var i = res[0].length - 1; i >= 0; i--) {
                            if (res[0][i]['who_say'] == 1) { //自己的訊息
                                message_html += "<div class=\"menu-item menu-message row user\">" +
                                    "                <div class=\"message-block\">" +
                                    "                    <p class=\"message-content\">" + res[0][i]['message'] + "</p>" +
                                    "                    <p class=\"message-date\">" + res[0][i]['date'] + "</p>" +
                                    "                </div>" +
                                    "            </div>";
                            } else { //對方的訊息
                                message_html += "<div class=\"menu-item menu-message row content-person\">" +
                                    "                <div class=\"message-block\">" +
                                    "                    <p class=\"message-content\">" + res[0][i]['message'] + "</p>" +
                                    "                    <p class=\"message-date\">" + res[0][i]['date'] + "</p>" +
                                    "                </div>" +
                                    "            </div>";
                            }
                        }
                        message_html += "</div>";

                        $(message_html).insertBefore('.old_' + (index - 1));

                    }
                }
            });
        }
    });
    /*舊資料載入觸發方法 end*/

    /*聊天訊息載入 start*/
    $.ajax({
        type: "POST",
        url: "https://ajcode.tk/teaching_platform_dev/student/getContactDetail",
        data: convey_data,
        dataType: 'json',
        async: true,//設定是否非同步，預設為非同步
        success: function (res) {
            $('.message-box').css("display", "block");

            //代表已聊過天的
            if (res[0].length != 0) {
                if (identity == "學生")
                    $('#chat_object').text(identity + chat_object + " 身分ID: " + res[0][0]['memberID']);
                else
                    $('#chat_object').text(identity + chat_object + " 身分ID: " + res[0][0]['teacherID']);
            }
            else { //從未聊過天
                $('#chat_object').text(identity + chat_object);
            }

            if (!$('#contact_detail' + div_num).hasClass("contact__inner_haveRead")) { //還沒已讀的時候
                $('#contact_detail' + div_num).removeClass("contact__inner");
                $('#contact_detail' + div_num).addClass("contact__inner_haveRead");

                if ($('.contact__inner').length > 99) { //新訊息超過99封的時候
                    $('#new_message_hint').text("99+");
                    $('#new_message_hint').css('visibility', 'visible');
                } else if ($('.contact__inner').length > 0) { //如有人傳送新訊息給你時
                    $('#new_message_hint').text($('.contact__inner').length);
                    $('#new_message_hint').css('visibility', 'visible');
                } else {
                    $('#new_message_hint').css('visibility', 'hidden'); //沒有新訊息則隱藏提示
                }
            }

            var message_html = "<div class='old_" + index + "'>";
            for (var i = res[0].length - 1; i >= 0; i--) {
                if (res[0][i]['who_say'] == 1) { //自己的訊息
                    message_html += "<div class=\"menu-item menu-message row user\">" +
                        "                <div class=\"message-block\">" +
                        "                    <p class=\"message-content\">" + res[0][i]['message'] + "</p>" +
                        "                    <p class=\"message-date\">" + res[0][i]['date'] + "</p>" +
                        "                </div>" +
                        "            </div>";
                } else { //對方的訊息
                    message_html += "<div class=\"menu-item menu-message row content-person\">" +
                        "                <div class=\"message-block\">" +
                        "                    <p class=\"message-content\">" + res[0][i]['message'] + "</p>" +
                        "                    <p class=\"message-date\">" + res[0][i]['date'] + "</p>" +
                        "                </div>" +
                        "            </div>";
                }
            }
            message_html += "</div>";
            $('.message_area').append(message_html);

            var scrollHeight = $('.message_area').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
            $('.message_area').scrollTop(scrollHeight, 200);

            /*案下enter來聊天*/
            $("#input_message").keypress(function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                var input_message = $('#input_message').val();

                if (event.keyCode == 13 && event.shiftKey) { //案下 shift + enter 換行
                    $('#input_message').val($('#input_message').val());
                } else if (code == 13) {
                    e.preventDefault(); //停止案下enter換行的動作;改成 shift + enter 換行
                    if (input_message != "") {
                        input_message = input_message.replace(/\n|\r\n/g, "<br>");
                        var nowdate = new Date(); //取得現在時間物件
                        var now_time = nowdate.toLocaleString();
                        var message_html = "";

                        message_html += "<div class=\"menu-item menu-message row user\">" +
                            "                <div class=\"message-block\">" +
                            "                    <p class=\"message-content\">" + input_message + "</p>" +
                            "                    <p class=\"message-date\">" + now_time + "</p>" +
                            "                </div>" +
                            "            </div>";

                        $('.message_area').append(message_html);

                        $('#input_message').val(""); //傳送後清空輸入框

                        var scrollHeight = $('.message_area').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                        $('.message_area').scrollTop(scrollHeight, 200);

                        /*以上是呈現部分*/

                        /*以下是後端處理部分*/
                        var convey_data = {
                            id: id2,
                            message: input_message
                        };

                        if (identity == "管理員" | identity == "老師") {
                            $.ajax({
                                type: "POST",
                                url: "https://ajcode.tk/teaching_platform_dev/student/addContact_StoT",
                                data: convey_data,
                                dataType: 'json',
                                success: function (res) {
                                    if (!res['status']) {
                                        $('#hint_text').text(res['msg']);
                                        $('#hint_window').modal();
                                    }
                                }
                            });
                        } else if (identity == "學生") {
                            $.ajax({
                                type: "POST",
                                url: "https://ajcode.tk/teaching_platform_dev/student/addContact_TtoS",
                                data: convey_data,
                                dataType: 'json',
                                success: function (res) {
                                    if (!res['status']) {
                                        $('#hint_text').text(res['msg']);
                                        $('#hint_window').modal();
                                    }
                                }
                            });
                        }

                    }
                }

            });
        }
    });
    chatroom_continually_updated(id, id3);
    /*聊天訊息載入 end*/
}


/*訊息視窗 開啟/關閉 start*/
$(".message-box #message-box-close").click(function () {
    $('.message-box').css('display', 'none');
    chatroom_continually_updated_type = 0;
})
/*訊息視窗 開啟/關閉 end*/

/*聊天室窗持續更新*/
function chatroom_continually_updated(id, id3) {
    if (chatroom_continually_updated_type == 1) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "https://ajcode.tk/teaching_platform_dev/student/getNewContactDetail",
            async: true,
            // timeout: 60000,  //ajax請求超時時間60秒
            data: {id: id, id2: id3},
            dataType: 'json',
            success: function (result) {
                if (result['status']) {
                    id3 = result['contact']['0']['id'];
                    var message_html = "<div class='old_" + index + "'>" +
                        "<div class=\"menu-item menu-message row content-person\">" +
                        "                <div class=\"message-block\">" +
                        "                    <p class=\"message-content\">" + result['contact']['0']['message'] + "</p>" +
                        "                    <p class=\"message-date\">" + result['contact']['0']['date'] + "</p>" +
                        "                </div>" +
                        "            </div>" +
                        "</div>"
                    $('.message_area').append(message_html);
                    var scrollHeight = $('.message_area').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                    $('.message_area').scrollTop(scrollHeight, 200);

                    if (!$("#input_message").is(":focus")) { //聊天視窗不是焦點時
                        /*聊天訊息通知*/
                        if (Notification.permission == "granted") {
                            var notification = new Notification("新聊天通知", {
                                body: result['contact']['0']['message']
                            });
                        }
                    }

                    chatroom_continually_updated(id, id3);
                } else
                    chatroom_continually_updated(id, id3);
            },
            //Ajax請求超時，繼續查詢
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                chatroom_continually_updated(id, id2)
            }
        });
    }
}

function timeFn(d1) { //計算時間差
    var dateBegin = new Date(d1);
    var dateEnd = new Date(); //取得當前時間
    var dateDiff = dateEnd.getTime() - dateBegin.getTime();//時間差的毫秒數
    var dayDiff = Math.floor(dateDiff / (24 * 3600 * 1000));//計算出相差天數
    var leave1 = dateDiff % (24 * 3600 * 1000)    //計算天數後剩餘的毫米數
    var hours = Math.floor(leave1 / (3600 * 1000))//計算出小時數
    //計算相差分鐘數
    var leave2 = leave1 % (3600 * 1000)    //計算小時數後剩餘的毫秒數
    var minutes = Math.floor(leave2 / (60 * 1000))//計算相差分鐘數
    //計算相差秒數
    var leave3 = leave2 % (60 * 1000)      //計算分鐘數後剩餘的毫秒數
    var seconds = Math.round(leave3 / 1000)
    var timeDiff = {
        day: dayDiff,
        hours: hours,
        min: minutes
    };
    return timeDiff;
}


//////////////// 與管理員聯繫
function liveChatToAdmin(id1, id2, id3) {
    return {
        id1, // 第一個是聊天室窗ID
        id2, // 第二個是會員ID
        id3, // 第三個是最新訊息ID
        msg_load_index: 0, // 取得舊訊息的index
        buildAdminContactDetail: function () { // 取得聊天紀錄
            if (!$('#contact_detail0').hasClass("contact__inner_haveRead")) { //還沒已讀的時候
                $('#contact_detail0').removeClass("contact__inner");
                $('#contact_detail0').addClass("contact__inner_haveRead");

                if ($('.contact__inner').length > 99) { //新訊息超過99封的時候
                    $('#new_message_hint').text("99+");
                    $('#new_message_hint').css('visibility', 'visible');
                } else if ($('.contact__inner').length > 0) { //如有人傳送新訊息給你時
                    $('#new_message_hint').text($('.contact__inner').length);
                    $('#new_message_hint').css('visibility', 'visible');
                } else {
                    $('#new_message_hint').css('visibility', 'hidden'); //沒有新訊息則隱藏提示
                }
            }

            let convey_data = {
                index: 0
            };

            $.ajax({
                type: "POST",
                url: "https://ajcode.tk/teaching_platform_dev/student/getAdminContactDetail",
                datatype: "json",
                data: convey_data,
                success: function (res) {
                    res = JSON.parse(res);
                    res = res[0].reverse();
                    let content = ""; //聊天內容

                    for (let i = 0; i < res.length; i++) {
                        if (res[i]['who_say'] == "1") {
                            content = '' +
                                '                   <div class="inner__box inner__box-others d-flex">' +
                                '                        <div class="box__user-avatar"></div>' +
                                '                        <div class="box__body">' +
                                '                            <div class="box__user-header d-flex align-items-center mb-1">' +
                                '                                <div class="box__user-name mr-1">管理員</div>' +
                                '                                <p class="message-date">' + res[i]['date'] + '</p>' +
                                '                            </div>' +
                                '                            <div class="box__message">' +
                                '                                <p class="message-content">' + res[i]['message'] + '</p>' +
                                '                            </div>' +
                                '                        </div>' +
                                '                    </div>' +
                                '';
                        } else if (res[i]['who_say'] == "0") {
                            content = '' +
                                '                <div class="inner__box inner__box-self d-flex">' +
                                '                        <div class="box__body">' +
                                '                            <div class="box__user-header mb-1">' +
                                '                                <p class="message-date">' + res[i]['date'] + '</p>' +
                                '                            </div>' +
                                '                            <div class="box__message">' +
                                '                                <p class="message-content mb-2">' + res[i]['message'] + '</p>' +
                                '                            </div>' +
                                '                        </div>' +
                                '                    </div>' +
                                '';
                        }
                        $("#chatBodyInner").append(content);
                        if (typeof window.LCTA !== 'undefined') {
                            window.LCTA.setChatScroll();
                        }
                    }
                    window.LCTA.eventNewAdminContactDetail();
                    window.LCTA.setChatScroll();
                },
            });
        },
        addAdminContact: function (message) { // 傳送訊息給管理員
            let convey_data = {
                message: message
            };

            $.ajax({
                type: "POST",
                url: "https://ajcode.tk/teaching_platform_dev/student/addAdminContact",
                datatype: "json",
                data: convey_data,
                success: function (res) {
                    res = JSON.parse(res);

                    var nowdate = new Date(); //取得現在時間物件
                    var now_time = nowdate.toLocaleString();
                    //時間字串處理 格式(2019/02/02 16:04:05)
                    if (now_time.indexOf("上午") != -1) { //是上午的話
                        now_time = now_time.replace("上午", "");
                    } else if (now_time.indexOf("下午") != -1) { //是下午的話
                        now_time = now_time.replace("下午", "");

                        //處理現在時間為下午時 + 12 (24小時制)
                        now_time = now_time.split(" ")[0] + " " +
                            (eval(now_time.split(" ")[1].split(":")[0]) + 12) +
                            ":" +
                            now_time.split(" ")[1].split(":")[1] +
                            ":" +
                            now_time.split(" ")[1].split(":")[2];
                    }

                    content = '' +
                        '                <div class="inner__box inner__box-self d-flex">' +
                        '                        <div class="box__body">' +
                        '                            <div class="box__user-header mb-1">' +
                        '                                <p class="message-date">' + now_time + '</p>' +
                        '                            </div>' +
                        '                            <div class="box__message">' +
                        '                                <p class="message-content mb-2">' + message + '</p>' +
                        '                            </div>' +
                        '                        </div>' +
                        '                    </div>' +
                        '';
                    $("#chatBodyInner").append(content);
                    window.LCTA.setChatScroll();
                },
            });
        },
        setChatScroll: function () { // 設定卷軸定位
            let y = document.getElementById("chatBodyInner").scrollHeight;
            $("#chatBodyInner").scrollTop(y);
        },
        loadOlderMessage: function () { // 載入舊訊息
            let convey_data = {
                index: this.msg_load_index
            };

            $.ajax({
                type: "POST",
                url: "https://ajcode.tk/teaching_platform_dev/student/getAdminContactDetail",
                datatype: "json",
                data: convey_data,
                success: function (res) {
                    res = JSON.parse(res);
                    res = res[0];
                    if (res.length > 0) {
                        let content = ""; //聊天內容

                        for (let i = 0; i < res.length; i++) {
                            if (res[i]['who_say'] == "1") {
                                content = '' +
                                    '                   <div class="inner__box inner__box-others d-flex">' +
                                    '                        <div class="box__user-avatar"></div>' +
                                    '                        <div class="box__body">' +
                                    '                            <div class="box__user-header d-flex align-items-center mb-1">' +
                                    '                                <div class="box__user-name mr-1">管理員</div>' +
                                    '                                <p class="message-date">' + res[i]['date'] + '</p>' +
                                    '                            </div>' +
                                    '                            <div class="box__message">' +
                                    '                                <p class="message-content">' + res[i]['message'] + '</p>' +
                                    '                            </div>' +
                                    '                        </div>' +
                                    '                    </div>' +
                                    '';
                            } else if (res[i]['who_say'] == "0") {
                                content = '' +
                                    '                <div class="inner__box inner__box-self d-flex">' +
                                    '                        <div class="box__body">' +
                                    '                            <div class="box__user-header mb-1">' +
                                    '                                <p class="message-date">' + res[i]['date'] + '</p>' +
                                    '                            </div>' +
                                    '                            <div class="box__message">' +
                                    '                                <p class="message-content mb-2">' + res[i]['message'] + '</p>' +
                                    '                            </div>' +
                                    '                        </div>' +
                                    '                    </div>' +
                                    '';
                            }
                            $("#chatBodyInner").prepend(content);
                        }
                    } else { // 抓到空值的時候 msg_load_index--
                        window.LCTA.msg_load_index--;
                    }
                },
            });
        },
        eventNewAdminContactDetail: function () { // 詢問有沒有人傳訊息
            if ($("#liveChat").css("display") == "block") {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "https://ajcode.tk/teaching_platform_dev/student/getNewAdminContactDetail",
                    async: true,
                    // timeout: 60000,  //ajax請求超時時間60秒
                    data: {
                        id: window.LCTA.id3
                    },
                    dataType: 'json',
                    success: function (result) {
                        console.log(result);
                        if (result['status']) {
                            window.LCTA.id3 = result['contact'][0]['id'];
                            content = '' +
                                '                   <div class="inner__box inner__box-others d-flex">' +
                                '                        <div class="box__user-avatar"></div>' +
                                '                        <div class="box__body">' +
                                '                            <div class="box__user-header d-flex align-items-center mb-1">' +
                                '                                <div class="box__user-name mr-1">管理員</div>' +
                                '                                <p class="message-date">' + result['contact'][0]['date'] + '</p>' +
                                '                            </div>' +
                                '                            <div class="box__message">' +
                                '                                <p class="message-content">' + result['contact'][0]['message'] + '</p>' +
                                '                            </div>' +
                                '                        </div>' +
                                '                    </div>' +
                                '';
                            $("#chatBodyInner").append(content);
                            window.LCTA.setChatScroll();
                            window.LCTA.eventNewAdminContactDetail();
                        } else {
                            window.LCTA.eventNewAdminContactDetail();
                        }
                    },
                    //Ajax請求超時，繼續查詢
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        window.LCTA.eventNewAdminContactDetail();
                    }
                });
            } else if ($("#liveChat").css("display") == "none") {
                return;
            }
        }
    }
}

// 建立與管理員的聊天室 Start
// if($("#liveChat")) {

function buildLiveChatToAdmin(element) {
    toggleLiveChat(); // 打開聯繫管理員視窗
    let chatToAdminInfo = $(element).attr("data-admin__chat").split(","); // 取得管理員訊息參數

    window.LCTA = liveChatToAdmin(chatToAdminInfo[0], chatToAdminInfo[1], chatToAdminInfo[2]); // 建立與管理員聊天室
    window.LCTA.buildAdminContactDetail(); // 載入訊息
}

function toggleLiveChat() { // 打開聯繫管理員視窗
    $("#liveChat").toggle(function () {
    });
}

$('#sendMessageInput').keypress(function (event) { // 傳送訊息
    let keycode = (event.keyCode ? event.keyCode : event.which);

    if (keycode == 13 && event.shiftKey) { //案下 shift + enter 換行
        console.log($('.sendMessage__input').val());
    } else if (keycode == 13) { //按下enter時
        event.preventDefault(); //停止案下enter換行的動作;改成 shift + enter 換行
        let message = $(event.target).val();
        $(event.target).val('');
        if (message != "") {
            message = message.replace(/\n|\r\n/g, "<br>");
            window.LCTA.addAdminContact(message);
        }
    }
    event.stopPropagation();
});

$('.chat__body-inner').unbind('scroll').scroll(function () { // 監聽管理員聊天室
    let window_scrollTop = $('.chat__body-inner').scrollTop();

    if (window_scrollTop == 0) { //捲軸滑到最上面時
        window.LCTA.msg_load_index++;
        window.LCTA.loadOlderMessage();
    }
});
// 建立與管理員的聊天室 End

/*共用跳提示方法*/
function share_hint_fun(hint_text) {
    var hint_box = {
        "任務介面" : "請先去認證信箱吧",
        "工資管理" : "請先去成為老師吧"
    };

    $("#hint_text").text(hint_box[hint_text]);
    $("#hint_window").modal();
}


/*---------------------------------------------------響應RWD---------------------------------------------------*/

/*------------------------取得元素------------------------*/
var right_icon_group = $(".right-icon-group");


/*------------------------頁面載入時設定元素------------------------*/
var wdth = $(window).width(); // 取得視窗寬度

if (wdth <= 1368) {
    right_icon_group.addClass("col-sm-12")
} else if (wdth > 1368) {
    right_icon_group.removeClass("col-sm-12")
}

if (wdth < 576) {
    //探索課程的Menu
    $(".menu").each(function (index) {
        index++; //因為index是從0開始，所以要加一

        $(".menu" + index).click(function () {
            $(".dropdown-item").css("color", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("color", "blue");
            $(".dropdown-item").css("font-weight", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("font-weight", "bold");
            $(".search .menu").css("display", "none"); //將此樣式先刪除在進行下一行處理
            $("#menu" + index).css("display", "unset");
            return false;
        });

        $("#menu" + index).mouseleave(function () {
            $("#menu" + index).css("display", "none"); //將此樣式先刪除在進行下一行處理
            return false;
        });
    });
}

if (wdth > 576) {

    //探索課程的Menu
    $(".menu").each(function (index) {
        //探索課程
        // $(".sd-rwd").mouseover(function () {
        //     $(".main-menu").css("display", "block");
        // })

        index++; //因為index是從0開始，所以要加一

        $(".menu" + index).click(function () {
            $(".dropdown-item").css("color", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("color", "blue");
            $(".dropdown-item").css("font-weight", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("font-weight", "bold");
            $(".search .menu").css("display", "none"); //將此樣式先刪除在進行下一行處理
            $("#menu" + index).css("display", "unset");
            return false;
        });

        $(".menu" + index).hover(function () {
            $(".dropdown-item").css("color", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("color", "blue");
            $(".dropdown-item").css("font-weight", "unset"); //將此樣式先刪除在進行下一行處理
            $(".menu" + index).css("font-weight", "bold");
            $(".search .menu").css("display", "none"); //將此樣式先刪除在進行下一行處理
            $("#menu" + index).css("display", "unset");
            return false;
        });

        $("#menu" + index).mouseleave(function () {
            $("#menu" + index).css("display", "none"); //將此樣式先刪除在進行下一行處理
            return false;
        });
    });
}

if (wdth >= 992) {
    var search_element = $("#nav-search");
    var right_icon = $("#right-icon");
    var right_item = $("#right-item");
    right_icon.remove("#nav-search");
    right_item.prepend(search_element);

} else if (wdth < 992) {
    var search_element = $("#nav-search");
    var right_icon = $("#right-icon");
    var right_item = $("#right-item");
    right_item.remove("#nav-search");
    right_icon.prepend(search_element);
}


/*------------------------隨著視窗大小調整元素------------------------*/
$(window).resize(function () {
    var wdth = $(window).width();
    if (wdth < 386) {
    } else if (wdth < 576) {
    } else if (wdth < 768) {
    } else if (wdth < 992) {
    } else if (wdth < 1368) {
        right_icon_group.addClass("col-sm-12")
    } else if (wdth > 1368) {
        right_icon_group.removeClass("col-sm-12")
    }

    if (wdth >= 992) {
        var search_element = $("#nav-search");
        var right_icon = $("#right-icon");
        var right_item = $("#right-item");
        right_icon.remove("#nav-search");
        right_item.prepend(search_element);

    } else if (wdth < 992) {
        var search_element = $("#nav-search");
        var right_icon = $("#right-icon");
        var right_item = $("#right-item");
        right_item.remove("#nav-search");
        right_icon.prepend(search_element);
    }
});

