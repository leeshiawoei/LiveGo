@extends('layouts.master')

@section('title','Live GO 新增商城商品')
@section('heads')
<script src="js/add_product.js"></script>
@stop

@section('wrapper')
<div class="wrapper">
    <div id="sidebar_page"></div>
@stop
@section('navbar')
    <!-- Page Content  -->
    <div id="content">
        <div id="navbar_page"></div>
        <!--Nav bar end-->
@stop
@section('content')
@if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
@endif
        <div class="main bg-light shadow">
            <h3 class="m-3">新增商城商品</h3>
            <hr>
            <div class="row main">
                <div class="col-12 col-md-6">
                    <form action="{{ route('add_product') }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }}
                        <div class="row mb-2">
                            <div class="col-md-5 ml-3" id="blah">
                                <div class="text-center">
                                <!-- {!! Form::uploadcare('image', null, array('data-crop' => '3:4')) !!} -->
                                    <input type="file" class="custom-file-input" name="image" id="imgInp" required>
                                    <div class="align-middle text-muted mb-4" id="div_text">
                                            <i class="icofont icofont-plus align-middle"></i>
                                            <P>點選新增照片</P>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 ml-3 d-flex invisible" id="Upload_div">
                                <img scr="" id="product_upload_img" class="img-fluid img mh-100 m-auto" />
                            </div>
                            <div class="editicon_pic invisible">
                                <i class="icofont icofont-edit"></i>
                                <br>點選編輯
                            </div>
                        </div>
                        <h4>商品資訊</h4>
                        <div class="form-group">
                            <label for="exampleFormControlInput1"> 商品名稱</label>
                            <input type="text" name="name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1"> 商品分類</label>
                            <select class="form-control form-control-sm" name="category" id="exampleFormControlSelect1">
                                @foreach($categories as $category)
                                    <option>{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"> 商品描述</label>
                            <textarea class="form-control form-control-sm" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <h4>價格與庫存</h4>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">商品價格</label>
                            <input type="number" name="price" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">商品數量</label>
                            <input type="number" name="num" class="form-control form-control-sm">
                        </div>
                        <h4>狀態</h4>
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-sm btn-secondary  active">
                                    <input type="radio" name="status" id="option1" autocomplete="off" value="launched" checked >已上架
                                </label>
                                <label class="btn btn-sm btn-secondary">
                                    <input type="radio" name="status" id="option2" autocomplete="off" value="sold" >已售完
                                </label>
                            </div>
                        </div>
                        <input class="btn btn-info " type="submit" value="送出">
                    </form>
                    {!! Uploadcare::scriptTag() !!}
                </div>
            </div>
        </div>
            <!-- main end -->
    </div>
    <!-- Cotent end-->
    <!-- Edit picture div strat -->
    <div class="col-md-12 d-none" id="activity">
        <div class="card col-offset-4" id="activtiy-content">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <i class="icofont icofont-ui-close float-right activity_close m-1"></i>
                        <div class="imageBox">
                            <div class="thumbBox"></div>
                            <div class="spinner">Loading...</div>
                        </div>
                        <div class="mr-3 mt-2">
                            <button class="btn btn-success btn-sm float-right" id="btnCrop">儲存</button>
                            <button class="btn btn-danger btn-sm float-right mr-2" id="btnDelete_pic">刪除</button>
                            <button type="button" class="btn btn-secondary btn-sm float-right mr-2" id="btnZoomIn">放大</button>
                            <button type="button" class="btn btn-secondary btn-sm float-right mr-2 " id="btnZoomOut">縮小</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit picture div end -->
</div>
<!-- jQuery CDN - Slim version (=without AJAX) -->
@stop 
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
    <!-- My JS -->
    <script src="js/Live_go.js"></script>
    <!-- copping picture js -->
    <script src="js/Cropping.js"></script>
@stop