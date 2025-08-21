@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('unit.units') }}
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
                    <li class="breadcrumb-item active">{{ trans('unit.units') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('unit.units') }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#addUnitModal">
                                {{ trans('unit.add_new_unit') }}
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary text-white" href="{{ route('admin.units.trash') }}">
                                {{ trans('unit.trashed_units') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example-ajax" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('unit.ar.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('unit.en.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('unit.code') }}</th>
                                    @canany(['view_unit', 'edit_unit', 'delete_unit', 'active_unit', 'restore_unit'])
                                        <th class="border-bottom-0">{{ trans('dashboard.actions') }}</th>
                                    @endcanany
                                    <th class="border-bottom-0">{{ trans('dashboard.created') }}</th>
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

    @include('admin.units.partials.add')

    @include('admin.units.partials.edit')
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
            ajax: '{{ route('admin.units.index') }}',
            columns: [{
                    data: 'translate.ar.name',
                    name: 'translate.ar.name'
                },
                {
                    data: 'translate.en.name',
                    name: 'translate.en.name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
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
                url: '{{ route('admin.units.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('unit.add_success') }}',
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

                        $('.error-' + key.replace(/\./g, '-')).text(message);
                    });
                }
            });
        });

        // فتح مودال التعديل
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            $.get('/admin/units/' + id + '/edit', function(data) {
                $('#editUnitForm').find('input[name="id"]').val(data.id);

                $.each(data.translations, function(index, translation) {
                    $('#editUnitForm')
                        .find(`input[name="translations[${translation.locale}][name]"]`)
                        .val(translation.name);
                });

                $('#editUnitForm').find('input[name="code"]').val(data.code);
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
                url: '/admin/units/' + id,
                method: 'POST',
                data: formData,
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('unit.updated_success') }}',
                        showConfirmButton: true
                    })
                    $('#editUnitModal').modal('hide');
                    $('#editUnitForm .text-danger').text('');
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;

                    Object.keys(errors).forEach(function(key) {
                        $('.error-' + key.replace(/\./g, '-') + '-edit').text(errors[key][0]);
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
                            url: '/admin/units/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function() {
                                swal({
                                    type: 'success',
                                    title: '{{ trans('unit.deleted_success') }}',
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
