<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>匹配時間管理頁面</title>

    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.print.css"
          media="print">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../resource/package/css/clockpicker.css">
    <link rel="stylesheet" href="../../resource/css/teacher/match_time.css"/>

    <!--  View  -->
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css">

    <!-- FontAwesome v4.7.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="../../student"><img class="header-logo"
                                                                            src="../../resource/pics/share/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?= $headerRightBar ?>
        </div>
    </nav>

    <!--    導覽列右側MENU    -->
    <?= $headerRightIconMenu ?>

    <!--        右側帳號資訊欄         -->
    <?= $RightInformationColumn ?>


    <div class="message-box menu" id="page-2"> <!-- 聊天 -->
        <div class="top-bar col-sm">
            <b id="chat_object">傳送對象姓名</b>
            <i class="fa fa-close" id="message-box-close"></i>
        </div>

        <div class="message_area">
        </div>

        <div class="send-message">
            <textarea class="input_message" id="input_message" rows="3" cols="32" placeholder="輸入訊息..."></textarea>
        </div>
    </div>

</header>

<div class="course_name">
    <h2>
        <button type="button" class="btn btn-dark return_CM_btn"
                onclick="window.location='../../course_management/index/type_live_course'">返回課程管理頁面
        </button>
        <b>課程名稱 : <?= $className ?></b>
    </h2>
</div>

<div id="calendar" class="calendar_body"></div>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>
<script src="../../resource/package/js/clockpicker.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/share.js"></script>
<script src="../../resource/js/teacher/match_time.js"></script>

<script>

    //初始載入資料
    var calendar = $("#calendar");
    window.Events = <?=json_encode($Events);?> ;

    $("#calendar").fullCalendar({

        header: { // 頂部排版
            left: "title", //放置標題
            center: "",
            right: "prev,next today" // 左邊放置上一頁、下一頁和今天
        },
        monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        dayNamesShort: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        dayClick: function (date, allDay, jsEvent, view) {
            document.getElementById('from_match_time_text').value = "";
            document.getElementById('end_match_time_text').value = "";
            document.getElementById('maxPeople').value = "";

            $('.specify_student').css('display','block');
            $('.class_content').css('display','block');

            $('#match_time_edit,#match_time_delete').css('display', 'none');
            $('#match_time_confirm').css('display', 'block');
            var date = date['_d'].toJSON().toString();
            date = date.substr(0, date.indexOf("T"));
            $('#select_date').val(date);
            //開啟匹配時間modal start
            $('#match_time_window').modal();

            $("#match_time_confirm").unbind("click").bind("click", function () { //當按下確定時
                var from_match_time = document.getElementById('from_match_time_text').value;  //取匹配開始時間的值
                var end_match_time = document.getElementById('end_match_time_text').value;  //取匹配結束時間的值
                var max_people = document.getElementById('maxPeople').value;  //取人數上限的值
                var note_value = document.getElementById('classContent').value;  //取備註的值
                var designated_mod = document.getElementsByName("designatedMod");
                
                if(designated_mod[0].checked)
                    designated_mod = 0;
                else
                    designated_mod = 1;

                var input_judgment_result = input_judgment(from_match_time, end_match_time, max_people); //判斷輸入是否有問題

                if (input_judgment_result) {
                    var id = <?=json_encode($id);?> ;

                    var convey_data = { //欲傳送資料
                        id: id,
                        Specify: $('#specifyStudent').val(),
                        date: date + "_" + from_match_time + "~" + end_match_time,
                        maxPeople: max_people,
                        note: $('#classContent').val(),
                        designatedMod: designated_mod
                    };

                    //新增
                    $.ajax({
                        type: "POST",
                        url: "../../course_management/addLiveTime",
                        data: convey_data,
                        dataType: 'json',
                        success: function (res) {
                            // $('#hint_text').text(res['msg']);
                            // $('#hint_window').modal();

                            if (res['status']) {
                                var write = {
                                    id: res['id'],
                                    start: date,
                                    title: from_match_time + "~" + end_match_time + "\n最多: " + max_people + "人\n已匹配人數: 0人\n備註:" + note_value
                                }

                                window.Events.push(write);

                                calendar.fullCalendar('removeEventSource', window.Events);
                                calendar.fullCalendar('addEventSource', window.Events);
                                calendar.fullCalendar('refetchEvents');

                                $('#match_time_window').modal('hide');
                            }

                        }
                    });
                }
            });
            //開啟匹配時間modal end
        },
        eventClick: function (date, event, view) {

            var id = <?=json_encode($id);?> ;

            $('#match_time_edit').attr('onclick', 'edit_match_time(\'' + id + '\',\'' + date.id + '\')'); //抓取對應事件的id
            $('#match_time_delete').attr('onclick', 'delete_match_time(\'' + date.id + '\')'); //抓取對應事件的id

            var select_date = date['start']['_i'];
            $('#select_date').val(select_date);

            $('#match_time_window').modal();
            $('#match_time_edit,#match_time_delete').css('display', 'block');
            $('#match_time_confirm').css('display', 'none');

            $('.specify_student').css('display','none');
            $('.class_content').css('display','none');

            var from_match_time_text = date.title.split("~")[0]; //起始時間
            var end_match_time_text = date.title.split("~")[1].split("\n")[0]; //終止時間
            var max_people = date.title.split("~")[1].split("\n")[1].split(" ")[2];  //人數上限
            max_people = max_people.substring(0, max_people.length - 1); //處理人數上限字串

            $('#from_match_time_text').val(from_match_time_text);
            $('#end_match_time_text').val(end_match_time_text);
            $('#maxPeople').val(max_people);
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
        events: window.Events
    });

</script>


</body>
</html>