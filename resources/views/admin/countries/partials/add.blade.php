<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createUnitForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('country.add_new_country') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('country.name') }}</label>
                        <input type="text" name="name" class="form-control" />
                        <span class="text-danger error-name"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('country.english') }}</label>
                        <input type="text" name="english" class="form-control" />
                        <span class="text-danger error-english"></span>
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
