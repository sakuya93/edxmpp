<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="hint_window" class="modal" tabindex="-1" style="z-index: 9999999">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  id="hint_Title">提示</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="hint_text">

            </div>
            <div class="modal-footer" id="hint_footer">
                <button id="confirm" style="display: none;" type="button" class="btn btn-primary">確定</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php
