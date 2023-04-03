@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex align-items-center">
                <h4 class="header-title">{{ _lang('Task') }}</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>{{ _lang('Aanvraag') }}</th>
                            <th>{{ _lang('Prioritering') }}</th>
                            <th>{{ _lang('Status') }}</th>
                            <th>{{ _lang('Laatst geupdate') }}</th>
                            <th>{{ _lang('Werknemer') }}</th>
                            <th>{{ _lang('Notities') }}</th>
                            <th class="text-center">{{ _lang('Acties') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr id="row_{{ $task->id }}">
                            <td class='tax_name'>{{ $task->name }}</td>
                            <td class="text-center">
                                <form action="{{action('TaxController@destroy', $tax['id'])}}" method="post">
                                    <a href="{{action('TaxController@edit', $tax['id'])}}"
                                        data-title="{{ _lang('Update Tax') }}"
                                        class="btn btn-warning btn-sm ajax-modal"><i class="ti-pencil-alt"></i></a>
                                    {{ csrf_field() }}
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger btn-sm btn-remove" type="submit"><i
                                            class="ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
