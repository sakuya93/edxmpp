<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>測試頁面</title>
    <!--  Package Start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <!--  Package End -->

    <!--  View Start  -->
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/share/live_chat.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/student.css">
    <link rel="stylesheet" href="resource/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--  View End  -->

    <style>
        .show__message {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>
</head>
<body>


<!--  聊天室測試  -->
<section class="container mt-5">
    <!-- Chat in 10 lines of JavaScript code using PubNub JavaScript V4 SDK-->
<!--    <p>Enter chat and press enter.</p>-->
<!--    <input id="input" placeholder="Your Message Here"/>-->
<!--    <p>Chat Output:-->
<!--    <p>-->
<!--    <div id="box"></div>-->
<!--    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.23.0.min.js"></script>-->
<!--    <script>(function () {-->
<!--            var pubnub = new PubNub({-->
<!--                publishKey: 'pub-c-80df65c2-07b9-4b13-91af-30f1146230e9',-->
<!--                subscribeKey: 'sub-c-4f0bfd60-69d8-11ea-a7c4-5e95b827fd71'-->
<!--            }); // Your PubNub keys here. Get them from https://dashboard.pubnub.com.-->
<!--            var box = document.getElementById("box"), input = document.getElementById("input"), channel = 'chat';-->
<!--            pubnub.subscribe({channels: [channel]}); // Subscribe to a channel.-->
<!--            pubnub.addListener({-->
<!--                message: function (m) {-->
<!--                    box.innerHTML = ('' + m.message).replace(/[<>]/g, '') + '<br>' + box.innerHTML; // Add message to page.-->
<!--                }-->
<!--            });-->
<!--            input.addEventListener('keypress', function (e) {-->
<!--                (e.keyCode || e.charCode) === 13 && pubnub.publish({ // Publish new message when enter is pressed.-->
<!--                    channel: channel, message: input.value, x: (input.value = '')-->
<!--                });-->
<!--            });-->
<!--        })();</script>-->
</section>


<!-- facebook for developers -->
<section class="container mt-5">
    <!--    <fb:login-button-->
    <!--            scope="public_profile,email"-->
    <!--            onlogin="checkLoginState();">-->
    <!--    </fb:login-button>-->
    <!---->
    <!--    <div class="fb-share-button"-->
    <!--         data-href="https://ajcode.tk/teaching_platform_dev/"-->
    <!--         data-layout="button_count">-->
    <!--    </div>-->
<!--    <div class="btn btn-primary"-->
<!--         id="shareBtn"-->
<!--         data-layout="button_count">-->
<!--        Share-->
<!--    </div>-->
<!--    <iframe src="" width="0" height="0" frameborder="0" id="newWin"></iframe>-->
<!---->
<!--     Load Facebook SDK for JavaScript -->
<!--    <div id="fb-root"></div>-->
<!--    <script async defer crossorigin="anonymous"-->
<!--            src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v6.0&appId=2280269085600029&autoLogAppEvents=1"></script>-->
    <!-- Your share button code -->
    <!--    <div class="fb-share-button" data-href="https://ajcode.tk/teaching_platform_dev/test" data-layout="button"-->
    <!--         data-size="small"><a target="_blank"-->
    <!--                              href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fajcode.tk%2Fteaching_platform_dev%2Ftest&amp;src=sdkpreparse"-->
    <!--                              class="fb-xfbml-parse-ignore">分享</a>-->
    <!--    </div>-->
</section>

<section class="container">
    <p class="h2">未登入TEAMS帳號看可不可以看</p>
    <iframe width="640" height="360"
            src="https://web.microsoftstream.com/embed/video/ccd3458e-8ecf-4523-a249-1b9ba968151d?autoplay=false&amp;showinfo=true"
            allowfullscreen style="border:none;"></iframe>
</section>


<!--  Script  -->
<!--  Package  -->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!-- View -->
<script src="resource/js/test.js"></script>


</body>
</html>
