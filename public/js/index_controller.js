$(document).ready(function(){
    var tbody = $("#tbody");
    var table = $("#table");

    setInterval(function showChatMessageAndScroll() {
    // console.log("數量 : " +tbody.children().length);
    //  console.log("scrollTop : " + parseInt($("#tbody")[0].scrollTop+366));
    //  console.log("scrollHeight :" + $("#tbody")[0].scrollHeight);
    var a = parseInt($("#tbody")[0].scrollTop+766);
    var b = $("#tbody")[0].scrollHeight;
                
    if (a >= b)
        tbody.scrollTop($("#tbody")[0].scrollHeight);


    }, 1000);

    // // 取得iframe寬
    // var ifrw = $("#ifrFB>iframe").attr("width");
    // if($("#ifrFB iframe").attr('src').includes("width=360")){
    //     $("#main-top iframe").css({"max-width": "none","left":"0"});
    //     $("#ifrFB").css({"margin-bottom":"-90%","margin-top":"5vh"});
        
    // }else{
       
    //     $("#main-top iframe").css({"max-width": "100%","left":"0vh","margin-top":"5vh"});
    // }
    resizeLiveVideo();
    $(window).resize(resizeLiveVideo);
    function resizeLiveVideo(){
        var ifrw = $("#ifrFB iframe").attr("width");
        var ifrh = $("#ifrFB iframe").attr("height");

        var wraph = $("#ifrFB").height();
        var maintop = $("#main-top").height();
        
    // console.log(ifrw);
    // console.log(ifrh);
    if(ifrw>ifrh){ //電腦版
        $("#main-top #ifrFB iframe").css("margin-top",maintop/6+"px");
        $("#main-top #ifrFB iframe").addClass( "w-100");
        
    }else{      //手機板
        $("#main-top #ifrFB iframe").addClass( "iframe_phone w-50");
        $("#ifrFB").css({"margin-bottom":"none","margin-top":"none"});
    }

    }



    // $("#ifrFB").css({"margin-bottom":"none","margin-top":"none"});
    // $('iframe').attr('id','live_video');
    // $('#live_video').removeAttr('height');
    // $('#live_video').removeAttr('width');
    // $( "#live_video" ).addClass( "float-left" );
    $(".emojionearea-button").append("<div id='comment_icon'><i class='icofont icofont-audio'></i></div>");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //抓取留言
    setInterval(function () {
        ajax();
    }, 5500);
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var now=new Date();
    var time_now=now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+" "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
    function ajax() {
    $.ajax({
            /* the route pointing to the post function */
            url: '/update_message',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: { video_id:'{{$video_id}}',page_token:'{{$token}}',_token:CSRF_TOKEN},   //
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                if(data!=null)
                {
                    $("#tbody").children().remove();
                $.each(data, function(i, comment) {
                    $( "#tbody" ).append(
                    "<tr class='border-bottom'> <td>\
                                <img src='https://graph.facebook.com/"+comment.from.id+"/picture?type=small' />\
                            </td>\
                            <td><h6>"+comment.from.name+
                        "</h6>\
                        <small>"+comment.message+"</small>\
                            </td>\
                            <td>\
                            <input type='hidden' id='message_id' class='message_id' value='"+comment.id+"'>\
                            <input type='hidden' id='winner_id' class='winner_id' value='"+comment.from.id+"'>\
                            <input type='hidden' id='winner_name' class='winner_name' value='"+comment.from.name+"'>\
                            <input type='hidden' id='comment_time' class='comment_time' value='"+time_now+"'>\
                            <input type='hidden' id='comment_message' class='comment_message' value='"+comment.message+"'>\
                                <button type='button' class='btn btn-xm btn-primary' id='reply' onclick='reply(event)'>\
                                    <i class='icofont icofont-speech-comments mr-1'></i>訊息</button>\
                                <button type='button' class='btn btn-xm btn-danger' onclick='bid_win(event)'><i class='icofont icofont-check-circled mr-1'></i>得標</button>\
                            </td></tr>");
                    });
                }
            }
    });
    }
    
    //按開始競標
    $( "#time_start" ).click(function() {
        var goods_name=$("#goods_name").val();
        alertify.prompt('系統訊息', '請確認商品名稱是否為'+goods_name+'?'
    , function (evt, value) {
                        //禁止修改名稱及+1最高價制
                        $("#goods_name").attr("disabled", true);
                        $("#type").attr("disabled", true);
                        //start->end
                        $("#time_start").removeClass("d-block").addClass("d-none");
                        $("#time_end").removeClass("d-none").addClass("d-block");

                        $("#buyer_list").children().remove();
                        $( "#buyer_list" ).append("<li class='list-group-item list-group-item-action list-group-item-info winner_list'>\
                            <B>得標清單</B>\
                        </li>");
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var now=new Date();
            var start_time=now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+" "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
            $.ajax({
                    /* the route pointing to the post function */
                    url: '/start_record',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: { start_time:start_time,page_token:'{{$token}}',post_video_id:'{{$post_video_id}}',goods_name:goods_name,_token:CSRF_TOKEN},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) {
                        
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.responseText);
                    }
            });
    });
    });
    //按結束競標
    $( "#time_end" ).click(function() {
        var goods_name=$("#goods_name").val();
        var type=$("#type").find("option:selected").val();
        var now=new Date();
        var end_time=now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+" "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
        //+1制
        if(type==1)
        {
            $.ajax({
                    /* the route pointing to the post function */
                    url: '/end_record',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data:{ video_id:'{{$video_id}}',page_token:'{{$token}}',end_time:end_time,post_video_id:'{{$post_video_id}}',goods_name:goods_name,_token:CSRF_TOKEN},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) {
                        if(data!="")
                        {
                            // var data = JSON.parse(data);
                            $.each(data, function(i, comment) {
                            $( "#buyer_list" ).append("<li class='list-group-item delete bid_winner'>\
                            <div id='bid-list-iformation' aria-labelledby='Notice'>\
                                <a>\
                                    <img src='https://graph.facebook.com/"+comment.id+"/picture' class='rounded-circle'>\
                                    <div class='text-truncate w-100'>\
                                        <div class='d-flex w-100  justify-content-between '>\
                                            <h6 class='mb-1'>\
                                                <b>"+comment.name+"</b>\
                                            </h6>\
                                        </div>\
                                        <small>"+goods_name+"得標數量：</small>\
                                        <small id='small_num'>"+comment.num+"</small>\
                                    </div>\
                                    <div class='align-middle m-auto '>\
                                        <button type='button' class='btn btn-xm btn-danger btn_delete' onclick='delete_getter(event)'>刪除</button>\
                                    </div>\
                                    <input type='hidden' id='fb_id' value='"+comment.id+"'>\
                                            <input type='hidden' id='message_time' value='"+comment.message_time+"'>\
                                            <input type='hidden' id='message_id' value='"+comment.message_id+"'>\
                                </a>\
                            </div>\
                        </li>");
                                });
                                $( "#buyer_list" ).append("<li class='sticky-bottom list-group-item border-top-0' button_confirm>\
                            <div class='col-md-12 text-center'>\
                                <button type='button' id='confirm' class='btn btn-secondary  btn-block' >確定</button>\
                            </div>\
                        </li>");
                        if($("#buyer_list>li").length==2)
                        {
                            $("#buyer_list").children().remove();
                            $( "#buyer_list" ).append("<li class='list-group-item list-group-item-action list-group-item-info winner_list'>\
                                                        <B>得標清單</B>\
                                                        </li>");
                            $("#goods_name").attr("disabled", false);
                            $("#type").attr("disabled", false);
                        }
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.responseText);
                    }
            });
        }
        //最高價制
        if(type==2)
        {
            $.ajax({
                    /* the route pointing to the post function */
                    url: '/end_record_top_price',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: { video_id:'{{$video_id}}',page_token:'{{$token}}',end_time:end_time,post_video_id:'{{$post_video_id}}',goods_name:goods_name,_token:CSRF_TOKEN},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) {
                        if(data!="")
                        {
                            $( "#buyer_list" ).append("<li class='list-group-item delete bid_winner'>\
                            <div id='bid-list-iformation' aria-labelledby='Notice'>\
                                <a>\
                                    <img src='https://graph.facebook.com/'"+data[0][0].id+"'/picture' class='rounded-circle'>\
                                    <div class='text-truncate w-100'>\
                                        <div class='d-flex w-100  justify-content-between '>\
                                            <h6 class='mb-1'>\
                                                <b>"+data[0][0].name+"</b>\
                                            </h6>\
                                        </div>\
                                        <small>"+goods_name+"得標價錢：</small>\
                                        <small id='small_num'>"+data[0][0].price+"元得標</small>\
                                    </div>\
                                    <div class='align-middle m-auto '>\
                                        <button type='button' class='btn btn-xm btn-danger btn_delete' onclick='delete_getter(event)'>刪除</button>\
                                    </div>\
                                    <input type='hidden' id='fb_id' value='"+data[0][0].id+"'>\
                                            <input type='hidden' id='message_time' value='"+data[0][0].message_time+"'>\
                                            <input type='hidden' id='message_id' value='"+data[0][0].message_id+"'>\
                                </a>\
                            </div>\
                        </li>");
                            
                                $( "#buyer_list" ).append("<li class='sticky-bottom list-group-item border-top-0' button_confirm>\
                            <div class='col-md-12 text-center'>\
                                <button type='button' id='confirm' class='btn btn-secondary  btn-block' >確定</button>\
                            </div>\
                        </li>");
                        if($("#buyer_list>li").length==2)
                        {
                            $("#buyer_list").children().remove();
                            $( "#buyer_list" ).append("<li class='list-group-item list-group-item-action list-group-item-info winner_list'>\
                                                        <B>得標清單</B>\
                                                        </li>");
                            
                        }
                        $("#goods_name").attr("disabled", false);
                        $("#type").attr("disabled", false);
                        }
                        else
                        {
                            alert(JSON.parse(data));
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.responseText);
                    }
            });
        }
        $("#time_end").removeClass("d-block").addClass("d-none");
        $("#time_start").removeClass("d-none").addClass("d-block");
        
    });
    
    //點擊確認後，將得標清單轉成array or json傳至後台存入資料庫
    $('#buyer_list').on('click','#confirm', function(){
        var buyer = [];

        for (i = 2; i < $("#buyer_list>li").length; i++) {
            var name = $("ul li:nth-child("+i+")").find("b").html();
            var comment = $("ul li:nth-child("+i+")").find("#small_num").html();
            var id=$("ul li:nth-child("+i+")").find("#fb_id").val();
            var message_id=$("ul li:nth-child("+i+")").find("#message_id").val();
            tmp = {
                'name': name,
                'comment': comment,
                'id':id,
                'message_id':message_id,
            };

            buyer.push(tmp);
        }
        
        var type=$("#type").find("option:selected").val();
        var goods_name=$("#goods_name").val();
        var note=$("#note").val();
        var goods_price=$("#goods_price").val();
        if(type==2)
        {
            goods_price= $("ul li:nth-child(2)").find("#small_num").html();
        }
        if(isNaN(goods_price)&&type==1)
        {
            alert("成交價格請輸入數字！");
        }
        else
        {
            $.ajax({
                    /* the route pointing to the post function */
                    url: '/store_streaming_order',
                    type: 'POST',
                    /* send the csrf-token and the input to the controller */
                    data: {type:type,buyer:buyer,goods_name:goods_name,note:note,page_token:'{{$token}}',goods_price:goods_price,_token:CSRF_TOKEN},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) {
                        $("#buyer_list").children().remove();
                        $( "#buyer_list" ).append("<li class='list-group-item list-group-item-action list-group-item-info winner_list'>\
                            <B>得標清單</B>\
                        </li>");
                            $("#goods_name").attr("disabled", false);
                        $("#type").attr("disabled", false);
                        alert("得標訊息已私訊得標者!");
                    },
                    error: function(XMLHttpRequest, status, error) {
                        alert(error);
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.responseText);
                    }
            });
        }
    });
});

//訊息
function reply(event) {
    alertify.prompt('私訊', ''
, function (evt, value) {
    var reply_text=$(".ajs-input").val();                //接收傳送的私訊
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var message_id=$(event.target).siblings('.message_id').val();
        $.ajax({
                /* the route pointing to the post function */
                url: '/reply',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: { reply_text:reply_text,message_id:message_id,page_token:'{{$token}}'},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                        //alert('回覆成功！');
                },
                error: function(xhr, status, error) {
                    alert(error);
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.responseText);
                }
        });
});
}
    
//留言處點擊得標
function bid_win(event) {
    if( $( "#time_start" ).hasClass("d-block"))
    {
        
        
        var winner_name=$(event.target).siblings('.winner_name').val();
        var message_id=$(event.target).siblings('.message_id').val();
        var winner_id=$(event.target).siblings('.winner_id').val();
        var comment_time=$(event.target).siblings('.comment_time').val();
        var comment_message=$(event.target).siblings('.comment_message').val();
        if($("#buyer_list>li").length==1)
        {
            $( ".winner_list" ).after("<li class='sticky-bottom list-group-item border-top-0' button_confirm>\
                    <div class='col-md-12 text-center'>\
                        <button type='button' id='confirm' class='btn btn-secondary  btn-block' >確定</button>\
                    </div>\
                </li>");
        }
        $( ".winner_list" ).after("<li class='list-group-item delete bid_winner'>\
                    <div id='bid-list-iformation ' aria-labelledby='Notice '>\
                        <a>\
                            <div class='text-truncate w-100 '>\
                                <div class='d-flex w-100 justify-content-between '>\
                                    <h6 class='mb-1 '>\
                                        <b>"+winner_name+"</b>\
                                    </h6>\
                                    <small class='text-muted float-right ' >\
                                        <button type='button' class='btn btn-xm btn-danger btn_delete' onclick='delete_getter(event)'>刪除</button>\
                                    </small>\
                                    <input type='hidden' id='fb_id' value='"+winner_id+"'>\
                                    <input type='hidden' id='message_time' value='"+comment_time+"'>\
                                    <input type='hidden' id='message_id' value='"+message_id+"'>\
                                <small id='comment'>"+comment_message+"</small></div>\
                            </div>\
                        </a>\
                    </div>\
                </li>");
        
    }
}


//貼文留言
function enter_event(event) {
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var x = event.which || event.keyCode;
if(x==13)
{
    var comment_message=$(".emojionearea-editor").text();
    if(comment_message!='')
    {
        $.ajax({
            /* the route pointing to the post function */
            url: '/add_comment',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {comment:comment_message,post_video_id:'{{$post_video_id}}',page_token:'{{$token}}',_token:CSRF_TOKEN},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                $(".emojionearea-editor").html("");
            },
            error: function(XMLHttpRequest, status, error) {
                $(".emojionearea-editor").html("");
                alert(error);
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.responseText);
            }
        });
    }
}
}

//得標清單點擊刪除
function delete_getter(event)
{
    $(event.target).parents('.delete').remove();
    if($("#buyer_list>li").length==2)
    {
            $("#buyer_list").children().remove();
        $( "#buyer_list" ).append("<li class='list-group-item list-group-item-action list-group-item-info winner_list'>\
                        <B>得標清單</B>\
                    </li>");
                        $("#goods_name").attr("disabled", false);
                    $("#type").attr("disabled", false);
    }
}  