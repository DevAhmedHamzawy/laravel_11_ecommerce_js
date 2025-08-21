<!-- Modal: تعديل ضريبة -->
<div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editUnitForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('city.update_city') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('city.name') }}</label>
                        <input type="text" name="name" class="form-control" />
                        <span class="text-danger error-name-edit"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('city.english') }}</label>
                        <input type="text" name="english" class="form-control" />
                        <span class="text-danger error-english-edit"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('city.shipping_cost') }}</label>
                        <input type="text" name="shipping_cost" class="form-control" />
                        <span class="text-danger error-shipping_cost-edit"></span>
                    </div>
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
