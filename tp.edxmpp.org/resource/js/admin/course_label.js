//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function getIdSelections(field) {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row[field]
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
            },{
                title: '標籤',
                field: 'label',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
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
})

//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////

function addLabel() {
    $('#course_label_window').modal();

    $('#add_confirm').unbind("click").bind('click', function () {
        var convey_data = {
            label: document.getElementById('course_label_name').value,
        };

        $.ajax({
            type: "POST",
            url: "course_label/addLabel",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#hint_window').on('hidden.bs.modal', function () {
                        document.getElementById('course_label_name').value = "";
                        $('#course_label_window').modal("hide");
                        initTable();
                    })
                }
            }
        });
    })
}

function deleteLabel() {
    var select_data = getIdSelections("label"); //獲取選擇的資料

    if (select_data.length != 0) { //有選取資料時
        var convey_data = {};

        for (var i = 0; i < select_data.length; i++) {
            convey_data[i] = {
                label: select_data[i]
            };
        }

        $('#confirm').css('display', 'block');
        $('#hint_text').text("你確定要刪除這" + select_data.length + "筆資料嗎?");
        $('#hint_window').modal();

        $('#confirm').unbind("click").bind('click', function () {
            $.ajax({
                type: "POST",
                url: "course_label/deleteLabel",
                data: convey_data,
                dataType: 'json',
                success: function (res) {
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                    $('#confirm').css('display', 'none');

                    if (res['status']) {
                        $('#hint_window').on('hidden.bs.modal', function () {
                            $('#course_label_window').modal('hide');
                            initTable();
                        })
                    }
                }
            });
        });

        $('#close').unbind("click").bind('click', function () {
            $('#confirm').css('display', 'none');
        });
    }
    else {
        $('#hint_text').text("請至少選取一筆要刪除的資料");
        $('#hint_window').modal();
    }
}