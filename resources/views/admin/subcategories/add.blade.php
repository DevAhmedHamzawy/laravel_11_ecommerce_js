@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
    {{ trans('category.add_new_subcategory') }}
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
                        <a href="{{ route('admin.categories.index') }}">{{ trans('category.categories') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.subcategories.index', $category) }}">{{ $category->name }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('category.add_new_subcategory') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('category.add_new_subcategory_to') }} {{ $category->name }}
                    </div>
                    <form action="{{ route('admin.subcategories.store', $category) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <input type="file" name="main_image" class="dropify" data-height="200" />
                                    @error('main_image')
                                        <h2 class="text-danger">{{ $message }}</h2>
                                    @enderror
                                </div>


                                @foreach (['ar', 'en'] as $locale)
                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('category.' . $locale . '.name') }}</p>
                                            <input
                                                class="form-control @error('translations.' . $locale . '.name') is-invalid @enderror"
                                                placeholder="{{ trans('category.' . $locale . '.name') }}" type="text"
                                                name="translations[{{ $locale }}][name]"
                                                value="{{ old('translations.' . $locale . '.name') }}">
                                            @error('translations.' . $locale . '.name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('category.' . $locale . '.description') }}</p>
                                            <textarea name="translations[{{ $locale }}][description]"
                                                class="form-control @error('translations.' . $locale . '.description') is-invalid @enderror textarea" cols="30"
                                                rows="10">{{ old('translations.' . $locale . '.description') }}</textarea>
                                            @error('translations.' . $locale . '.description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('category.appear_home') }}</p>
                                        <div class="main-toggle main-toggle-success">
                                            <span></span>
                                        </div>
                                        <input type="hidden" id="hiddenInput" name="appear_home" value="0">
                                    </div>
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

                                <div class="col-md mt-4 mt-xl-0">
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
