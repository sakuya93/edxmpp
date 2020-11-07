/*晃動*/
jQuery.fn.shake = function (intShakes /*Amount of shakes*/, intDistance /*Shake distance*/, intDuration /*Time duration*/) {
    this.each(function () {
        var jqNode = $(this);
        jqNode.css({ position: 'relative' });
        for (var x = 1; x <= intShakes; x++) {
            jqNode.animate({ left: (intDistance * -1) }, (((intDuration / intShakes) / 4)))
                .animate({ left: intDistance }, ((intDuration / intShakes) / 2))
                .animate({ left: 0 }, (((intDuration / intShakes) / 4)));
        }
    });
    return this;
}

function signIn_sendOut(data) {
    var convey_data = {
        account: data[0].value,
        password: data[1].value
    };

    $.ajax({
        type: "POST",
        url: "signIn/signIn",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            if (res['status'] == true) {
                window.location = res['url'];
            } else {
                // $('#hint_window').modal();
                // $('#hint_text').text(res['msg']);
                $('#signIn_form input').val("");
                $("#signIn_form input").shake(2, 10, 400); //呼叫晃動方法
            }
        },
        error: function (e) {
            console.log("錯誤");
        }
    });
};