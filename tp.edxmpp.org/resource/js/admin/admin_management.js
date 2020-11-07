$(document).ready(function () {

});

function status() {
    var s = $('#status').val();
    var url = "";
    if(s == "未審核"){
        url = "teacherCheck/getNotCheck";
        $("#table").attr("data-url", url);
    }else if(s == "已審核"){
        url = "teacherCheck/getCheck";
        $("#table").attr("data-url", url);
    }else if(s == "禁止"){
        url = "teacherCheck/getBanCheck";
        $("#table").attr("data-url", url);
    }else if(s == "前台管理員"){
        url = "teacherCheck/getDesignatedAdministrator";
        $("#table").attr("data-url", url);
    }

    $("#table").bootstrapTable('refreshOptions',{
        url: url,
        silent: true
    });
}

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
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

// 詳細資訊
function operateFormatter(value, row, index) {
    return [
        '<a class="info" href="javascript:void(0)" title="Info">',
        '<i class="fa fa-info-circle" style="font-size: 28px"></i>',
        '</a>  ',
    ].join('')
}

//  事件
window.operateEvents = {
    'click .info': function (e, value, row, index) {
        var url = "teacherCheck_detail/" + row['id'];
        window.open(url);
    },
    'click .remove': function (e, value, row, index) {
        $table.bootstrapTable('remove', {
            field: 'id',
            values: [row.id]
        })
    }
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

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        columns: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 2,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'id',
                rowspan: 2,
                align: 'center',
                valign: 'middle',
                width: '200',
                sortable: true,
            }, {
                title: '會員姓名',
                field: 'name',
                rowspan: 2,
                align: 'center',
                valign: 'middle',
                width: '200',
                sortable: true,
            }, {
                title: '資訊',
                colspan: 3,
                align: 'center'
            }],
            [{
                title: '國籍',
                field: 'country',
                sortable: true,
                width: '300',
                align: 'center'
            }, {
                title: '會說語言',
                field: 'speakLanguage',
                sortable: true,
                align: 'center',
            },{
                field: 'operate',
                title: '詳細資訊',
                align: 'center',
                clickToSelect: false,
                events: window.operateEvents,
                formatter: operateFormatter
            }]
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
$(function() {
    initTable()

    $('#locale').change(initTable)
})
//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////
