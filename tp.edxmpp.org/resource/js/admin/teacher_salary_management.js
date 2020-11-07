var $table = $('#table')
var $remove = $('#remove')
var selections = []

var data_status = 0; //(預設為顯示未匯款之資料)

$(document).ready(function () {
    getDate(); //取得今天日期

    var date = $("#date").val(); //選擇日期(預設為今日日期)。

    getSalaryData(data_status); //取得薪資申請資料(預設為顯示未匯款之資料)

    $('#locale')
        .change(function () {
            getSalaryData(data_status); //取得薪資申請資料
        });

    $("#status")
        .change(function () {
            data_status = $('#status').val();
            getSalaryData(data_status); //取得薪資申請資料
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

//////////////////////////////// Bootstrap Table Start

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

function detailFormatter(index, row) {
    var html = []

    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

function operateFormatter(value, row, index) {
    return [
        '<i class="fa fa-info-circle" style="font-size:24px;cursor: pointer" onclick="openSalaryDetailWindow(\'' + row['id'] + '\');" title="詳細資訊"></i>',
    ].join('')
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
                title: '薪資ID',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '老師ID',
                field: 'teacherID',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '老師姓名',
                field: 'teacherName',
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
                title: '匯款金額(美金/USD)',
                field: 'price',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '詳細',
                formatter: operateFormatter,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
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

function getSalaryData(status) { // 取得薪資申請紀錄
    convey_data = {
        status: status,
    };

    $.ajax({
        type: "POST",
        url: "teacher_salary_management/getSalaryData",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            initTable(res);
        }
    });
}

function openSalaryDetailWindow(id) { // 顯示詳細資訊
    convey_data = {
        id: id,
    };

    $.ajax({
        type: "POST",
        url: "teacher_salary_management/getSalaryDetail",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            console.log(res);

            $('#teacher_photo').attr("src", "resource/image/student/photo/" + res[0]['photo'] + "?v=" + Math.random());
            $('#teacher_name').val(res[0]['teacherName']);
            $('#remittance_name').val(res[0]['name']);
            $('#remittance_code').val(res[0]['code']);
            $('#remittance_account').val(res[0]['account']);
            $('#remittance_account_name').val(res[0]['accountName']);
            $('#remittance_money').val(res[0]['price']);
            $('#teacher_salary_management_window').modal();

            $('#remittance_confirm').attr("onclick", "remittance(" + id + ")");
        }
    });

    // $("#hint_Title").text(data + "的薪資詳細資訊");
    // $("#hint_text").text("詳細內容");
    // var onclickContent = "confirmRemittance('" + data + "')";
    // $("#confirm")
    //     .text("確認匯款")
    //     .attr("onclick", onclickContent)
    //     .css("display", "block");
    // $("#hint_window").modal();
}

function remittance(id) { //匯款
    convey_data = {
        id: id,
    };

    $.ajax({
        type: "POST",
        url: "teacher_salary_management/updateSalaryStatus",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $("#hint_text").text(res['msg']);
            $("#hint_window").modal();

            if (res['status']) {
                $('#teacher_salary_management_window').modal("hide");
            }
        }
    });
}

//////////////////////////////// Bootstrap Table End