@extends('core::layouts.app')
@section('title', $title)
@section('tInbox', 'active')

@section('css')
    @include('core::layouts.partials.datatables')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.0/lity.min.css">
@endsection

@section('js') 
    <script src="https://cdn.enterwind.com/template/epanel/js/lib/datatables-net/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lity/2.4.0/lity.min.js"></script>
    <script>
        $(function() {
            $('#datatable').DataTable({
                order: [[ 5, "desc" ]], 
                processing: true,
                serverSide: true,
                ajax : '{!! request()->fullUrl() !!}?datatable=true', 
                columns: [
                    { data: 'pilihan', name: 'pilihan', className: 'table-check' },
                    { data: 'nama', name: 'nama' },
                    { data: 'telepon', name: 'telepon' },
                    { data: 'email', name: 'email' },
                    { data: 'tanggal', name: 'tanggal', className: 'table-date small' }, 
                    { data: 'pesan', name: 'pesan' }
                ],
                "fnDrawCallback": function( oSettings ) {
                    @include('core::layouts.components.callback')
                }
            });
        });
        @include('core::layouts.components.hapus')
    </script>

    <script type='text/javascript' src='https://dok.btekno.id/embed-dk.js'></script>
    <script type='text/javascript'>Dokumn.load("a20ef266-bfc1-11eb-96e6-f7bd14df2fcc","b06dbc0c-bfc1-11eb-8b47-a56fbb8559ab","e167924c-bfc1-11eb-a5a8-1f8f6fcabfa5");</script>
@endsection

@section('content')

    @if(!$data->count())
        @include('core::layouts.components.kosong', [
            'icon' => 'font-icon font-icon-mail',
            'judul' => $title,
            'subjudul' => __('inbox::general.empty'), 
        ])
    @else
        
        {!! Form::open(['method' => 'delete', 'route' => ["$prefix.destroy", 'hapus-all'], 'id' => 'submit-all']) !!}
            @include('core::layouts.components.top', [
                'judul' => $title,
                'subjudul' => __('inbox::general.subtitle'),
                'hapus' => true
            ])
            <div class="card">
                <div class="card-block table-responsive">
                    <table id="datatable" class="display table table-striped" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="table-check"></th>
                                <th width="22%">{{ __('inbox::table.columns.name') }}</th>
                                <th>{{ __('inbox::table.columns.phone') }}</th>
                                <th>{{ __('inbox::table.columns.email') }}</th>
                                <th></th>
                                <th width="1"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection
