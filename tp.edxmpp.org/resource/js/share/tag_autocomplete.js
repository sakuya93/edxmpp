function tag_autocomplete(url1, url2) {
    $.ajax({
        url: url1 + url2 + "/getCourseLabelOption",
        type: "POST",
        success: function (res) {
            res = JSON.parse(res);
            var list = [];
            for (var i = 0; i < res.length; i++) {
                list.push(res[i]['label']);
            }
            $("#tag_value").autocomplete({
                source: [
                    list
                ]
            });
        }
    });
}