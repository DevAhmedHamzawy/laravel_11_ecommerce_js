<!-- Modal: تعديل ضريبة -->
<div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editCouponForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('coupon.update_coupon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('coupon.code') }}</label>
                        <input type="text" name="code" class="form-control" />
                        <span class="text-danger error-code-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.type') }}</label>
                        <select name="type" class="form-control">
                            <option value="percent">{{ trans('coupon.type_percent') }}</option>
                            <option value="fixed">{{ trans('coupon.type_fixed') }}</option>
                        </select>
                        <span class="text-danger error-type-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.value') }}</label>
                        <input type="number" name="value" class="form-control" step="0.01" />
                        <span class="text-danger error-value-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.min_order') }}</label>
                        <input type="number" name="min_order" class="form-control" step="0.01" />
                        <span class="text-danger error-min_order-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.max_usage') }}</label>
                        <input type="number" name="max_usage" class="form-control" />
                        <span class="text-danger error-max_usage-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control" />
                        <span class="text-danger error-start_date-edit"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control" />
                        <span class="text-danger error-end_date-edit"></span>
                    </div>
                    <!-- لا تعرض active في التعديل -->
                </div>
                <div class="modal-footer px-3 py-2">
                    <button type="submit" class="btn btn-primary w-50 me-2 rounded-0">
                        {{ trans('dashboard.edit') }}
                    </button>
                    <button type="button" class="btn btn-secondary w-50 rounded-0" data-dismiss="modal">
                        {{ trans('dashboard.close') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
