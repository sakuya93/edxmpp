<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="block_member_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="block_member_Title">封鎖會員</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="block_member_text">
                <span><b>封鎖原因 : </b></span>
                <input type="text" class="form-control" id="blocking_reason" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button id="block_member_confirm" type="button" class="btn btn-primary">確定</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
