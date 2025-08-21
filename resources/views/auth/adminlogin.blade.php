@extends('admin.layouts.master2')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection

@section('title')
    {{ trans('login.sign_in') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                <div class="row wd-100p mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                        <img src="{{ URL::asset('assets/img/brand/logo.png') }}"
                            class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">


                                <div class="card-sigin">
                                    <div class="mb-5 d-flex"> <a href="{{ url('/' . ($page = 'index')) }}"><img
                                                src="{{ URL::asset('assets/img/brand/logo.png') }}"
                                                class="sign-favicon ht-40" alt="logo"></a></div>
                                    <div class="card-sigin">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{ route('language', 'en') }}" class="d-flex ">
                                                    <img width="24" height="24"
                                                        src="{{ URL::asset('assets/img/flags/us_flag.jpg') }}"
                                                        alt="img">
                                                    <span class="ml-2 text-dark">{{ trans('dashboard.english') }}</span>
                                                </a>
                                            </div>

                                            <div class="col-md-3">
                                                <a href="{{ route('language', 'ar') }}" class="d-flex ">
                                                    <img width="24" height="24"
                                                        src="{{ URL::asset('assets/img/flags/egypt_flag.jpg') }}"
                                                        alt="img">
                                                    <span class="ml-2 text-dark">{{ trans('dashboard.arabic') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="main-signup-header">
                                            <h2>{{ trans('login.welcome') }}</h2>
                                            <h5 class="font-weight-semibold mb-4">{{ trans('login.please_sign_in') }}</h5>
                                            <form method="POST" action="{{ route('admin.login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label>{{ trans('login.email') }}</label> <input
                                                        placeholder="{{ trans('login.enter_email') }}" type="email"
                                                        name="email"
                                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                        value="{{ old('email') }}">
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('login.password') }}</label> <input
                                                        placeholder="{{ trans('login.enter_password') }}" type="password"
                                                        name="password"
                                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group form-check">
                                                    <input type="checkbox" class="form-check-input" name="remember"
                                                        {{ old('remember') ? 'checked' : '' }} id="exampleCheck1">
                                                    <label class="form-check-label small text-muted pr-3"
                                                        for="exampleCheck1">{{ trans('login.remember_me') }}</label>
                                                </div>

                                                <div class="main-signin-footer mt-5">
                                                    <p><a
                                                            href="{{ route('admin.forget-password') }}">{{ trans('forgot_password.title') }}</a>
                                                    </p>
                                                    </p>
                                                </div>


                                                <button type="submit"
                                                    class="btn btn-main-primary btn-block">{{ trans('login.sign_in') }}</button>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
@endsection
@section('js')
@endsection
