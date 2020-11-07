function setReceptionAdmin(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck_detail/setReceptionAdmin";
    ajax_share(convey_data, url);
}

function checkPass(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/checkPass";
    ajax_share(convey_data, url);
}


function cancelTeacherIdentity(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/cancelTeacherIdentity";
    ajax_share(convey_data, url);
}


function banBecomeTeacher(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/banBecomeTeacher";
    ajax_share(convey_data, url);
}

function cancelBanBecomeTeacher(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/cancelBanBecomeTeacher";
    ajax_share(convey_data, url);
}

function logoutTeacherIdentity(id){
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/logoutTeacherIdentity";
    ajax_share(convey_data, url);
}

function setDesignatedAdministrator(id) {
    var convey_data = {
        id: id
    };
    url = "../teacherCheck/setDesignatedAdministrator";
    ajax_share(convey_data, url);
}

function ajax_share(convey_data, url) {
    $.ajax({
        type: 'POST',
        url: url,
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        },
    });
}