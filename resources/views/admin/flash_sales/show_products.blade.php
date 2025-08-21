@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
    {{ trans('flash_sale.add_new_flash_sale') }}
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
                        <a href="{{ route('admin.flash_sales.index') }}">{{ trans('flash_sale.flash_sales') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('flash_sale.add_new_flash_sale') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('flash_sale.add_new_flash_sale') }}
                    </div>
                    <form action="{{ route('admin.flash_sales.create') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">

                                <div class="mb-3">
                                    <button type="button" id="selectAll"
                                        class="btn btn-sm btn-success">{{ trans('flash_sale.select_all') }}</button>
                                    <button type="button" id="unselectAll"
                                        class="btn btn-sm btn-danger">{{ trans('flash_sale.unselect_all') }}</button>
                                </div>

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <div class="row form-group">
                                        @foreach ($products as $product)
                                            <div class="col-md-3">
                                                <label>
                                                    <input type="checkbox" name="product_ids[]" class="product-checkbox"
                                                        value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                </label>
                                            </div>
                                        @endforeach
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

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function() {
                $('.product-checkbox').prop('checked', true);
            });

            $('#unselectAll').on('click', function() {
                $('.product-checkbox').prop('checked', false);
            });
        });
    </script>
@endsection
