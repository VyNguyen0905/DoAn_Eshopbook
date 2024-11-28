@extends('layout')
@section('content')
<style>
    /* Thêm một số style cho modal */
    #form .container {
        padding: 20px;
        max-width: 840px;
        margin: 0 auto;
    }

    /* Style cho các form đăng nhập và đăng ký */
    .login-form,
    .signup-form {
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: none;
        width: 100%;
        max-width: 380px;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }

    /* Tiêu đề form */
    .login-form h2,
    .signup-form h2 {
        text-align: center;
        color: #333;
        font-size: 20px;
        margin-bottom: 15px;
    }

    /* Định dạng các input */
    .login-form input,
    .signup-form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Nút button */
    .login-form button,
    .signup-form button {
        width: 100%;
        padding: 12px;
        background-color: #5cb85c;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
    }

    /* Responsive thiết kế */
    @media (max-width: 840px) {
        #form .row {
            flex-direction: column;
            align-items: stretch;
        }

        .login-form,
        .signup-form {
            max-width: 100%;
        }
    }
</style>
<section id="form">
    <div class="container">
        <!-- Modal -->
        <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="nav nav-tabs" id="authTab" role="tablist">
                            <!-- Tab Đăng nhập -->
                            <li class="nav-item">
                                <a class="nav-link" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="false">Đăng nhập</a>
                            </li>
                            <!-- Tab Đăng ký (Đặt class 'active' ở đây để tự động chọn khi mở modal) -->
                            <li class="nav-item">
                                <a class="nav-link active" id="signup-tab" data-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="true">Đăng ký</a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <div class="tab-content" id="authTabContent">
                            <!-- Login Form -->
                            <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <div class="login-form">
                                    <h2>Đăng nhập tài khoản</h2>
                                    <form action="{{URL::to('/login-customer')}}" method="POST">
                                        {{csrf_field()}}
                                        <input type="text" name="email_account" placeholder="Tài khoản" />
                                        <input type="password" name="password_account" placeholder="Password" />
                                        <button type="submit" class="btn btn-default">Đăng nhập</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Sign Up Form -->
                            <div class="tab-pane fade show active" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                                <div class="signup-form">
                                    <h2>Đăng ký</h2>
                                    <form action="{{URL::to('/add-customer')}}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="text" name="customer_name" placeholder="Họ và tên" />
                                        <input type="email" name="customer_email" placeholder="Địa chỉ email" />
                                        <input type="password" name="customer_password" placeholder="Mật khẩu" />
                                        <input type="text" name="customer_phone" placeholder="Phone" />
                                        <button id="login" type="submit" class="btn btn-default">Đăng ký</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thêm script để sử dụng Modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    window.onload = function() {
        // Mở modal tự động khi trang được tải
        $('#authModal').modal('show');

        // Mở tab Đăng ký khi modal được hiển thị
        $('#signup-tab').tab('show');
    }
</script>
@endsection