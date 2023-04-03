@extends('layouts.app')

@section('content')
<form method="post" class="validate" autocomplete="off" action="{{ route('customers.store') }}"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">{{ _lang('Klanten') }}</h4>
                </div>

                <div class="card-body">
                    {{ csrf_field() }}

                    <div class="row">


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
                                <label class="control-label">{{ _lang('City') }}</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                               <div class="form-group">
                                    <label class="control-label">{{ _lang('Zip') }}</label>
                                     <input type="text" class="form-control" name="zip" value="{{ old('city') }}">
                        </div>
                        </div>



                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Address') }}</label>
                                <textarea class="form-control" name="address">{{ old('address') }}</textarea>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">


        <div class="col-md-12 mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save"></i> {{ _lang('Save Contact') }}</button>
        </div>

    </div>
</form>
@endsection
