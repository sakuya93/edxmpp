function status() {
    var s = $('#status').val();
    var url = "";
    if (s == "0") {
        url = "member_management/getMemberData/0";
        $("#table").attr("data-url", url);
    } else if (s == "1") {
        url = "member_management/getMemberData/1";
        $("#table").attr("data-url", url);
    }

    $("#table").bootstrapTable('refreshOptions', {
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

// 圖片格式化
function picFormatter(value, row, index) {
    if (row['photo'] != null) {
        return [
            '<img width="100" src="resource/image/student/photo/' + row['photo'] + '?value=' + Math.random() + '">'
        ].join('')
    }
    else {
        return [
            '<img width="100" src="resource/image/student/photo/noPhoto.jpg?value=' + Math.random() + '">'
        ].join('')
    }

}

//  事件
window.operateEvents = {
    'click .info': function (e, value, row, index) {
        var url = "member_management_detail/" + row['id'];
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
                title: 'id',
                field: 'id',
                rowspan: 2,
                align: 'center',
                valign: 'middle',
                width: '300',
                sortable: true,
            }, {
                title: '會員姓名',
                field: 'name',
                rowspan: 2,
                align: 'center',
                valign: 'middle',
                width: '300',
                sortable: true,
            }, {
                title: '學生頭貼',
                field: 'photo',
                rowspan: 2,
                formatter: picFormatter,
                align: 'center',
                valign: 'middle',
                width: '300',
                sortable: true,
            }, {
                title: '詳細資訊',
                field: 'operate',
                rowspan: 2,
                clickToSelect: false,
                events: window.operateEvents,
                formatter: operateFormatter,
                align: 'center',
                valign: 'middle',
            }],
            []
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
})
//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////