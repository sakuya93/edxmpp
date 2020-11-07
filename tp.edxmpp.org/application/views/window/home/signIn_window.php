<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <style>
        #signIn_btn {
            width: 100%;
            height: 50px;
            margin: 30px auto;
            font-size: 22px;
        }

        #signIn_form input {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div id="signIn_window" class="modal" tabindex="-1" style="z-index: 99999">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
<!--                --><?//=$login_modal['login']?>
                <h3 class="modal-title">登入</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="signIn_form"
                      onsubmit="signIn_sendOut(document.getElementsByClassName('signIn-data')); return false;">
                    <span class="input_text">帳號</span>
                    <input type="text" class="form-control signIn-data" placeholder="帳號" id="sigInAccount" name="account">
<!--                    --><?//=$login_modal['password']?>
                    <span class="input_text">密碼</span>
<!--                    --><?//=$login_modal['password']?>
                    <input type="password" class="form-control signIn-data" placeholder="密碼" id="sigInPassword" name="password">

<!--                    --><?//=$login_modal['login']?>
                    <input id="signIn_btn" type="submit" class="btn btn-primary" value="登入">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    $(document).ready(function () {
        $("#signIn_form").submit(function (event) {
            //阻止自動提交
            event.preventDefault();
        });
    });
</script>

<?php
