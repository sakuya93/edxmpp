var $table = $('#table')
var $remove = $('#remove')
var selections = []

$(document).ready(function () {
    getDate(); //取得今天日期

    var date = $("#date").val(); //選擇日期(預設為今日日期)。
    get_payment_history(date); //取得鑽石付款紀錄

    $('.display_sel_history')
        .change(function () {
            if ($("input[name='reportManagementOption__control']:checked").val() == 'diamond_StoredValue') {
                get_payment_history($("#date").val()); //取得鑽石付款紀錄
            } else {
                get_payment_history_class($("#date").val()); //取得課程購買紀錄
            }
        });
    $('#locale')
        .change(function () {
            if ($("input[name='reportManagementOption__control']:checked").val() == 'diamond_StoredValue') {
                get_payment_history($("#date").val()); //取得鑽石付款紀錄
            } else {
                get_payment_history_class($("#date").val()); //取得課程購買紀錄
            }
        });

    $('#date')
        .change(function () {
            if ($("input[name='reportManagementOption__control']:checked").val() == 'diamond_StoredValue') {
                get_payment_history($("#date").val()); //取得鑽石付款紀錄
            } else {
                get_payment_history_class($("#date").val()); //取得課程購買紀錄
            }
        });

    //取得平台抽成及賺取金額
    $.ajax({
        type: "POST",
        url: "payment_history/getPlatformEarn",
        dataType: 'json',
        success: function (res) {
            if( typeof res[0]['draw_into'] !== 'undefined'){
                $("#inputSalesCommission").val(res[0]['draw_into']);
            }
            $("#earned_amount").val(res[0]['earned_amount']);
        }
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

function checkingOrder(order) { //查詢訂單
    convey_data = {
        order: order,
    };

    $.ajax({
        type: "POST",
        url: "payment_history/checkingOrder",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            alert(res['msg']);
        }
    });
}

//////////////////////////////// Master Table Start

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
        '<a class="btn btn-outline-primary mb-2" onclick="checkingOrder(\'' + row['id'] + '\')" title="" target="_blank">',
        '設定為已付款',
        '</a>',
    ].join('')
}

function tablePhoto(value, row, index) { // user img
    return [
        '<img class="rounded m-auto d-block" width="64" src="resource/image/student/photo/' + row['photo'] + '">',
    ].join('')
}

function tableStatus(value, row, index) {
    if (row['status'] == 1) {
        return [
            '<span>已付款</span>',
        ].join('')
    } else {
        return [
            '<span>未付款</span>',
        ].join('')
    }
}

function initTable(detailView, option, data) { // 生成 Master Table
    let dataColumns = getColumns(option);

    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        data: data,
        columns: dataColumns,
        detailView,
        onExpandRow: function (index, row, $detail) { // 生成Detail Table
            if (option == "option__BuyCourse") { // 選項為購買課程時生成子表
                buildSubTableOfPaymentHistory(index, row, $detail); //生成子表
            }
        },
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

function get_payment_history(date) { //取得付款紀錄並呼叫生成master table
    convey_data = {
        date: date,
    };

    $.ajax({
        type: "POST",
        url: "payment_history/getPaymentHistoryPoint",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            initTable(false, "", res);
        }
    });
}

function get_payment_history_class(date) { //取得課程購買紀錄並呼叫生成master table
    convey_data = {
        date: date,
    };

    $.ajax({
        type: "POST",
        url: "payment_history/getPaymentHistoryClass",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            initTable(true, "option__BuyCourse", res);
        }
    });
}

function getColumns(option) {
    if(option == "option__BuyCourse"){
        return [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: '訂單編號',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '姓名',
                field: 'name',
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
                title: '項目',
                field: 'project',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '老師實際獲取金額',
                field: 'price',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '平台抽成金額',
                field: 'drawInto',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '平台抽成%數',
                field: 'scale',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '狀態',
                field: 'status',
                formatter: tableStatus,
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
                title: '功能',
                formatter: operateFormatter,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }]
        ];
    }else{
        return [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: '訂單編號',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '姓名',
                field: 'name',
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
                title: '項目',
                field: 'project',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '價錢',
                field: 'price',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '狀態',
                field: 'status',
                formatter: tableStatus,
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
                title: '功能',
                formatter: operateFormatter,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }]
        ];
    }
}

//////////////////////////////// Master Table End

function clearExpiredPaymentHistory() {
    $.ajax({
        type: "POST",
        url: "payment_history/clearExpiredPaymentHistory",
        success: function (res) {
            $("#hint_text").text("清除成功!");
            $("#hint_window").modal();
        },
        error: function (res) {
            $("#hint_text").text("清除失敗!");
            $("#hint_window").modal();
        }
    });

    $.ajax({
        type: "POST",
        url: "payment_history/getPaymentHistoryClass",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            initTable(res);
        }
    });
}

//////////////////////////////// Sub Table Start
function subTablePhoto(value, row, index) { //
    return [
        '<img class="rounded m-auto d-block" width="64" src="' + row['imagePath'] + '/' + row['image'] + '">',
    ].join('')
}

InitSubTable = function (index, row, $detail, data, columns) { //生成子表
    var reported_id = row.reported;
    var cur_table = $detail.html('<table></table>').find('table');

    $(cur_table).bootstrapTable({
        data: data,
        clickToSelect: true,
        uniqueId: "date",
        pageSize: 10,
        pageList: [10, 25],
        columns: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            },{
                title: '課程ID',
                field: 'courseID',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '課程名稱',
                field: 'courseName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '課程類別',
                field: 'courseType',
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
                title: '課程縮圖',
                formatter: subTablePhoto,
                field: 'edit_4',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            },]
        ],
    });
};
function buildSubTableOfPaymentHistory(index, row, $detail, data) {
    convey_data = {
        order: row['id'],
    };

    $.ajax({
        type: "POST",
        url: "payment_history/getOrderClassData",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            console.log(res);
            InitSubTable(index, row, $detail, res); //生成子表
        },
        error: function (e) {
            console.log(e);
        }
    });

}

//////////////////////////////// Sub Table End


// Set sales commission
function setSalesCommisstion() {
    let per = $("#inputSalesCommission").val();
    convey_data = {
        per: per,
    };

    if(per >= 0 && per <= 100) {
        $.ajax({
            type: "POST",
            url: "payment_history/setSalesCommisstion",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                //Reload page?
                console.log(res);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
}