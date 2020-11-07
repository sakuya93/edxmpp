<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <style>
        #recommend_text {
            align-items: center;
            text-align: center;
        }

        #modal_recommend_content {
            position: absolute;
            left: -50px;
            min-height: 460px;
            min-width: 600px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
        }

        p {
            padding: 12px 0 0 0;
        }

        .btn.option{
            width: 133px;
            height: 64px;
            margin-bottom: 10px;
            border: 1px solid #d2d2d2;
        }

        .btn.option:hover{
            border: 1px solid #02cab9;
        }

        .hidden{
            display:none;
        }

        .mt-10{
            margin-top: 10px;
        }

    </style>
</head>
<body>
<div id="recommend_window" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content" id="modal_recommend_content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row justify-content-center" id="recommend_text">
                <div class="hidden" id="recommend_text_page1">
                    <div class="col-sm-12">
                        <h1><?=$recommend_text["page1_title1"]?></h1>
                        <p><?=$recommend_text["page1_title2"]?></p>
                    </div>
                    <div class="col-sm-12">
                        <i class="fa fa-info-circle" style="font-size: 48px"></i>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary col-sm-4" onclick="startChooese()"><?=$recommend_text["start"]?></button>
                    </div>
                </div>

                <div class="hidden" id="recommend_text_page2">
                    <div class="col-sm-12">
                        <h1><?=$recommend_text["page2_title1"]?>?</h1>
                        <p><?=$recommend_text["page2_title2"]?></p>
                    </div>
                    <div class="col-sm-12">
                        <select class="target btn btn-info mt-10">
                                <option value="option1" disabled  selected="selected"><?=$recommend_text["page2_option_disabled"]?></option>
                                <option value="option1"><?=$recommend_text["page2_option1"]?></option>
                                <option value="option2"><?=$recommend_text["page2_option2"]?></option>
                                <option value="option3"><?=$recommend_text["page2_option3"]?></option>
                            </select>
                        </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary mt-10" onclick="nextPage()" id="next-page"><?=$recommend_text["nextPage"]?></button>
                    </div>
                </div>
                
                <div class="hidden" id="recommend_text_page3">
                    <div class="col-sm-12">
                        <h1><?=$recommend_text["page3_title1"]?></h1>
                        <p><?=$recommend_text["page3_title2"]?></p>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn option"><?=$recommend_text["page3_option1"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option2"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option3"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option4"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option5"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option6"]?></button>
                        <button type="button" class="btn option"><?=$recommend_text["page3_option7"]?></button>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary mt-10" onclick="nextPage()" id="next-page"><?=$recommend_text["nextPage"]?></button>
                    </div>
                </div>

                <div class="hidden" id="recommend_text_page4">
                    <div class="col-sm-12">
                        <h1><?=$recommend_text["page4_title1"]?></h1>
                        <p><?=$recommend_text["page4_title2"]?></p>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-primary mt-10" onclick="nextPage()" id="next-page"><?=$recommend_text["nextPage"]?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



</body>
</html>
<?php
