function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

let countdown = setInterval(function() {
    var time = $("#countdown").text();
    time--;
    $("#countdown").text(time);
    if(time == 0){
        clearInterval(countdown);
        $("#countdown_box").attr("style", "display: none !important")
        let search_array = getUrlVars();
        $("#countdown__finished").append(
            "<a class='h2 countdown__finished-text' href='" + search_array['type'] + "/" + search_array['id'] + "?c=" + search_array['c']  + "'>點此處前往課程<i class=\"fa fa-external-link ml-2\" aria-hidden=\"true\"></i>\n</a>"
        );
    }
}, 1000);