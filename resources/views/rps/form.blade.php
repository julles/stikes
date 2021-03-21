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
                <div id="tabs">
                    <ul>
                        <li><a href="#text_book">Text Book</a></li>
                        <li><a href="#attribute">Attribubte</a></li>
                        <li><a href="#cp">CP</a></li>
                        <li><a href="#topik" onclick = "return getCp();">Topic</a></li>
                        <li><a onclick = "return summary();" href="#summary">Summary</a></li>
                    </ul>
                    <div id="text_book">
                        @include("rps.partials.text_book")
                    </div>
                    <div id="attribute">
                        @include("rps.partials.attribute")
                    </div>
                    <div id="cp">
                        @include("rps.partials.cp")
                    </div>
                    <div id="topik">
                        @include("rps.partials.topik")
                    </div>
                    <div id="summary">
                        @include("rps.partials.summary")
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    Submit
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
{!! JsValidator::formRequest('App\Http\Requests\InputTextBookRequest', '#form'); !!}
<script>
$(function() {
    $( "#tabs" ).tabs();
});
</script>
@endpush


