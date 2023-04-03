@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">{{ _lang('Update User') }}</h4>
            </div>
            <div class="card-body">
                <form method="post" class="validate" autocomplete="off"
                    action="{{ action('UserController@update', $id) }}" enctype="multipart/form-data">
                    {{ csrf_field()}}
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label class="col-xl-3 col-form-label">{{ _lang('Name') }}</label>
                                <div class="col-xl-9">
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}"
                                        required>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-xl-3 col-form-label">{{ _lang('Email') }}</label>
                                <div class="col-xl-9">
                                    <input type="text" class="form-control" name="email" value="{{ $user->email }}"
                                        required>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-xl-3 col-form-label">{{ _lang('Password') }}</label>
                                <div class="col-xl-9">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-xl-3 col-form-label">{{ _lang('Valid To') }}</label>
                                <div class="col-xl-9">
                                    <input type="text" class="form-control datepicker" name="valid_to"
                                        value="{{ $user->valid_to }}">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-xl-3 col-form-label">{{ _lang('Profile Picture') }}</label>
                                <div class="col-xl-9">
                                    <input type="file" class="form-control dropify" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ profile_picture($user->profile_picture) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-xl-9 offset-xl-3">
                                    <button type="submit" class="btn btn-primary">{{ _lang('Update User') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
