var $table = $('#table')
var $remove = $('#remove')
var selections = []

$(document).ready(function () {
    getDate(); //取得今天日期

    var reportIdentify__select = $("#reportIdentify"); //檢舉對象。 老師|學生、會員
    var date = $("#date"); //選擇日期(預設為今日日期)。

    buildClassReport(
        date.val(),
        reportIdentify__select.val(),
        getReportMasterTableColumns(reportIdentify__select.val())
    ); //生成Master table

    $('input[name=reportManagementOption__control]').change(function () {
        var options = $(this).val().split('|');

        $("#reportIdentify").empty(); //清空選項
        for (var i = 0; i < options.length; i++) { //對象 插入option元素
            if (i == 0) {
                jQuery('<option>', {
                    text: getReportManagementOption(options[i]),
                    value: options[i],
                    selected: true
                }).appendTo("#reportIdentify");
            } else {
                jQuery('<option>', {
                    text: getReportManagementOption(options[i]),
                    value: options[i],
                }).appendTo("#reportIdentify")
            }
        }
        buildClassReport(
            date.val(),
            reportIdentify__select.val(),
            getReportMasterTableColumns(reportIdentify__select.val())
        );
    });

    $('#locale')
        .change(function () {
            buildClassReport(
                date.val(),
                reportIdentify__select.val(),
                getReportMasterTableColumns(reportIdentify__select.val())
            )
        });

    $('#date')
        .change(function () {
            buildClassReport(
                date.val(),
                reportIdentify__select.val(),
                getReportMasterTableColumns(reportIdentify__select.val())
            )
        });

    $('#reportIdentify')
        .change(function () {
            buildClassReport(
                date.val(),
                reportIdentify__select.val(),
                getReportMasterTableColumns(reportIdentify__select.val())
            );
        });
});


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


//////////////////////////////// Master Table START
function detailFormatter(index, row) {
    var html = []

    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

function operateFormatter(value, row, index) {
    var url = "teacherCheck_detail/"; //根據選擇身分而跳轉的網址。 老師:teacherCheck_detail , 學生:member_management_detail
    if (
        $("#reportIdentify").val() == "getStudentReport" ||
        $("#reportIdentify").val() == "getReportRecord"
    ) {
        url = "member_management_detail/";
    }

    return [
        '<a class="btn btn-outline-primary mb-2" href="' + url + row['managementID'] + '" title="管理" target="_blank">',
        '管理',
        '</a>',
    ].join('')
}

function initTable(url, data, columns) {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        data: data,
        columns: columns,
        onExpandRow: function (index, row, $detail) {
            /* eslint no-use-before-define: ["error", { "functions": false }]*/
            buildClassReportDetail(url, index, row, $detail);
        }
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

//////////////////////////////// Master Table END


//////////////////////////////// Detail table START
function subTablePhoto(value, row, index) {
    return [
        '<img class="rounded m-auto d-block" width="64" src="resource/image/student/photo/' + row['reportPhoto'] + '">',
    ].join('')
}

function subTableReportCourseOperate(value, row, index) {
    return [
        '<a class="btn btn-outline-primary mb-2" href="' + row['coursePath'] + '" title="前往課程頁面" target="_blank">',
        '前往課程頁面',
        '</a>  ',
        '<a class="btn btn-outline-info" href="dashboard/' + row['report'] + '/1" title="前往檢舉人頁面" target="_blank">',
        '前往檢舉人頁面',
        '</a>  ',
    ].join('');
}

function subTableReportUserOperate(value, row, index) {

    if(row['courseID'] != null){
        row['coursePath'] = row['coursePath'].replace("null", row['courseID']);
        return [
            '<a class="btn btn-outline-info mb-2" href="' + row['coursePath'] + '" title="前往課程" target="_blank">',
                '前往課程',
            '</a>  ',
            '<a class="btn btn-outline-info" href="' + row['reportedPath'] + row['reportedID'] + '/1" title="前往檢舉人頁面" target="_blank">',
                '前往檢舉人頁面',
            '</a>  ',
        ].join('');
    }else {
        return [
            '<a class="btn btn-outline-info" href="' + row['reportedPath'] + row['reportedID'] + '/1" title="前往檢舉人頁面" target="_blank">',
            '前往檢舉人頁面',
            '</a>  ',
        ].join('');
    }

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
        columns: columns,
    });
};

//////////////////////////////// Detail table END

function buildClassReport(date, url, columns, subTableUrl) { //建立主表資料
    convey_data = {
        date: date,
    };

    $.ajax({
        type: "POST",
        url: "report_management/" + url,
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var data = res;
            for (var i = 0; i < data.length; i++) {
                data[i]['option'] = getReportOption(data[i]['option']);
            }
            console.log(data);
            initTable(url, data, columns);
        }
    });
}

function buildClassReportDetail(masterTableUrl, index, row, $detail) { //建立子表資料
    var subTable = {
        url: {
            getClassReport: "getClassReportDetail",
            getStudentReport: "getClassReportDetail",
            getReportRecord: "getReportRecordDetail",
        },
        id: {
            getClassReport: row['reported'],
            getStudentReport: row['reported'],
            getReportRecord: row['report'],
        },
        keys: {
            getClassReport: "reported",
            getStudentReport: "reported",
            getReportRecord: "report",
        }
    };

    var convey_data = {};
    convey_data[subTable['keys'][masterTableUrl]] = subTable['id'][masterTableUrl];
    console.log(convey_data);

    $.ajax({
        type: "POST",
        url: "report_management/" + subTable["url"][masterTableUrl],
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var data = res;
            for (var i = 0; i < data.length; i++) {
                console.log(data[i]['coursePath']);
                data[i]['option'] = getReportOption(data[i]['option']);
                data[i]['coursePath'] += data[i]['courseID'] + "?c=TWD"
            }
            console.log(data);
            var columns = getReportSubTableColumns(masterTableUrl);
            InitSubTable(index, row, $detail, data, columns);
        }
    });
}

function getReportOption(option) { //取得被檢舉的選項
    var data = {
        1: "帶有傷害或人身攻擊的言論",
        2: "學生擾亂上課秩序",
        3: "學生發布不實消息",
        4: "老師未依照正確時間上課",
        5: "老師無故提早下課",
        6: "老師不認真上課",
        7: "老師實際上課內容與課程介紹不符",
        8: "老師課程搜尋關鍵詞用與課程無關",
        9: "上課老師與課程老師不同人"
    };
    return data[option];
}

function getReportManagementOption(option) { //取得檢舉管理類型的檢舉對象
    var data = {
        getClassReport: "老師",
        getStudentReport: "學生",
        getReportRecord: "會員"
    };
    return data[option];
}

function getReportMasterTableColumns(option) { //根據檢舉管理類型去取得table欄位。 被檢舉管理、檢舉紀錄管理
    var columns = {
        getClassReport: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'reported',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '被檢舉人',
                field: 'reportedName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近被檢舉的選項',
                field: 'option',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近被檢舉的內容',
                field: 'content',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '時間',
                field: 'date',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '工具',
                field: 'operate',
                formatter: operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }]
        ],
        getStudentReport: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'reported',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '被檢舉人',
                field: 'reportedName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近被檢舉的選項',
                field: 'option',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近被檢舉的內容',
                field: 'content',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '時間',
                field: 'date',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '工具',
                field: 'operate',
                formatter: operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }]
        ],
        getReportRecord: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'reported',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '檢舉人',
                field: 'reportedName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近檢舉的選項',
                field: 'option',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '最近檢舉的內容',
                field: 'content',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '時間',
                field: 'date',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
            }, {
                title: '工具',
                field: 'operate',
                formatter: operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }]
        ],
    };

    return columns[option];
}

function getReportSubTableColumns(option) { //根據檢舉管理類型去取得table欄位。 被檢舉管理、檢舉紀錄管理
    var columns = {
        getClassReport: [{
            checkbox: true
        }, {
            field: 'report',
            title: 'id'
        }, {
            field: 'reportName',
            title: '檢舉人'
        }, {
            field: 'identify',
            formatter: subTablePhoto,
            title: '檢舉人照片'
        }, {
            field: 'option',
            title: '檢舉選項'
        }, {
            field: 'content',
            title: '檢舉內容'
        }, {
            field: 'date',
            title: '時間',
            sortable: true,
        }, {
            title: '工具',
            field: 'operate',
            formatter: subTableReportCourseOperate,
            rowspan: 1,
            align: 'center',
        }],
        getStudentReport: [{
            checkbox: true
        }, {
            field: 'report',
            title: 'id'
        }, {
            field: 'reportName',
            title: '檢舉人'
        }, {
            field: 'identify',
            formatter: subTablePhoto,
            title: '檢舉人照片'
        }, {
            field: 'option',
            title: '檢舉選項'
        }, {
            field: 'content',
            title: '檢舉內容'
        }, {
            field: 'date',
            title: '時間',
            sortable: true,
        }, {
            title: '工具',
            field: 'operate',
            formatter: subTableReportCourseOperate,
            rowspan: 1,
            align: 'center',
        }],
        getReportRecord: [{
            checkbox: true
        }, {
            field: 'reportedID',
            title: 'id'
        }, {
            field: 'reportName',
            title: '被檢舉人'
        }, {
            field: 'identify',
            formatter: subTablePhoto,
            title: '被檢舉人照片'
        }, {
            field: 'option',
            title: '被檢舉選項'
        }, {
            field: 'content',
            title: '被檢舉內容'
        }, {
            field: 'date',
            title: '時間',
            sortable: true,
        }, {
            title: '工具',
            field: 'operate',
            formatter: subTableReportUserOperate,
            rowspan: 1,
            align: 'center',
        }],
    };

    return columns[option];
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

    month = yyyy + '-' + mm;
    $("#date").val(month)
}

function renameKeys(obj, newKeys) { //rename object key name
    const keyValues = Object.keys(obj).map(key => {
        const newKey = newKeys[key] || key;
        return {[newKey]: obj[key]};
    });
    return Object.assign({}, ...keyValues);
}