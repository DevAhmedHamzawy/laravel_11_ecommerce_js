<div class="modal fade" id="addAttributeModal" tabindex="-1" role="dialog" aria-labelledby="addAttributeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="createAttributeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('attribute.add_new_attribute') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach (['ar', 'en'] as $locale)
                        <div class="form-group">
                            <label>{{ trans('attribute.' . $locale . '.name') }}</label>
                            <input type="text" name="translations[{{ $locale }}][name]" class="form-control" />
                            <span class="text-danger error-translations-{{ $locale }}-name"></span>
                        </div>
                    @endforeach
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
