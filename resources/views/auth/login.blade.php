<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{url('design/admin')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
          type="text/css"/>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{url('design/admin')}}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('design/admin')}}/assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('design/admin')}}/assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('design/admin')}}/assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css"/>
    <!--end::Global Stylesheets Bundle-->

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .left-side {
            background-image: url('design/admin/assets/media/logos/fruits.jpg');;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .company-logo {
            width: 100%;
            max-width: 100%;
            padding: 10px;
        }

        .bottom-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 20px;
            color: #000;
            background: rgba(255, 255, 255, 0.7);
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            color: white;
        }

        .welcome-text {
            font-size: 42px;
            font-weight: 500;
            margin-bottom: 60px;
            text-align: center;
        }

        .right-side {
            background-color: #014294;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-btn {
            margin-top: 40px;
            padding: 12px !important;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row full-height m-0">
        <!-- Left Side with Image and Logo -->
        <div class="col-lg-6 left-side p-0">
            {{--<img src="{{url('design/admin')}}/assets/media/logos/fruits.jpg" class="company-logo" alt="Company Logo">
            --}}{{--<div class="bottom-text">
                تصميم و تنفيذ شركة الطارق للتقنية و الاستثمار
            </div>--}}
        </div>

        <!-- Right Side with Login Form -->
        <div class="col-lg-6 right-side p-0">
            <div class="login-box">
                <div class="welcome-text">Welcome Back!</div>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label" style="color: white;">Email</label>
                        <input type="text" class="form-control" id="email" name="email" style="padding: 14px;" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label" style="color: white;">Password</label>
                        <input type="password" class="form-control" id="passwordLogin" name="password" style="padding: 14px;" @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordLogin">
                            <i class="fa fa-eye" id="passwordIcon"></i>
                        </button>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="my-6 form-check d-flex justify-content-between">
                        <div>
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" style="color: white;" for="remember">Remember me</label>
                        </div>

                        <div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            @endif
                        </div>
                    </div>

                    <div class="">
                        <input type="submit" class="form-control login-btn" value="Login" style="padding: 14px;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{url('design/admin')}}/assets/plugins/global/plugins.bundle.js"></script>
<script src="{{url('design/admin')}}/assets/js/scripts.bundle.js"></script>
<script src="{{url('design/assets/js/helper.js')}}"></script>
<script src="{{url('design/admin')}}/assets/js/custom.js"></script>
<script src="{{url('design/admin')}}/assets/js/moment.min.js"></script>
<script src="{{url('design/admin')}}/assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    document.getElementById('togglePasswordLogin').addEventListener('click', function () {
        var passwordField = document.getElementById('passwordLogin');
        var passwordIcon = document.getElementById('passwordIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    });
</script>
<!--end::Global Javascript Bundle-->
</body>
</html>

