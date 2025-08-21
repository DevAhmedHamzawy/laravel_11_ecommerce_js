@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('city.cities') }}
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
                        <a href="{{ route('admin.countries.index') }}">{{ trans('country.countries') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a
                            href="{{ route('admin.governorates.index', $country) }}">{{ $locale == 'ar' ? $country->name : $country->english }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        {{ trans('governorate.governorates') }}
                    </li>
                    <li class="breadcrumb-item">
                        <a
                            href="{{ route('admin.cities.index', ['country' => $country, 'governorate' => $governorate]) }}">{{ $locale == 'ar' ? $governorate->name : $governorate->english }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('city.cities') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('city.cities') }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#addUnitModal">
                                {{ trans('city.add_new_city') }}
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary text-white"
                                href="{{ route('admin.cities.trash', ['country' => $country, 'governorate' => $governorate]) }}">
                                {{ trans('city.trashed_cities') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example-ajax" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('city.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('city.english') }}</th>
                                    <th class="border-bottom-0">{{ trans('city.shipping_cost') }}</th>
                                    @canany(['view_city', 'edit_city', 'delete_city', 'active_city', 'restore_city'])
                                        <th class="border-bottom-0">{{ trans('dashboard.actions') }}</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

    @include('admin.cities.partials.add')

    @include('admin.cities.partials.edit')
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

    <script>
        // تهيئة DataTable
        const table = $('#example-ajax').DataTable({
            processing: true,
            ajax: '{{ route('admin.cities.index', ['country' => $country, 'governorate' => $governorate]) }}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'english',
                    name: 'english'
                },
                {
                    data: 'shipping_cost',
                    name: 'shipping_cost'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],
            "language": {
                "search": "",
            },
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-primary buttons-copy buttons-html5',
                    text: 'Copy'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-primary buttons-copy buttons-html5',
                    text: 'Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-primary buttons-copy buttons-html5',
                    text: 'PDF'
                },
                {
                    extend: 'colvis',
                    className: 'btn btn-primary buttons-copy buttons-html5',
                    text: 'Column Visibility'
                }
            ],
            pageLength: 10,
            initComplete: function() {
                let searchInput = $('#example-ajax_filter input');
                searchInput.attr('placeholder', 'search...');
                searchInput.addClass('form-control');

                $('#example-ajax_filter label').contents().filter(function() {
                    return this.nodeType === 3; // text node
                }).remove();
            }
        });

        $('#activeToggle').on('click', function() {
            const input = $('input[name="active"]');
            input.val(input.val() === '0' ? '1' : '0');
            $(this).toggleClass('on');
        });

        $('#createUnitForm').on('submit', function(e) {
            e.preventDefault();

            $('#createUnitForm .text-danger').text('');

            $.ajax({
                url: '{{ route('admin.cities.store', ['country' => $country, 'governorate' => $governorate]) }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('city.add_success') }}',
                        showConfirmButton: true
                    })
                    $('#addUnitModal').modal('hide');
                    $('#createUnitForm')[0].reset();
                    $('#createUnitForm .text-danger').text('');
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;

                    $('#createUnitForm .text-danger').text('');

                    Object.keys(errors).forEach(function(key) {
                        let message = errors[key][0];

                        $('.error-' + key).text(message);
                    });
                }
            });
        });

        // فتح مودال التعديل
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const country_id = {!! $country->id !!}
            const governorate_id = {!! $governorate->id !!}
            $.get('/admin/countries/' + country_id + '/governorates/' + governorate_id + '/cities/' + id + '/edit',
                function(data) {
                    $('#editUnitForm').find('input[name="id"]').val(data.id);
                    $('#editUnitForm').find('input[name="name"]').val(data.name);
                    $('#editUnitForm').find('input[name="english"]').val(data.english);
                    $('#editUnitForm').find('input[name="shipping_cost"]').val(data.shipping_cost);
                    $('#editUnitModal').modal('show');
                });
        });

        // تعديل ضريبة
        $('#editUnitForm').on('submit', function(e) {
            e.preventDefault();

            $('#editUnitForm .text-danger').text('');

            let formData = $(this).serialize() + '&_method=PUT';

            let id = $(this).find('input[name="id"]').val();
            $.ajax({
                url: '/admin/countries/' + {!! $country->id !!} + '/governorates/' +
                    {!! $governorate->id !!} + '/cities/' + id,
                method: 'POST',
                data: formData,
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('city.updated_success') }}',
                        showConfirmButton: true
                    })
                    $('#editUnitModal').modal('hide');
                    $('#editUnitForm .text-danger').text('');
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;

                    Object.keys(errors).forEach(function(key) {
                        $('.error-' + key + '-edit').text(errors[key][0]);
                    });
                }
            });
        });

        // حذف ضريبة
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            swal({
                    title: "{{ trans('dashboard.are_you_sure') }}",
                    text: "{{ trans('dashboard.you_will_not_be_able_to_revert_this') }}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{ trans('dashboard.yes') }}",
                    cancelButtonText: "{{ trans('dashboard.no') }}",
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '/admin/countries/' + {!! $country->id !!} + '/governorates/' +
                                {!! $governorate->id !!} + '/cities/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function() {
                                swal({
                                    type: 'success',
                                    title: '{{ trans('city.deleted_success') }}',
                                    showConfirmButton: true
                                })
                                table.ajax.reload();
                            }
                        });
                    }
                });
        });

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            // لو فيه redraw في الـ DataTable (زي pagination):
            $('#example-ajax').on('draw.dt', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
@endsection
