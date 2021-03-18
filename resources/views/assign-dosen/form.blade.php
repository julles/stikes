{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
<h1>{{ $__menu }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                {{ $titleAction }}
            </div>
            {!! Form::model($model,["id" =>"form","method"=>"post","files"=>true]) !!}
            <div class="box-body">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label("Semester") !!}
                            {!! Form::select("id_semester",$semesterListsBox,null,["class"=>"form-control select2"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Mata Kuliah") !!}
                            {!! Form::select("id_matakuliah",$matakuliahListsBox,null,["class"=>"form-control select2"]) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label("SME") !!}
                            {!! Form::select("sme_id",$dosenListsBox,@$model->pm_assign->sme_id,["class"=>"form-control select2"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Reviewer") !!}
                            {!! Form::select("reviewer_id",$dosenListsBox,@$model->pm_assign->reviewer_id,["class"=>"form-control select2"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Approval") !!}
                            {!! Form::select("approval_id",$dosenListsBox,@$model->pm_assign->approval_id,["class"=>"form-control select2"]) !!}
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    {{ $titleAction == "Tambah Data" ? "Simpan" : "Edit" }}
                </button>

                <a href="{{ url($__route) }}" class="btn btn-default btn-sm">
                    Back
                </a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@push("js")
{!! JsValidator::formRequest('App\Http\Requests\AssignDosenRequest', '#form'); !!}
@endpush
