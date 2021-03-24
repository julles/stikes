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
                            {!! Form::text("semester",$pengembangMateri->semester->nama_semester,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Judul Buku") !!}
                            {!! Form::text("title",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Pengarang") !!}
                            {!! Form::text("author",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Tahun Terbit") !!}
                            {!! Form::number("tahun",null,["class"=>"form-control","maxlength" => 4,"readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("ISBN") !!}
                            {!! Form::text("isbn",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <hr>

                        @if($userStatus == 'reviewer')
                            @if($model->approv_commen)
                                <div class="row mb-3">
                                    <div class="col">
                                        {!! Form::label("Kajur Notes") !!}
                                        {!! Form::textarea("approv_commen",null,["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                    </div>
                                </div>
                            @endif
                            @if($model->status == 0)
                                <div class="row">
                                    <div class="col">
                                        {!! Form::label("Reviewer Notes") !!}
                                        {!! Form::textarea("reviewer_commen",null,["class"=>"form-control","row"=>"5"]) !!}
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="submit" name="status" value="1" class="btn btn-success mr-2">
                                            Approve
                                        </button>
                                        <button type="submit" name="status" value="0" class="btn btn-danger">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <div class="col">
                                        {!! Form::label("Reviewer Notes") !!}
                                        {!! Form::textarea("reviewer_commen",null,["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                    </div>
                                </div>
                            @endif

                        @elseif($userStatus == 'approv')
                            @if($model->reviewer_commen)
                                <div class="row mb-3">
                                    <div class="col">
                                        {!! Form::label("Reviewer Notes") !!}
                                        {!! Form::textarea("reviewer_commen",null,["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                    </div>
                                </div>
                            @endif
                            @if($model->status == 1)
                                <div class="row">
                                    <div class="col">
                                        {!! Form::label("Kajur Note") !!}
                                        {!! Form::textarea("approv_commen",null,["class"=>"form-control","row"=>"5"]) !!}
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-right">
                                        <button type="submit" name="status" value="1" class="btn btn-success mr-2">
                                            Approve
                                        </button>
                                        <button type="submit" name="status" value="0" class="btn btn-danger">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <div class="col">
                                        {!! Form::label("Kajur Notes") !!}
                                        {!! Form::textarea("approv_commen",null,["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label("Mata Kuliah") !!}
                            {!! Form::text("mata_kuliah",$pengembangMateri->matakuliah->mk_nama,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Edisi") !!}
                            {!! Form::text("edition",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Penerbit") !!}
                            {!! Form::text("publisher",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label("Kategori") !!}
                            {!! Form::text("kategori",null,["class"=>"form-control","readonly"=>true,"disabled"]) !!}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col text-center">
                                    {!! Form::label("Cover") !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-center">
                                    <img width="400px" src="{{ Storage::url(contents_path().$model->gbr_cover) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@push("js")
{!! JsValidator::formRequest('App\Http\Requests\InputTextBookRequest', '#form'); !!}
@endpush
