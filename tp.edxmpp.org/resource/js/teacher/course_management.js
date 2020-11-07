function contact_detail_courseManagement(div_num, identity, id, chat_object, id2, id3) { //打開聊天訊息視窗

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

function delete_courses(actualMovie) {
    //檢查是否要刪除
    $('#hint_window #confirm').css('display', 'block');
    var value_of_onclick = "delete_courses_true('" + actualMovie + "');";
    $('#hint_window #confirm').attr('onclick', value_of_onclick);
    $('#hint_text').text("確定要刪除嗎?");
    $('#hint_window').modal();

    $('#hint_window').on('hide.bs.modal', function () {
        $('#hint_window #confirm').css('display', 'none');
    });
}

function delete_courses_true(name) {
    $('#hint_window #confirm').css('display', 'none');
    $('#hint_window').modal('hide');

    var convey_data = {
        actualMovie: name
    };

    $.ajax({
        url: "../../live_courses/delete_courses",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);

            var value_of_onclick = "histoy_go(0);";
            $('#hint_window #close').attr('onclick', value_of_onclick);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        },
    });
}

function histoy_go() {
    window.history.go(0);
}

function edit_courses(name) {
    var convey_data = {
        actualMovie: name
    };

    $.ajax({
        url: "live_courses/edit_courses",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
        },
    });
}


// 切換直播類型
$('#type_live_course').click(function () {
    window.location = '../../course_management/index/' + this.id;
});

//切換影片類型
$('#type_film_course').click(function () {
    window.location = '../../course_management/index/' + this.id;
});

//切換募資類型
$('#type_fundraising_course').click(function () {
    window.location = '../../course_management/index/' + this.id;
});

//下拉系列影片
function openCollapse(id, type) {
    var openItem = $('#open_' + id);
    var internal = $('#internal_' + id);
    if (type == 1) { //open
        openItem.removeClass('fa-plus');
        openItem.addClass('fa-minus');
        internal.attr('onclick', 'openCollapse(' + id + ",0)");
    } else if (type == 0) { //close
        //刪掉class fa-plus
        openItem.removeClass('fa-minus');
        openItem.addClass('fa-plus');
        internal.attr('onclick', 'openCollapse(' + id + ",1)");
    }
}

/*發佈監聽事件(相同的資料，讓資料庫沒做更新的話會出現錯誤訊息視窗)*/
var Is_Update = false; //判斷是否有調整過是否發佈
$("input[type='checkbox']").change(function () {
    Is_Update = true;
});

/*發佈功能*/
function update_release() {
    var is_Course_type_len = document.getElementsByClassName('course_type').length;
    if (is_Course_type_len == 0) { //當沒有任何課程的時候
        $('#hint_text').text("目前無課程，請新增後再次嘗試發佈");
        $('#hint_window').modal();
    } else { //當有課程的時候
        if (Is_Update) { //有調整過是否發佈
            var course_type = document.getElementsByClassName('course_type')[0].innerText;
            var release = document.getElementsByClassName('release');
            var course_id = document.getElementsByClassName('course_id');

            var convey_data = {};

            for (var i = 0; i < release.length; i++) {
                convey_data[i] = {id: course_id[i].innerText, type: release[i].checked == true ? 1 : 0};
            }

            $.ajax({
                url: "../../course_management/updateRelease/" + course_type,
                type: "POST",
                data: convey_data,
                datatype: 'json',
                success: function (res) {
                    res = JSON.parse(res);
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                    if (res['status'])
                        Is_Update = false; //成功將是否更新狀態變否
                },
                error: function (e) {
                    Is_Update = false; //失敗將是否更新狀態變否
                }
            });
        } else {
            $('#hint_text').text("未做過任何調整，請調整後再試一次");
            $('#hint_text').append("<div style='margin-top: 15px'><p style='font-size: 20px;'>※課程狀態提示</p>" +
                "<div><p>未勾選: 課程瀏覽中不會出現該課程</p>" +
                "<p>勾選: 課程瀏覽中顯示該課程</p></div>");
            $('#hint_window').modal();
        }
    }
}


/*   影片課程   */
function delete_film_course(id) {
    $('#hint_window #confirm').css('display', 'block');
    var value_of_onclick = "delete_film_true('" + id + "');";
    $('#hint_window #confirm').attr('onclick', value_of_onclick);
    $('#hint_text').text("確定要刪除嗎?");
    $('#hint_window').modal();

    $('#hint_window').on('hide.bs.modal', function () {
        $('#hint_window #confirm').css('display', 'none');
    });
}

function delete_film_true(id) {
    $('#hint_window #confirm').css('display', 'none');
    $('#hint_window').modal('hide');

    var convey_data = {
        cf_id: id
    };

    $.ajax({
        url: "../../film_course/delete_film_course",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            var value_of_onclick = "histoy_go(0);";
            $('#hint_window #close').attr('onclick', value_of_onclick);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        },
    });
}

/*直播 - 開始上課*/
function start_class(id1, index) {
    //清空modal
    $('#live_class_information_area').empty();

    //取得課程匹配時間
    $.ajax({
        url: "../../course_management/getMatchTime/" + id1,
        type: "POST",
        success: function (res) {
            res = JSON.parse(res);
            //載入匹配時間calander
            $("#live_class_information_area").append("<div id='calendar'></div>");
            build_match_time(res['Events'], id1, index);
        },
    });


    //一開始先抓取該課程的直播網址(如沒設定則為空)
    // var convey_data = {
    //     id: $('#course_id' + index).text()
    // };
    //
    // $.ajax({
    //     url: "../../course_management/getLiveURL",
    //     type: "POST",
    //     data: convey_data,
    //     datatype: 'json',
    //     success: function (res) {
    //         res = JSON.parse(res);
    //         //修改modal內容
    //         if (res['url'] != null) {
    //             $('#live_class_information_area').append("" +
    //                 "<p>上課網址</p><input type='url' class='form-control' id='live_url' value='" + res['url'] + "'>");
    //         } else {
    //             $('#live_class_information_area').append("<p>上課網址</p><input type='url' class='form-control' id='live_url'>");
    //         }
    //     },
    // });

    //顯示
    $('#live_class_information_window').modal();

    //新增或修改直播網址
    // $('#class_status_confirm').unbind('click').bind('click', function (e) {
    //     var convey_data = {
    //         id1: $('#course_id' + index).text(),
    //         url: $('#live_url').val()
    //     };
    //
    //     $.ajax({
    //         url: "../../course_management/updateLiveURL",
    //         type: "POST",
    //         data: convey_data,
    //         datatype: 'json',
    //         success: function (res) {
    //             res = JSON.parse(res);
    //             $('#hint_text').text(res['msg']);
    //             $('#hint_window').modal();
    //             if (res['status']) {
    //                 $('#internal' + index).css("backgroundColor", "rgba(51,204,204,0.3)");
    //                 $('#live_class_information_window').modal("hide");
    //             }
    //         },
    //     });
    // });
}

function build_match_time(events, id1, index) { // 生成匹配時間
    //初始化
    var calendar = $("#calendar");
    calendar.fullCalendar({
        header: { // 頂部排版
            left: "title", //放置標題
            center: "",
            right: "prev,next today" // 左邊放置上一頁、下一頁和今天
        },
        monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        dayNamesShort: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        eventClick: function (date, event, view) {
            openStudentList(id1, date['id']);
        },
        eventMouseover: function (calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" id="toolTipEvent">' + calEvent.title + '</div>';
            var $tooltip = $(tooltip).appendTo('body');
            $(this).mouseover(function (e) {
                $(this).css('z-index', 10000);
                $tooltip.fadeIn('500');
                $tooltip.fadeTo('10', 1.9);
            }).mousemove(function (e) {
                $tooltip.css('top', e.pageY + 10);
                $tooltip.css('left', e.pageX + 20);
            }).mouseleave(function (e) {
                $("#toolTipEvent").remove();
            });
        },
        events: events
    });
}

// function end_class(index) {
//     //清空modal
//     $('#hint_text').empty();
//
//     //修改modal內容
//     $('#hint_Title').text("結束上課");
//     $('#hint_text').append("<p>您確定要結束上課了嗎?</p>");
//
//     //顯示
//     $('#hint_window').modal();
//     $('#confirm').css('display', 'block');
//
//     $('#confirm').unbind('click').bind('click', function (e) {
//         var convey_data = {
//             id: $('#course_id' + index).text()
//         };
//
//         $.ajax({
//             url: "../../course_management/cancelAttendClass",
//             type: "POST",
//             data: convey_data,
//             datatype: 'json',
//             success: function (res) {
//                 res = JSON.parse(res);
//                 $('#hint_text').text(res['msg']);
//                 $('#hint_window').modal();
//
//                 if (res['status']) {
//                     $('#internal' + index).css("backgroundColor", "unset");
//                     $('#hint_window').modal("hide");
//                     $('#confirm').css('display', 'none');
//                 }
//             },
//         });
//     });
// }

/*課程通知 start*/
function course_notice(type, id) {
    $('#specific_ID').val(id);

    if (type == "live") { //直播
        $('#notice_object').val(6);
        $('#specific_ID_Title').text("直播頁面");
        $('#specific_ID_hint').append("1.選擇對應的直播課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "../../resource/image/admin/notice/liveCourse_specific_ID.jpg");
    }
    else if (type == "film") { //影片
        $('#notice_object').val(5);
        $('#specific_ID_Title').text("影片頁面");
        $('#specific_ID_hint').append("1.選擇對應的影片課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "../../resource/image/admin/notice/filmCourse_specific_ID.jpg");
    }
    else if (type == "F_C") { //募資
        $('#notice_object').val(7);
        $('#specific_ID_Title').text("募資頁面");
        $('#specific_ID_hint').append("1.選擇對應的募資課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "../../resource/image/admin/notice/fundraisingCourse_specific_ID.jpg");
    }

    $('#course_notice_window').modal();

    $('#add_confirm').unbind("click").bind('click', function () {
        if ($('#email_or_notice').val() == 0 | $('#email_or_notice').val() == 1) {
            $('#loading_text').text("信件正在發送中，請稍後..."); //顯示loading視窗
            $('.loading').css("display", "inline-block"); //顯示loading視窗
        }

        var convey_data = {
            notice_object: document.getElementById('notice_object').value,
            specificObject: document.getElementById('specific_ID').value,
            email_or_notice: document.getElementById('email_or_notice').value,
            message_title: document.getElementById('message_title').value,
            send_message: CKEDITOR.instances.editor1.getData()
        };

        $.ajax({
            type: "POST",
            url: "../../course_management/addCourseNotice",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('.loading').css("display", "none"); //隱藏loading視窗

                /*
                    1. 在按鈕之前插入元素
                    2. 顯示訊息並於 3 秒後清除訊息
                 */
                let parentElement = $("#courseNoticeFooter");
                parentElement.prepend("<p id='__showMessage' class='mb-2' value='3' style='color: red'>" + res["msg"] + "</p>")
                var seconds = $("#__showMessage").attr("value");
                var countdown = setInterval(function() {
                    seconds--;
                    $("#__showMessage").attr("value", seconds);
                    if (seconds <= 0) {
                        $("#__showMessage").empty();
                        clearInterval(countdown)
                    };
                }, 1000);


                if (res['status']) {
                    CKEDITOR.instances.editor1.setData("");
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#course_notice_window').modal('hide');
                        document.getElementById('notice_object').value = "0";
                        document.getElementById('email_or_notice').value = "0";
                        document.getElementById('message_title').value = "";
                        document.getElementById('send_message').value = "";
                        initTable();
                    })
                }
            }
        });
    })
}

/*課程通知 end*/

/*募資課程管理 start*/
function cancel_fundraising_course(id) { //取消募資課程(目前暫時刪掉)
    $('#confirm').css('display', 'block');

    $('#hint_text').text("確定要取消此募資課程的募資活動嗎?此操作將會寄信通知對此感興趣的會員，並歸還預付點數所以是不可逆的操作。");
    $('#hint_window').modal();

    $('#confirm').unbind('click').bind('click', function (e) {
        var convey_data = {
            id: id
        };

        $.ajax({
            url: "../../course_management/stopFundraising",
            type: "POST",
            data: convey_data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#confirm').css('display', 'none');
                    $('#hint_window').on('hide.bs.modal', function () {
                        location.reload();
                    });
                }
            },
        });
    });
}

function delete_fundraising_course(id) { //刪除募資課程
    $('#confirm').css('display', 'block');

    $('#hint_text').text("確定要刪除這筆募資課程嗎?");
    $('#hint_window').modal();

    $('#confirm').unbind('click').bind('click', function (e) {
        var convey_data = {
            id: id
        };

        $.ajax({
            url: "../../course_management/deleteFundraising",
            type: "POST",
            data: convey_data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#confirm').css('display', 'none');
                    $('#hint_window').on('hide.bs.modal', function () {
                        location.reload();
                    });
                }
                else {
                    $('#confirm').css('display', 'none');
                }
            },
        });
    });
}

function fundraisingCourseToOrdinaryClass(id) { //轉換成普通課程
    var convey_data = {
        id: id
    };
    $('#loading_text').text("轉換成普通課程中，請稍後..."); //顯示loading視窗
    $('.loading').css("display", "inline-block"); //顯示loading視窗

    $.ajax({
        url: "../../course_management/fundraisingCourseToOrdinaryClass",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            $('.loading').css("display", "none"); //隱藏loading視窗
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            $('#confirm').css('display', 'none');
            if (res['status']) {
                $('#hint_window').on('hide.bs.modal', function () {
                    location.reload();
                });
            }
        },
    });
}

function fundraisingCourseToOrdinaryClassNotice(id) { //募資成功通知
    var convey_data = {
        id: id
    };

    $('#loading_text').text("傳送募資成功通知中，請稍後..."); //顯示loading視窗
    $('.loading').css("display", "inline-block"); //顯示loading視窗

    $.ajax({
        url: "../../course_management/fundraisingCourseToOrdinaryClassNotice",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            $('.loading').css("display", "none"); //隱藏loading視窗
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            $('#confirm').css('display', 'none');
            if (res['status']) {
                $('#hint_window').on('hide.bs.modal', function () {
                    location.reload();
                });
            }
        },
    });
}


function stopFundraising(id) { //   課程管理-募資課程-募資失敗通知
    var convey_data = {
        id
    };

    $.ajax({
        type: "POST",
        url: "../../course_management/stopFundraising",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $("#hint_text").text(res['msg']);
            $("#hint_window").modal("show");
            console.log(res);
        },
        error: function (e) {
            $("#hint_text").text(res['msg']);
            $("hint_window").modal("show");
            console.log(e);
        }
    });
}

/*募資課程管理 end*/


////////////////////////////////////Bootstrap Table - 學生名單////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    })
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })
    return res;
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

function operateFormatter(value, row, index) {
    return [
        '<a class="like" href="javascript:void(0)" title="Like">',
        '<i class="fa fa-heart"></i>',
        '</a>  ',
        '<a class="remove" href="javascript:void(0)" title="Remove">',
        '<i class="fa fa-trash"></i>',
        '</a>'
    ].join('')
}

window.operateEvents = {
    'click .like': function (e, value, row, index) {
        alert('You click like action, row: ' + JSON.stringify(row))
    },
    'click .remove': function (e, value, row, index) {
        $table.bootstrapTable('remove', {
            field: 'id',
            values: [row.id]
        })
    }
}

function totalTextFormatter(data) {
    return 'Total'
}

function totalNameFormatter(data) {
    return data.length
}

function totalPriceFormatter(data) {
    var field = this.field
    return '$' + data.map(function (row) {
        return +row[field].substring(1)
    }).reduce(function (sum, i) {
        return sum + i
    }, 0)
}

function initTable(data) {
    $table.bootstrapTable('destroy').bootstrapTable({
        locale: $('#locale').val(),
        data: data,
        columns: [
            [{
                title: 'teamsAccount',
                field: 'teamsAccount',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '學生名稱',
                field: 'studentName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            },]
        ]
    })
    $table.on('check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table',
        function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

            // save your data, here just save the current page
            selections = getIdSelections()
            // push or splice the selections if you want to save all data selections
        })
    $table.on('all.bs.table', function (e, name, args) {
        // console.log(name, args)
    })
    $remove.click(function () {
        var ids = getIdSelections()
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        })
        $remove.prop('disabled', true)
    })
}

$(function () {
    $('#locale').change(initTable)
})

function openStudentList(id1, id2) { //打開上課名單視窗
    var convey_data = {
        id1: id1,
        id2: id2
    };

    $.ajax({
        url: "../../live_courses/getClassData",
        type: "POST",
        data: convey_data,
        datatype: "json",
        success: function (res) {
            var data = JSON.parse(res);
            $("#studentList__label").text("" + data['className'] + "的上課名單");

            //限制創建教室條件 上課10分鐘前
            $("#btn_createClassRoom").attr("onclick", "createClassRoom('" + id2 + "','" + "" + "')");

            initTable(data['studentList']); //刷新table
            $("#studentList__window").modal();
            $("#SignIn").attr("onclick", "signIn('" + data['key']['applicationKey'] + "','" + data['key']['listKey'] + "')");
        },
    });

    $("#live_class_information_window").css("z-index", "1051");
}

function createClassRoom(id, note) {
    var convey_data = {
        id: id,
    };

    var matchTime = "";

    $.ajax({
        url: "../../live_courses/getMatchTime",
        data: convey_data,
        type: "POST",
        success: (function (res) {
            matchTime = res;
            var d1 = matchTime.substring(0, matchTime.indexOf("~"));
            var d1 = d1.replace(/-/g, "/");
            var d1 = d1.replace("_", " ");
            var d1 = d1.replace(".0000000", "");
            d1 += ":00";
            console.log(d1);
            var timeDiff = timeFn(d1);
            console.log(timeDiff);
            var canCreate = false;
            if (timeDiff["day"] == -1 && timeDiff["hours"] == -1 && timeDiff["min"] < 0 && timeDiff["min"] >= -9 && window.msalApplication.getAccount()) {
                canCreate = true;
            } else {
                alert("還不是創建時間，請稍後在試");
            }

            if (canCreate) {
                var convey_data = {
                    id: id,
                    note: note
                };

                // 調整視窗 z-index、按鈕
                $("#live_class_information_window").css("z-index", "1050");
                $("#studentList__window").css("z-index", "1051");
                $("#hint_window").css("z-index", "1052");
                $("#hint_text").html("" +
                    "<p>確定創建本次Teams上課教室嗎?</p>" +
                    "");
                $("#confirm").css("display", "block");
                $("#hint_window").modal();

                // 調整視窗 z-index、按鈕 回原本狀態
                $('#hint_window').on('hidden.bs.modal', function (e) {
                    $("#live_class_information_window").css("z-index", "1051");
                    $("#studentList__window").css("z-index", "");
                    $("#hint_window").css("z-index", "");
                })

                $("#confirm").unbind('click').click(function () {
                    $.ajax({
                        url: "../../live_courses/createClassRoom",
                        type: "POST",
                        data: convey_data,
                        datatype: "json",
                        success: function (res) {
                            var data = JSON.parse(res);
                            console.log(data);
                            API_createTeamsOnlineMeeting(data);
                            $("#hint_text").html("" +
                                "<p>創建Teams教室成功!</p>" +
                                "");
                            $("#confirm").css("display", "none");
                            $("#confirm").removeAttr("onclick");
                        },
                    });
                })
            }
        })
    });
}

async function API_createTeamsOnlineMeeting(data) {

    console.log(data);

    //use outlook to create online meeting
    var attendees = [];

    for (var i = 0; i < data['list'].length; i++) {
        attendees.push({
            emailAddress: {
                address: data['list'][i]['teamsAccount'],
                name: 'student' + i
            },
            type: "required"
        });
    }

    const event = {
        subject: data['subject'],
        body: {
            contentType: "HTML",
            content: data['content']
        },
        start: {
            dateTime: data['startTime'],
            timeZone: data['timeZone']
        },
        end: {
            dateTime: data['endTime'],
            timeZone: data['timeZone']
        },
        attendees: attendees,
        allowNewTimeProposals: true,
        isOnlineMeeting: true,
        onlineMeetingProvider: "teamsForBusiness",
    };

    console.log(event);

    try {
        let res = await window.client.api('/me/events')
            .version('beta')
            .post(event);
        console.log(res);
    } catch (e) {
        // throw e
    }
}

////////////////////////////////////Bootstrap Table - 學生名單////////////////////////////////////

/*體驗上課申請名單 Bootstrap Table start*/


function openExperienceApplicationList() {
    buildExperienceApplicationTable();
    $('#experience_application_list_window').modal();
}

/*體驗上課申請名單 Bootstrap Table end*/


///////////////////////////////// Microsoft Graph ///////////////////////////////////
async function signIn(key1, key2) {

    //註解掉的程式碼為 挑出視窗手動輸入識別碼。
    // var signInContent = "" +
    //     "<div>應用程式 (用戶端) 識別碼</div>" +
    //     "<input type='text' name='' class='form-control input_user signIn-data' value='" + key1 + "'>" +
    //     "<div>目錄 (租用戶) 識別碼</div>" +
    //     "<input type='text' name='' class='form-control input_user signIn-data' value='" + key2 + "'>" +
    //     "";

    // 調整視窗 z-index、按鈕
    // $("#live_class_information_window").css("z-index", "1050");
    // $("#studentList__window").css("z-index", "1051");
    // $("#hint_window").css("z-index", "1052");
    // $("#hint_Title").text("登入資訊");
    // $("#hint_text").html(signInContent);
    // $("#hint_footer").prepend("<button id=\"confirm_signIn\"  type=\"button\" class=\"btn btn-primary\">登入</button>")
    // $("#hint_window").modal();

    // 調整視窗 z-index、按鈕 回原本狀態
    // $('#hint_window').on('hidden.bs.modal', function (e) {
    //     $("#live_class_information_window").css("z-index", "1051");
    //     $("#studentList__window").css("z-index", "");
    //     $("#hint_Title").text("提示");
    //     $("#hint_text").html("");
    //     $("#confirm_signIn").css("display", "none");
    //     $("#hint_window").css("z-index", "");
    // });

    // $("#confirm_signIn").click(function () { //登入
    // var data = document.getElementsByClassName('signIn-data'); //抓input資料

    window.msalConfig = {
        auth: {
            clientId: key1,
            authority: "https://login.microsoftonline.com/" + key2,
            redirectUri: "https://ajcode.tk/teaching_platform_dev/teams_liveManagement",
        },
    };

    window.graphScopes = [".default"]; // An array of graph scopes
    window.msalApplication = new Msal.UserAgentApplication(window.msalConfig);
    window.options = new MicrosoftGraph.MSALAuthenticationProviderOptions(window.graphScopes);
    window.authProvider = new MicrosoftGraph.ImplicitMSALAuthenticationProvider(window.msalApplication, window.options);
    window.MSGCO_options = {
        authProvider, // An instance created from previous step
    };
    window.Client = MicrosoftGraph.Client;
    window.client = window.Client.initWithMiddleware(MSGCO_options);

    //判斷使用者有沒有登入。
    try {
        window.msalApplication.loginPopup(window.graphScopes).then(function (loginResponse) {
            //Login Success
            console.log(loginResponse);

            var btn_signIn = $("#SignIn");
            btn_signIn.attr("onClick", "signOut()");
            btn_signIn.text("登出MS帳號");
            $("#hint_window").modal("hide");
            var account = msalApplication.getAccount().userName;
            $("#studentList__label").text($("#studentList__label").text() + "   歡迎登入: " + account);

        }).catch(function (error) {
            console.log(error);
        });
    } catch (e) {
        console.log(e);
    } finally {
        console.log(msalApplication);
        console.log(msalApplication.getAccount());
    }

    // });
}

function signOut() {
    window.msalApplication.logout();
}

/////////////////////////////////// Microsoft Graph ///////////////////////////////////


/*-----------------------------------RWD-----------------------------------*/
var film_info_left = $(".film_info_left"); //影片課程標頭左邊
var film_info_right = $(".film_info_right"); //影片課程標頭右邊
var film_detail = $(".film_detail"); //影片課程詳細

var live_info = $(".live_info"); // 直播課程資訊
var live_info_left = $(".live_info_left") // 直播課程資訊左半邊
var live_info_right = $(".live_info_right") // 直播課程資訊左半邊
var live_detail = $(".live_detail"); // 直播課程詳細 右半邊


if ($("#fc_info").length > 0) { //如果類型是募資課程就替換title欄位屬性
    $("#list_field").css({
        "font-size": "24px",
        "margin-right": "0",
        "margin-left": "0",
        "width": "100%",
        "padding": "10px",

    })
}

var wdth = $(window).width();
/*------------------頁面載入初始化------------------*/
//wdth 小於
if (wdth <= 386) {
    live_info.removeClass("col-sm-5");
    live_info.addClass("col-sm-12");
    live_info_left.removeClass("col-sm-12");
    live_info_left.addClass("col-sm-3");
    live_info_right.removeClass("col-sm-12");
    live_info_right.addClass("col-sm-8");
    live_detail.removeClass("col-sm-8");
    live_detail.addClass("col-sm-12");
    $('#btn_area div').addClass("col-sm-12");
    $('.edit_hint_btn').css("margin-left", "-125px");
} else if (wdth < 576) {
    film_info_left.removeClass("col-sm-2");
    film_info_left.removeClass("col-sm-3");
    film_info_left.addClass("col-sm-5");
    film_info_right.removeClass("col-sm-4");
    film_info_right.removeClass("col-sm-9");
    film_info_right.addClass("col-sm-7");
    film_detail.removeClass("col-sm-6");
    film_detail.addClass("col-sm-12");

    live_info.removeClass("col-sm-5");
    live_info.addClass("col-sm-12");
    live_info_left.removeClass("col-sm-12");
    live_info_left.addClass("col-sm-3");
    live_info_right.removeClass("col-sm-12");
    live_info_right.addClass("col-sm-8");
    live_detail.removeClass("col-sm-8");
    live_detail.addClass("col-sm-12");
} else if (wdth < 768) {
    film_info_left.removeClass("col-sm-3");
    film_info_left.removeClass("col-sm-2");
    film_info_left.addClass("col-sm-5");
    film_info_right.removeClass("col-sm-4");
    film_info_right.removeClass("col-sm-9");
    film_info_right.addClass("col-sm-7");
    film_detail.removeClass("col-sm-6");
    film_detail.addClass("col-sm-12");

    live_info.removeClass("col-sm-5");
    live_info.addClass("col-sm-12");
    live_info_left.removeClass("col-sm-12");
    live_info_left.addClass("col-sm-3");
    live_info_right.removeClass("col-sm-12");
    live_info_right.addClass("col-sm-8");
    live_detail.removeClass("col-sm-8");
    live_detail.addClass("col-sm-12");
} else if (wdth <= 992) {
    film_info_left.removeClass("col-sm-2");
    film_info_left.addClass("col-sm-5");
    film_info_right.removeClass("col-sm-4");
    film_info_right.addClass("col-sm-7");
    film_detail.removeClass("col-sm-6");
    film_detail.addClass("col-sm-12");

    live_info.removeClass("col-sm-5");
    live_info.addClass("col-sm-12");
    live_info_left.removeClass("col-sm-12");
    live_info_left.addClass("col-sm-3");
    live_info_right.removeClass("col-sm-12");
    live_info_right.addClass("col-sm-8");
    live_detail.removeClass("col-sm-8");
    live_detail.addClass("col-sm-12");
}


//wdth 大於
if (wdth > 992) {
    film_info_left.removeClass("col-sm-3");
    film_info_left.removeClass("col-sm-2");
    film_info_left.addClass("col-sm-2");
    film_info_right.removeClass("col-sm-4");
    film_info_right.removeClass("col-sm-9");
    film_info_right.addClass("col-sm-4");
    film_detail.removeClass("col-sm-12");
    film_detail.addClass("col-sm-6");
}


//wdth 其他設定
if (wdth >= 400) {
    $('#btn_area div').removeClass("col-sm-12");
    $('.edit_hint_btn').css("margin-left", "10px");
}

/*------------------RWD------------------*/
$(window).resize(function () {
    var wdth = $(window).width();

    //wdth 小於
    if (wdth <= 386) {
        $('#btn_area div').addClass("col-sm-12");
        $('.edit_hint_btn').css("margin-left", "-125px");
    } else if (wdth < 576) {
        film_info_left.removeClass("col-sm-2");
        film_info_left.removeClass("col-sm-3");
        film_info_left.addClass("col-sm-5");
        film_info_right.removeClass("col-sm-4");
        film_info_right.removeClass("col-sm-9");
        film_info_right.addClass("col-sm-7");
        film_detail.removeClass("col-sm-6");
        film_detail.addClass("col-sm-12");
    } else if (wdth < 768) {
        film_info_left.removeClass("col-sm-3");
        film_info_left.removeClass("col-sm-2");
        film_info_left.addClass("col-sm-5");
        film_info_right.removeClass("col-sm-4");
        film_info_right.removeClass("col-sm-9");
        film_info_right.addClass("col-sm-7");
        film_detail.removeClass("col-sm-6");
        film_detail.addClass("col-sm-12");
    } else if (wdth <= 992) {
        film_info_left.removeClass("col-sm-3");
        film_info_left.removeClass("col-sm-2");
        film_info_left.addClass("col-sm-5");
        film_info_right.removeClass("col-sm-4");
        film_info_right.addClass("col-sm-7");
        film_detail.removeClass("col-sm-6");
        film_detail.addClass("col-sm-12");

        live_info.removeClass("col-sm-5");
        live_info.addClass("col-sm-12");
        live_info_left.removeClass("col-sm-12");
        live_info_left.addClass("col-sm-3");
        live_info_right.removeClass("col-sm-12");
        live_info_right.addClass("col-sm-8");
        live_detail.removeClass("col-sm-8");
        live_detail.addClass("col-sm-12");
    }

    //wdth 大於
    if (wdth > 992) {
        film_info_left.removeClass("col-sm-5");
        film_info_left.removeClass("col-sm-2");
        film_info_left.addClass("col-sm-2");
        film_info_right.removeClass("col-sm-7");
        film_info_right.removeClass("col-sm-9");
        film_info_right.addClass("col-sm-4");
        film_detail.removeClass("col-sm-12");
        film_detail.addClass("col-sm-6");

        live_info.removeClass("col-sm-12");
        live_info.addClass("col-sm-5");
        live_info_left.removeClass("col-sm-3");
        live_info_left.addClass("col-sm-12");
        live_info_right.removeClass("col-sm-8");
        live_info_right.addClass("col-sm-12");
        live_detail.removeClass("col-sm-12");
        live_detail.addClass("col-sm-8");
    }

    //wdth 其他設定
    if (wdth >= 400) {
        $('#btn_area div').removeClass("col-sm-12");
        $('.edit_hint_btn').css("margin-left", "10px");
    }
});
/*-----------------------------------RWD-----------------------------------*/
