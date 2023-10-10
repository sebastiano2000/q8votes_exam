@extends('layouts.app')
@section('content')
    <section id="wrapper" class="wrap-login">
        <div class="login-register" style="background-image:url(upload/loginbackground.jpeg);padding-top: 10%;">
            

            <div class="login-box card">
                <div class="card-body" style="">
                    <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">
                        @csrf
                        <h3 class="text-center m-b-20">تسجيل الدخول</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="الايميل" name="email" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="كلمة السر" name="password" value=""> </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button style="margin-top:2%;" class="btn btn-block btn-lg btn-info btn-rounded" type="submit" name="login">تسجيل الدخول</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!--<div class="login-register" style="background-image:url(upload/loginbackground.jpeg);padding-top: 10%;">-->
        <!--    <div class="login-box card">-->
        <!--        <div class="card-body" style="">-->
        <!--            <form class="form-horizontal form-material" id="loginform" action="{{ route('login') }}" method="POST">-->
        <!--                @csrf-->
        <!--                <h3 class="text-center m-b-20">تسجيل الدخول</h3>-->
        <!--                <div class="form-group ">-->
        <!--                    <div class="col-xs-12 ">-->
        <!--                        <input class="form-control-login form-control @error('email') is-invalid @enderror" type="text" required placeholder="الايميل" autocomplete="email" autofocus name="email" value="{{ old('email') }}">-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="form-group">-->
        <!--                    <div class="col-xs-12">-->
        <!--                        <input id="password" type="password" class="form-control-login form-control @error('password') is-invalid @enderror" placeholder="كلمة السر" name="password" required autocomplete="current-password" value="">-->
        <!--                </div>-->
        <!--                <a style="margin:0 5px 10px 0;" href="/?forget_password=1">نسيت كلمة المرور ؟</a>-->
        <!--                <div class="form-group text-center">-->
        <!--                    <div class="col-xs-12 p-b-20">-->
        <!--                        <button style="margin-top:2%;" class="btn btn-block btn-lg btn-info btn-rounded" type="submit" name="login">تسجيل الدخول</button>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </form>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </section>
@endsection
