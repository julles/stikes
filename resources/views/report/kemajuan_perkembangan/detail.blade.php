{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>{{ $__menu }} Kemajuan Perkembangan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header">
                	<h3>{{ $title }}</h3>
                </div>
                <div class="box-body">
                	<table></table>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
@endpush