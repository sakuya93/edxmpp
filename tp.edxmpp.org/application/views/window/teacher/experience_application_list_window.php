<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="experience_application_list_window" class="modal bd-example-modal-xl" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document" id="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="E_A_L_title">體驗課程申請</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table
                        id="experienceApplicationTable"
                        data-search="true"
                        data-show-refresh="true"
                        data-show-toggle="true"
                        data-show-fullscreen="true"
                        data-show-columns="true"
                        data-detail-view="false"
                        data-show-export="true"
                        data-click-to-select="true"
                        data-detail-formatter="detailFormatter"
                        data-minimum-count-columns="2"
                        data-show-pagination-switch="true"
                        data-pagination="true"
                        data-id-field="id"
                        data-page-size="10"
                        data-page-list="[10, 25, 50, 100, all]"
                        data-show-footer="false"
                        data-url="../../live_courses/getExperienceClass"
                        data-response-handler="responseHandler">
                </table>
            </div>
            <div class="modal-footer">
                <button id="E_A_L_close" type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<!--  Bootstrap table  -->
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>

<script>
    var experienceApplicationTable = $("#experienceApplicationTable");

    function experienceApplicationClassPhoto(value, row, index) {
        return [
            '<img class="btn btn-primary" width="64" src="../../resource/image/student/photo/' + row['memberPhoto'] + '">',
        ].join('')
    }

    function experienceApplicationContact(value, row, index){
        return [
            "<button type=\"button\" class=\"btn btn-outline-primary col-sm-12\" onclick=\""+ row['contact']+"\">聯繫</button>",
        ].join('')
    }

    function buildExperienceApplicationTable(){
        experienceApplicationTable.bootstrapTable('destroy').bootstrapTable({
            columns: [
                [{
                    title: '申請人頭貼',
                    formatter: experienceApplicationClassPhoto,
                    rowspan: 1,
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                },{
                    title: '申請人姓名',
                    field: 'memberName',
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
                },{
                    title: '聯絡',
                    formatter: experienceApplicationContact,
                    rowspan: 1,
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                },]
            ]
        })
    }
</script>

</body>
</html>
<?php
