<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <style>
        #collection_course_window .modal-lg {
            max-width: 67vw !important;
            width: 67vw !important;
        }

        #collection_course_window .modal-header {
            border: 0 !important;
        }

        #collection_course_window .modal-title {
            font-size: 20px;
        }

        #collection_course_window .modal-content {
            min-height: 600px;
            max-height: 600px;
        }

        #collection_course_window .modal-body {
            overflow-y: auto;
        }

        /*沒收藏任何教師*/
        .panel {
            font-size: 1rem;
        }

        .empty-state {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            min-height: 380px;
        }

        .empty-state p {
            color: grey;
            font-size: 18px;
            font-weight: 300;
        }

        /*有收藏教師*/
        /*卡片部分*/
        .card div {
            margin-top: 0;
        }

        .card {
            border: 1px solid lightgray;
            min-width: 300px;
            min-height: 400px;
            height: auto;
            margin: 0 15px 15px 20px;
        }

        .teachers .card:hover {
            box-shadow: 3px 6px 5px 1px #cccccc;
        }

        /*頭像部分*/
        .teachers .sticker {
            width: 100px;
            margin: 10px auto;
            border-radius: 50%;
        }

        /*課程價錢部分*/
        .teachers .price {
            margin: 0 auto;
        }

        .teachers .price span {
            color: grey;
        }

        /*收藏狀態部分*/
        .heart i {
            color: red;
        }

        .follow_btn {
            width: auto;
            /*margin: 0 auto;*/
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .follow_text {
            font-size: 13px;
        }

        /*課程名稱*/
        .teachers .course_information {
            font-size: 20px;
            margin: 10px 0 10px 0;
            border-bottom: 1px dashed grey;
        }

        #course_name_text, #teacher_name_text {
            font-size: 20px;
            color: grey;
        }

        /*師資介紹*/
        .teachers #presentation_text {
            margin-bottom: 10px;
        }

        /*目前價格*/
        .teachers .current_price {
            margin-top: 10px;
        }

        .teachers .current_price b {
            font-size: 20px;
        }

        /*card footer 按鈕*/
        .card_footer_btn {
            padding: 10px;
            margin-top: 80px!important;
        }

    </style>
</head>
<body>
<div id="collection_course_window" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">我的收藏課程</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="teachers">
                        <!--沒有收藏教師訊息 顯示=flex;隱藏=none -->
                        <div class="empty-state" style="display: none"><p>目前沒有收藏的教師</p></div>

                        <div class="row">

                            <!--師資卡 start-->
                            <div class="card col-sm-3">
                                <img class="sticker"
                                     src="resource/image/teacher/live/5d88a7032d2af.png?v=5e351fb547983">

                                <div class="price">
                                    <b>NT$500 /<span> 54 小時</span></b>
                                </div>

                                <button class="follow_btn btn btn-outline-dark">
                                    <span class="heart">
                                        <i class="fa fa-heart"></i>
                                    </span>
                                    <span class="follow_text">取消</span>
                                </button>

                                <div class="course_information">
                                    <p>課程名稱: <span id="course_name_text">trhtrh</span></p>
                                    <p>老師名稱: <span id="teacher_name_text">陳建智</span></p>
                                </div>

                                <p id="presentation_text">正版入門英文教學</p>

<!--                                <div class="row current_price">-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <p><b>NT$240</b></p>-->
<!--                                        <span>25分鐘</span>-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-6">-->
<!--                                        <p><b>NT$496</b></p>-->
<!--                                        <span>50分鐘</span>-->
<!--                                    </div>-->
<!--                                </div>-->

                                <div class="row card_footer_btn">
                                    <button type="button" class="btn btn-primary col-sm-6">前往課程</button>
                                    <div class="col-sm-1"></div> <!--按鈕中間留空-->
                                    <button type="button" class="btn btn-outline-secondary col-sm-4">聯繫老師</button>
                                </div>
                            </div>
                            <!--師資卡 end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>
<?php
