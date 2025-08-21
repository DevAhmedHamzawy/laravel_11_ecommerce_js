@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
@endsection

@section('title')
    {{ trans('page.update_page') }}
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
                        <a href="{{ route('admin.pages.index') }}">{{ trans('page.pages') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('page.update_page') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('page.update_page') }} {{ $page->translate($locale)->title }}
                    </div>
                    <form action="{{ route('admin.pages.update', $page->id) }}" method="post"
                        enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">


                                @foreach (['ar', 'en'] as $locale)
                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('page.' . $locale . '.title') }}</p>
                                            <input
                                                class="form-control @error('translations.' . $locale . '.title') is-invalid @enderror"
                                                placeholder="{{ trans('page.' . $locale . '.title') }}" type="text"
                                                name="translations[{{ $locale }}][title]"
                                                value="{{ old('translations.' . $locale . '.title', $page->translate($locale)->title) }}">
                                            @error('translations.' . $locale . '.title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('page.' . $locale . '.content') }}</p>
                                            <textarea name="translations[{{ $locale }}][content]"
                                                class="form-control @error('translations.' . $locale . '.content') is-invalid @enderror textarea" cols="30"
                                                rows="10">{{ old('translations.' . $locale . '.content', $page->translate($locale)->content) }}</textarea>
                                            @error('translations.' . $locale . '.content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach


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
@endsection
