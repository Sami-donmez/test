@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
                <div class="page_title_left d-flex align-items-center">
                    <h3 class="f_s_25 f_w_700 dark_text mr_30">Contacten</h3>
                    <ol class="breadcrumb page_bradcam mb-0">
                    </ol>
                </div>
                <div class="page_title_right">
                    <div class="page_date_button d-flex align-items-center">
                        <a class="btn btn-primary btn-sm ml-auto" href="{{route('contacts.create')}}"><i class="ti-plus"></i> {{ _lang('Add New') }}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
            </div>

            <div class="card-body">
                <table id="contacts-table" class="table table-bordered data-table-ajax" data-url="{{route('contacts.data')}}" data-column='[{"data":"contact_image"},{"data":"profile_type"},{"data":"contact_name"},{"data":"contact_email"},{"data":"contact_phone"},{"data":"group.name"}]'>
                    <thead>
                        <tr>
                            <th>{{ _lang('Image') }}</th>
                            <th>{{ _lang('Profieltype') }}</th>
                            <th>{{ _lang('Naam') }}</th>
                            <th>{{ _lang('E-mail') }}</th>
                            <th>{{ _lang('Telefoon') }}</th>
                            <th>{{ _lang('Groep') }}</th>
                            <th class="text-center">{{ _lang('Actie') }}</th>
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
