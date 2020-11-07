<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <style>
        #notice-modal-content {
            background-color: rgb(218, 255, 252);
        }

        .body_left i {
            color: #436a80;
            font-size: 50px;
            margin-left: 30px;
        }

        .body_right .content-body {
            color: grey;
            font-size: 20px;
            font-weight: bold;
            word-break: break-all;
        }

        .body_right .content-time {
            color: grey;
            word-break: break-all;
        }

        .body_left, .body_right {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        /*.body_right button {*/
        /*margin-top: 30px;*/
        /*float: right;*/
        /*}*/
    </style>
</head>
<body>
<div id="notice_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="notice-modal-content">
            <div class="modal-header">
                <!--                通知訊息標題-->
                <h5 class="modal-title" id="notice_Title">通知</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row" id="notice_text">
                <div class="body_left col-sm-3">
                    <i class="fa fa-exclamation-circle"></i>
                </div>

                <div class="body_right col-sm-9">
                    <!--                通知訊息內容-->
                    <div class="content-body">訊息內容:</div>
                    <div class="content-time">通知時間:</div>
                    <!--                底部按鈕區-->
                    <!--                    <button type="button" class="btn btn-outline-info" data-dismiss="modal">關閉通知視窗</button>-->
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
