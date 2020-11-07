// 堂數價格
var court_discount_count = 0; //第幾筆堂數價格

var court_discount_delete_data = {}; //欲刪除的資料

function add_court_discount() { //新增一筆堂數價格
    court_discount_count++;
    var court_discount_content =
        "<div class=\"mtr-3 content\" id=\"court_discount_content" + court_discount_count + "\">" +
        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_court_discount('" + court_discount_count + "')\"></i>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "<div class=\"input-title col-sm-12\">堂數</div>" +
        "<input type=\"number\" class=\"form-control court_discount-data\" placeholder=\"ex: 3\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "<div class=\"input-title col-sm-12\">貸幣選擇</div>" +
        "<select class=\"custom-select court_discount-data\" id=\"currency_type\">" +
        "<option value=\"TWD\" selected=\"\">台幣</option>" +
        "<option value=\"VND\">越南盾</option>" +
        "<option value=\"MYR\">馬來幣</option>" +
        "</select>" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "<div class=\"input-title col-sm-12\">價格(請自行轉換成美金)</div>" +
        "<input type=\"number\" class=\"form-control court_discount-data\" placeholder=\"ex: 300\">" +
        "</div>" +
        "</div>";

    $('#court_discount_body').append(court_discount_content);
}

function load_court_discount(data, classMode) { //載入堂數價格
    console.log(classMode);

    var court_discount_content = "";
    data = JSON.parse(data);
    if(classMode == 1){
        $("#addButton").remove();
        var currency_array = {
            '台幣': 'TWD',
            '越南盾': 'VND',
            '馬來幣': 'MYR'
        };
        var currency_option = "";
        if(data.length == 0){
            court_discount_content +=
                "<div class=\"input-group col-sm-6 mb-3\" style=\"display: none\">" +
                "<div class=\"input-title col-sm-12\">堂數</div>" +
                "<input type=\"number\" class=\"form-control court_discount-data\" value=\"0\" placeholder=\"ex: 3\">" +
                "</div>" +
                "<div class=\"input-group col-sm-6 mb-3\">" +
                "<div class=\"input-title col-sm-12\">貸幣選擇</div>" +
                "<select class=\"custom-select court_discount-data\" id=\"currency_type" + 1 + "\">" +
                '<select class="custom-select court_discount-data" id="currency_type1"><option value="TWD" selected="">台幣</option><option value="VND">越南盾</option><option value="MYR">馬來幣</option></select>' +
                "</select>" +
                "</div>" +
                "<div class=\"input-group col-sm-6 mb-3\">" +
                "<div class=\"input-title col-sm-12\">價格</div>" +
                "<input type=\"number\" class=\"form-control court_discount-data\" placeholder=\"ex: 300\">" +
                "</div>" +
                "</div>";
            $('#court_discount_body').append(court_discount_content);
        }else {
            var number = data[0]['number'];
            var discountedPrice = data[0]['discountedPrice'];
            var currency = data[0]['currency'];


            for (var key in currency_array) { //貸幣初始載入選項處理
                if (currency_array[key] == currency) {
                    currency_option += "<option value=\"" + currency + "\" selected=\"\">" + key + "</option>";
                } else {
                    currency_option += "<option value=\"" + currency_array[key] + "\">" + key + "</option>";
                }
            }
            court_discount_content +=
                "<div class=\"input-group col-sm-6 mb-3\" style=\"display: none\">" +
                "<div class=\"input-title col-sm-12\">堂數</div>" +
                "<input type=\"number\" class=\"form-control court_discount-data\" value=\"0\" placeholder=\"ex: 3\">" +
                "</div>" +
                "<div class=\"input-group col-sm-6 mb-3\">" +
                "<div class=\"input-title col-sm-12\">貸幣選擇</div>" +
                "<select class=\"custom-select court_discount-data\" id=\"currency_type" + 1 + "\">" +
                currency_option +
                "</select>" +
                "</div>" +
                "<div class=\"input-group col-sm-6 mb-3\">" +
                "<div class=\"input-title col-sm-12\">價格</div>" +
                "<input type=\"number\" class=\"form-control court_discount-data\" value=\"" + discountedPrice + "\" placeholder=\"ex: 300\">" +
                "</div>" +
                "</div>";
            $('#court_discount_body').append(court_discount_content);
        }
    }else {


        if (data != null)
            if (data.length != 0) {
                court_discount_count = data.length;

                var currency_array = {
                    '台幣': 'TWD',
                    '越南盾': 'VND',
                    '馬來幣': 'MYR'
                };

                for (i = 0, j = 1; j <= court_discount_count; i++, j++) {
                    var number = data[i]['number'];
                    var discountedPrice = data[i]['discountedPrice'];
                    var currency = data[i]['currency'];

                    var currency_option = "";
                    for (var key in currency_array) { //貸幣初始載入選項處理
                        if (currency_array[key] == currency) {
                            currency_option += "<option value=\"" + currency + "\" selected=\"\">" + key + "</option>";
                        } else {
                            currency_option += "<option value=\"" + currency_array[key] + "\">" + key + "</option>";
                        }
                    }

                    court_discount_content +=
                        "<div class=\"mtr-3 content\" id=\"court_discount_content" + j + "\">" +
                        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_court_discount('" + j + "')\"></i>" +
                        "<div class=\"input-group col-sm-6 mb-3\">" +
                        "<div class=\"input-title col-sm-12\">堂數</div>" +
                        "<input type=\"number\" class=\"form-control court_discount-data\" value=\"" + number + "\" placeholder=\"ex: 3\">" +
                        "</div>" +
                        "<div class=\"input-group col-sm-6 mb-3\">" +
                        "<div class=\"input-title col-sm-12\">貸幣選擇</div>" +
                        "<select class=\"custom-select court_discount-data\" id=\"currency_type" + j + "\">" +
                        currency_option +
                        "</select>" +
                        "</div>" +
                        "<div class=\"input-group col-sm-6 mb-3\">" +
                        "<div class=\"input-title col-sm-12\">價格(請自行轉換成美金)</div>" +
                        "<input type=\"number\" class=\"form-control court_discount-data\" value=\"" + discountedPrice + "\" placeholder=\"ex: 300\">" +
                        "</div>" +
                        "</div>";

                }

                $('#court_discount_body').append(court_discount_content);
            }
    }
}

function delete_court_discount(id) {
    var item = "court_discount_content" + id;
    document.getElementById(item).remove();

    court_discount_count--;
}

function court_discount_sendOut(id, data, classMode) {
    console.log(classMode);

    var convey_data = {};
    if(classMode == "1"){
        convey_data = {
            0: {
                id: id,
                currency: data[1].value,
                discountedPrices: data[2].value
            }
        }
    }else{
        for (i = 0, a = 0; i < court_discount_count; i++, a += 3) {
            var temp = {
                id: id,
                currency: data[a + 1].value,
                number: data[a].value,
                discountedPrices: data[a + 2].value
            }
            convey_data[i] = temp;
        }
    }

    console.log(convey_data);

    $.ajax({
        type: "POST",
        url: "../../course_management/setNumberLessonsPreferential",
        data: convey_data,
        dataType: 'json',
        success: function (res) {

            // 顯示訊息並於 3 秒後清除訊息
            let parentElement = $("#setLiveSubjectForm");
            parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")
            var seconds = $("#__showMessage").attr("value");
            var countdown = setInterval(function() {
                seconds--;
                $("#__showMessage").attr("value", seconds);
                if (seconds <= 0) {
                    $("#__showMessage").empty();
                    clearInterval(countdown)
                };
            }, 1000);

        }
    });
}

//堂數價格結束