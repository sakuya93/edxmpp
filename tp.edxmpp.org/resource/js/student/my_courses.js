$(document).ready(function () {
    //抓取今天日期(要判斷今天之前的都不許匹配)
    var dt = new Date();
    var day = dt.getDate();

    if (day.toString().length == 1) { //如果日期為 12/(3) ; 故 3 補0 = 12/03;
        day = "0" + day;
    }
    var today = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + day;  //月份加一是因為從0開始算


    // 形成事件
    if (typeof temp_Events !== 'undefined') {
        for (var index  in temp_Events) {
            var calendar = "#calendar_" + index;
            $(calendar).append("<p style='color: red;font-weight: bold'>本平台使用時區為台灣(GMT+8)，如是越南的學生，請自行將時間扣1小時。</p>");
            $(calendar).fullCalendar({
                // 參數設定[註1]
                header: { // 頂部排版
                    left: "title", //放置標題
                    center: "",
                    right: "prev,next today" // 左邊放置上一頁、下一頁和今天
                },
                monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                dayNamesShort: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
                events: temp_Events[index],
                eventClick: function (date, event, view) {
                    var matchTime = date.start['_i'] + " " + date.title.substring(0, date.title.indexOf("~")) + ":00";
                    var d1 = matchTime.replace(/-/g, "/");
                    var timeDiff = timeFn(d1);

                    var canMatch = false;
                    if(timeDiff["day"] < 0 && timeDiff["hours"] < 0  && timeDiff["min"] < -10){
                        canMatch = true;
                    }else{
                        alert("還不是匹配時間，請稍後在試");
                    }

                    if(canMatch) {
                        if (date.start['_i'].indexOf('-0') != -1) { //將日期格式改成 yyyy/1/1 而不是 yyyy/01/01
                            date.start['_i'] = date.start['_i'].replace('-0', '-');
                        }

                        if (today <= date.start['_i']) { //如果點選的匹配時間早於今天的話
                            if (parseInt(date.amount) > 0) {
                                var convey_data = { //欲傳送資料
                                    id: date.id,
                                    id2: date.id2,
                                    id3: date.id3
                                };

                                if (convey_data.id != null && convey_data.id2 != null && convey_data.id3 != null) {
                                    var fc_title = event.target; //抓取是否已匹配用的變數
                                    var match_status = fc_title.innerHTML.indexOf("已匹配") != -1 ? true : false; //是否已匹配

                                    if (match_status) { //已匹配
                                        $('#hint_text').text("你確定要取消匹配這個時間嗎?");
                                        $('#hint_window').modal();
                                        $('#confirm').css('display', 'block');

                                        $('#confirm').unbind('click').bind('click', function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "../my_course_fun/deleteStudentMatchTime",
                                                data: convey_data,
                                                dataType: 'json',
                                                success: function (res) {
                                                    $('#confirm').css('display', 'none');
                                                    if (res['status']) {
                                                        $('#hint_text').html("您已成功取消匹配時間!");
                                                    } else {
                                                        $('#hint_text').html("取消匹配時間失敗，請在試一次!");
                                                    }

                                                    $('#hint_window').modal();
                                                    if (res['status']) {
                                                        //抓取事件title及人數
                                                        var title = date.title;
                                                        title = title.substr(0, 12) + "<br>剩餘: " + res['lastPeople'] + "人";

                                                        //取得該事件的物件去更改顏色及內文
                                                        var fc_event = event.currentTarget;
                                                        var fc_title = event.target;
                                                        fc_event.style.background = "#aaffaa";
                                                        fc_title.innerHTML = "<span class=\"fc-title\">" + title + "</span>";
                                                    }
                                                },
                                            });
                                        });
                                    } else { //未匹配
                                        $('#hint_text').text("你確定匹配這個時間嗎?");
                                        $('#hint_window').modal();
                                        $('#confirm').css('display', 'block');

                                        $('#confirm').unbind('click').bind('click', function () {
                                            $.ajax({
                                                type: "POST",
                                                url: "../my_course_fun/addStudentMatchTime",
                                                data: convey_data,
                                                dataType: 'json',
                                                success: function (res) {
                                                    $('#confirm').css('display', 'none');
                                                    $('#hint_text').html(res['msg']);
                                                    $('#hint_window').modal();
                                                    if (res['status']) {
                                                        //抓取事件title及人數
                                                        var title = date.title;

                                                        title = title.substr(0, 12) + "<br>已匹配";

                                                        //取得該事件的物件去更改顏色及內文
                                                        var fc_event = event.currentTarget;
                                                        var fc_title = event.target;
                                                        fc_event.style.background = "#EA9086";
                                                        fc_title.innerHTML = "<span class=\"fc-title\">" + title + "</span>";
                                                    }
                                                },
                                            });
                                        });
                                    }

                                    $('#hint_window').on('hidden.bs.modal', function (e) {
                                        $('#confirm').css('display', 'none');
                                    })
                                }
                            }
                        } else {
                            $('#hint_text').text("請選取今天或今天之後的匹配日期!");
                            $('#hint_window').modal();
                        }
                    }
                },
                eventMouseover: function(calEvent, jsEvent) {
                    var tooltip = '<div class="tooltipevent" id="toolTipEvent">' + calEvent.title + '</div>';
                    var $tooltip = $(tooltip).appendTo('body');
                    $(this).mouseover(function(e) {
                        $(this).css('z-index', 10000);
                        $tooltip.fadeIn('500');
                        $tooltip.fadeTo('10', 1.9);
                    }).mousemove(function(e) {
                        $tooltip.css('top', e.pageY + 10);
                        $tooltip.css('left', e.pageX + 20);
                    }).mouseleave(function (e) {
                        $("#toolTipEvent").remove();
                    });
                },
            });
        }
    }else{
        console.log("抓不到temp_Events");
    }

    // 形成當沒事件時的提示訊息
    if (typeof temp_noEvents !== 'undefined') {
        for (var index in temp_noEvents) {
            var calendar = $("#calendar_" + index);
            calendar.html(temp_noEvents[index]);
        }
    }else{
        console.log("抓不到temp_noEvents");
    }
});

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




/*---------------------------------------------------響應RWD---------------------------------------------------*/
var buy_record_row = $(".buy_record_row"); // 課程標題欄位
var course_info = $(".course_info"); // 課程資訊(左半邊)
var course_detail = $(".course_detail"); // 課程詳細(形式、類別、時/堂數、工具)


var wdth = $(window).width();

if (wdth <= 974) {

    buy_record_row.css("display", "none");
    course_info.removeClass("col-sm-4");
    course_info.addClass("col-sm-12");
    course_detail.removeClass("col-sm-8");
    course_detail.addClass("col-sm-12");
} else if (wdth > 974) {

}


$(window).resize(function () {
    var wdth = $(window).width();

    if (wdth < 386) {
    } else if (wdth < 576) {
    } else if (wdth <= 974) {
        buy_record_row.css("display", "none");
        course_info.removeClass("col-sm-4");
        course_info.addClass("col-sm-12");
        course_detail.removeClass("col-sm-8");
        course_detail.addClass("col-sm-12");
    } else if (wdth > 974) {
        buy_record_row.css("display", "flex");
        course_info.removeClass("col-sm-12");
        course_info.addClass("col-sm-4");
        course_detail.removeClass("col-sm-12");
        course_detail.addClass("col-sm-8");
    }

});

