$(document).ready(function () {
    var convey_data = {
        index: 0
    };

    //取得聯絡訊息
    $.ajax({
        type: 'POST',
        url: 'message_management/getAdminContact',
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);

            for (var i = 0; i < res.length; i++) {
                var isRead = " "; //看是否要新增未已讀的class

                if (res[i]['who_say'] == "M" && res[i]['haveRead'] == 0) {
                    isRead = " unread "; //未已讀
                }

                $('.message_present_left').append(" <!--一整個聊天訊息-->" +
                    "                    <div class=\"message_box" + isRead + "row\" id=\"message_box" + i + "\" onclick='open_right_window(" + i + ")'>" +
                    "<span class='data_area' id='m_id_" + i + "'>" + res[i]['memberID'] + "</span>" +
                    "<span class='data_area' id='t_id_" + i + "'>" + res[i]['teacherID'] + "</span>" +
                    "                        <div class=\"avatar_area col-sm-3\">" +
                    "                            <img class=\"avatar\" src=\"resource/image/student/photo/" + res[i]['memberPhoto'] + "?v=" + Math.random() + "\"" +
                    "                                 alt=\"\">" +
                    "                        </div>" +
                    "                        <div class=\"information_area col-sm-5\">" +
                    "                            <span class=\"message_name\" id=\"message_name" + i + "\">" + res[i]['memberName'] + "</span>" +
                    "                            <span class=\"new_message_text\" id=\"new_message_text" + i + "\">" + res[i]['message'] + "</span>" +
                    "                        </div>" +
                    "                        <div class=\"date_area col-sm-4\">" +
                    "                            <span class=\"new_message_date\">" + res[i]['date'].substr(0, 10) + "</span>" +
                    "                        </div>" +
                    "                    </div>"
                )
                ;
            }

        }
    });
});

//指定聯絡人視窗案確定時
function designated_contact() {
    $('#designated_contact_window').modal();

    $('#designated_contact_confirm').unbind('click').bind('click', function () {
        if ($('#designated_contact_id').val() != "" && $('#designated_contact_message').val() != "") {
            var convey_data = {
                id: $('#designated_contact_id').val(),
                message: $('#designated_contact_message').val()
            };

            $.ajax({
                type: 'POST',
                url: 'message_management/addAdminContact_a',
                data: convey_data,
                datatype: 'json',
                success: function (res) {
                    res = JSON.parse(res);

                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();

                    if (res['status']) {
                        $('#designated_contact_window').modal('hide');
                    }
                }
            });
        } else {
            $('#hint_text').text("輸入框資料請勿留空");
            $('#hint_window').modal();
        }
    });
}

//指定聯絡人輸入ID後觸發事件
function input_ID_event(id) {
    if ($('#designated_contact_id').val() != "") { //刪除時為空不會觸發
        $('#designated_contact_message').attr('disabled', false);

        var convey_data = {
            id: id
        };

        $.ajax({
            type: "POST",
            url: "message_management/getMemberData",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                if (res == null) { //找不到聯絡人ID
                    $('#hint_text').text("找不到聯絡人ID");
                    $('#hint_window').modal();
                    $('#designated_contact_id').val("");
                    $('#designated_contact_message').val("");
                    $('#designated_contact_message').attr('disabled', true);
                    $('.contact_person_data_area #contact_person_name').text("");
                    $('.contact_person_data_area #contact_person_img').attr("src", "");
                    $('.contact_person_data_area').css("display", "none");
                } else {
                    $('.contact_person_data_area').css("display", "block");
                    $('.contact_person_data_area #contact_person_name').text(res['name']);
                    $('.contact_person_data_area #contact_person_img').attr("src", "resource/image/student/photo/" + res['photo'] + "?v=" + Math.random());
                }
            }
        });
    } else {
        $('#designated_contact_id').val("");
        $('#designated_contact_message').val("");
        $('#designated_contact_message').attr('disabled', true);
        $('.contact_person_data_area #contact_person_name').text("");
        $('.contact_person_data_area #contact_person_img').attr("src", "");
        $('.contact_person_data_area').css("display", "none");
    }

}

//打開右側訊息框
var Last_time_message_index = -1; //定義初始訊息編號
var msg_load_index = 0; //定義取得訊息資料index
var chatroom_continually_updated_type = 1; //持續更新訊息開啟狀態

function open_right_window(message_index, type) {
    $('#message_box' + message_index).removeClass('unread'); //刪除未讀背景顏色
    chatroom_continually_updated_type = 1; //持續更新訊息開啟狀態

    $(document).unbind('keydown').keydown(function (event) {
        var input_message = $('.sendMessage__input').val();

        if (event.keyCode == 27) { //按下ESC時
            var scrollHeight = $('.chat__body-inner').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
            $('.chat__body-inner').scrollTop(scrollHeight, 200);
            close_right_window();
        } else if (event.keyCode == 13 && event.shiftKey) { //案下 shift + enter 換行
            console.log($('.sendMessage__input').val());
        } else if (event.keyCode == 13) { //按下enter時
            event.preventDefault(); //停止案下enter換行的動作;改成 shift + enter 換行
            if (input_message != "") {
                input_message = input_message.replace(/\n|\r\n/g, "<br>");

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
                var message_html = "";

                message_html += "<div class=\"inner__box inner__box-self d-flex\">" +
                    "                                    <div class=\"box__body\">" +
                    "                                        <div class=\"box__user-header mb-1\">" +
                    "                                            <p class=\"message-date\">" + now_time + "</p>" +
                    "                                        </div>" +
                    "                                        <div class=\"box__message\">" +
                    "                                            <p class=\"message-content mb-2\">" + input_message + "</p>" +
                    "                                        </div>" +
                    "                                    </div>" +
                    "                                </div>";

                $('.chat__body-inner').append(message_html);

                $('.sendMessage__input').val(""); //傳送後清空輸入框

                var scrollHeight = $('.chat__body-inner').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                $('.chat__body-inner').scrollTop(scrollHeight, 200);

                /*以上是呈現部分*/

                /*以下是後端處理部分*/
                var convey_data = {
                    id: $('#m_id_' + message_index).text(),
                    message: input_message
                };
                console.log(convey_data);
                $.ajax({
                    type: "POST",
                    url: "message_management/addAdminContact_a",
                    data: convey_data,
                    dataType: 'json',
                    success: function (res) {
                        $('#new_message_text' + message_index).text(input_message);
                        if (!res['status']) {
                            $('#hint_text').text(res['msg']);
                            $('#hint_window').modal();
                        }
                    }
                });

            }

        }
    });

    /*以下兩個變數是要用來做持續更新訊息的功能*/
    var new_id3 = "";
    var msg_informant_id = $('#m_id_' + message_index).text();

    if (Last_time_message_index != message_index) { //如果這次編號和上次不同才執行
        msg_load_index = 0; //初始取得訊息資料index
        $('.chat__body-inner').html(""); //清空訊息內容
        $('.message_present_right').css('display', 'block'); //改用block 原本是flex(定位)到中間用
        $('.message_bar').removeClass('d-none'); //將訊息欄的區塊顯示出來
        $('.not_sel_message').css('display', 'none'); //未選擇的文字隱藏
        $('#informant_name').text($('#message_name' + message_index).text()); //更改聊天視窗的對象名稱

        var convey_data = {
            id: $('#m_id_' + message_index).text(),
            index: msg_load_index
        };

        //取得聊天對象名稱跟圖片
        $.ajax({
            type: 'POST',
            url: 'message_management/getAdminContactDetail',
            data: convey_data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);
                console.log(res);
                var message_html = "";
                var new_id = "";
                for (var i = res.length - 1; i >= 0; i--) {
                    if (i == 0) {
                        new_id = "id = \"" + res[i]['id'] + "\""; //是最後一筆時加上最新ID編號
                        new_id3 = res[i]['id'];
                    }

                    if (res[i]['who_say'] == 'A') { //管理員本人訊息
                        message_html += "<div class=\"inner__box inner__box-self d-flex\" " + new_id + ">" +
                            "                                    <div class=\"box__body\">" +
                            "                                        <div class=\"box__user-header mb-1\">" +
                            "                                            <p class=\"message-date\">" + res[i]['date'] + "</p>" +
                            "                                        </div>" +
                            "                                        <div class=\"box__message\">" +
                            "                                            <p class=\"message-content mb-2\">" + res[i]['message'] + "</p>" +
                            "                                        </div>" +
                            "                                    </div>" +
                            "                                </div>";
                    } else { //對方的訊息
                        message_html += "<div class=\"inner__box inner__box-others d-flex\" " + new_id + ">" +
                            "                                    <img class=\"box__user-avatar\" src=\"resource/image/student/photo/" + res[0]['photo'] + "?v=" + Math.random() + "\">" +
                            "                                    <div class=\"box__body\">" +
                            "                                        <div class=\"box__user-header d-flex align-items-center mb-1\">" +
                            "                                            <div class=\"box__user-name mr-1\">" + res[i]['memberName'] + "</div>" +
                            "                                            <p class=\"message-date\">" + res[i]['date'] + "</p>" +
                            "                                        </div>" +
                            "                                        <div class=\"box__message\">" +
                            "                                            <p class=\"message-content\">" + res[i]['message'] + "</p>" +
                            "                                        </div>" +
                            "                                    </div>" +
                            "                                </div>";
                    }

                }

                $('.chat__body-inner').append(message_html);

                var scrollHeight = $('.chat__body-inner').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                $('.chat__body-inner').scrollTop(scrollHeight, 200);

                chatroom_continually_updated(msg_informant_id, new_id3, message_index);
            }
        });

        /*舊訊息載入觸發方法 start*/
        $('.chat__body-inner').unbind('scroll').scroll(function () {
            var window_scrollTop = $('.chat__body-inner').scrollTop();

            if (window_scrollTop < 5 & window_scrollTop != 0) { //捲軸滑到幾乎最上面時但是不是完全最上面
                msg_load_index++;

                var convey_data = {
                    id: $('#m_id_' + message_index).text(),
                    index: msg_load_index
                };

                $.ajax({
                    type: "POST",
                    url: "message_management/getAdminContactDetail",
                    data: convey_data,
                    dataType: 'json',
                    async: true,//設定是否非同步，預設為非同步
                    success: function (res) {
                        console.log(res);
                        if (res.length != 0) { //如果有抓到值才做
                            var message_html = "";
                            for (var i = res.length - 1; i >= 0; i--) {
                                if (res[i]['who_say'] == 'A') { //管理員本人訊息
                                    message_html += "<div class=\"inner__box inner__box-self d-flex\">" +
                                        "                                    <div class=\"box__body\">" +
                                        "                                        <div class=\"box__user-header mb-1\">" +
                                        "                                            <p class=\"message-date\">" + res[i]['date'] + "</p>" +
                                        "                                        </div>" +
                                        "                                        <div class=\"box__message\">" +
                                        "                                            <p class=\"message-content mb-2\">" + res[i]['message'] + "</p>" +
                                        "                                        </div>" +
                                        "                                    </div>" +
                                        "                                </div>";
                                } else { //對方的訊息
                                    message_html += "<div class=\"inner__box inner__box-others d-flex\">" +
                                        "                                    <img class=\"box__user-avatar\" src=\"resource/image/student/photo/" + res[0]['photo'] + "?v=" + Math.random() + "\">" +
                                        "                                    <div class=\"box__body\">" +
                                        "                                        <div class=\"box__user-header d-flex align-items-center mb-1\">" +
                                        "                                            <div class=\"box__user-name mr-1\">" + res[i]['memberName'] + "</div>" +
                                        "                                            <p class=\"message-date\">" + res[i]['date'] + "</p>" +
                                        "                                        </div>" +
                                        "                                        <div class=\"box__message\">" +
                                        "                                            <p class=\"message-content\">" + res[i]['message'] + "</p>" +
                                        "                                        </div>" +
                                        "                                    </div>" +
                                        "                                </div>";
                                }

                            }

                            $(message_html).prependTo('.chat__body-inner');

                            var scrollHeight = $('.chat__body-inner').prop("scrollHeight") / 4; //自動將卷軸捲到最新(最下方)的地方
                            $('.chat__body-inner').scrollTop(scrollHeight, 200);
                        } else {
                            msg_load_index--; //如果抓到空資料將載入的index維持上一次的
                        }
                    }
                });
            }
        });
        /*舊訊息載入觸發方法 end*/

        Last_time_message_index = message_index; //將這次的訊息視窗編號丟給暫存

    }
}



/*聊天室窗持續更新*/
function chatroom_continually_updated(msg_informant_id, new_id3, message_index) {
    if (chatroom_continually_updated_type == 1) {
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "message_management/getNewAdminContactDetail",
                async: true,
                // timeout: 60000,  //ajax請求超時時間60秒
                data: {memberID: msg_informant_id, id: new_id3},
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    var message_html = "";
                    if (result['status']) {
                        new_id3 = result['contact'][0]['id'];
                        if (result['contact'][0]['who_say'] == '1') { //管理員本人訊息
                            message_html += "<div class=\"inner__box inner__box-self d-flex\"> " +
                                "                                    <div class=\"box__body\">" +
                                "                                        <div class=\"box__user-header mb-1\">" +
                                "                                            <p class=\"message-date\">" + result['contact']['0']['date'] + "</p>" +
                                "                                        </div>" +
                                "                                        <div class=\"box__message\">" +
                                "                                            <p class=\"message-content mb-2\">" + result['contact']['0']['message'] + "</p>" +
                                "                                        </div>" +
                                "                                    </div>" +
                                "                                </div>";
                        } else { //對方的訊息
                            message_html += "<div class=\"inner__box inner__box-others d-flex\"> " +
                                "                                    <img class=\"box__user-avatar\" src=\"resource/image/student/photo/" + result['contact']['0']['photo'] + "?v=" + Math.random() + "\">" +
                                "                                    <div class=\"box__body\">" +
                                "                                        <div class=\"box__user-header d-flex align-items-center mb-1\">" +
                                "                                            <div class=\"box__user-name mr-1\">" + result['contact']['0']['name'] + "</div>" +
                                "                                            <p class=\"message-date\">" + result['contact']['0']['date'] + "</p>" +
                                "                                        </div>" +
                                "                                        <div class=\"box__message\">" +
                                "                                            <p class=\"message-content\">" + result['contact']['0']['message'] + "</p>" +
                                "                                        </div>" +
                                "                                    </div>" +
                                "                                </div>";
                        }


                        $('.chat__body-inner').append(message_html);

                        var scrollHeight = $('.chat__body-inner').prop("scrollHeight"); //自動將卷軸捲到最新(最下方)的地方
                        $('.chat__body-inner').scrollTop(scrollHeight, 200);

                        $('#new_message_text' + message_index).text(result['contact']['0']['message']);

                        if (!$(".sendMessage__input").is(":focus")) { //聊天視窗不是焦點時
                            /*聊天訊息通知*/
                            if (Notification.permission == "granted") {
                                var notification = new Notification("新聊天通知", {
                                    body: result['contact']['0']['message']
                                });
                            }
                        }

                        chatroom_continually_updated(msg_informant_id, new_id3, message_index);
                    } else
                        chatroom_continually_updated(msg_informant_id, new_id3, message_index);
                },
                //Ajax請求超時，繼續查詢
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    chatroom_continually_updated(msg_informant_id, new_id3, message_index)
                }
            }
        );
    }
}

//關閉訊息窗時(不管是按ESC或X)
function close_right_window() {
    Last_time_message_index = -1; //關掉訊息視窗時初始化訊息編號變數為-1
    chatroom_continually_updated_type = 0; //取消持續讀取訊息的事件
    $('.not_sel_message').css('display', 'block');
    $('.message_present_right').css('display', 'flex');
    $('.message_bar').addClass('d-none');
}
