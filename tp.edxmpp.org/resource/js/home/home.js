$(document).ready(function () {
    getClassificationData("live");
});

// getClassificationData : 取得分類課程資料
function getClassificationData(option) {
    let convey_data = {
        type: option,
    };

    $.ajax({
        type: "POST",
        url: "student/get_classificationData",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            // console.log(res);
            loadClassification(option, res);
        },
        error: function (e) {
            console.log(e);
        }
    });
}

// loadClassification : 載入課程分類
function loadClassification(option, data) {
    let courseCarousels = $("#courseCarousels");
    courseCarousels.empty();


    // 計算有多少個輪播項目
    let countCarouselItem = data.length / 6;
    if (!Number.isInteger(countCarouselItem)) {
        countCarouselItem = parseInt(countCarouselItem) + 1;
    }

    let carouselItem = [];
    let card;
    let countCards = 0;
    let isAddCardOver = false; // 檢查卡片是否已經放到 6 個了

    // 判斷使用者選擇直播還是影片課程分類
    let courseType;
    if(option == "live"){
        courseType = "l_type";
    }else if(option == "film"){
        courseType = "cf_type";
    }

    let url = "Course_introduction/";

    for (let i = 0; i < countCarouselItem; i++) {
        if (i === 0) {
            carouselItem.push('' +
                '<div class="carousel-item active">' +
                '<div class="row py-2 justify-content-center" id="courseCards' + (i + 1) + '">'
            );
        }else {
            carouselItem.push('' +
                '<div class="carousel-item">' +
                '<div class="row py-2 justify-content-center" id="courseCards' + (i + 1) + '">'
            );
        }

        for (let j = countCards; j < data.length; j++, countCards++) {
            if (isAddCardOver == false && countCards !== 0 && countCards % 6 === 0) {
                isAddCardOver = true;
                break;
            }
            isAddCardOver = false;
            card = '' +
                '<a href="' + (url + option + "/1?s=" + data[j][courseType] + "&c=TWD") + '" class="c-card col-sm-3 m-3" style="text-decoration:none;">' +
                '    <div class="card-body">' +
                '         <div class="row align-items-center py-3 text-monospace">' +
                '              <i class="fas fa-graduation-cap text-primary" style="font-size: 24px"></i>' +
                '              <p class="ml-2 font-weight-bold">' + data[j][courseType] + '</p>' +
                '         </div>' +
                '     </div>' +
                '</a>';
            carouselItem[i] += card;
        }
    }

    courseCarousels.append(carouselItem);
}