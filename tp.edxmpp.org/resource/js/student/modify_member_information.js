/*切換大頭照*/
$("#photo").change(function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#mug_shot").attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

function modify_member_information_sendOut(data) {
    //取得會說語言的字串 開始
    var speakLanguage_value = "";
    var speakLanguage_Element = document.getElementById('speakLanguage');
    for (var i = 0; i < speakLanguage_Element.options.length; i++) {
        if (speakLanguage_Element.options[i].selected) {
            speakLanguage_value += speakLanguage_Element.options[i].value + ",";
        }
    }
    speakLanguage_value = speakLanguage_value.substr(0, speakLanguage_value.length - 1);
    //取得會說語言的字串 結束

    if (data[1].value.toString().charAt(4) != '-') { //代表年分輸入超過4字元
        var errorText = document.getElementsByClassName('basic-data-error');
        errorText[1].innerText = "請確認生日格式";
        errorText[1].style.visibility = "visible";
        return;
    }

    var convey_data = {
        name: data[0].value,
        date: data[1].value,
        timezone: data[2].value,
        country: data[3].value,
        motherTongue: data[4].value,
        speakLanguage: speakLanguage_value,
        city: data[5].value
    };

    $.ajax({
        type: "POST",
        url: "modify_member_information/modify_member_information",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var errorText = document.getElementsByClassName('basic-data-error');
            for (var i = 0; i < errorText.length; ++i)
                errorText[i].style.visibility = "hidden";
            if (res['status'] == "姓名不可為空")
                errorText[0].style.visibility = "visible";
            if (res['status'] == "生日不可為空")
                errorText[1].style.visibility = "visible";
            if (res['status'] == "時區不可為空")
                errorText[2].style.visibility = "visible";
            if (res['status'] == "國籍不可為空")
                errorText[3].style.visibility = "visible";
            if (res['status'] == "母語不可為空")
                errorText[4].style.visibility = "visible";
            if (res['status'] == "會說語言不可為空")
                errorText[5].style.visibility = "visible";
            if (res['status'] == "城市不可為空")
                errorText[6].style.visibility = "visible";
            if (res['status'] == "修改成功") {
                $('#save_data').css('backgroundColor', 'gray');
                $('#save_data').css('border', 'unset');
                $('#save_data').attr('disabled', true);

                // 顯示訊息並於 3 秒後清除訊息
                let parentElement = $("#basicInfo");
                parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")
                var seconds = $("#__showMessage").attr("value");
                var countdown = setInterval(function () {
                    seconds--;
                    $("#__showMessage").attr("value", seconds);
                    if (seconds <= 0) {
                        $("#__showMessage").empty();
                        clearInterval(countdown)
                    }
                    ;
                }, 1000);

                // $('#hint_text').text(res['status']);
                // $('#hint_window').modal();
                // $('#hint_window').on('hidden.bs.modal', function () {
                //
                // })
            }
        }
    });

}

function change_password_sendOut(data) {
    var convey_data = {
        old_password: data[0].value,
        new_password: data[1].value,
        confirm_password: data[2].value
    };

    // console.log(convey_data);

    if (convey_data['new_password'] != convey_data['confirm_password']) {
        $('#error-text').css("display", "unset");
    } else {
        $('#error-text').css("display", "none");

        $.ajax({
            type: "POST",
            url: "modify_member_information/change_password",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();
                if (res['status'] == true) {
                    // 顯示訊息並於 3 秒後清除訊息
                    let parentElement = $("#changePasswdForm");
                    parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")
                    var seconds = $("#__showMessage").attr("value");
                    var countdown = setInterval(function () {
                        seconds--;
                        $("#__showMessage").attr("value", seconds);
                        if (seconds <= 0) {
                            $("#__showMessage").empty();
                            clearInterval(countdown)
                        }
                        ;
                    }, 1000);
                    // $('#error-text').css("display", "none");
                } else if (res['status'] == false) {
                    $('#error-text').css("display", "unset");
                    $('#error-text').text("目前密碼輸入錯誤，請重新輸入後提交!");
                    // console.log("目前密碼輸入錯誤，請重新輸入後提交!");
                } else {
                    $('#error-text').css("display", "unset");
                    $('#error-text').text(res['status']);
                    // console.log(res['status']);
                }

            },
        });
    }

}

//驗證碼認證
function email_verification() {
    $('#loading_text').text("驗證碼正在發送中，請稍後..."); //顯示loading視窗
    $('.loading').css("display", "inline-block"); //顯示loading視窗

    var email = document.getElementById('email_verification').value;

    var convey_data = {
        email: email,
    };

    $.ajax({
        type: "POST",
        url: "modify_member_information/email_send",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var errorText = document.getElementById('email-data-error');
            errorText.style.visibility = "hidden"
            $('.loading').css("display", "none"); //隱藏loading視窗

            if (res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
                $('#hint_window').on('hidden.bs.modal', function () {

                })
            } else {
                errorText.innerText = res['msg'];
                errorText.style.visibility = "visible";
            }
        }
    });
}

//
function TeamsAccount_verification() {
    $('#loading_text').text("Teams帳號正在設定中，請稍後..."); //顯示loading視窗
    $('.loading').css("display", "inline-block"); //顯示loading視窗

    var TeamsAccount = document.getElementById('TeamsAccount_verification').value;

    var convey_data = {
        teamsAccount: TeamsAccount,
    };

    $.ajax({
        type: "POST",
        url: "modify_member_information/updateTeamsAccount",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var errorText = document.getElementById('TeamsAccount-data-error');
            errorText.style.visibility = "hidden"
            $('.loading').css("display", "none"); //隱藏loading視窗

            if (res['status']) {
                // 顯示訊息並於 3 秒後清除訊息
                let parentElement = $("#verificationForm");
                parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")
                var seconds = $("#__showMessage").attr("value");
                var countdown = setInterval(function () {
                    seconds--;
                    $("#__showMessage").attr("value", seconds);
                    if (seconds <= 0) {
                        $("#__showMessage").empty();
                        clearInterval(countdown)
                    }
                    ;
                }, 1000);

                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();
                // $('#hint_window').on('hidden.bs.modal', function () {
                //
                // })
            } else {
                errorText.innerText = res['msg'];
                errorText.style.visibility = "visible";
            }
        }
    });
}

function phone_verification() {
    var countryCode = document.getElementById('countryCode_verification').value;  //國碼
    var phone = document.getElementById('phone_verification').value;
    console.log(countryCode + phone);
}


//以下為註冊帳號密碼強度判斷顯示
//測試某個字符是屬於哪一類
function CharMode(iN) {
    if (iN >= 48 && iN <= 57) //數字
        return 1;
    if (iN >= 65 && iN <= 90) //大寫字母
        return 2;
    if (iN >= 97 && iN <= 122) //小寫
        return 4;
    else
        return 8; //特殊字符
}

//計算出當前密碼當中一共有多少種模式
function bitTotal(num) {
    modes = 0;
    for (i = 0; i < 4; i++) {
        if (num & 1) modes++;
        num >>>= 1;
    }
    return modes;
}

//checkStrong函数
//返回密碼的強度級別
function checkStrong(sPW) {
    if (sPW.length <= 4)
        return 0; //密碼太短
    Modes = 0;
    for (i = 0; i < sPW.length; i++) {
        //测试每一个字符的类别并统计一共有多少种模式.
        Modes |= CharMode(sPW.charCodeAt(i));
    }
    return bitTotal(Modes);
}

//當使用者放開鍵盤或密碼輸入框失去焦點時,根據不同的級別顯示不同的颜色
function pw_judgment(pwd) {
    O_color = "#eeeeee";
    L_color = "#FF0000";
    M_color = "#FF9900";
    H_color = "#33CC00";
    if (pwd == null || pwd == '') {
        Lcolor = Mcolor = Hcolor = O_color;
    }
    else {
        S_level = checkStrong(pwd);
        switch (S_level) {
            case 0:
                Lcolor = Mcolor = Hcolor = O_color;
            case 1:
                Lcolor = L_color;
                Mcolor = Hcolor = O_color;
                break;
            case 2:
                Lcolor = Mcolor = M_color;
                Hcolor = O_color;
                break;
            default:
                Lcolor = Mcolor = Hcolor = H_color;
        }
    }
    document.getElementById("strength_W").style.background = Lcolor;
    document.getElementById("strength_M").style.background = Mcolor;
    document.getElementById("strength_S").style.background = Hcolor;
    return;
}

/* 版权声明：本文为CSDN博主「jianai0602」的原创文章，遵循 CC 4.0 BY-SA 版权协议，转载请附上原文出处链接及本声明。
原文链接：https://blog.csdn.net/jianai0602/article/details/78593280 */

