$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    //collapse 點選其他選單時收起      
    $("#pageSubmenu").on("show.bs.collapse", function () {
        $("#homeSubmenu").collapse('hide');
    });
    $("#homeSubmenu").on("show.bs.collapse", function () {
        $("#pageSubmenu").collapse('hide');
    });

    //關閉視窗
    $(".activity_close").click(function () {
        $("#activity").addClass("d-none");
    });


    $("#time_start").click(function () {
        alertify.prompt('系統訊息', '請確認商品名稱是否為 ㄩㄩ ?'
            , function (evt, value) {
                $("#time_end").removeClass("d-none").addClass("d-block");
                $("#time_start").removeClass("d-block").addClass("d-none");
            });
    });

    $("#time_end").click(function () {
        $("#time_start").removeClass("d-none").addClass("d-block");
        $("#time_end").removeClass("d-block").addClass("d-none");
    });

    $("#btnEdit").click(function () {
        $("#btnSubmit").removeClass("d-none");
        $("#btnEdit").addClass("d-none");
    });

    $("#btnSubmit").click(function () {
        $("#btnEdit").removeClass("d-none");
        $("#btnSubmit").addClass("d-none");
    });

    $("#btnDelete_pic").click(function () {
        $("#Upload_div").addClass("invisible");
        $("#imgInp").val('');
        $("#activity").addClass("d-none");
        $("#blah").removeClass("d-none");
    });


    $('#table_normal').DataTable({
        "columns": [
            { "data": "date" },
            { "data": "income" },
            { "data": "Growth" },
            { "data": "Controler" },
        ],
        language: {
            "sProcessing": "處理中...",
            "sLengthMenu": "_MENU_ 顯示筆數",
            "sZeroRecords": "沒有結果",
            "sInfo": " 顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
            "sInfoPostFix": "", "sSearch": "<i class='icofont icofont-search'> </i>",
            "sUrl": "", "sEmptyTable": "表單沒有任何資料",
            "sLoadingRecords": "載入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首頁",
                "sPrevious": "上頁",
                "sNext": "下頁", "sLast":
                    "末頁"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        }
    });

    $('#table_normal tbody').on('click', 'td button', function () {
        var tr = $(this).closest('tr');
        var row = $('#table_normal').DataTable().row(tr);
        format(row.data());
    });
    function format(d) {
        console.log(d.date + d.income + d.Growth); //後端抓取值
    }



    // shopping dt
    $('#table_cart').DataTable({
        "columns": [
            {
                "defaultContent": '',
                "orderable": false,
                "data": "controler",

            },
            {
                "data": "picture",
                "defaultContent": '',
                "orderable": false,
            },
            { "data": "name" },
            { "data": "price" },
            { "data": "amounth" },
            { "data": "total_price" },
            { "data": "bid_time" },
        ],
        language: {
            "sProcessing": "處理中...",
            "sLengthMenu": "_MENU_ 顯示筆數",
            "sZeroRecords": "沒有結果",
            "sInfo": " 顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
            "sInfoPostFix": "", "sSearch": "<i class='icofont icofont-search'> </i>",
            "sUrl": "", "sEmptyTable": "表單沒有任何資料",
            "sLoadingRecords": "載入中...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "首頁",
                "sPrevious": "上頁",
                "sNext": "下頁", "sLast":
                    "末頁"
            },
            "oAria": {
                "sSortAscending": ": 以升序排列此列",
                "sSortDescending": ": 以降序排列此列"
            }
        }, "order": [[2, 'asc']]
    });

    $('#table_normal tbody').on('click', 'td button', function () {
        var tr = $(this).closest('tr');
        var row = $('#table_normal').DataTable().row(tr);
        format(row.data());
    });
    function format(d) {
        console.log(d.date + d.income + d.Growth); //後端抓取值
    }

});



// checkbox 單選
$('#main #Chose_fan div div div input').click(function () {
    if ($(this).prop('checked')) {
        $('#main #Chose_fan div div div input:checkbox').prop('checked', false);
        $(this).prop('checked', true);
    }
});



//alertfy

$(function () {
    $('.run').click(function (event) {
        alertify.confirm('hello', 'want to fk u ', 'yes').show();
    });
});

//edit img 
$('#pictureEdit').mouseenter(function () {
    $('#pictureEdit').addClass('mh-on')
    $('.pictureEdit_item').removeClass('invisible')
    $(".pictureEdit_item").css({ top: $("#pictureEdit").height() / 4, left: $("#pictureEdit").position().left + $("#pictureEdit").width() / 2.15 });
}).mouseleave(function () {
    $('#pictureEdit').removeClass('mh-on')
    $('.pictureEdit_item').addClass('invisible')
})
$(".pictureEdit_item").click(function () {
    $("#activity").removeClass("d-none");


    //////
    var options =
    {
        imageBox: '.imageBox',
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    }
    var cropper;
    var reader = new FileReader();

    
    options.imgSrc = "img/59891.jpg";
    cropper = new cropbox(options);
    cropper.zoomStart();

    reader.readAsDataURL(options.files[0]);
    options.files = [];
    
});


// function enter_event(event){
//     var x = event.which || event.keyCode;
//    if(x==13){
//        console.log("enter");
//    }

// }





//up load img
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#product_upload_img').attr('src', e.target.result);
            $("#blah").addClass("d-none");
            $("#Upload_div").removeClass("invisible");
            $('#Upload_div').attr('style', 'background-color:#f3f3f3');
            $('#Upload_div').addClass('border');
            $('#Upload_div').mouseenter(function () {
                $('#Upload_div').addClass('mh-on')
                $('.editicon_pic').removeClass('invisible')
                $(".editicon_pic").css({ top: $("#blah").height() / 4, left: $("#Upload_div").position().left + $("#Upload_div").width() / 2.15 });
            }).mouseleave(function () {
                $('#Upload_div').removeClass('mh-on')
                $('.editicon_pic').addClass('invisible')
            })
            $(".editicon_pic").click(function () {
                $("#activity").removeClass("d-none");
                cropper.zoomOut();
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgInp").change(function () {
    readURL(this);
});

window.onload = function () {
    var options =
    {
        imageBox: '.imageBox',
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    }
    var cropper;
    document.querySelector('#imgInp').addEventListener('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            options.imgSrc = e.target.result;
            cropper = new cropbox(options);
            cropper.zoomStart();
        }
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    })
    document.querySelector('#btnCrop').addEventListener('click', function () {
        var img = cropper.getDataURL()
        $('#product_upload_img').attr('src', img);
        $("#activity").addClass("d-none");
    })
    document.querySelector('#btnZoomIn').addEventListener('click', function () {
        cropper.zoomIn();
    })
    document.querySelector('#btnZoomOut').addEventListener('click', function () {
        cropper.zoomOut();
    })
};