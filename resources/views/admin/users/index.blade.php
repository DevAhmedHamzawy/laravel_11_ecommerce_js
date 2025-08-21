@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('user.users') }}
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
                    <li class="breadcrumb-item active">{{ trans('user.users') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('user.users') }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white" href="{{ route('admin.users.create') }}">
                                {{ trans('user.add_new_user') }}
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary text-white" href="{{ route('admin.users.trash') }}">
                                {{ trans('user.trashed_users') }}
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
                                    <th class="border-bottom-0">{{ trans('dashboard.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('user.email') }}</th>
                                    @canany(['view_user', 'edit_user', 'delete_user', 'active_user', 'restore_user'])
                                        <th class="border-bottom-0">{{ trans('dashboard.actions') }}</th>
                                    @endcanany
                                    <th class="border-bottom-0">{{ trans('dashboard.created') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><img width="50" height="50" src="{{ $user->img_path }}" alt=""
                                                srcset=""></td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        @canany(['view_user', 'edit_user', 'delete_user', 'active_user', 'restore_user'])
                                            <td class="row pl-3">
                                                @can('edit_user')
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning"
                                                        data-placement="top" data-toggle="tooltip"
                                                        data-original-title="{{ trans('dashboard.edit') }}"><i
                                                            class="fas fa-edit"></i></a>
                                                @endcan
                                                &nbsp;&nbsp;
                                                @can('active_user')
                                                    @if ($user->active == 1)
                                                        <a href="{{ route('admin.users.active', $user->id) }}"
                                                            class="btn btn-danger" data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.deactive') }}"><i
                                                                class="fas fa-toggle-off"></i></a>
                                                    @else
                                                        <a href="{{ route('admin.users.active', $user->id) }}" class="btn btn-info"
                                                            data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.active') }}"><i
                                                                class="fas fa-toggle-on"></i></a>
                                                    @endif
                                                @endcan
                                                &nbsp;&nbsp;
                                                @can('delete_user')
                                                    <form id="delete-form-{{ $user->id }}"
                                                        action="{{ route('admin.users.destroy', $user->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger delete-btn" data-id="{{ $user->id }}"
                                                            data-placement="top" data-toggle="tooltip"
                                                            data-original-title="{{ trans('dashboard.delete') }}" type="button"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endcanany
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
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
