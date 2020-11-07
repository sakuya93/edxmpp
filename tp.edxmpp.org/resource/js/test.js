$(document).ready(function () {

});
// var src= "https://code.jquery.com/jquery-3.4.1.slim.min.js";
// var t = window.open('http://www.facebook.com/sharer/sharer.php?u='.concat(encodeURI("http://ajcode.tk/teaching_platform_dev/test")),
//     "_blank",
// )



document.getElementById('shareBtn').addEventListener('click', function() {
    window.newWin = window.open('http://www.facebook.com/sharer/sharer.php?u='.concat(encodeURI("http://ajcode.tk/teaching_platform_dev/test")),
        "toolbar=no,location=yes,directories=no,width=300,height=400"
    );
     var prevLoaded = 0;
    var pageLoaded = setInterval(function() {
        // var isLoaded = window.newWin.length || -1;
        var isLoaded = window.newWin.length;
        console.log(window.newWin.length);
        console.log(isLoaded);
        if(isLoaded >= 0 && isLoaded != prevLoaded) {
            prevLoaded = isLoaded;
        }
        if(isLoaded >= 0 && isLoaded == prevLoaded) {
            console.log(window.newWin.document)
            console.log(window.newWin.document.body)
            alert(newWin.document.body);
            // clearInterval(pageLoaded);
        }
    }, 1000);
}, false);