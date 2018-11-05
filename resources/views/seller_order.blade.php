@extends('layouts.master')

@section('title','Live GO 我的訂單')

   
@section('heads')
 <!-- 我新增的 CSS -->
 <link rel="stylesheet" href="css/list_mgnt.css">
     <!-- datatable + bootstrap 4  -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    $(function () {
        $('#order .row .col-md-12 .nav a').on('click', function () {
            $('.nav a').removeClass('selected');
            $(this).addClass('selected');
        });
    });
</script>
@stop


@section('content')
    <div class="wrapper">
        <!-- Sidebar  -->
        <div id="sidebar_page"></div>
        <!-- Page Content  -->
        <div id="content">
            <div id="navbar_page"></div>
            <!--Nav bar end-->
            <!-- main -->
            <div id="order" class="container-fluid main">
                <div class="row">
                    <div class="col-md-12">
                        <!-- 狀態列 -->
                        <div id="order_st_nav " class="container-fluid st_nav">
                            <div class="row">
                                <div class="col-md-12">
                                    <nav class="nav nav-tabs">
                                        <a class="nav-link selected" href="#!">全部</a>
                                        <a class="nav-link tip" href="#!">
                                            <span data-tooltip="{{$countUnpaidOrder}}筆新訂單"> 未付款
                                                <sub>{{$countUnpaidOrder}}</sub>
                                        </a>
                                        <a class="nav-link tip" href="#!">
                                            <span data-tooltip="{{$countUndeliveredOrder}}筆新訂單">等待出貨
                                                <sub>{{$countUndeliveredOrder}}</sub>
                                        </a>
                                        <a class="nav-link tip" href="#!">
                                            <span data-tooltip="{{$countDeliveredOrder}}筆新訂單">運送中
                                                <sub>{{$countDeliveredOrder}}</sub>
                                        </a>
                                        <a class="nav-link tip" href="#!">
                                            <span data-tooltip="{{$countFinishedOrder}}筆新訂單">已完成</a>
                                        <a class="nav-link tip" href="#!">
                                            <span data-tooltip="{{$countCanceledOrder}}筆新訂單">已取消
                                                <sub>{{$countCanceledOrder}}</sub>
                                        </a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <!-- 手機板狀態列 -->
                        <div id="order_st_nav_md" class="container-fluid st_nav_md">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn-block btn st_nav_md_picker" data-toggle="collapse" data-target="#order_st_nav_md_list" aria-expanded="false"
                                        aria-controls="order_st_nav_md_list">訂單狀態</a>
                                    <div id="order_st_nav_md_list" class="collapse multi-collapse st_nav_md_list">
                                        <nav class="nav flex-column">
                                            <a class="btn btn-block btn-light" href="#!">全部</a>
                                            <a class="btn btn-block btn-light" href="#!">未付款
                                                <sub>{{$countUnpaidOrder}}</sub>
                                            </a>
                                            <a class="btn btn-block btn-light" href="#!">等待出貨
                                                <sub>{{$countUndeliveredOrder}}</sub>
                                            </a>
                                            <a class="btn btn-block btn-light" href="#!">運送中
                                                <sub>{{$countDeliveredOrder}}</sub>
                                            </a>
                                            <a class="btn btn-block btn-light" href="#!">已完成 </a>
                                            <a class="btn btn-block btn-light" href="#!">已取消
                                                <sub>{{$countCanceledOrder}}</sub>
                                            </a>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 手機板狀態列end -->
                        <!-- 狀態列end -->

                    </div>
                    <!-- 訂單列表 -->

                    <div id="order_list" class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="table_nocontroler" class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>訂單編號</th>
                                            <th>訂單產生時間</th>
                                            <th>物流</th>
                                            <th>金額</th>
                                            <th>狀態</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="order_item">
                                            <td scope="row">
                                                <img id="order_img" src="img/livego.png" class="img-fluid img" alt="Responsive image">
                                            </td>
                                            <td>10344250</td>
                                            <td>8/7哭誒機壞逮</td>
                                            <td>2018-08-07-19:50:51</td>
                                            <td>87,877$</td>
                                            <td>等待出貨</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- 頁碼 -->
                                <span id="list_table_page" class="list_table_page"></span>
                                <!-- 頁碼end -->
                            </div>
                        </div>
                    </div>
                    <!-- 訂單列表end -->
                </div>
            </div>
            <!-- main end -->
        </div>
        <!-- Cotent end-->
    </div>
    <!-- jQuery CDN - Slim version (=without AJAX) -->
@stop
@section('footer')
    <!--   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
    <!-- My JS -->
    <script src="js/Live_go.js"></script>
    <!-- 我新增的JS -->
    <script src="js/list_mgnt.js"></script>
    <!-- <script src="js/jquery-tablepage-1.0.js"></script> -->
    <!-- <script src="js/moment.js"></script> -->
    <!-- DataTable + Bootstrap 4  cdn引用-->
    <script defer src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script> -->
@stop