function registered_sendOut(data) {
    var convey_data = {
        name: data[0].value,
        account: data[1].value,
        password: data[2].value,
        password_confirm: data[3].value,
    };

    //是首頁的時候
    if (location.href.indexOf('tp.edxmpp.org/home#') != -1 | location.href.indexOf('tp.edxmpp.org/#') != -1) {
        $.ajax({
            type: "POST",
            url: "home/registered",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                if (res['status'] == '註冊成功') {
                    $('#registered_window').modal('hide');
                    $('#registered_form input').val("");
                    // $('#hint_window').off().on('hidden', 'hidden.bs.modal');
                }
                else {
                    $('#hint_text').text(res['status']);
                    $('#hint_window').modal();
                }
            },
        });
    }
    else { //其他頁面的時候
        $.ajax({
            type: "POST",
            url: "https://tp.edxmpp.org/home/registered",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['status']);
                $('#hint_window').modal();
                if (res['status'] == '註冊成功') {
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#registered_window').modal('hide');
                        // $('#hint_window').off().on('hidden', 'hidden.bs.modal');
                    })
                }
            },
        });
    }
}

function signIn_sendOut(data) {
    var convey_data = {
        account: data[0].value,
        password: data[1].value
    };

    //是首頁的時候
    if (location.href.indexOf('tp.edxmpp.org/home#') != -1 | location.href.indexOf('tp.edxmpp.org/#') != -1) {
        $.ajax({
            type: "POST",
            url: "home/login",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                // $('#hint_text').text(res['status']);
                // $('#hint_window').modal();
                if (res['status'] == "登入成功") {
                    // $('#hint_window').on('hidden.bs.modal', function () {
                    window.location = res['url'];
                    // });
                }
                else { //錯誤時才顯示提示視窗
                    $('#hint_text').text(res['status']);
                    $('#hint_window').modal();
                    document.getElementById('sigInAccount').value = "";
                    document.getElementById('sigInPassword').value = "";
                }
            },
            error: function (e) {
                $('#hint_text').text("錯誤");
                $('#hint_window').modal();
            }
        });
    }
    else {  //其他頁面的時候
        $.ajax({
            type: "POST",
            url: "https://tp.edxmpp.org/home/login",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                if (res['status'] == "登入成功") {
                    location.reload();
                }
                else { //錯誤時才顯示提示視窗
                    $('#hint_text').text(res['status']);
                    $('#hint_window').modal();
                    document.getElementById('sigInAccount').value = "";
                    document.getElementById('sigInPassword').value = "";
                }
            }
        });
    }

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