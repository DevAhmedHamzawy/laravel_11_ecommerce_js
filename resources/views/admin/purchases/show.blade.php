@extends('admin.layouts.master')

@section('css')
    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
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
                        <a href="{{ route('admin.purchases.index') }}">{{ trans('purchase.purchases') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $purchase->reference_number }}</li>
                </ol>
            </nav>


            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        {{ trans('dashboard.show') }} {{ trans('purchase.purchase') }} {{ $purchase->reference_number }}
                    </div>
                    <div class="pd-30 pd-sm-40 bg-gray-200">
                        <div class="row row-xs">


                            <div class="col-lg-12">
                                <div class="bg-gray-200">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('purchase.supplier_name') }}</p>
                                        <select name="supplier_id" id="supplier_id"
                                            class="form-control @error('supplier_id') is-invalid @enderror" readonly>

                                            <option value="">{{ trans('purchase.choose') }}</option>

                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    @if ($supplier->id == $purchase->supplier_id) selected @endif>
                                                    {{ $supplier->name }}</option>
                                            @endforeach

                                        </select>

                                        @error('supplier_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row row-xs mg-t-20">


                            <div class="col-lg-6">
                                <div class="bg-gray-200">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('purchase.invoice_date') }}</p>
                                        <input type="date" name="date" value="{{ $purchase->date }}"
                                            class="form-control @error('date') is-invalid @enderror"
                                            placeholder="{{ trans('purchase.invoice_date') }}" readonly>

                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="bg-gray-200">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('purchase.reference_number') }}</p>
                                        <input type="number" name="reference_number"
                                            value="{{ $purchase->reference_number }}"
                                            class="form-control @error('reference_number') is-invalid @enderror"
                                            placeholder="{{ trans('purchase.reference_number') }}" readonly>

                                        @error('reference_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <img src="{{ $purchase->img_path }}" alt="" srcset="">
                            </div>



                            <div class="col-lg-6">
                                <div class="bg-gray-200">
                                    <div class="form-group">
                                        <p class="mg-b-10">{{ trans('purchase.status') }}</p>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror"
                                            readonly>

                                            <option value="">{{ trans('purchase.choose') }}</option>

                                            <option value="pending" @if ($purchase->status == 'pending') selected @endif>
                                                {{ trans('purchase.pending') }}</option>
                                            <option value="completed" @if ($purchase->status == 'completed') selected @endif>
                                                {{ trans('purchase.completed') }}</option>

                                        </select>

                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror


                                    </div>
                                </div>
                            </div>



                        </div>

                        <table class="table mt-4 mx-auto" id="table">
                            <thead style="background-color: #0099ff;color: #fff;">
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('purchase.product_name') }}</th>
                                    <th>{{ trans('purchase.attributes') }}</th>
                                    <th>{{ trans('purchase.unit_price') }}</th>
                                    <th>{{ trans('purchase.qty') }}</th>
                                    <th>{{ trans('purchase.discount') }}</th>
                                    <th>{{ trans('purchase.subtotal_before_vat') }}</th>
                                    <th>{{ trans('purchase.vat') }}</th>
                                    <th>{{ trans('purchase.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->items as $item)
                                    @php $n = rand(0,333)  @endphp
                                    <tr id="r{{ $n }}">
                                        @php $parentLoop = $loop; @endphp
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            @foreach ($item->attributes as $attr)
                                                <input type="hidden" name="attribute_ids[{{ $parentLoop->index }}][]"
                                                    value="{{ $attr->attributeValue->id }}">
                                                <input type="hidden" name="attribute_parents[{{ $parentLoop->index }}][]"
                                                    value="{{ $attr->parent->id }}">
                                                <strong>{{ $attr->parent->name }}</strong>:
                                                {{ $attr->attributeValue->name }} <br>
                                            @endforeach
                                        </td>

                                        <td>{{ $item->unit_cost }}</td>
                                        <td>{{ $item->qty }}</td>

                                        @php $item->discount_sort == 'percentage' ? $specialChar = "%" : $specialChar = "ج.م" @endphp
                                        <td>{{ $item->discount }}{{ $specialChar }}</td>


                                        <td class="prices_after_discount" id="price_after_discount_{{ $n }}">
                                            {{ $item->sub_total }}</td>
                                        <td class="vat_values" id="vat_value_{{ $n }}">
                                            {{ $item->vat }}</td>
                                        <td class="total_prices" id="total_price_{{ $n }}">
                                            {{ $item->total }}
                                        </td>



                                        <input type="hidden" name="unit_prices[]" id="unit_price_{{ $n }}"
                                            value="{{ $item->unit_cost }}">
                                        <input type="hidden" name="discount_sorts[]"
                                            id="discount_sort_{{ $n }}" value="{{ $item->discount_sort }}">
                                        <input type="hidden" name="discount_amounts[]"
                                            id="discount_amount_{{ $n }}" value="{{ $item->discount }}">
                                        <input type="hidden" name="prices_after_discount[]"
                                            id="price_after_discount_{{ $n }}" value="{{ $item->sub_total }}">
                                        <input type="hidden" name="qtys[]" id="qty_{{ $n }}"
                                            value="{{ $item->qty }}">

                                        <input type="hidden" name="product_ids[]" id="product_id_{{ $n }}"
                                            value="{{ $item->product->id }}">

                                        <input type="hidden" name="vats_to_pay[]" id="vat_to_pay_{{ $n }}"
                                            value="{{ $item->vat }}">
                                        <input type="hidden" name="total_prices[]" id="total_price_{{ $n }}"
                                            value="{{ $item->total }}">

                                    </tr>
                                @endforeach
                                <tr class="total_data">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="width: 16%;"> {{ trans('purchase.subtotal_before_vat') }}
                                        <br><br>
                                        {{ trans('purchase.vat') }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div id="subtotal">{{ $purchase->subtotal }} <input type="hidden"
                                                name="subtotal" value="{{ $purchase->subtotal }}"></div>
                                        <br>
                                        <div id="tax">{{ $purchase->vat }} <input type="hidden" name="vat"
                                                value="{{ $purchase->vat }}"></div>
                                    </td>
                                </tr>
                                <tr class="total_data">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ trans('purchase.total') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <div id="total">{{ $purchase->total }} <input type="hidden" name="total"
                                                value="{{ $purchase->total }}"></div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>


                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea name="notes" placeholder="{{ trans('purchase.notes') }}" class="form-control" id=""
                                    cols="30" rows="10">{{ $purchase->notes }}</textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                    </div>


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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
@endsection
