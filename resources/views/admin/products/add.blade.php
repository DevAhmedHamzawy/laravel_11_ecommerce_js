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
    {{ trans('product.add_new_product') }}
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
                    <li class="breadcrumb-item active">{{ trans('product.add_new_product') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('product.add_new_product') }}
                    </div>
                    <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
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
                                                    التصنيف
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                                    فروع التصنيفات
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
                                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                                                        {{ old('tax_id') == $tax->id ? 'selected' : '' }}>
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
                                                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
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
                                            <p class="mg-b-10">{{ trans('product.' . $locale . '.mini_description') }}</p>
                                            <textarea name="translations[{{ $locale }}][mini_description]"
                                                class="form-control @error('translations.' . $locale . '.mini_description') is-invalid @enderror textarea"
                                                cols="30" rows="10">{{ old('translations.' . $locale . '.mini_description') }}</textarea>
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
                                                cols="30" rows="10">{{ old('translations.' . $locale . '.description') }}</textarea>
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
                                            value="{{ old('sku') }}">
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
                                            value="{{ old('barcode') }}">
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
                                            name="selling_price" value="{{ old('selling_price') }}">
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
                                            name="buying_price" value="{{ old('buying_price') }}">
                                        @error('buying_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('product.images') }}</p>
                                        <input id="demo" type="file" name="files"
                                            accept=".jpg, .png, image/jpeg, image/png" multiple>
                                    </div>
                                </div>


                                <div id="uploaded-files"></div>



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

    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>

    <script>
        function getSubCategories(item) {
            $.ajax({
                url: '../../list_subcategories/' + item.value,
                method: 'GET',
                success: function(data) {
                    $('#category_id').empty();
                    for (let subcategory of data) {
                        $('#category_id').append('<option value="' + subcategory.id + '">' + subcategory.name +
                            '</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('خطأ أثناء جلب البيانات:', error);
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
    </script>
@endsection
