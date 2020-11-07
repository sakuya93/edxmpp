<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div id="match_time_window" class="modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="match_time_Title">匹配時間設定</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <span>日期: </span>
                <div class="select_date row">
                    <input type="text" id="select_date" class="form-control col-sm-4" disabled>
                </div>


                <div class="specify_student my-2">
                    <span>指定學生: </span>
                    <input type="text" class="form-control mb-2" id="specifyStudent">
                    <input type="radio" name="designatedMod" id="specifyStudent1" value="0" disabled><label
                            for="specifyStudent1">免費指定</label><br>
                    <input type="radio" name="designatedMod" id="specifyStudent2" value="1" disabled><label
                            for="specifyStudent2">扣除直播課程上課次數指定</label><br>
                </div>

                <span>時間: </span>
                <div class="date_set row">
                    <div class="clockpicker col-sm-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" id="from_match_time_text" value="" class="form-control" autocomplete="off"
                               readonly="readonly">
                    </div>

                    <span>~</span>

                    <div class="clockpicker col-sm-4" data-placement="bottom" data-align="top" data-autoclose="true">
                        <input type="text" id="end_match_time_text" value="" class="form-control" autocomplete="off"
                               readonly="readonly">
                    </div>
                </div>

                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>

                <span>人數上限: </span>
                <div class="max_people row">
                    <input type="number" id="maxPeople" class="form-control col-sm-4" min="0">
                </div>

                <div class="class_content mt-2">
                    <span>上課內容: </span>
                    <textarea class="form-control" id="classContent"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button id="match_time_confirm" type="button" class="btn btn-primary">新增</button>
                <button id="match_time_delete" type="button" class="btn btn-danger" onclick="delete_match_time()"
                        style="display: none">刪除
                </button>
                <button type="button" id="match_time_close" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var specifyStudent = document.querySelector("#specifyStudent")
    specifyStudent.addEventListener("change", function(){
        console.log(specifyStudent.value);
        if(specifyStudent.value){
            document.querySelector("#specifyStudent1").disabled = false;
            document.querySelector("#specifyStudent2").disabled = false;
        }else{
            document.querySelector("#specifyStudent1").disabled = true;
            document.querySelector("#specifyStudent2").disabled = true;
        }
    });
    $(function () {
        $('.clockpicker').clockpicker();
    });
</script>
</html>
<?php
