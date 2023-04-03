@extends('layouts.app')

@section('content')
<form method="post" class="validate" autocomplete="off" action="{{ route('leads.store') }}"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">{{ _lang('Add New Lead') }}</h4>
                </div>

                <div class="card-body">
                    {{ csrf_field() }}

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Titel') }}</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('organization') }}</label>
                                <input type="text" class="form-control" name="organization"
                                    value="{{ old('organization') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Contact Name') }}</label>
                                <input type="text" class="form-control" name="contact_person"
                                    value="{{ old('contact_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('phone') }}</label>
                                                        <input type="text" class="form-control" name="phone"
                                                            value="{{ old('phone') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="control-label">{{ _lang('Email') }}</label>
                                                                                <input type="text" class="form-control" name="email"
                                                                                    value="{{ old('email') }}" required>
                                                                            </div>
                                                                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Amount') }}</label>
                                <input type="number" class="form-control" name="amount"
                                    value="{{ old('amount') }}">
                            </div>
                        </div>
<div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Amount') }}</label>
                                <select  class="form-control" name="currency">
                                    <option>USD</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save"></i> {{ _lang('Save Lead') }}</button>
        </div>

    </div>
</form>
@endsection
