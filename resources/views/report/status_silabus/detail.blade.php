{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>{{ $__menu }} Status Silabus</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header">
                	<h3>{{ $title }}</h3>
				    <a class="btn btn-default fill-right" href="{{ URL($__route.'/status-silabus') }}">
				    	<i class="fas fa-arrow-left"></i> Kembali
				    </a>
                </div>
                <div class="box-body">
                	<table class="table table-border">
                		<thead>
                			<tr>
                				<th>Sesi Ke</th>
                				<th>Topik</th>
                				<th>Sub Topic</th>
                				<th>Media Keterangan</th>
                				<th>Materi Pembelajaran</th>
                				<th>Capaian Keterangan</th>
                			</tr>
                		</thead>
                		<tbody>
                			@if(count($report) < 1 )
                				<tr>
                					<td colspan="6" class="text-center">
                						Tidak ada data
                					</td>
                				</tr>
                			@endif
                			@foreach($report as $key => $v)
                				<tr>
                					<td class="text-right">{{ $v['sesi'] }}</td>
                					<td>{{ $v['topic'] }}</td>
                					<td>{!! checklistIcon($v['media_keterangan']) !!}</td>
                					<td>{!! checklistIcon($v['sub_topic']) !!}</td>
                					<td>{!! checklistIcon($v['media_pembelajaran']) !!}</td>
                					<td>{!! checklistIcon($v['cp']) !!}</td>
                				</tr>
                			@endforeach
                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
@endpush