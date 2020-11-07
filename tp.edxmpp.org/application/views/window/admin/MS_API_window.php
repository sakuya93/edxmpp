<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="MS_API_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MS_API_window_Title">呼叫MS_API</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="MS_API_window_body">
                <span>
                    <b>選擇API: </b>
                    <select class="form-control " id="MS_API" onchange="change_MS_API()">
                        <option value="API_me()" selected>API_me</option>
                        <option value="API_createOnlineMeeting()">API_createOnlineMeeting</option>
                        <option value="API_outlookCreateOnlineMeeting()">API_outlookCreateOnlineMeeting</option>
                        <option value="API_createEvent()">API_createEvent</option>
                        <option value="API_test()">API_test</option>
                    </select>
                    <span id='MS_API_content'></span>
                </span>
            </div>
            <div class="modal-footer">
                <button type="button" id="call_MS_API" class="btn btn-primary" onclick="API_me()">呼叫API</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
