$(document).ready(function () {
    if(window.location.pathname == "/teaching_platform_dev/stored_value"){
        checkStoredValueStstus();    // /teaching_platform_dev/stored_value 儲值中心頁面 檢查儲值狀態
    }
});

var sel_stored_value_method = "VISA"; //儲存所選擇的儲值方式(預設VISA)
//選擇儲值項目後
function sel_stored_value_option(t) {
    //選擇效果
    if (!$(t).hasClass('option_sel')) { //未選擇時選擇
        $(t).addClass('option_sel');
        if (t.id == 'visa_option') { //選擇VISA信用卡時
            $('#option_information_text').text('使用VISA或MASTER 3D驗證信用卡，享受最便利又安全的儲值體驗。');
            $('#select_option_text').append('<i class="fa fa-file-text" style="font-size:20px;"></i>&nbsp;信用卡儲值');
            sel_stored_value_method = "VISA";
        }
    }
}

function sendStoredValue() { //儲值鑽石
    var price = $('.currency_item:checked').val();

    var turnForm = document.createElement("form");
    //一定要加入到body中！！
    document.body.appendChild(turnForm);
    turnForm.method = 'post';
    turnForm.action = 'stored_value/storedValueSend';
    turnForm.target = '_blank';
    //创建隐藏表单

    var newElement = document.createElement("input");
    newElement.setAttribute("name", "price");
    newElement.setAttribute("type", "hidden");
    newElement.setAttribute("value", price);
    turnForm.appendChild(newElement);

    turnForm.submit();
}

function checkStoredValueStstus() { //檢查儲值狀態
    var href = window.location.href;
    if(href.indexOf("status") != -1){
        var search = window.location.search;
        var status = search.substring(search.indexOf("=") + 1);

        var hint_text = { //提示訊息
            1: "儲值成功",
            2: "付款成功，但發生錯誤請記住付款時間聯絡管理員",
            3: "付款失敗",
        };

        $("#hint_text").text(hint_text[status]);
        $("#hint_window").modal("show");
        $("#close").click(function () {
            window.location.href = "stored_value";
        });
    }
}