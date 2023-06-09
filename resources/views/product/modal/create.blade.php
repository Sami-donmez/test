<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('products.store') }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row p-2">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Product Name') }}</label>
                <input type="text" class="form-control" name="item_name" value="{{ old('item_name') }}" required>
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Supplier') }}</label>
                <select class="form-control select2" name="supplier_id">
                    {{ create_option("suppliers","id","supplier_name",old('supplier_id'),array("company_id="=>company_id())) }}
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Product Cost').' '.currency() }}</label>
                <input type="text" class="form-control" name="product_cost" value="{{ old('product_cost') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Product Price') .' '.currency() }}</label>
                <input type="text" class="form-control" name="product_price" value="{{ old('product_price') }}"
                    required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <a href="" data-reload="false"
                    data-title="{{ _lang('Add Product Unit') }}" class="ajax-modal-2 select2-add"><i
                        class="ti-plus"></i> {{ _lang('Add New') }}</a>
                <label class="control-label">{{ _lang('Product Unit') }}</label>
                <select class="form-control select2-ajax" data-value="unit_name" data-display="unit_name"
                    data-table="product_units" data-where="1" name="product_unit" required>
                    <option value="">{{ _lang('- Select Product Unit -') }}</option>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Description') }}</label>
                <textarea class="form-control" name="description">{{ old('description') }}</textarea>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-12">

                <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save"></i>
                    {{ _lang('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
