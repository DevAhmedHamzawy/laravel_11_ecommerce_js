@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
    {{ trans('admin.update_admin') }}
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.admins.index') }}">{{ trans('admin.admins') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('admin.update_admin') }}</li>
                </ol>
            </nav>


            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('admin.update_admin') }} {{ $admin->name }}
                    </div>
                    <form action="{{ route('admin.admins.update', $admin->id) }}" method="post"
                        enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <input type="file" name="main_image" class="dropify" data-height="200" />
                                    @error('main_image')
                                        <h2 class="text-danger">{{ $message }}</h2>
                                    @enderror
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('admin.name') }}</p>
                                        <input class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ trans('admin.name') }}" type="text" name="name"
                                            value="{{ $admin->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('admin.email') }}</p>
                                        <input class="form-control @error('email') is-invalid @enderror"
                                            placeholder="{{ trans('admin.email') }}" type="text" name="email"
                                            value="{{ $admin->email }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('admin.role') }}</p>
                                            <select class="form-control select2 @error('role') is-invalid @enderror"
                                                name="role">
                                                <option value="" label="role">
                                                    {{ trans('admin.role') }}
                                                </option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ $admin->roles->pluck('name')[0] == $role->name ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('admin.password') }}</p>
                                        <input class="form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ trans('admin.password') }}" type="text" name="password"
                                            value="{{ old('password') }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-md mt-4 mt-xl-0">
                                    <button class="btn btn-main-primary btn-block">{{ trans('dashboard.edit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Select2.min js -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-layouts.js') }}"></script>

    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endsection
