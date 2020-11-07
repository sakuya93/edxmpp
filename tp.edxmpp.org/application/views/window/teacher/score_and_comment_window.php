<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="score_and_comment_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="window_title">評價視窗</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>學生姓名:</p>
                <input class='form-control' id='s_name' type='text' value="" readonly>
                <p>課程名稱:</p>
                <input class='form-control' id='c_name' type='text' value="" readonly>

                <div id="window_content">
                    <!--                    <p class='content_title'>評分:</p>-->
                    <!--                    <input class='form-control score_content' type='number' min='0' max='100' value='0'>-->

                    <div class="rating_block">
                        <p class='content_title'>評分:</p>
                        <div>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div> 1~5分: <input class="data-5d88a7032d2af score_content" type="number" name="quantity"
                                           min="1" max="5">
                        </div>
                    </div>

                    <p class='content_title'>評語:</p>
                    <textarea class='comment_content' cols=\"53\" rows='15'></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button id="window_confirm" type="button" class="btn btn-success">送出<i class="fa fa-paper-plane"></i>
                </button>
                <button id="window_edit" type="button" class="btn btn-info" onclick="">修改<i class="fa fa-pencil"></i>
                </button>
                <button id="window_delete" type="button" class="btn btn-danger" onclick="">刪除<i class="fa fa-close"></i>
                </button>
                <button id="window_close" type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
