<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        #teams_account_issues_text input{
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div id="teams_account_issues_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teams_account_issues_Title">Teams帳號發放</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="teams_account_issues_text">
                <span><b>帳號 : </b></span>
                <input type="text" class="form-control" id="teamsAccount" autocomplete="off">
                <span><b>密碼 : </b></span>
                <input type="text" class="form-control" id="teamsPassword" autocomplete="off">
                <span><b>應用程式 (用戶端) 識別碼 : </b></span>
                <input type="text" class="form-control" id="applicationKey" autocomplete="off">
                <span><b>目錄 (租用戶) 識別碼 : </b></span>
                <input type="text" class="form-control" id="listKey" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button id="teams_account_issues_add_confirm" type="button" class="btn btn-primary">發放</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
