@extends('layouts.app')

@section('content')
<h4 class="page-title">{{ _lang('Leads Management') }}</h4>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('Leads List') }}</h4>
                <a class="btn btn-primary btn-sm ml-auto" href="{{route('leads.create')}}"><i class="ti-plus"></i> {{ _lang('Add New') }}</a>
            </div>

            <div class="card-body">
                <table id="contacts-table" class="table table-bordered data-table-ajax" data-url="{{route('leads.data')}}" data-column='[{"data":"name"},{"data":"contact_person"},{"data":"email"},{"data":"amount"},{"data":"currency"},{"data":"action"}]'>
                    <thead>
                        <tr>
                            <th>{{ _lang('Titel') }}</th>
                            <th>{{ _lang('Contact Person') }}</th>
                            <th>{{ _lang('Email') }}</th>
                            <th>{{ _lang('Amount') }}</th>
                            <th>{{ _lang('Currency') }}</th>
                            <th class="text-center">{{ _lang('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-script')
<script src="{{ asset('public/backend/assets/js/datatables/contacts.js?v=1.1') }}"></script>
@endsection
