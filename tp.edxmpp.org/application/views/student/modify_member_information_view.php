<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>帳號設定</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/share.css">
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/student.css">
    <link rel="stylesheet" href="resource/css/student/modify_member_information.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
</head>
<body>
<!--loading視窗載入-->
<div class='loading'>
    <span id="loading_text"></span>
</div>
<!--loading視窗結束-->

<div id="background"></div>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="student"><img class="header-logo"
                                                                      src="resource/pics/share/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?= $headerRightBar ?>
        </div>
    </nav>

    <!--    導覽列右側MENU    -->
    <?= $headerRightIconMenu ?>

    <!--        右側帳號資訊欄         -->
    <?= $RightInformationColumn ?>


    <div class="message-box menu" id="page-2"> <!-- 聊天 -->
        <div class="top-bar col-sm">
            <b id="chat_object">傳送對象姓名</b>
            <i class="fa fa-close" id="message-box-close"></i>
        </div>

        <div class="message_area">
        </div>

        <div class="send-message">
            <textarea class="input_message" id="input_message" rows="3" cols="32" placeholder="輸入訊息..."></textarea>
        </div>
    </div>

</header>

<main>
    <div class="container mtr-10 mbr-3 modify_member_information_body" id="basicInfo">
        <div class="input-title col-sm-12 ft-24">基本資料</div>
        <div class="input-group col-sm mb-3 row">
            <div class="input-title col-sm-12">照片</div>
            <img class="ml-10 mug_shot" id="mug_shot" src="resource/image/student/photo/<?= $photoPath ?>">
        </div>


        <form id="upload_image_form" action="modify_member_information/upload_image" enctype="multipart/form-data"
              method="post">
            <input type="file" id="photo" name="photo" accept="image/png, image/jpeg"
                   onchange="$('#upload_image_btn').css('backgroundColor','#007bff');$('#upload_image_btn').attr('disabled', false);">
            <button type="submit" class="btn btn-primary" id="upload_image_btn" disabled>確認更新大頭照</button>
        </form>
        <br><br>


        <form onsubmit="modify_member_information_sendOut(document.getElementsByClassName('modify_member_information-data')); return false;">
            <div class="row">
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">姓名<span class="error-text basic-data-error">姓名不可為空</span></div>
                    <input type="text" class="form-control modify_member_information-data" placeholder="輸入姓名"
                           value="<?= $name ?>"
                           onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);"><br>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">生日<span class="error-text basic-data-error">生日不可為空</span></div>
                    <input type="date" class="form-control modify_member_information-data" value="<?= $date ?>"
                           onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">時區<span class="error-text basic-data-error">時區不可為空</span></div>
                    <select class="custom-select modify_member_information-data" id="timezone"
                            onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                        <option value="" selected disabled>請選擇時區</option>
                        <option value="台灣">台灣</option>
                        <option value="越南">越南</option>
                        <option value="馬來西亞">馬來西亞</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">國籍<span class="error-text basic-data-error">國籍不可為空</span></div>
                    <select class="custom-select modify_member_information-data" id="country"
                            onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                        <option value="" selected disabled>請選擇國籍</option>
                        <option value="台灣">台灣</option>
                        <option value="美國">美國</option>
                        <option value="日本">日本</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">母語<span class="error-text basic-data-error">母語不可為空</span></div>
                    <select class="custom-select modify_member_information-data" id="motherTongue"
                            onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                        <option value="" selected disabled>請選擇母語</option>
                        <option value="中文">中文</option>
                        <option value="英文">英文</option>
                        <option value="日文">日文</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3 language_spoken">
                    <div class="input-title col-sm-12">會說語言<span class="error-text basic-data-error">會說語言不可為空</span>
                    </div>
                    <select class="custom-select selectpicker form-control" id="speakLanguage" multiple
                            onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                        <option value="中文">中文</option>
                        <option value="英文">英文</option>
                        <option value="日文">日文</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">城市<span class="error-text basic-data-error">城市不可為空</span></div>
                    <input type="text" class="form-control modify_member_information-data" placeholder="輸入城市"
                           value="<?= $city ?>"
                           onchange="$('#save_data').css('backgroundColor','#007bff');$('#save_data').attr('disabled', false);">
                </div>
            </div>

            <input class="btn btn-primary mb-3" type="submit" id="save_data" value="儲存" disabled>
        </form>
    </div>


    <div class="container mbr-3 modify_member_information_body" id="verificationForm">
        <div class="input-title col-sm-12 ft-24">帳號驗證</div>
        <div class="mls-15 verification">
            <div class="input-group col-sm-6 mb-3">
                <!--電子信箱驗證-->
                <div class="input-title col-sm-12">電子信箱<span class="error-text" id="email-data-error">電子信箱不可為空</span>
                </div>
                <input type="email" class="form-control" id="email_verification" placeholder="輸入電子信箱"
                       value="<?= $email ?>"
                       onchange="$('#email_verification_btn').css('backgroundColor','#dc3545');$('#email_verification_btn').css('color','white');$('#email_verification_btn').attr('disabled', false);">
                <button type="button" id="email_verification_btn" class="btn btn-outline-danger" disabled
                        onclick="email_verification()">
                    發送驗證信
                </button>

                <div class="successful_verification" id="email_verification_prompt">
                    <i class="fa fa-check-circle-o"></i>
                    <span>已經驗證</span>
                </div>

                <!--Teams帳號設定-->
                <div class="input-title col-sm-12">Teams帳號設定<span class="error-text" id="TeamsAccount-data-error">Teams帳號設定不可為空</span>
                </div>
                <input type="text" class="form-control" id="TeamsAccount_verification" placeholder="輸入Teams帳號"
                       value="<?= $TeamsAccount ?>"
                       onchange="$('#TeamsAccount_verification_btn').css('backgroundColor','#dc3545');$('#TeamsAccount_verification_btn').css('color','white');$('#TeamsAccount_verification_btn').attr('disabled', false);">
                <button type="button" id="TeamsAccount_verification_btn" class="btn btn-outline-danger" disabled
                        onclick="TeamsAccount_verification()">
                    確認設定
                </button>

                <div class="successful_verification" id="TeamsAccount_verification_prompt">
                    <i class="fa fa-check-circle-o"></i>
                    <span>完成設定</span>
                </div>

                <!--手機驗證-->
                <!--                <div class="input-title col-sm-12">電話號碼</div>-->
                <!---->
                <!--                <div class="input-group mb-3">-->
                <!--                    <div class="input-group-prepend">-->
                <!--                        <select class="custom-select" id="countryCode_verification">-->
                <!--                            <option value="" disabled selected>選擇國碼</option>-->
                <!--                            <option value="(+866)">台灣(+866)</option>-->
                <!--                            <option value="(+1)">美國(+1)</option>-->
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                    <input type="text" class="form-control" id="phone_verification" placeholder="輸入號碼">-->
                <!--                    <button type="button" id="phone_verification_btn" class="btn btn-outline-danger"-->
                <!--                            onclick="phone_verification()">發送驗證信-->
                <!--                    </button>-->
                <!---->
                <!--                    <div class="successful_verification" id="phone_verification_prompt">-->
                <!--                        <i class="fa fa-check-circle-o"></i>-->
                <!--                        <span>已經驗證</span>-->
                <!--                    </div>-->
                <!--                </div>-->
            </div>
        </div>
    </div>


    <div class="container mbr-3 modify_member_information_body" id="changePasswdForm">
        <div class="input-title col-sm-12 ft-24">更改密碼</div>

        <div class="mls-15">
            <form id="passwd-form"
                  onsubmit="change_password_sendOut(document.getElementsByClassName('change_password-data')); return false;">
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">目前密碼</div>
                    <input type="password" name="old_password" class="form-control change_password-data" onchange="$('#change_PS').css('backgroundColor','#007bff');$('#change_PS').attr('disabled', false);">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">新密碼</div>
                    <input type="password" name="new_password" class="form-control change_password-data"
                           onKeyUp="pw_judgment(this.value)" onchange="$('#change_PS').css('backgroundColor','#007bff');$('#change_PS').attr('disabled', false);">

                    <div class="pw_strength row col-sm-12">
                        <span class="col-sm-">密碼強度:</span>
                        <div class="strength col-sm-3" id="strength_W">弱</div>
                        <div class="strength col-sm-3" id="strength_M">中</div>
                        <div class="strength col-sm-3" id="strength_S">強</div>
                    </div>
                    <div class="col-sm-12 changePS_hint_text">
                        <label style="color: red">密碼至少為8個字元。請混合使用大小寫字母、數字及特殊符號，讓密碼更安全(第一個字是英文字)</label>
                    </div>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">確認密碼</div>
                    <input type="password" name="confirm_password" class="form-control change_password-data" onchange="$('#change_PS').css('backgroundColor','#007bff');$('#change_PS').attr('disabled', false);">
                </div>
                <div class="ft-red col-sm-6 wd-nobreak mb-3 dy-none" id="error-text">新密碼與確認密碼不一樣，請再重新輸入。</div>

                <div class="col-sm-6">
                    <input class="btn btn-primary my-2" type="submit" id="change_PS" value="更新密碼" disabled>
                </div>
            </form>
        </div>
    </div>

    <!-- 頁尾 start-->
    <div class="max-width-800 about" data-v-4e5639f4="">
        <div class="row about">
            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">XXX教學平台</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">關於我們</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">部落格</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">媒體</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">工作機會</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">加入 XXX教學平台</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">成為教師</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">找學生</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">學習</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">找教師</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">找課程</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">學生評價</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">客戶支援</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">常見問題</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">系統更新</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer class="py-4 bg-dark text-white-50">
        <div class="container text-center">
            <div class="footer_left">
                <small>© XXX, Inc.</small>
            </div>

            <div class="footer_right">
                <a href="#">網站地圖</a>・<a href="#">服務條款隱私聲明</a>&nbsp;&nbsp;&nbsp;
                <a href="#"><i class="fa fa-facebook-square"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-instagram"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-twitter"></i>&nbsp;</a>
            </div>

        </div>
    </footer>
    <!-- 頁尾 end-->
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--匯入所需JS-->
<script src="resource/js/student/student.js"></script>
<script src="resource/js/student/modify_member_information.js"></script>
<script src="resource/js/share.js"></script>

<script>
    /*初始載入*/
    $(function () {
        $('#timezone').val('<?=$timezone?>'); //時區
        $('#country').val('<?=$country?>'); //國籍
        $('#motherTongue').val('<?=$motherTongue?>') //母語

        //會說語言
        var speakLanguage = '<?=$speakLanguage?>'.split(",");
        var select = document.getElementById('speakLanguage');

        for (var i = 0; i < select.length; i++) {
            for (var j = 0; j < speakLanguage.length; j++) {
                if (select.options[i].text == speakLanguage[j]) {
                    select.options[i].selected = true;
                }
            }
        }
        
        if ($('#email_verification').val() != "") {
            $('#email_verification').attr('readOnly', true);
            $('#email_verification_btn').css('display', 'none');
            $('#email_verification_prompt').css('display', 'block');
        }
        if ($('#TeamsAccount_verification').val() != "") {
            $('#TeamsAccount_verification').attr('readOnly', true);
            $('#TeamsAccount_verification_btn').css('display', 'none');
            $('#TeamsAccount_verification_prompt').css('display', 'block');
        }
    });
</script>

</body>
</html>