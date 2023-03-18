@extends('layouts.app')

@section('content')
<h4 class="page-title">{{ _lang('Company Settings') }}</h4>
<div class="row">
    <div class="col-sm-3">
        <ul class="nav flex-column nav-tabs settings-tab" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general"><i
                        class="ti-settings"></i> {{ _lang('General Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoice_settings"><i
                        class="ti-receipt"></i> {{ _lang('Invoice & Quotation Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payment_gateway"><i
                        class="ti-credit-card"></i> {{ _lang('Payment Gateway') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logo"><i class="ti-image"></i>
                    {{ _lang('Logo') }}</a></li>
        </ul>
    </div>

    <div class="col-sm-9">
        <div class="tab-content">

            <div id="general" class="tab-pane active">
                <div class="card card-default">
                    <div class="card-header"><span class="header-title">{{ _lang('Company Settings') }}</span></div>

                    <div class="card-body">
                        <form method="post" class="settings-submit params-card" autocomplete="off"
                            action="{{ route('company.change_settings', 'store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Company Name') }}</label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="{{ get_company_option('company_name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Phone') }}</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ get_company_option('phone') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Email') }}</label>
                                        <input type="text" class="form-control" name="email"
                                            value="{{ get_company_option('email') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Currency') }}</label>
                                        <select class="form-control select2 auto-select"
                                            data-selected="{{ get_company_option('currency', get_option('currency')) }}"
                                            name="currency" id="currency" required>
                                            <option value="">{{ _lang('Select One') }}</option>
                                            {{ get_currency_list() }}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('VAT ID') }}</label>
                                        <input type="text" class="form-control" name="vat_id"
                                            value="{{ get_company_option('vat_id') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Language') }}</label>
                                        <select class="form-control select2" name="language">
                                            <option value="">{{ _lang('Select One') }}</option>
                                            {{ load_language( get_company_option('language') ) }}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Theme Direction') }}</label>
                                        <select class="form-control" name="backend_direction" required>
                                            <option value="ltr"
                                                {{ get_company_option('backend_direction') == 'ltr' ? 'selected' : '' }}>
                                                {{ _lang('LTR') }}</option>
                                            <option value="rtl"
                                                {{ get_company_option('backend_direction') == 'rtl' ? 'selected' : '' }}>
                                                {{ _lang('RTL') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Address') }}</label>
                                        <textarea class="form-control"
                                            name="address">{{ get_company_option('address') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="ti-save"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="invoice_settings" class="tab-pane fade">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="header-title">{{ _lang('Invoice & Quotation Settings') }}</span>
                    </div>
                    <div class="card-body">
                        <form method="post" class="settings-submit params-card" autocomplete="off"
                            action="{{ route('company.change_settings', 'store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Invoice Prefix') }}</label>
                                        <input type="text" class="form-control" name="invoice_prefix"
                                            value="{{ get_company_option('invoice_prefix') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Next Invoice Number') }}</label>
                                        <input type="number" class="form-control" name="invoice_starting" min="1"
                                            value="{{ get_company_option('invoice_starting',1001) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Quotation Prefix') }}</label>
                                        <input type="text" class="form-control" name="quotation_prefix"
                                            value="{{ get_company_option('quotation_prefix') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Next Quotation Number') }}</label>
                                        <input type="number" class="form-control" name="quotation_starting" min="1"
                                            value="{{ get_company_option('quotation_starting',1001) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Invoice Footer') }}</label>
                                        <textarea class="form-control summernote"
                                            name="invoice_footer">{{ get_company_option('invoice_footer') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Quotation Footer') }}</label>
                                        <textarea class="form-control summernote"
                                            name="quotation_footer">{{ get_company_option('quotation_footer') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="ti-save"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="payment_gateway" class="tab-pane fade">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="header-title">{{ _lang('Payment Gateway') }}</span>
                    </div>
                    <div class="card-body">
                        <form method="post" class="settings-submit params-card" autocomplete="off"
                            action="{{ route('company.change_settings', 'store') }}" enctype="multipart/form-data">

                            @csrf

                            <h5 class="header-title">{{ _lang('Invoice Payment Configuration') }}</h5>
                            <div class="params-card border border-secondary p-3 mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Default Account') }}</label>
                                            <select class="form-control select2" name="default_account" required>
                                                <option value="">{{ _lang('Select One') }}</option>
                                                {{ create_option("accounts","id","account_title",get_company_option('default_account'),array("company_id="=>company_id())) }}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Default Income Category') }}</label>
                                            <select class="form-control select2" name="default_chart_id" required>
                                                <option value="">{{ _lang('Select One') }}</option>
                                                {{ create_option("chart_of_accounts","id","name",get_company_option('default_chart_id'),array("type="=>"income","AND company_id="=>company_id())) }}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <h5 class="header-title">{{ _lang('PayPal Checkout Configuration') }}</h5>
                            <div class="params-card border border-secondary p-3 mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('PayPal Active') }}</label>
                                            <select class="form-control" name="paypal_active" required>
                                                <option value="no"
                                                    {{ get_company_option('paypal_active') == 'no' ? 'selected' : '' }}>
                                                    {{ _lang('No') }}</option>
                                                <option value="yes"
                                                    {{ get_company_option('paypal_active') == 'yes' ? 'selected' : '' }}>
                                                    {{ _lang('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('PayPal Mode') }}</label>
											<select class="form-control auto-select"
												data-selected="{{ get_company_option('paypal_mode','sandbox') }}"
												name="paypal_mode" required>
												<option value="sandbox">{{ _lang('Sandbox') }}</option>
												<option value="production">{{ _lang('Production') }}</option>
											</select>
										</div>
									</div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Client ID') }}</label>
                                            <input type="text" class="form-control" name="paypal_client_id"
                                                value="{{ get_company_option('paypal_client_id') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Secret') }}</label>
                                            <input type="text" class="form-control" name="paypal_secret"
                                                value="{{ get_company_option('paypal_secret') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <h5 class="header-title">{{ _lang('Stripe Configuration') }}</h5>
                            <div class="params-card border border-secondary p-3 mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Stripe Active') }}</label>
                                            <select class="form-control" name="stripe_active" required>
                                                <option value="no"
                                                    {{ get_company_option('stripe_active') == 'no' ? 'selected' : '' }}>
                                                    {{ _lang('No') }}</option>
                                                <option value="yes"
                                                    {{ get_company_option('stripe_active') == 'yes' ? 'selected' : '' }}>
                                                    {{ _lang('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Secret Key') }}</label>
                                            <input type="text" class="form-control" name="stripe_secret_key"
                                                value="{{ get_company_option('stripe_secret_key') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ _lang('Publishable Key') }}</label>
                                            <input type="text" class="form-control" name="stripe_publishable_key"
                                                value="{{ get_company_option('stripe_publishable_key') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg"><i
                                                class="ti-save"></i> {{ _lang('Save Settings') }}</button>
                                    </div>
                                </div>
                            </div>
                            <!--End Stripe Configuration-->
                        </form>
                    </div>
                </div>
            </div>


            <div id="logo" class="tab-pane fade">
                <div class="card card-default">
                    <div class="card-header"><span class="header-title">{{ _lang('Logo Upload') }}</span></div>
                    <div class="card-body">
                        <form method="post" class="settings-submit params-card" autocomplete="off"
                            action="{{ route('company.change_logo') }}" enctype="multipart/form-data">

                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ _lang('Upload Logo') }}</label>
                                        <input type="file" class="form-control dropify" name="logo"
                                            data-max-file-size="8M"
                                            data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG"
                                            data-default-file="{{ get_company_logo() }}" required>
                                    </div>
                                </div>

                                <br>
                                <div class="col-md-4 offset-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i
                                                class="ti-upload"></i> {{ _lang('Upload') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection