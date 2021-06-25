@extends('core::layouts.modal')
@section('title', __('inbox::general.detail'))

@section('content')
    <div class="card-header">
        <h5 class="card-title" style="margin-bottom:0">{{ __('inbox::general.detail') }}</h5>
    </div>
    <div class="card-block" style="padding:0">
        <table class="table table-sm table-bordered">
            <tr>
                <th class="table-active" width="15%">ID</th>
                <td><b>#{{ $data->uuid }}</b></td>
            </tr>
            <tr>
                <th class="table-active">Pengirim</th>
                <td>{{ $data->nama }}</td>
            </tr>
            <tr>
                <th class="table-active">Email</th>
                <td>{{ $data->email }}</td>
            </tr>
            <tr>
                <th class="table-active">Telepon</th>
                <td>{{ $data->telepon }}</td>
            </tr>
        </table>
        <div style="padding: 0 20px;">
            <blockquote>{!! $data->isi !!}</blockquote>
        </div>
    </div>
@endsection