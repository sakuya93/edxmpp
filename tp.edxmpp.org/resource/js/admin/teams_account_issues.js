//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function sel_issues_status() {
    var s = $('#sel_issues_status').val();
    var url = "teams_account_issues/getTeacherData/" + s;

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

// 發放帳號
function operateFormatter(value, row, index) {
    return [
        '<a class="info" onclick="open_account_issue_window(\'' + row['id'] + '\')" title="發放帳號" style="color: #007bff">',
        '<i class="fa" style="font-size: 28px;color: black">&#xf2b5;</i>',
        '</a>  ',
    ].join('')
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
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
                title: '老師名稱',
                field: 'teacherName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: 'Teams帳號',
                field: 'teamsAccount',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: 'applicationKey',
                field: 'applicationKey',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            },{
                title: 'listKey',
                field: 'listKey',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            },{
                title: '發放按鈕',
                field: 'operate',
                formatter: operateFormatter,
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

//打開發放帳號視窗
function open_account_issue_window(id) {
    $('#teams_account_issues_window').modal();
    $('#teamsAccount').val("");
    $('#teamsPassword').val("");
    $('#teamsPassword').val("");
    $("#applicationKey").val("");

    $('#teams_account_issues_add_confirm').unbind("click").bind('click', function () {
        $('#loading_text').text("Teams帳號信件發送中，請稍後..."); //顯示loading視窗
        $('.loading').css("display","inline-block"); //顯示loading視窗

        var convey_data = {
            id: id,
            teamsAccount: $('#teamsAccount').val(),
            teamsPassword: $('#teamsPassword').val(),
            applicationKey: $("#applicationKey").val(),
            listKey: $("#listKey").val(),
        };

        console.log(convey_data);

        $.ajax({
            type: "POST",
            url: "teams_account_issues/freedAccount",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('.loading').css("display","none"); //隱藏loading視窗
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#teams_account_issues_window').modal('hide');

                        initTable();
                    })
                }
            }
        });
    });
}