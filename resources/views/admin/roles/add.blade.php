@extends('admin.layouts.master')

@section('title')
    {{ trans('roles.add_new_role') }}
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
                        <a href="{{ route('admin.roles.index') }}">{{ trans('roles.roles') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('roles.add_new_role') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('roles.add_new_role') }}
                    </div>
                    <form action="{{ route('admin.roles.store') }}" method="post">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">
                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <input class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ trans('dashboard.name') }}" type="text" name="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <br><br><br>
                                <div class="form-group row">
                                    <label for="name" class="col-md-12 col-form-label">
                                        <h3>{{ trans('permissions.permissions') }}</h3>
                                    </label>

                                    <div class="col-md-12 mt-2">
                                        @foreach ($permissions as $key => $value)
                                            <h5>{{ trans('permissions.' . $key) }}</h5>
                                            <br>
                                            @foreach ($value as $permission)
                                                <input type="checkbox" name="permissions[]" id="{{ $permission->name }}"
                                                    value="{{ $permission->name }}">
                                                <label
                                                    for="{{ $permission->name }}">{{ trans('permissions.' . $permission->name) }}</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                            <br><br>
                                        @endforeach
                                    </div>

                                    @error('permissions')
                                        <h2 class="text-danger">{{ $message }}</h2>
                                    @enderror

                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('user.active') }}</p>
                                        <div class="main-toggle main-toggle-success">
                                            <span></span>
                                        </div>
                                        <input type="hidden" id="hiddenInput" name="active" value="0">
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4 mt-xl-0">
                                    <button class="btn btn-main-primary btn-block">{{ trans('dashboard.add') }}</button>
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
