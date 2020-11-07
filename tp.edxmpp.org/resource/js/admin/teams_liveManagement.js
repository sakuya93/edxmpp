$(document).ready(function () {
    getDate();
})

//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
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
    console.log(res);
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
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

// 詳細資訊
function operateFormatter(value, row, index) {
    return [
        '<a class="info" onclick="open_detail_window(\'' + row['id'] + '\')" title="詳細內容" style="color: #1592A6">',
        '<i class="fa fa-info-circle" style="font-size: 28px"></i>',
        '</a>  ',
    ].join('')
}

function createTeamsRoom(value, row, index) {
    return [
        '<a class="create" onclick="getTeamsLiveData(\'' + row['id'] + '\')" title="創建Teams教室" style="color: #0062cc">',
        '<i class="fa fa-plus-circle" style="font-size: 28px"></i>',
        '</a>  ',
    ].join('')
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        url: "teams_liveManagement/getLiveMatchTime/" + $("#date").val(),
        columns: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: '課程名稱',
                field: 'liveName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '上課老師',
                field: 'teacherName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '上課時間',
                field: 'matchTime',
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '匹配人數',
                field: 'matchPeople',
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '詳細資訊',
                field: 'operate',
                formatter: operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '創建Teams教室',
                field: 'operate',
                formatter: createTeamsRoom,
                rowspan: 1,
                sortable: true,
                align: 'center',
            },],
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

//  產生TABLE
$(function () {
    initTable()

    $('#locale').change(initTable)
    $('#date').change(initTable)
})

//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////


function open_detail_window(id) { //查看詳細直播課程視窗
    //open_detail_window('5e423ceaf3343')
    var convey_data = {id: id};

    $.ajax({
        type: "POST",
        url: "teams_liveManagement/getLiveMatchTimeDetail/" + id,
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            console.log(res);

            //修改值
            $('#liveName').val(res['matchData']['liveName']);
            $('#teacherName').val(res['matchData']['teacherName']);
            $('#matchTime').val(res['matchData']['matchTime']);
            //學生顯示
            var student_list = [];
            for (i = 0; i < res['student'].length; i++) {
                student_list += '<textarea class="student btn btn-outline-info mb-1 mr-1" onclick="copy(this)">' +
                    res['student'][i]['teamsAccount'] +
                    '</textarea>';
            }
            $('#student').html(student_list);

            $('#matchPeople').val(parseInt(res['matchData']['matchPeople']));
            $('#note').html(res['matchData']['note']);

            $('#teams_liveManagement_detail_window').modal();
        },
        error: function (res) {
            console.log(res);
        }
    });
}

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

    today = yyyy + '-' + mm + '-' + dd;
    $("#date").val(today)
}

function copy(obj) { //複製學生名單
    obj.select()
    document.execCommand("copy");
    obj.select()
}

function getTeamsLiveData(id) { //取得資料去創建一個Teams行事曆

    $.ajax({
        type: "POST",
        url: "teams_liveManagement/getTeamsLiveData/" + id,
        dataType: 'json',
        success: function (res) {
            // console.log(res);
            //呼叫創建Event API
            var tokenResponse = myMSALObj.acquireTokenSilent(requestObj);
        },
        error: function (res) {
            console.log(res);
        }
    });
}
