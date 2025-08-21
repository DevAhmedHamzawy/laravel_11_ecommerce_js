<!-- Modal: تعديل ضريبة -->
<div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editBrandForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('brand.update_brand') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('dashboard.image') }}</label>
                        <input type="file" name="main_image" class="dropify" />
                        <span class="text-danger error-main_image-edit"></span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('brand.name') }}</label>
                        <input type="text" name="name" class="form-control" />
                        <span class="text-danger error-name-edit"></span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('brand.description') }}</label>
                        <input type="text" name="description" class="form-control" />
                        <span class="text-danger error-code-description"></span>
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
