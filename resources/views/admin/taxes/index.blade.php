@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('tax.taxes') }}
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
                    <li class="breadcrumb-item active">{{ trans('tax.taxes') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('tax.taxes') }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#addTaxModal">
                                {{ trans('tax.add_new_tax') }}
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary text-white" href="{{ route('admin.taxes.trash') }}">
                                {{ trans('tax.trashed_taxes') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example-ajax" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('tax.name') }}</th>
                                    <th class="border-bottom-0">{{ trans('tax.code') }}</th>
                                    <th class="border-bottom-0">{{ trans('tax.rate') }}</th>
                                    @canany(['view_tax', 'edit_tax', 'delete_tax', 'active_tax', 'restore_tax'])
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

    @include('admin.taxes.partials.add')

    @include('admin.taxes.partials.edit')
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
            ajax: '{{ route('admin.taxes.index') }}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'rate',
                    name: 'rate'
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

        $('#createTaxForm').on('submit', function(e) {
            e.preventDefault();

            $('#createTaxForm .text-danger').text('');

            $.ajax({
                url: '{{ route('admin.taxes.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('tax.add_success') }}',
                        showConfirmButton: true
                    })
                    $('#addTaxModal').modal('hide');
                    $('#createTaxForm')[0].reset();
                    $('#createTaxForm .text-danger').text('');
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;

                    $('#createTaxForm .text-danger').text('');

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
            $.get('/admin/taxes/' + id + '/edit', function(data) {
                $('#editTaxForm').find('input[name="id"]').val(data.id);
                $('#editTaxForm').find('input[name="name"]').val(data.name);
                $('#editTaxForm').find('input[name="code"]').val(data.code);
                $('#editTaxForm').find('input[name="rate"]').val(data.rate);
                $('#editTaxModal').modal('show');
            });
        });

        // تعديل ضريبة
        $('#editTaxForm').on('submit', function(e) {
            e.preventDefault();

            $('#editTaxForm .text-danger').text('');

            let formData = $(this).serialize() + '&_method=PUT';

            let id = $(this).find('input[name="id"]').val();
            $.ajax({
                url: '/admin/taxes/' + id,
                method: 'POST',
                data: formData,
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('tax.updated_success') }}',
                        showConfirmButton: true
                    })
                    $('#editTaxModal').modal('hide');
                    $('#editTaxForm .text-danger').text('');
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
                            url: '/admin/taxes/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function() {
                                swal({
                                    type: 'success',
                                    title: '{{ trans('tax.deleted_success') }}',
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
