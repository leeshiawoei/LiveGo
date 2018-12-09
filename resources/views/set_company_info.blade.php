@extends('layouts.master')

@section('title','設定公司資訊')



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
<script>
    message_danger();
</script>
@endif
        <div id="main" class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('set_company_info') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button">公司地址設定</button>
                                </div>
                                <input type="text" name="address" value="{{ $address }}" class="form-control" placeholder="請輸入地址 ..." aria-label="" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button">公司電話設定</button>
                                </div>
                                <input type="text" name="phone" value="{{ $phone }}" class="form-control" placeholder="請輸入電話 ..." aria-label="" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button">銀行代碼</button>
                                </div>
                                <input type="text" class="form-control" placeholder="請輸入銀行代碼 ..." aria-label="" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button">銀行名稱</button>
                                </div>
                                <input type="text" class="form-control" placeholder="請輸入銀行名稱 ..." aria-label="" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" type="button">帳戶名稱</button>
                                </div>
                                <input type="text" class="form-control" placeholder="請輸入帳戶名稱 ..." aria-label="" aria-describedby="basic-addon1">
                            </div>
                            <div class="form-row text-center">
                                <div class="col-12">
                                    <input type="submit" id="btnSubmit" class="btn btn-outline-success" value="修改">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cotent end-->
</div>
@stop 
@section('footer')
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
    <!-- My JS -->
    <script src="js/Live_go.js"></script>
    <!--alertify-->
    <script src="js/Live_go.js"></script>
@stop