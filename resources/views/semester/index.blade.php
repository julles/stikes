{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>{{ $__menu }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header">
                    {!! Html::link($__route."/create","Add New",["class"=>"btn btn-primary btn-sm"]) !!}
                </div>
                <div class="box-body">
                    <table class="table table-bordered" id = "table">
                        <thead>
                            <tr>
                                <th width = "30%">Nama Semester</th>
                                <th width = "30%">Dari</th>
                                <th width = "30%">Sampai</th>
                                <th width = "10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id = "tbody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
<script>
$(function() {
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ $__route.'/data' }}",
        "createdRow": function( row, data, dataIndex){
            console.log(data)
            if( data.active == "0"){
                $(row).addClass('danger');
            }
        },
        ordering:false,
        columns: [
            { data: 'nama_semester', name: 'nama_semester' },
            { data: 'from', name: 'from' },
            { data: 'to', name: 'to' },
            { data: 'action', name: 'action' ,searchable:false}
        ]
    });
});
</script>
@endpush