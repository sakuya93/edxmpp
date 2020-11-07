<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!--    <meta name="google-signin-client_id"-->
    <!--          content="111428200097-q0kvf29p5tu4t6unur5ljt6ehb0hnm5r.apps.googleusercontent.com">-->
    <!--    <script src="https://apis.google.com/js/platform.js" async defer></script>-->

    <style>
        #modal-content {
            width: 100%;
        }

        #registered_btn {
            width: 100%;
            height: 50px;
            margin: 30px auto;
            font-size: 22px;
        }

        #registered_form input {
            margin-bottom: 10px;
        }

        .seperate-div {
            font-size: 12px;
            text-align: center;
            position: relative;
            display: flex;
            align-items: center;
            margin: 20px auto;
        }

        .seperate-div .line {
            flex: 1;
            height: 1px;
            background-color: #d2d2d2;
        }

        .seperate-div .text {
            color: #d2d2d2;
            flex: 0 0 auto;
            padding: 0 10px;
            font-style: inherit;
            font-weight: inherit;
            font-size: 18px;
        }

        /*密碼強度提示*/
        .pw_strength{
            margin: 10px 0 10px 0;
        }

        .pw_strength .strength{
            border: 1px solid grey;
            text-align: center;
            display: inline;
        }
    </style>
</head>
<body>
<div id="registered_window" class="modal" tabindex="-1" style="z-index: 99999">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
<!--                --><?//= $registered_modal['registered'] ?>
                <h3 class="modal-title">註冊</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!--                <div class="g-signin2" data-onsuccess="onSignIn"></div>-->

                <!--                <div class="seperate-div">-->
                <!--                    <div class="line"></div>-->
                <!--                    <span class="text">or</span>-->
                <!--                    <div class="line"></div>-->
                <!--                </div>-->

                <form id="registered_form"
                      onsubmit="registered_sendOut(document.getElementsByClassName('registered-data')); return false;">
<!--                    --><?//= $registered_modal['name'] ?>
                    <span class="input_text">姓名</span>
<!--                    --><?//= $registered_modal['name'] ?>
                    <input type="text" class="form-control registered-data" placeholder="姓名" id="name" name="name">

                    <span class="input_text">帳號</span>
                    <input type="text" class="form-control registered-data" placeholder="帳號" id="account"
                           name="account">
                    <label style="color: red"><?= "帳號需要8~16個字(第一個字是英文字，其餘英文數字皆可)
                    <br>或者信箱帳號" ?></label><br>
<!--                    --><?//= $registered_modal['password'] ?>
                    <span class="input_text">密碼</span>
<!--                    --><?//= $registered_modal['password'] ?>
                    <input type="password" class="form-control registered-data" placeholder="密碼" id="password"
                           name="password" onKeyUp="pw_judgment(this.value)">
                    <div class="pw_strength row">
                        <span class="col-sm-">密碼強度:</span>
                        <div class="strength col-sm-3" id="strength_W">弱</div>
                        <div class="strength col-sm-3" id="strength_M">中</div>
                        <div class="strength col-sm-3" id="strength_S">強</div>
                    </div>

<!--                    --><?//= $registered_modal['password_hint'] ?>
                    <label style="color: red">密碼需要8~16個字(第一個字是英文字，其餘英文數字皆可)</label>
                    <br>
<!--                    --><?//= $registered_modal['password_confirm'] ?>
                    <span class="input_text">確認密碼</span>
<!--                    --><?//= $registered_modal['password_confirm'] ?>
                    <input type="password" class="form-control registered-data" placeholder="確認密碼" id="confirm_password"
                           name="confirm_password">

<!--                    --><?//= $registered_modal['registered'] ?>
                    <input id="registered_btn" type="submit" class="btn btn-primary" value="註冊">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    $(document).ready(function () {
        $("#registered_form").submit(function (event) {
            //阻止自動提交
            event.preventDefault();
        });
    });

    function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
        console.log('ID: ' + profile.getId());
        console.log('Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log('Image URL: ' + profile.getImageUrl());
        console.log('Email: ' + profile.getEmail());
    }
</script>

<?php
