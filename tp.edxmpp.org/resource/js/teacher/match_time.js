// function edit_match_time(l_id, lt_id) { //修改對應排程時間
//     var select_date = document.getElementById('select_date').value;  //取選擇的日期的值
//     var from_match_time = document.getElementById('from_match_time_text').value;  //取匹配開始時間的值
//     var end_match_time = document.getElementById('end_match_time_text').value;  //取匹配結束時間的值
//     var max_people = document.getElementById('maxPeople').value;  //取人數上限的值
//     var note_value = document.getElementById('classContent').value;  //取備註的值
//
//     var input_judgment_result = input_judgment(from_match_time, end_match_time, max_people); //判斷輸入是否有問題
//
//     if (input_judgment_result) {
//         var convey_data = { //欲傳送資料
//             oldid: lt_id,
//             id: l_id,
//             date: select_date + "_" + from_match_time + "~" + end_match_time,
//             maxPeople: max_people
//         };
//
//         //修改
//         $.ajax({
//             type: "POST",
//             url: "../../course_management/editLiveTime",
//             data: convey_data,
//             dataType: 'json',
//             success: function (res) {
//                 $('#hint_text').text(res['msg']);
//                 $('#hint_window').modal();
//                 $('#match_time_window').modal('hide');
//
//                 for (var i = 0; i < window.Events.length; i++) {
//                     if (window.Events[i].id.includes(lt_id)) {
//                         window.Events[i].id = res['lt_id'];
//                         window.Events[i].title = from_match_time + "~" + end_match_time + "\n最多: " + max_people + "人\n" + window.Events[i].title.split("\n")[2] + "\n " + window.Events[i].title.split("\n")[3];
//                     }
//                 }
//
//                 calendar.fullCalendar('removeEvents');
//                 calendar.fullCalendar('addEventSource', window.Events);
//                 calendar.fullCalendar('refetchEvents');
//             }
//         });
//     }
// }

function delete_match_time(lt_id, fc_event, fc_title) { //刪除對應排程時間
    var convey_data = { //欲傳送資料
        id: lt_id
    };

    $('#hint_text').text("您確定要刪除嗎?");
    $('#hint_window').modal();
    $('#confirm').css('display', 'block');

    $('#confirm').bind('click', function () {
        //刪除
        $.ajax({
            type: "POST",
            url: "../../course_management/deleteLiveTime",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();
                $('#match_time_window').modal('hide');

                if (res['status'] == true) {
                    for (var i = 0; i < window.Events.length; i++) {
                        if (window.Events[i].id.includes(lt_id)) {
                            window.Events.splice(i, 1); //刪除對應id的事件元素
                        }
                    }


                    $('#calendar').fullCalendar("removeEvents", lt_id);
                }

                $('#confirm').css('display', 'none');
            }
        });
    });

    $('#close').bind('click', function () {
        $('#confirm').css('display', 'none');
    });

}

function input_judgment(from_match_time, end_match_time, max_people) { //判斷輸入的資訊是否有空或錯誤
    var rex = /^\d{2}:\d{2}$/; //設定正規化格式

    if (from_match_time == "" | end_match_time == "") { //如果都沒輸入匹配時間的話
        $('#hint_text').text("匹配時間不可留空");
        $('#hint_window').modal();
        return false;
    } else if (max_people == "") { //如果沒有輸入人數上限的話
        $('#hint_text').text("請輸入人數上限");
        $('#hint_window').modal();
        return false;
    } else if (!rex.test(from_match_time) | !rex.test(end_match_time)) { //輸入的格式不匹配的話 test為正規化內建方法
        $('#hint_text').text("不符合格式");
        $('#hint_window').modal();
        return false;
    } else if (from_match_time > end_match_time) { //比對輸入的開始時間是否晚於結束時間
        $('#hint_text').text("開始時間不得晚於結束時間");
        $('#hint_window').modal();
        return false;
    } else {
        return true;
    }
}