@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!--Internal  Datetimepicker-slider css -->
    <link href="{{ URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/pickerjs/picker.min.css') }}" rel="stylesheet">

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
                    <form action="{{ route('admin.flash_sales.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @php
                            $product_ids = old('product_ids', $product_ids ?? []);
                        @endphp

                        @foreach ($product_ids as $id)
                            <input type="hidden" name="product_ids[]" value="{{ $id }}">
                        @endforeach

                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('flash_sale.name') }}</p>
                                        <input class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ trans('flash_sale.name') }}" type="text" name="name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('flash_sale.start_time') }}</p>
                                        <input class="form-control @error('start_time') is-invalid @enderror"
                                            placeholder="{{ trans('flash_sale.start_time') }}" type="text"
                                            id="datetimepicker" name="start_time" value="{{ old('start_time') }}">
                                        @error('start_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('flash_sale.end_time') }}</p>
                                        <input class="form-control @error('end_time') is-invalid @enderror"
                                            placeholder="{{ trans('flash_sale.end_time') }}" type="text"
                                            id="datetimepicker2" name="end_time" value="{{ old('end_time') }}">
                                        @error('end_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('flash_sale.discount') }}</p>
                                        <input class="form-control @error('discount') is-invalid @enderror"
                                            placeholder="{{ trans('flash_sale.discount') }}" type="text" name="discount"
                                            value="{{ old('discount') }}">
                                        @error('discount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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

    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>

    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
    <!-- Ionicons js -->
    <script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
    <!--Internal  pickerjs js -->
    <script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>

    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>


    <script>
        $(document).ready(function() {


            // AmazeUI Datetimepicker
            $("#datetimepicker").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                autoclose: true,
            });

            // AmazeUI Datetimepicker
            $("#datetimepicker2").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                autoclose: true,
            });
        });
    </script>
@endsection
