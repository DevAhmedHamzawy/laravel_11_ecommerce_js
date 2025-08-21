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
                    <form action="{{ route('admin.flash_sales.show_products') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">

                                <div class="col-sm-12 col-md-12 mb-2">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <button type="button" id="selectAll"
                                                class="btn btn-sm btn-success">{{ trans('flash_sale.select_all') }}</button>
                                            <button type="button" id="unselectAll"
                                                class="btn btn-sm btn-danger">{{ trans('flash_sale.unselect_all') }}</button>
                                        </div>

                                        <div id="categoriesWrapper">
                                            @foreach ($categories as $category)
                                                <div class="mb-2">
                                                    <!-- التصنيف الرئيسي -->
                                                    <label>
                                                        <input type="checkbox" class="category-checkbox parent"
                                                            data-id="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </label>

                                                    @if ($category->children->count())
                                                        <div
                                                            class="ml-4  {{ $locale == 'ar' ? 'pr-3 border-right' : 'pl-3 border-left' }}">
                                                            @foreach ($category->children as $child)
                                                                <label class="d-block">
                                                                    <input type="checkbox" class="category-checkbox child"
                                                                        data-parent-id="{{ $category->id }}"
                                                                        name="category_ids[]" value="{{ $child->id }}">
                                                                    -- {{ $child->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
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
            // تحديد كل التصنيفات
            $('#selectAll').on('click', function() {
                $('.category-checkbox').prop('checked', true);
            });

            // إلغاء تحديد كل التصنيفات
            $('#unselectAll').on('click', function() {
                $('.category-checkbox').prop('checked', false);
            });

            // لما تحدد أو تفك تحديد parent → حدد أو فك تحديد أولاده
            $('.parent').on('change', function() {
                const parentId = $(this).data('id');
                const isChecked = $(this).is(':checked');
                $(`.child[data-parent-id="${parentId}"]`).prop('checked', isChecked);
            });

            // لما تتحدد أو تتفك child → اتحكم في parent لو كلهم متحددين أو لأ
            $('.child').on('change', function() {
                const parentId = $(this).data('parent-id');
                const allChildren = $(`.child[data-parent-id="${parentId}"]`);
                const allChecked = allChildren.length === allChildren.filter(':checked').length;
                $(`.parent[data-id="${parentId}"]`).prop('checked', allChecked);
            });
        });
    </script>
@endsection
