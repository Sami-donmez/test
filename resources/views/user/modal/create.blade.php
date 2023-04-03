<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('users.store') }}"
    enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row p-2">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Voornaam Achternaam') }}</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Functietitel') }}</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('E-mailadres*') }}</label>
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Wachtwoord') }}</label>
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
            </div>
        </div>



        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('User Type') }}</label>
                <select class="form-control select2 auto-select" data-selected="{{ old('user_type') }}" name="user_type"
                    required>
                    <option value="">{{ _lang('Select One') }}</option>
                    <option value="admin">{{ _lang('Admin') }}</option>
                    <option value="user">{{ _lang('User') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">{{ _lang('Branches') }}</label>
                <select class="form-control select2 auto-select" data-selected="{{ old('user_type') }}" name="user_type"
                    required>
                    <option value="">{{ _lang('Select One') }}</option>
                    <option value="lagaranta">{{ _lang('Lagaranta') }}</option>
                    <option value="nederhoje">{{ _lang('Nederhoje') }}</option>
                    <option value="bid">{{ _lang('BID') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">{{ _lang('Profile Picture') }}</label>
                <input type="file" class="form-control dropify" name="profile_picture">
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group">

                <button type="submit" class="btn btn-primary btn-lg"><i class="ti-save"></i> {{ _lang('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
