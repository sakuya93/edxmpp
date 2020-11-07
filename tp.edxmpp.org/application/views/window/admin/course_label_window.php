<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="course_label_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="course_label_Title">課程標籤新增</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="course_label_body">
                <span><b>標籤名稱 : </b></span>
                <input type="text" class="form-control" id="course_label_name" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button id="add_confirm" type="button" class="btn btn-primary">新增</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
