<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="teams_liveManagement_detail_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teams_liveManagement_detail_Title">直播課程詳細內容</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="teams_liveManagement_detail_text">
                <span><b>課程名稱 : </b></span>
                <input type="text" class="form-control" id="liveName" autocomplete="off" readonly="readonly">
                <span><b>上課老師 : </b></span>
                <input type="text" class="form-control" id="teacherName" autocomplete="off" readonly="readonly">
                <span><b>上課時間 : </b></span>
                <input type="text" class="form-control" id="matchTime" autocomplete="off" readonly="readonly">
                <span><b>上課學生名單 : </b></span>
                <span class="modal-body__inner-body align-items-center d-flex flex-wrap" id="student">

                </span>
                <span><b>匹配人數 : </b></span>
                <input class="form-control" id="matchPeople" autocomplete="off" readonly="readonly">
                <span><b>課程備注 : </b></span>
                <span class="modal-body__inner-body" id="note" readonly="readonly"></span>
            </div>
            <div class="modal-footer">
                <button type="button" id="complete" class="btn btn-primary" data-dismiss="modal">教室設定完成</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
