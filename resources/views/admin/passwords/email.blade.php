@extends('admin.layouts.master2')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection

@section('title')
    {{ trans('forgot_password.title') }}
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
                                <div class="mb-5 d-flex"> <a href="{{ url('/' . ($page = 'index')) }}"><img
                                            src="{{ URL::asset('assets/img/brand/logo.png') }}" class="sign-favicon ht-40"
                                            alt="logo"></a></div>
                                <div class="card-sigin">

                                    <div class="main-card-signin d-md-flex">
                                        <div class="wd-100p">
                                            <div class="main-signin-header">
                                                <h2>{{ trans('forgot_password.title') }}</h2>
                                                <h4>{{ trans('forgot_password.enter_email') }}</h4>
                                                <form method="POST" action="{{ route('admin.send-reset-link') }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>{{ trans('login.email') }}</label> <input
                                                            class="form-control"
                                                            placeholder="{{ trans('login.enter_email') }}" type="email"
                                                            name="email">
                                                        @error('email')
                                                            <p>{{ $message }}</p>
                                                        @enderror

                                                        @session('success')
                                                            {{ session('success') }}
                                                        @endsession

                                                        @session('error')
                                                            {{ session('error') }}
                                                        @endsession

                                                    </div>
                                                    <button
                                                        class="btn btn-main-primary btn-block">{{ trans('forgot_password.send_password_reset_link') }}</button>
                                                </form>
                                            </div>
                                            <div class="main-signup-footer mg-t-20">
                                                <p><a href="{{ route('admin.login') }}">{{ trans('login.sign_in') }}</a>
                                                </p>
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
