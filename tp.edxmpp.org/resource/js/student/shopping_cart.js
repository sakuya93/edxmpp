$(document).ready(function () {
    var url = location.href;
    var buy_status = 0; //購買狀態

    if (url.indexOf("?status") != -1) { //代表有帶購買狀態參數
        buy_status = url.substr(url.indexOf("?status") + 8, 1);

        if (buy_status == 1) { //購買成功
            $('#hint_text').text("購買成功");
        } else if (buy_status == 2) { //付款成功
            $('#hint_text').text("付款成功，但發生錯誤請記住付款時間並連絡管理員");
        } else if (buy_status == 3) { //付款失敗
            // $('#hint_text').text("付款失敗");
            $('#hint_text').text("親愛的學員，您的鑽石不足，快去購買吧");
        }
        $('#hint_window').modal();

        $('#hint_window').on('hidden.bs.modal', function (e) {
            window.location = "https://ajcode.tk/teaching_platform_dev/shopping_cart";
        })
        
    }

    // var currency_div = document.getElementById('currency0') == undefined ? '' : document.getElementById('currency0');
    // if (currency_div != '') {
    //     var currency = currency_div.innerText;
    //     var price_div = document.getElementsByClassName('unit_price');
    //
    //     var price_total = 0;
    //
    //     for (var i = 0; i < price_div.length; i++) {
    //         price_total += eval(price_div[i].innerText);
    //     }
    //     $('#price_total').text(currency + price_total);
    // } else {
    //     $('.separation_line').css('display', 'none');
    // }
});

function change_classes(classes_vlaue, id) { //調整課程金額並計算總價
    var price = classes_vlaue.substring(classes_vlaue.indexOf('$') + 1, classes_vlaue.length).trim();
    var unit_price = $('#price' + id);

    unit_price.text(price);

    //計算總價
    var total_data = $('.unit_price'); //取得所有明細單價

    var total = 0;
    for (i = 0; i < total_data.length; i++) {
        if (total_data[i].className.indexOf("not_calculated") == -1) { //判斷是否有不計算標籤，如有則跳過不計算
            total += parseInt(total_data[i].innerHTML);
        }
    }
    $('#price_total').text("TWD$ " + total);
}

function shoppingBuyClass_sendOut() {
    var check_box_data = document.getElementsByClassName("check_box-data");
    var shopping_cart_data = document.getElementsByClassName("shopping_cart-data");

    var convey_data = {};

    //判斷打勾
    for (i = 0, one = 0, two = 1; i < check_box_data.length; i++, one += 2, two += 2) {

        var paymode = $('#credit_card_payment').is(":hidden") == true ? 1 : 0; //判斷是選擇哪種付款方式
        if (check_box_data[i].checked) {
            var class_num_end = shopping_cart_data[two].value.indexOf('堂');
            var class_num_end2 = shopping_cart_data[two].value.indexOf('$') + 1;
            convey_data[i] = {
                id: shopping_cart_data[one].innerHTML,
                NumberOfLessons: shopping_cart_data[two].value.substr(0, class_num_end),
                payMod: paymode
            };
        }
    }

    var turnForm = document.createElement("form");
    //一定要加入到body中！！
    document.body.appendChild(turnForm);
    turnForm.method = 'post';
    turnForm.action = 'shopping_cart/shoppingBuyClass';
    turnForm.target = '_blank';
    //创建隐藏表单


    for (var key1 in convey_data) {
        for (var key2 in convey_data[key1]) {
            var newElement = document.createElement("input");
            newElement.setAttribute("name", "sc[" + key1 + "][" + key2 + "]");
            newElement.setAttribute("type", "hidden");
            newElement.setAttribute("value", convey_data[key1][key2]);
            turnForm.appendChild(newElement);
        }
    }

    turnForm.submit();
}

$('.check_box-data').click(function () {
    var id = this.id.substr(-1); //取第幾個商品
    var amount = document.getElementById('price' + id).innerText; //加減總金額數目
    var original_amount = $('#price_total').text().split(" "); //原本價錢

    if ($("#detail" + id).hasClass('detail_show')) {
        $("#detail" + id).removeClass('detail_show'); //從明細中刪除
        $("#detail" + id).addClass('detail_hide');

        $("#price" + id).addClass('not_calculated'); //在明細中標註不要計算價錢的class

        $('#price_total').text(original_amount[0] + " " + (parseInt(original_amount[1]) - parseInt(amount))); //扣除總價
    } else {
        $("#detail" + id).addClass('detail_show'); //從明細中顯示
        $("#detail" + id).removeClass('detail_hide');

        $("#price" + id).removeClass('not_calculated'); //在明細中刪除標註的class代表要計算價錢

        $('#price_total').text(original_amount[0] + " " + (parseInt(original_amount[1]) + parseInt(amount))); //提高總價
    }
});
