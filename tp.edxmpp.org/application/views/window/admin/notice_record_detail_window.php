<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="notice_record_detail_window" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notice_record_detail_Title">通知詳細資訊</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="notice_record_detail_text">
                <h6><b>通知對象 : </b></h6>
                <select class="form-control col-sm-6" id="notice_object_detail" disabled="disabled">
                    <option value="0" selected>全體</option>
                    <option value="1">學生</option>
                    <option value="2">老師</option>
                    <option value="3">特定會員</option>
                    <option value="4">特定老師</option>
                    <option value="5">特定影片課程</option>
                    <option value="6">特定直播課程</option>
                </select>

                <br>
                <h6><b>寄信或通知: </b></h6>
                <select class="form-control col-sm-6" id="email_or_notice_detail" disabled="disabled">
                    <option value="0" selected>寄信和通知</option>
                    <option value="1">寄信</option>
                    <option value="2">通知</option>
                </select>

                <br>
                <h6><b>訊息標題 : </b></h6>
                <input type="text" class="form-control" id="message_title_detail" autocomplete="off" readonly="readonly">

                <br>
                <h6><b>訊息內容 : </b></h6>
                <div id="message_detail"></div>

                <br>
                <h6><b>通知時間 : </b></h6>
                <input type="text" class="form-control" id="date_detail" autocomplete="off" readonly="readonly">
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
