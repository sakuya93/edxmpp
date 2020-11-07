<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="designated_contact_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="designated_contact_Title">指定聯絡人</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="designated_contact_text">
                對方ID:
                <input type="text" class="form-control" id="designated_contact_id" onchange="input_ID_event(this.value)"
                       placeholder="ID">
                訊息:
                <input type="text" class="form-control" id="designated_contact_message" placeholder="訊息" disabled>

                <div class="contact_person_data_area">
                    <h4>聯絡人資訊</h4>
                    <div>
                        <span class="title_text">學生頭像 : </span>
                        <img id="contact_person_img" src="" alt="找不到頭像">
                    </div>

                    <div>
                        <span class="title_text">學生姓名 : </span>
                        <span id="contact_person_name"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="designated_contact_confirm" type="button" class="btn btn-primary">確定</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
