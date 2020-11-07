<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="hand_outDiamonds_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document" id="dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hand_outDiamonds_Title">提示</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="hand_outDiamonds_text">
                <form>
                    <div class="form-group">
                        <label for="selectOptionForUserID">類別</label>
                        <select class="form-control" id="select_OptionForUserID" onchange="selectOptionForUserID();">
                            <option>全體</option>
                            <option>指定</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userID">指定對象ID</label>
                        <input type="text" class="form-control" id="userID" value="all" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label for="point">輸入鑽石</label>
                        <input type="number" class="form-control" id="point">
                    </div>
                    <div class="form-group">
                        <label for="notifyTitle">通知標題</label>
                        <input type="text" class="form-control" id="notifyTitle">
                    </div>
                    <div class="form-group">
                        <label for="notifyContent">通知內容</label>
                        <textarea class="form-control" id="notifyContent" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="confirm" type="button" class="btn btn-primary">確定</button>
                <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
