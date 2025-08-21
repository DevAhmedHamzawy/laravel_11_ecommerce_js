<div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="addCouponModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createCouponForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('coupon.add_new_coupon') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('coupon.code') }}</label>
                        <input type="text" name="code" class="form-control" />
                        <span class="text-danger error-code"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.type') }}</label>
                        <select name="type" class="form-control">
                            <option value="percent">{{ trans('coupon.type_percent') }}</option>
                            <option value="fixed">{{ trans('coupon.type_fixed') }}</option>
                        </select>
                        <span class="text-danger error-type"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.value') }}</label>
                        <input type="number" name="value" class="form-control" step="0.01" />
                        <span class="text-danger error-value"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.min_order') }}</label>
                        <input type="number" name="min_order" class="form-control" step="0.01" />
                        <span class="text-danger error-min_order"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.max_usage') }}</label>
                        <input type="number" name="max_usage" class="form-control" />
                        <span class="text-danger error-max_usage"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control" />
                        <span class="text-danger error-start_date"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('coupon.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control" />
                        <span class="text-danger error-end_date"></span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('dashboard.active') }}</label>
                        <div class="main-toggle main-toggle-success" id="activeToggle">
                            <span></span>
                        </div>
                        <input type="hidden" name="active" value="0">
                    </div>
                </div>

                <div class="modal-footer d-flex px-3 py-2">
                    <button type="submit" class="btn btn-primary w-50 me-2 rounded-0">
                        {{ trans('dashboard.add') }}
                    </button>
                    <button type="button" class="btn btn-secondary w-50 rounded-0" data-dismiss="modal">
                        {{ trans('dashboard.close') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
