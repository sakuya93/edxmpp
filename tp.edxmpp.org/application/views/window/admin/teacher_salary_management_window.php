<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="teacher_salary_management_window" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacher_salary_management_Title">薪資詳細資訊</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="teacher_salary_management_text">
                <h6><b>老師頭像:</b></h6>
                <img id="teacher_photo" src="" alt="" width="150">
                <br><br>

                <h6><b>老師姓名:</b></h6>
                <input type="text" class="form-control" id="teacher_name" autocomplete="off" readonly="readonly">
                <br>

                <h6><b>銀行/郵局名稱:</b></h6>
                <input type="text" class="form-control" id="remittance_name" autocomplete="off" readonly="readonly">

                <br>
                <h6><b>銀行/郵局代號:</b></h6>
                <input type="text" class="form-control" id="remittance_code" autocomplete="off" readonly="readonly">

                <br>
                <h6><b>銀行/郵局帳號:</b></h6>
                <input type="text" class="form-control" id="remittance_account" autocomplete="off" readonly="readonly">

                <br>
                <h6><b>銀行/郵局戶名:</b></h6>
                <input type="text" class="form-control" id="remittance_account_name" autocomplete="off"
                       readonly="readonly">

                <br>
                <h6><b>匯款金額(美金/USD):</b></h6>
                <input type="text" class="form-control" id="remittance_money" autocomplete="off" readonly="readonly">
            </div>
            <div class="modal-footer">
                <button id="remittance_confirm" type="button" class="btn btn-primary" onclick="">確認匯款</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
