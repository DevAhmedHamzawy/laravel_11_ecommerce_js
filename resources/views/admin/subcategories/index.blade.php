@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('category.subcategories') }}
@endsection

@section('content')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-12">

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
                    <li class="breadcrumb-item active">{{ trans('category.subcategories') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('category.subcategories') }} {{ trans('category.of') }}
                            {{ $category->name }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white"
                                href="{{ route('admin.subcategories.create', $category) }}">
                                {{ trans('category.add_new_subcategory') }}
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary text-white"
                                href="{{ route('admin.subcategories.trash', $category) }}">
                                {{ trans('category.trashed_subcategories') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('dashboard.image') }}</th>
                                    <th class="border-bottom-0">{{ trans('category.ar.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('category.en.name') }}</th>
                                    @canany(['view_category', 'edit_category', 'delete_category', 'active_category',
                                        'restore_category'])
                                        <th class="border-bottom-0">{{ trans('dashboard.actions') }}</th>
                                    @endcanany
                                    <th class="border-bottom-0">{{ trans('dashboard.created') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $subcategory)
                                    <tr>
                                        <td><img width="50" height="50" src="{{ $subcategory->img_path }}"
                                                alt="" srcset=""></td>
                                        <td>{{ $subcategory->translate('ar')->name }}</td>
                                        <td>{{ $subcategory->translate('en')->name }}</td>
                                        @canany(['view_category', 'edit_category', 'delete_category', 'active_category',
                                            'restore_category'])
                                            <td class="row pl-3">
                                                @can('edit_category')
                                                    <a href="{{ route('admin.subcategories.edit', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                        class="btn btn-warning" data-placement="top" data-toggle="tooltip"
                                                        data-original-title="{{ trans('dashboard.edit') }}"><i
                                                            class="fas fa-edit"></i></a>
                                                @endcan
                                                &nbsp;&nbsp;
                                                @can('active_category')
                                                    @if ($subcategory->active == 1)
                                                        <a href="{{ route('admin.subcategories.active', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                            class="btn btn-danger" data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.deactive') }}"><i
                                                                class="fas fa-toggle-off"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.subcategories.active', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                            class="btn btn-info" data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.active') }}"><i
                                                                class="fas fa-toggle-on"></i></a>
                                                    @endif
                                                @endcan
                                                &nbsp;&nbsp;
                                                @can('add_home_category')
                                                    @if ($subcategory->appear_home == 1)
                                                        <a href="{{ route('admin.subcategories.appear_home', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                            class="btn btn-danger" data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('category.disappear_home') }}"><i
                                                                class="fas fa-times"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.subcategories.appear_home', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                            class="btn btn-info" data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('category.appear_home') }}"><i
                                                                class="fas fa-home"></i></a>
                                                    @endif
                                                @endcan
                                                &nbsp;&nbsp;
                                                @can('delete_category')
                                                    <form id="delete-form-{{ $subcategory->slug }}"
                                                        action="{{ route('admin.subcategories.destroy', ['category' => $category, 'subcategory' => $subcategory]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger delete-btn"
                                                            data-id="{{ $subcategory->slug }}" data-placement="top"
                                                            data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.delete') }}" type="button"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endcanany
                                        <td>{{ $subcategory->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
@endsection
