@extends('layouts.app')

@section('content')
<form method="post" class="validate" autocomplete="off" action="{{ route('contacts.store') }}"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">{{ _lang('Add New Contact') }}</h4>
                </div>

                <div class="card-body">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Profile Type') }}</label>
                                <select class="form-control select2" name="profile_type" required>
                                    <option value="Company" {{ old('profile_type')=="Company" ? "selected" : "" }}>
                                        {{ _lang('Company') }}</option>
                                    <option value="Individual"
                                        {{ old('profile_type')=="Individual" ? "selected" : "" }}>
                                        {{ _lang('Individual') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Company Name') }}</label>
                                <input type="text" class="form-control" name="company_name"
                                    value="{{ old('company_name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Contact Name') }}</label>
                                <input type="text" class="form-control" name="contact_name"
                                    value="{{ old('contact_name') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Contact Email') }}</label>
                                <input type="email" class="form-control" name="contact_email"
                                    value="{{ old('contact_email') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Contact Phone') }}</label>
                                <input type="text" class="form-control" name="contact_phone"
                                    value="{{ old('contact_phone') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Country') }}</label>
                                <select class="form-control select2" name="country">
                                    {{ get_country_list( old('country') ) }}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Group') }}</label>
                                <select class="form-control select2" name="group_id">
                                    <option value="">{{ _lang('- Select Group -') }}</option>
                                    {{ create_option("contact_groups", "id", "name", old('group_id'), array("company_id="=>company_id())) }}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('City') }}</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Address') }}</label>
                                <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Remarks') }}</label>
                                <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="header-title">{{ _lang('Contact Image') }}</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Contact Image') }}</label>
                                <input type="file" class="form-control dropify" name="contact_image"
                                    data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <div class="col-md-12 mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save"></i> {{ _lang('Save Contact') }}</button>
        </div>

    </div>
</form>
@endsection
