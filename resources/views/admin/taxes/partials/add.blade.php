<div class="modal fade" id="addTaxModal" tabindex="-1" role="dialog" aria-labelledby="addTaxModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createTaxForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('tax.add_new_tax') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('tax.name') }}</label>
                        <input type="text" name="name" class="form-control" />
                        <span class="text-danger error-name"></span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('tax.code') }}</label>
                        <input type="text" name="code" class="form-control" />
                        <span class="text-danger error-code"></span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('tax.rate') }} (%)</label>
                        <input type="number" name="rate" class="form-control" step="0.01" />
                        <span class="text-danger error-rate"></span>
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
