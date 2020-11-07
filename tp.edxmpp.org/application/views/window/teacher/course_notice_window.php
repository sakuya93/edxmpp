<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="course_notice_window" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="course_notice_Title">課程通知新增區</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="course_notice_text">
                <h6><b>通知對象 : </b></h6>
                <select class="form-control col-sm-6" id="notice_object" disabled>
                    <option value="0" selected>全體</option>
                    <option value="1">學生</option>
                    <option value="2">老師</option>
                    <option value="3">特定會員</option>
                    <option value="4">特定老師</option>
                    <option value="5">特定影片課程</option>
                    <option value="6">特定直播課程</option>
                    <option value="7">特定募資課程</option>
                </select>

                <br>
                <h6><b>特定ID : </b><i class="fa fa-info-circle" title="該如何取得ID呢?" id="specific_ID_hint_icon" style="font-size: 20px;cursor: pointer"></i></h6>
                <input type="text" class="form-control" id="specific_ID" autocomplete="off">

                <!--                特定ID提示區-->
                <div class="specific_ID_hint_area">
                    <h3 id="specific_ID_Title"></h3>
                    <p id="specific_ID_hint">

                    </p>
                    <img id="specific_ID_img" src=""
                         alt="specific_ID_img_error" width="1000">
                </div>

                <br>
                <h6><b>寄信或通知: </b></h6>
                <select class="form-control col-sm-6" id="email_or_notice">
                    <option value="0" selected>寄信和通知</option>
                    <option value="1">寄信</option>
                    <option value="2">通知</option>
                </select>

                <br>
                <h6><b>訊息標題 : </b></h6>
                <input type="text" class="form-control" id="message_title" autocomplete="off">

                <br>
                <h6><b>傳送訊息 : </b></h6>
                <textarea name="editor1" id="editor1" rows="10" cols="80"></textarea>
            </div>
            <div class="modal-footer" id="courseNoticeFooter">
                <button id="add_confirm" type="button" class="btn btn-primary">確認新增通知</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace("editor1");
    $('#course_notice_window').on('shown.bs.modal', function () {
        $(document).off('focusin.modal');
    });

</script>

</body>
</html>
<?php
