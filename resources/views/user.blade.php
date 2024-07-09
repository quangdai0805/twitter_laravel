@extends('layout')
@section('title')
    User Setting 
@endsection
@section('content')
<div class="wrapper wrapper-content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="element-wrapper">
                    <div class="element-header"> THÔNG TIN</div>
                    <div class="element-box">
                        <div id="thongbao"></div>
                        <form>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Số Điện Thoại:</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly="readonly" class="form-control" value="0332544443">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Địa chỉ Email</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <input type="email" id="email" value="" placeholder="Nhập địa chỉ Email để xác minh" class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <input type="text" id="phone" value="0332544443" placeholder="Nhập số điện thoại liên hệ" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Họ và Tên</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <input type="text" id="fullname" value="" placeholder="Nhập họ và tên" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Số dư hiện tại</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly="readonly" class="form-control" value="800">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Thời gian đăng ký</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly="readonly" class="form-control" value="2024-04-23 09:04:30">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mật khẩu mới</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <input type="password" id="password" class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nhập lại mật khẩu mới</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <input type="password" id="repassword" class="form-control" required="">
                                    </div>
                                    <i>Thông tin được mã hóa khi đưa lên máy chủ của chúng tôi.</i>
                                </div>
                            </div>
                            <button type="button" id="ChangeProfile" class="btn btn-primary btn-rounded">
                                <span>LƯU THÔNG TIN</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

</div>
@endsection 
@section('js')
    <script src="js/check_x.js"></script>
@endsection