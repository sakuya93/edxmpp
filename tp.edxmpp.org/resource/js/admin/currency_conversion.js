//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function sel_notice_object() {
    var s = $('#sel_notice_object').val();
    var url = "notice_record/getNoticeRecord/" + s;

    $("#table").attr("data-url", url);

    $("#table").bootstrapTable('refreshOptions', {
        url: url,
        silent: true
    });
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
        '<a class="info" onclick="open_detail_window(' + row['id'] + ')" title="詳細內容" style="color: #007bff">',
        '<i class="fa fa-info-circle" style="font-size: 28px"></i>',
        '</a>  ',
    ].join('')
}

//通知對象
function notice_object_operateFormatter(value, row, index) {
    if (value == 0) {
        return "全體";
    }
    else if (value == 1) {
        return "學生";
    }
    else if (value == 2) {
        return "老師";
    }
    else if (value == 3) {
        return "特定會員";
    }
    else if (value == 4) {
        return "特定老師";
    }
    else if (value == 5) {
        return "特定影片課程";
    }
    else if (value == 6) {
        return "特定直播課程";
    }
}

//寄信或通知
function email_or_notice_operateFormatter(value, row, index) {
    if (value == 0) {
        return "寄信與通知";
    }
    else if (value == 1) {
        return "寄信";
    }
    else if (value == 2) {
        return "通知";
    }
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        onLoadSuccess: function () {
            initSwitch();
        },
        columns: [
            [{
                title: '貨幣',
                field: 'currency',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '轉換貨幣',
                field: 'toCurrency',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '匯率',
                field: 'exchangeRate',
                rowspan: 1,
                sortable: true,
                align: 'center',
            }],
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

function initSwitch() {
    $('.auto_update_status').bootstrapSwitch({
        onText: "開啟",
        offText: "關閉",
        onColor: "success",
        offColor: "warning",
        size: "small",

        onSwitchChange: function (event, state) {
            if (state == true) {
                console.log("ON");
                $.ajax({
                    type: "POST",
                    url: "currency_conversion/automatic_update_exchange_rate_controller",
                    // data: convey_data,
                    dataType: 'json',
                    success: function (res) {
                        $('#hint_text').text(res['msg']);
                        $('#hint_window').modal();

                        if (res['status']) {
                            $('#hint_window').on('hidden.bs.modal', function () {
                                // console.log("轉換的幣值為1:" + res['exchange_rate']);
                                // initTable();
                            })
                        }
                    }
                });
            } else {
                console.log("OFF");
                $.ajax({
                    type: "POST",
                    url: "currency_conversion/automatic_update_exchange_rate_controller",
                    // data: convey_data,
                    dataType: 'json',
                    success: function (res) {
                        $('#hint_text').text(res['msg']);
                        $('#hint_window').modal();

                        if (res['status']) {
                            $('#hint_window').on('hidden.bs.modal', function () {
                                // console.log("轉換的幣值為1:" + res['exchange_rate']);
                                // initTable();
                            })
                        }
                    }
                });
            }
        }
    })
}

//  產生TABLE
$(function () {
    initSwitch();
    initTable();

    $('#locale').change(initTable)
})

//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////

function update_exchange_rate() { //更新匯率
    $.ajax({
        type: "POST",
        url: "currency_conversion/update_exchange_rate",
        // data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            if (res['status']) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    console.log("轉換的幣值為1:" + res['exchange_rate']);
                    // initTable();
                })
            }
        }
    });
}