@extends('admin.layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ trans('stock.stock') }}
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
                    <li class="breadcrumb-item active">{{ trans('stock.stock') }}</li>
                </ol>
            </nav>


            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0">{{ trans('stock.stock') }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">{{ trans('stock.product_name') }}</th>
                                    <th class="border-bottom-0">{{ trans('stock.attributes') }}</th>
                                    <th class="border-bottom-0">{{ trans('stock.qty') }}</th>
                                    <th class="border-bottom-0">{{ trans('stock.price') }}</th>
                                    @canany(['view_stock'])
                                        <th class="border-bottom-0">{{ trans('dashboard.actions') }}</th>
                                    @endcanany
                                    <th class="border-bottom-0">{{ trans('dashboard.created') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock['product_name'] }}</td>
                                        <td>{{ $stock['attributes'] }}</td>
                                        <td>{{ $stock['qty'] }}</td>
                                        <td>{{ $stock['product_price'] }}</td>
                                        @canany(['view_stock', 'update_stock'])
                                            <td class="row pl-3">
                                                @can('view_stock')
                                                    <a href="{{ route('admin.stocks.show', $stock['id']) }}"
                                                        class="btn btn-warning" data-placement="top" data-toggle="tooltip"
                                                        data-original-title="{{ trans('dashboard.show') }}"><i
                                                            class="fas fa-eye"></i></a>
                                                @endcan
                                                @can('update_stock')
                                                    <button class="btn btn-primary edit-price-btn" data-id="{{ $stock['id'] }}"
                                                        data-price="{{ $stock['product_price'] }}" data-toggle="modal"
                                                        data-target="#editPriceModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        @endcanany

                                        <td>{{ $stock['created_at']->diffForHumans() }}</td>
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

    @include('admin.stocks.partials.edit')
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
        // عند الضغط على زر التعديل يفتح المودال ويملى البيانات
        $(document).on('click', '.edit-price-btn', function() {
            var id = $(this).data('id');
            var price = $(this).data('price');
            $('#stock_id').val(id);
            $('#stock_price').val(price);
        });

        // Ajax update
        $('#savePriceBtn').on('click', function() {
            var id = $('#stock_id').val();
            var price = $('#stock_price').val();

            $.ajax({
                url: "{{ route('admin.stocks.updatePrice') }}",
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    price: price
                },
                success: function(response) {
                    if (response.status) {
                        // عدل السعر في الجدول مباشرة
                        let row = $('button[data-id="' + id + '"]').closest('tr');
                        row.find('td:nth-child(4)').text(response.price);

                        // قفل المودال
                        $('#editPriceModal').modal('hide');

                        // رسالة نجاح
                        swal({
                            type: 'success',
                            title: '{{ trans('stock.updated_success') }}',
                            showConfirmButton: true
                        })
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection
