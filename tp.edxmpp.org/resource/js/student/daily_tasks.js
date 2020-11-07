var per = 0;
var loading;

$(document).ready(function () {
    for (var i = 1; i <= 3; i++) { //載入各個任務進度
        loading = setInterval(progressAnimate($('#status_' + i).text(), i), 5);
    }

    if (task1) { //簽到
        $('#task_btn_1').attr('disabled', true);
        $('#task_btn_1').attr('onclick', "");
        $('#task_btn_1').css('cursor', "not-allowed");
    }
    if (task2) { //FB分享
        $('#task_btn_2').attr('disabled', true);
        $('#task_btn_2').attr('onclick', "");
        $('#task_btn_2').css('cursor', "not-allowed");
    }
    if (task3) { //LINE分享
        $('#task_btn_3').attr('disabled', true);
        $('#task_btn_3').attr('onclick', "");
        $('#task_btn_3').css('cursor', "not-allowed");
    }

});

//進度條的變更方法
function progressAnimate(status, index) {
    if (status == 0) {
        $('#Progress_' + index).css("width", 0 + "%");
        clearInterval(loading);
    }
    else { //任務完成
        if (per == 1) {
            clearInterval(loading);
        }
        else {
            per = per + 1;
        }

        $('#Progress_' + index).css("width", (per * 100) + "%");
        $('#Progress_' + index).attr("aria-valuenow", per);
        $('#schedule_text_' + index).text("1/1");
    }
}

//任務的方法
function task_fun(task, index) {
    if (task == '簽到') {
        $.ajax({
            url: "daily_tasks_fun/addDailyCheckIn",
            type: "POST",
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);

                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();

                $('#status_' + index).text("1");
                loading = setInterval(progressAnimate($('#status_' + index).text(), index), 5);

                $('#task_btn_1').attr('disabled', true);
                $('#task_btn_1').attr('onclick', "");
                $('#task_btn_1').css('cursor', "not-allowed");
            },
        });
    }
    else if (task == 'FB分享') {
        var t = window.open('http://www.facebook.com/sharer/sharer.php?u='.concat(encodeURI("http://ajcode.tk/teaching_platform_dev/")),
            "_blank",
            "toolbar=no,location=yes,directories=no,width=300,height=400")

        var timer = setInterval(function () {

            if (t.closed) {
                clearInterval(timer);
                $.ajax({
                    url: "daily_tasks_fun/addFB_share",
                    type: "POST",
                    datatype: 'json',
                    success: function (res) {
                        res = JSON.parse(res);

                        // $('#hint_text').text(res['msg']);
                        // $('#hint_window').modal();

                        $('#status_' + index).text("1");
                        loading = setInterval(progressAnimate($('#status_' + index).text(), index), 5);

                        $('#task_btn_2').attr('disabled', true);
                        $('#task_btn_2').attr('onclick', "");
                        $('#task_btn_2').css('cursor', "not-allowed");
                    },
                });
            }
        }, 1000);
    }
    else if (task == 'LINE分享') {
        var t = window.open('http://lineit.line.me/share/ui?url='.concat(encodeURI("https://ajcode.tk/teaching_platform_dev/")),
            "_blank",
            "toolbar=no,location=no,directories=no,width=500,height=700")

        var timer = setInterval(function () {
            if (t.closed) {
                clearInterval(timer);
                $.ajax({
                    url: "daily_tasks_fun/addLINE_share",
                    type: "POST",
                    datatype: 'json',
                    success: function (res) {
                        res = JSON.parse(res);

                        // $('#hint_text').text(res['msg']);
                        // $('#hint_window').modal();

                        $('#status_' + index).text("1");
                        loading = setInterval(progressAnimate($('#status_' + index).text(), index), 5);

                        $('#task_btn_3').attr('disabled', true);
                        $('#task_btn_3').attr('onclick', "");
                        $('#task_btn_3').css('cursor', "not-allowed");
                    },
                });
            }
        }, 1000);
    }
}