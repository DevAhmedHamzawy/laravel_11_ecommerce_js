@extends('admin.layouts.master')

@section('title')
    {{ trans('roles.update_role') }}
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
                    <li class="breadcrumb-item active">{{ trans('roles.update_role') }}</li>
                </ol>
            </nav>


            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('roles.update_role') }} {{ $role->name }}
                    </div>
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">
                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <input class="form-control @error('name') is-invalid @enderror"
                                        placeholder="{{ trans('dashboard.name') }}" type="text" name="name"
                                        value="{{ $role->name }}">
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
                                                    value="{{ $permission->name }}"
                                                    @if ($role->permissions->contains($permission)) checked @endif>
                                                <label
                                                    for="{{ $permission->name }}">{{ trans('permissions.' . $permission->name) }}</label>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                            @endforeach
                                            <br><br>
                                        @endforeach

                                        @error('permissions')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mt-4 mt-xl-0">
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
