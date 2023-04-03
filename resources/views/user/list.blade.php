@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
            <div class="page_title_left d-flex align-items-center">
                <h3 class="f_s_25 f_w_700 dark_text mr_30">Gebruikers</h3>
                <ol class="breadcrumb page_bradcam mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                </ol>
            </div>
            <div class="page_title_right">
                <div class="page_date_button d-flex align-items-center">
                    <a class="btn btn-primary btn-sm ml-auto ajax-modal" data-title="{{ _lang('Create User') }}"
                       href="{{ route('users.create') }}"><i class="ti-plus"></i> {{ _lang('Add New') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{--<div class="row" style="display: none">--}}
{{--    <div class="col-lg-12">--}}
{{--        <div class="card white_card card_height_100 mb_30 pt-4 ">--}}

{{--            <div class="card-header d-flex align-items-center">--}}
{{--                <h4 class="header-title">{{ _lang('User List') }}</h4>--}}

{{--            </div>--}}

{{--            <div class="card-body">--}}
{{--                <table id="users_table" class="table data-table">--}}
{{--                    <thead>--}}
{{--                        <tr>--}}
{{--                            <th class="text-center">#</th>--}}
{{--                            <th>{{ _lang('Name') }}</th>--}}
{{--                            <th>{{ _lang('Email') }}</th>--}}
{{--                            <th class="text-center">{{ _lang('Action') }}</th>--}}
{{--                        </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                        @foreach($users as $user)--}}
{{--                        <tr data-id="row_{{ $user->id }}">--}}
{{--                            <td class='profile_picture text-center'><img--}}
{{--                                    src="{{ profile_picture($user->profile_picture) }}" class="thumb-sm img-thumbnail">--}}
{{--                            </td>--}}
{{--                            <td class='name'>{{ $user->name }}</td>--}}
{{--                            <td class='email'>{{ $user->email }}</td>--}}
{{--                            <td class="text-center">--}}
{{--                                <span class="dropdown">--}}
{{--                                    <button class="btn btn-primary dropdown-toggle btn-sm" type="button"--}}
{{--                                        id="dropdownMenuButton" data-toggle="dropdown">--}}
{{--                                        {{ _lang('Action') }}--}}
{{--                                    </button>--}}


{{--                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                            <a href="{{ route('users.update', $user['id']) }}"--}}
{{--                                                data-title="{{ _lang('Update User') }}"--}}
{{--                                                class="dropdown-item ajax-modal"><i class="ti-pencil-alt-alt"></i>--}}
{{--                                                {{ _lang('Edit') }}</a>--}}
{{--                                            <a class="btn-remove dropdown-item" type="submit"><i--}}
{{--                                                    class="ti-trash"></i> {{ _lang('Delete') }}</a>--}}
{{--                                        </div>--}}
{{--                                </span>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="row">
    @foreach($users as $user)
        <div class="col-md-6 col-lg-6 col-xl-4 box-col-6">
            <div class="card custom-card">
                <div class="card-header">
                </div>
                <div class="card-profile"><img class="rounded-circle" src="img/staf/2.png" alt="" data-original-title="" title=""></div>
                <div class="text-center profile-details">
                    <h4>{{ $user->name }}</h4>
                    <h5></h5>
                    <h6>{{ $user->email }}</h6>
                </div>
                <div class="card-footer row">
                    <div class="col-6 col-sm-4">
                        <a class="text-warning" href="?edit=101">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                            <div class="text-overline">Bewerken</div>
                        </a>
                    </div>
                    <div class="col-6 col-sm-4">
                        <a class="text-danger" href="?delete=2">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            <div class="text-overline">Verwijderen</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


</div>
@endsection
