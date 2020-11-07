function sendRemittanceData() { //領取薪資資料傳送申請
    var convey_data = {
        name: $('#remittance_name').val(),
        code: $('#remittance_code').val(),
        account: $('#remittance_account').val(),
        account_name: $('#remittance_account_name').val()
    };

    $.ajax({
        type: 'POST',
        url: "pay_page_fun/addGetSalary",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            if (res['status']) {
                $('#hint_window').on('hide.bs.modal', function () {
                    location.reload();
                })
            }

        },
    });
}

// var progress = $(".progress-bar");
// var percentValue = $("#percentValue");
// var per = 0;
// var loading = setInterval(progressAnimate, 5);
//
// function progressAnimate() {
//     if (per == 100) {
//         clearInterval(loading);
//     }
//     else {
//         per = per + 1;
//     }
//
//     // style="width: 56%" aria-valuenow="56"
//     progress.css("width", per + "%");
//     progress.attr("aria-valuenow", per);
//     percentValue.text(per + "%");
// }
//
// function getPayDetail() {
//     $.ajax({
//         type: "POST",
//         url: "_edit",
//         datatype: "json",
//         data: convey_data,
//         success: function (res) {
//             console.log(res);
//             $("#payProgress").css("width", res['_edit']);
//             $("#payProgress").attr("aria-valuenow", res['_edit']);
//             $("#percentValue").text(res['_edit']);
//             $("#moneyValue").text(res['_edit']);
//             $("#hoursValue").text(res['_edit']);
//             $("#soldValue").text(res['_edit']);
//         },
//     });
// }