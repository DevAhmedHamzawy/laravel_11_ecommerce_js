@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
    {{ trans('supplier.add_new_supplier') }}
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
                        <a href="{{ route('admin.suppliers.index') }}">{{ trans('supplier.suppliers') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('supplier.add_new_supplier') }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('supplier.add_new_supplier') }}
                    </div>
                    <form action="{{ route('admin.suppliers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="pd-30 pd-sm-40 bg-gray-200">
                            <div class="row row-xs">


                                <div class="col-lg-12">
                                    <div class="bg-gray-200">
                                        <div class="form-group">
                                            <p class="mg-b-10">{{ trans('supplier.country') }}</p>
                                            <select data-level="governorate" onchange="getAreas(this);"
                                                class="form-control select2 @error('area_id') is-invalid @enderror">
                                                <option value="" label="country_id">
                                                    {{ trans('supplier.country') }}
                                                </option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}"
                                                        {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                                        {{ $locale == 'ar' ? $area->name : $area->english }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('area_id')
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
                                            <p class="mg-b-10">{{ trans('supplier.governorate') }}</p>
                                            <select class="form-control select2 @error('area_id') is-invalid @enderror"
                                                id="governorate_id" data-level="city" onfocus="getAreas(this)"
                                                onchange="getAreas(this);">
                                                <option value="" label="governorate_id">
                                                    {{ trans('supplier.city') }}
                                                </option>
                                            </select>
                                            @error('area_id')
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
                                            <p class="mg-b-10">{{ trans('supplier.city') }}</p>
                                            <select class="form-control select2 @error('area_id') is-invalid @enderror"
                                                name="area_id" id="area_id">
                                                <option value="" label="area_id">
                                                    {{ trans('supplier.city') }}
                                                </option>
                                            </select>
                                            @error('area_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('supplier.name') }}</p>
                                        <input class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ trans('supplier.name') }}" type="text" name="name"
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
                                        <p class="mg-b-10">{{ trans('supplier.email') }}</p>
                                        <input class="form-control @error('email') is-invalid @enderror"
                                            placeholder="{{ trans('supplier.email') }}" type="text" name="email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('supplier.phone') }}</p>
                                        <input class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="{{ trans('supplier.phone') }}" type="text" name="phone"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('supplier.address') }}</p>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror textarea" cols="30"
                                            rows="10">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mg-t-10 mg-md-t-0">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('supplier.active') }}</p>
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


    <script>
        function getAreas(item) {
            $.ajax({
                url: "{{ route('get_areas') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: item.value
                },
                dataType: 'json',
                success: function(response) {
                    console.log(item.dataset.level);
                    if (item.dataset.level == 'governorate') {
                        $('#governorate_id').empty();
                        $('#governorate_id').append(
                            `<option value="">اختر المدينة</option>`
                        )
                        response.forEach(function(governorate) {
                            @if ($locale == 'ar')
                                $('#governorate_id').append(
                                    `<option value="${governorate.id}" data-lat="${governorate.latitude}" data-lng="${governorate.longitude}">${governorate.name}</option>`
                                );
                            @else
                                $('#governorate_id').append(
                                    `<option value="${governorate.id}" data-lat="${governorate.latitude}" data-lng="${governorate.longitude}">${governorate.english}</option>`
                                );
                            @endif
                        });
                    } else {
                        $('#area_id').empty();
                        $('#area_id').append(
                            `<option value="">اختر المدينة</option>`
                        )
                        response.forEach(function(city) {
                            @if ($locale == 'ar')
                                $('#area_id').append(
                                    `<option value="${city.id}" data-lat="${city.latitude}" data-lng="${city.longitude}">${city.name}</option>`
                                );
                            @else
                                $('#area_id').append(
                                    `<option value="${city.id}" data-lat="${city.latitude}" data-lng="${city.longitude}">${city.english}</option>`
                                );
                            @endif

                        });
                    }

                }
            })
        }
    </script>
@endsection
