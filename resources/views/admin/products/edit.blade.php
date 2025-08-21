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
    {{ trans('product.update_product') }}
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
                        <a href="{{ route('admin.products.index') }}">{{ trans('product.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('product.update_product') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('product.update_product') }} {{ $product->translate($locale)->name }}
                    </div>
                    <form action="{{ route('admin.products.update', $product->id) }}" method="post"
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


                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.categories') }}</p>
                                            <select onchange="getSubCategories(this);"
                                                class="form-control select2 @error('category_id') is-invalid @enderror">
                                                <option value="" label="category_id">
                                                    {{ trans('product.categories') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $theCategory == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.subcategories') }}</p>
                                            <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                                name="category_id" id="category_id">
                                                <option value="" label="category_id">
                                                    {{ trans('product.subcategories') }}
                                                </option>
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.brands') }}</p>
                                            <select name="brand_id"
                                                class="form-control select2 @error('brand_id') is-invalid @enderror">
                                                <option value="" label="brand_id">
                                                    التصنيف
                                                </option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.taxes') }}</p>
                                            <select name="tax_id"
                                                class="form-control select2 @error('tax_id') is-invalid @enderror">
                                                <option value="" label="tax_id">
                                                    التصنيف
                                                </option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->id }}"
                                                        {{ $product->tax_id == $tax->id ? 'selected' : '' }}>
                                                        {{ $tax->code }} - {{ $tax->rate }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tax_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.units') }}</p>
                                            <select name="unit_id"
                                                class="form-control select2 @error('unit_id') is-invalid @enderror">
                                                <option value="" label="unit_id">
                                                    التصنيف
                                                </option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unit_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @foreach (['ar', 'en'] as $locale)
                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.' . $locale . '.name') }}</p>
                                            <input
                                                class="form-control @error('translations.' . $locale . '.name') is-invalid @enderror"
                                                placeholder="{{ trans('product.' . $locale . '.name') }}" type="text"
                                                name="translations[{{ $locale }}][name]"
                                                value="{{ old('translations.' . $locale . '.name', $product->translate($locale)->name) }}">
                                            @error('translations.' . $locale . '.name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.' . $locale . '.mini_description') }}</p>
                                            <textarea name="translations[{{ $locale }}][mini_description]"
                                                class="form-control @error('translations.' . $locale . '.mini_description') is-invalid @enderror textarea"
                                                cols="30" rows="10">{{ old('translations.' . $locale . '.mini_description', $product->translate($locale)->mini_description) }}</textarea>
                                            @error('translations.' . $locale . '.mini_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-12 mg-t-10 mg-md-t-0">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('product.' . $locale . '.description') }}</p>
                                            <textarea name="translations[{{ $locale }}][description]"
                                                class="form-control @error('translations.' . $locale . '.description') is-invalid @enderror textarea"
                                                cols="30" rows="10">{{ old('translations.' . $locale . '.description', $product->translate($locale)->description) }}</textarea>
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
                                        <p class="mg-b-10">{{ trans('product.sku') }}</p>
                                        <input class="form-control @error('sku') is-invalid @enderror"
                                            placeholder="{{ trans('product.sku') }}" type="text" name="sku"
                                            value="{{ $product->sku }}">
                                        @error('sku')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('product.barcode') }}</p>
                                        <input class="form-control @error('barcode') is-invalid @enderror"
                                            placeholder="{{ trans('product.barcode') }}" type="text" name="barcode"
                                            value="{{ $product->barcode }}">
                                        @error('barcode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('product.selling_price') }}</p>
                                        <input class="form-control @error('selling_price') is-invalid @enderror"
                                            placeholder="{{ trans('product.selling_price') }}" type="text"
                                            name="selling_price" value="{{ $product->selling_price }}">
                                        @error('selling_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('product.buying_price') }}</p>
                                        <input class="form-control @error('buying_price') is-invalid @enderror"
                                            placeholder="{{ trans('product.buying_price') }}" type="text"
                                            name="buying_price" value="{{ $product->buying_price }}">
                                        @error('buying_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row" id="existing-images">
                                    @foreach ($product->images as $image)
                                        <div class="col-md-3 image-wrapper" data-id="{{ $image->id }}">
                                            <img src="{{ $image->img_path }}" width="100">
                                            <button type="button" class="btn btn-danger btn-sm delete-image"
                                                data-id="{{ $image->id }}">حذف</button>
                                        </div>
                                    @endforeach
                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('product.images') }}</p>
                                        <input id="demo" type="file" name="files"
                                            accept=".jpg, .png, image/jpeg, image/png" multiple>
                                    </div>
                                </div>


                                <div id="uploaded-files"></div>



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

    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>


    <script>
        $(document).ready(function() {
            let itemone = {};
            itemone.value = '{!! $theCategory !!}';
            getSubCategories(itemone);
        });

        function getSubCategories(itemone) {
            $.ajax({
                url: '../../../list_subcategories/' + itemone.value,
                method: 'GET',
                success: function(data) {
                    $('#category_id').empty();
                    for (let subcategory of data) {
                        let selected = (subcategory.id == {{ $theSubCategory->id }}) ? 'selected' : '';
                        $('#category_id').append('<option value="' + subcategory.id + '" ' + selected + '>' +
                            subcategory.name + '</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching subcategories:', error);
                }
            });
        }

        $('#demo').FancyFileUpload({
            url: "{{ route('admin.products.upload') }}",
            params: {
                _token: '{{ csrf_token() }}',
                section: 'products',
            },
            maxfilesize: 10000000,
            autoUpload: true,

            uploadcompleted: function(e, data) {
                alert('✅ تم رفع الصورة بنجاح');
                if (data.result.success) {
                    const fileName = data.result.filename;

                    // نحط اسم الصورة في input hidden
                    $('#uploaded-files').append(
                        `<input type="hidden" name="images[]" value="${fileName}">`
                    );
                } else {
                    alert('خطأ: ' + data.message);
                }
            },
        });

        $(document).on('click', '.delete-image', function() {
            let imageId = $(this).data('id');
            let wrapper = $(this).closest('.image-wrapper');

            if (confirm('هل أنت متأكد من حذف الصورة؟')) {
                $.ajax({
                    url: '{{ route('admin.products.delete_image') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: imageId
                    },
                    success: function(res) {
                        if (res.success) {
                            wrapper.remove();
                        } else {
                            alert('فشل في الحذف');
                        }
                    },
                    error: function() {
                        alert('حدث خطأ');
                    }
                });
            }
        });
    </script>
@endsection
