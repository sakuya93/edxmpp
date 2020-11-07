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
            }, {
                title: 'id',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: 'title',
                field: 'title',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: 'option',
                field: 'option',
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: 'key_words',
                field: 'key_words',
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
})

//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////

function add_option() {
    $('#course_options_window').modal();

    $('#add_confirm').unbind("click").bind('click', function () {
        var convey_data = {
            title: document.getElementById('option_Title').value,
            option: document.getElementById('option').value,
            key_words: document.getElementById('key_words').value
        };

        $.ajax({
            type: "POST",
            url: "course_options/addOption",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#course_options_window').modal('hide');
                        document.getElementById('option_Title').value = "";
                        document.getElementById('option').value = "";
                        document.getElementById('key_words').value = "";
                        initTable();
                    })
                }
            }
        });
    })
}

function del_option() {
    var select_data = getIdSelections(); //獲取選擇的資料

    if (select_data.length != 0) { //有選取資料時
        var convey_data = {};

        for (var i = 0; i < select_data.length; i++) {
            convey_data[i] = {id: select_data[i]};
        }

        $('#confirm').css('display', 'block');
        $('#hint_text').text("你確定要刪除這" + select_data.length + "筆資料嗎?");
        $('#hint_window').modal();

        $('#confirm').unbind("click").bind('click', function () {
            $.ajax({
                type: "POST",
                url: "course_options/deleteOption",
                data: convey_data,
                dataType: 'json',
                success: function (res) {
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                    $('#confirm').css('display', 'none');

                    if (res['status']) {
                        $('#hint_window').on('hidden.bs.modal', function () {
                            $('#course_options_window').modal('hide');
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