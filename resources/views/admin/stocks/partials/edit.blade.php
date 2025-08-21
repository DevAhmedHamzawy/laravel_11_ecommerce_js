<!-- Edit Price Modal -->
<div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog" aria-labelledby="editPriceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('stock.edit_price') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="stock_id">
                <div class="form-group">
                    <label>{{ trans('stock.price') }}</label>
                    <input type="number" step="0.01" id="stock_price" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ trans('dashboard.close') }}</button>
                <button type="button" class="btn btn-success" id="savePriceBtn">{{ trans('dashboard.add') }}</button>
            </div>
        </div>
    </div>
</div>
