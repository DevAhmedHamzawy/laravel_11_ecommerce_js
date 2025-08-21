@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('coupon.coupons') }}
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
                    <li class="breadcrumb-item active">{{ trans('coupon.coupons') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('coupon.coupons') }}</h4>
                        <div class="d-flex">
                            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#addCouponModal">
                                {{ trans('coupon.add_new_coupon') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example-ajax" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('coupon.code') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.type') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.value') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.min_order') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.max_usage') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.used_count') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.start_date') }}</th>
                                    <th class="border-bottom-0">{{ trans('coupon.end_date') }}</th>
                                    @canany(['view_coupon', 'edit_coupon', 'delete_coupon', 'active_coupon',
                                        'restore_coupon'])
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

    @include('admin.coupons.partials.add')

    @include('admin.coupons.partials.edit')
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
            ajax: '{{ route('admin.coupons.index') }}',
            columns: [{
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'value',
                    name: 'value'
                },
                {
                    data: 'min_order',
                    name: 'min_order'
                },
                {
                    data: 'max_usage',
                    name: 'max_usage'
                },
                {
                    data: 'used_count',
                    name: 'used_count'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
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

        $('#createCouponForm').on('submit', function(e) {
            e.preventDefault();

            $('#createCouponForm .text-danger').text('');

            $.ajax({
                url: '{{ route('admin.coupons.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('coupon.add_success') }}',
                        showConfirmButton: true
                    })
                    $('#addCouponModal').modal('hide');
                    $('#createCouponForm')[0].reset();
                    $('#createCouponForm .text-danger').text('');
                    table.ajax.reload();
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;

                    $('#createCouponForm .text-danger').text('');

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
            $.get('/admin/coupons/' + id + '/edit', function(data) {
                $('#editCouponForm').find('input[name="id"]').val(data.id);
                $('#editCouponForm').find('input[name="code"]').val(data.code);
                $('#editCouponForm').find('select[name="type"]').val(data.type).trigger('change')
                $('#editCouponForm').find('input[name="value"]').val(data.value);
                $('#editCouponForm').find('input[name="min_order"]').val(data.min_order);
                $('#editCouponForm').find('input[name="max_usage"]').val(data.max_usage);
                $('#editCouponForm').find('input[name="start_date"]').val(data.start_date);
                $('#editCouponForm').find('input[name="end_date"]').val(data.end_date);
                $('#editCouponModal').modal('show');
            });
        });

        // تعديل ضريبة
        $('#editCouponForm').on('submit', function(e) {
            e.preventDefault();

            $('#editCouponForm .text-danger').text('');

            let formData = $(this).serialize() + '&_method=PUT';

            let id = $(this).find('input[name="id"]').val();
            $.ajax({
                url: '/admin/coupons/' + id,
                method: 'POST',
                data: formData,
                success: function() {
                    swal({
                        type: 'success',
                        title: '{{ trans('coupon.updated_success') }}',
                        showConfirmButton: true
                    })
                    $('#editCouponModal').modal('hide');
                    $('#editCouponForm .text-danger').text('');
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
                            url: '/admin/coupons/' + id,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function() {
                                swal({
                                    type: 'success',
                                    title: '{{ trans('coupon.deleted_success') }}',
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
