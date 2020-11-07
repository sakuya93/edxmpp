var $table = $('#table')
var $remove = $('#remove')
var selections = []

$(document).ready(function () {
    getDate(); //取得今天日期

    var date = $("#date").val(); //選擇日期(預設為今日日期)。
    buildHandOutDiamondsRecordTable(date); //生成鑽石發放紀錄

    // $('.display_sel_history')
    //     .change(function () {
    //         if ($("input[name='reportManagementOption__control']:checked").val() == 'Issue') {
    //             buildHandOutDiamondsRecordTable($("#date").val()); //生成鑽石發放紀錄
    //         }
    //         else {
    //             DiamondsRecord($("#date").val()); //生成鑽石儲值紀錄
    //         }
    //     });

    $('#locale')
        .change(function () {
            buildHandOutDiamondsRecordTable($("#date").val()); //生成鑽石發放紀錄
        });

    $('#date')
        .change(function () {
            buildHandOutDiamondsRecordTable($("#date").val()); //生成鑽石發放紀錄
        });

});

function getDate() { //取得今天日期
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }

    month = yyyy + '-' + mm;
    $("#date").val(month)
}


function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    })
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })
    return res
}


//////////////////////////////// Bootstrap Table START
function detailFormatter(index, row) {
    var html = []

    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

function IssueID(value, row, index) {
    if (row['acceptID'] == 'all') {
        return [
            '<span>所有人</span>'
        ].join('')
    }
    else {
        return [
            '<span>' + row['acceptID'] + '</span>'
        ].join('')
    }
}

function IssueName(value, row, index) {
    if (row['acceptID'] == 'all') {
        return [
            '<span>所有人</span>'
        ].join('')
    }
    else {
        return [
            '<span>' + row['name'] + '</span>'
        ].join('')
    }
}

function operateFormatter(value, row, index) {
    if ($("input[name='reportManagementOption__control']:checked").val() == "storedValue") {
        var text = "沒收鑽石";
    }
    else {
        var text = "取消發放";
    }
    return [
        '<a class="btn btn-outline-primary mb-2" onclick="cancelHandOutDiamonds(\'' + row['id'] + '\');" title="' + text + '" target="_blank">',
        text,
        '</a>',
    ].join('')
}

function tablePhoto(value, row, index) { // user img for
    if (row['acceptID'] == 'all') {
        return [
            '<span>所有人</span>',
        ].join('')
    }
    else {
        return [
            '<img class="rounded m-auto d-block" width="64" src="resource/image/student/photo/' + row['photo'] + '">',
        ].join('')
    }
}

function initTable(data) {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        data: data,
        columns: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'ID',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '對象',
                field: 'acceptID',
                formatter: IssueID,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '名字',
                field: 'name',
                formatter: IssueName,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '鑽石',
                field: 'point',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '時間',
                field: 'date',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '照片',
                formatter: tablePhoto,
                field: 'photo',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '功能',
                formatter: operateFormatter,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }]

        ],
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

function buildHandOutDiamondsRecordTable(date) { //生成鑽石發放紀錄
    convey_data = {
        date: date,
    };

    $.ajax({
        type: "POST",
        url: "hand_outDiamonds/getHandOutDiamondsRecord",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            initTable(res);
        }
    });
}

// function DiamondsRecord(date) { //生成鑽石儲值紀錄
//     convey_data = {
//         date: date,
//     };
//
//     $.ajax({
//         type: "POST",
//         url: "hand_outDiamonds/getDiamondsRecord",
//         data: convey_data,
//         dataType: 'json',
//         success: function (res) {
//             initTable(res);
//         }
//     });
// }

function handOutDiamonds() { // 開啟發放鑽石視窗
    $("#hand_outDiamonds_window").modal();
    $("#confirm").attr("onclick", "confirmHandOutDiamonds()");
}

function selectOptionForUserID() { // 選擇發放鑽石類型
    var type = $("#select_OptionForUserID").val();

    if (type == "全體") {
        $("#userID").val("all");
        $("#userID").attr("disabled", "disabled");
    } else if (type = "指定") {
        $("#userID").val("");
        $("#userID").attr("disabled", false);
    }
}

function confirmHandOutDiamonds() { // 確定發放鑽石
    var convey_data = {
        id: $("#userID").val(),
        point: $("#point").val(),
        messageTitle: $("#notifyTitle").val(),
        message: $("#notifyContent").val(),
    };

    $.ajax({
        type: "POST",
        url: "hand_outDiamonds/handOutDiamonds",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            //輸出訊息
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            if(res['status']){
                $('#hand_outDiamonds_window').modal('hide');
            }
        },
        error: function (e) {
            console.log(e);
        }
    });
}

function getHandOutDiamondsRecordSpecific(id) { //取得鑽石發放紀錄(指定人顯示)
    convey_data = {
        id
    };

    $.ajax({
        type: "POST",
        url: "hand_outDiamonds/getHandOutDiamondsRecordSpecific",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            //輸出訊息
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        },
        error: function (e) {
            console.log(e);
        }
    });
}

function cancelHandOutDiamonds(id) { //取消鑽石發放
    var hint_text = "";

    $('#hint_text').html("");
    $('#hint_Title').text("取消鑽石發放提示");
    $('#hint_confirm').css('display', 'block');
    $('#hint_window').modal();
    $('#hint_text').append('<div class="form-group">' +
        '                        <label for="cancel_notifyTitle">取消通知標題</label>' +
        '                        <input type="text" class="form-control" id="cancel_notifyTitle">' +
        '                    </div>' +
        '                    <div class="form-group">' +
        '                        <label for="cancel_notifyContent">取消通知內容</label>' +
        '                        <textarea class="form-control" id="cancel_notifyContent" rows="3"></textarea>' +
        '                    </div>');

    $('#hint_confirm').unbind('click').bind('click', function (e) {
        convey_data = {
            id: id,
            messageTitle: $('#cancel_notifyTitle').val(),
            message: $('#cancel_notifyContent').val()
        };

        $.ajax({
            type: "POST",
            url: "hand_outDiamonds/cancelHandOutDiamonds",
            data: convey_data,
            dataType: 'json',
            success: function (res) {

                //改回提示視窗原先設定
                $('#hint_Title').text("提示");
                $('#hint_window').modal('hide');
                $('#hint_text').html("");
                $('#hint_confirm').css('display', 'none');

                //輸出成功訊息
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
}