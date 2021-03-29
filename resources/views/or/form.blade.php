{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
<h1>OR</h1>
@stop

@section('css')
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                {!! $titleAction !!}
            </div>
            {!! Form::model($model,["id" =>"form","method"=>"post","files"=>true]) !!}
            <div class="box-body">
                <div id="tabs">
                    <ul>
                        <li><a href="#text_book">Text Book</a></li>
                        
                        <li><a href="#ppt">PPT</a></li>
                        <li><a href="#ln">LN</a></li>
                        <li><a href="#video">Video</a></li>
                        <li><a href="#materi-pendukung">Materi Pendukung</a></li>
                        <li><a href="#exercise">Exercise / Kuis</a></li>
                        <li><a href="#maping-topic">Mapping Topik</a></li>

                        <li><a id="summary_btn" onclick = "return summary();" href="#summary">Summary</a></li>
                    </ul>

                    <div id="text_book">
                        @include("or.partials.text_book")
                    </div>
                    <div id="ppt">
                        @include("or.partials.ppt")
                    </div>
                    <div id="ln">
                        @include("or.partials.ln")
                    </div>
                    <div id="video">
                        @include("or.partials.video")
                    </div>
                    <div id="materi-pendukung">
                        @include("or.partials.materi_pendukung")
                    </div>
                    
                    <div id="summary">
                        @include("or.partials.summary")
                    </div>
                </div>
            </div>

            <div class="box-footer">
                @if(!$review_stat)
                    <button type="submit" class="btn btn-primary btn-sm">
                        Submit
                    </button>

                    <a href="{{ url($__route) }}" class="btn btn-default btn-sm">
                        Back
                </a>
                @else
                    <div class="">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6">
                            
                            @if($userStatus == 'reviewer')
                                @if($rps->approv_commen)
                                    <div class="row mb-3">
                                        <div class="col">
                                            {!! Form::label("Kajur Notes") !!}
                                            {!! Form::textarea("approv_commen",$rps->approv_commen ?? '',["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                        </div>
                                    </div>
                                @endif
                                @if($rps->status == 0)
                                    <div class="row">
                                        <div class="col">
                                            {!! Form::label("Reviewer Notes") !!}
                                            {!! Form::textarea("reviewer_commen",$rps->reviewer_commen ?? '',["class"=>"form-control","row"=>"5"]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-5">
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
                                            {!! Form::textarea("reviewer_commen",$rps->reviewer_commen ?? '',["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                        </div>
                                    </div>
                                @endif

                            @elseif($userStatus == 'approv')
                                @if($rps->reviewer_commen)
                                    <div class="row mb-3">
                                        <div class="col">
                                            {!! Form::label("Reviewer Notes") !!}
                                            {!! Form::textarea("reviewer_commen",$rps->reviewer_commen ?? '',["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                        </div>
                                    </div>
                                @endif
                                @if($rps->status == 1)
                                    <div class="row">
                                        <div class="col">
                                            {!! Form::label("Kajur Note") !!}
                                            {!! Form::textarea("approv_commen",$rps->approv_commen ?? '',["class"=>"form-control","row"=>"5"]) !!}
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-5">
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
                                            {!! Form::textarea("approv_commen",$rps->approv_commen ?? '',["class"=>"form-control","row"=>"5",'readonly'=>true]) !!}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@push("js")
{!! JsValidator::formRequest('App\Http\Requests\InputTextBookRequest', '#form'); !!}

<script src="{{ asset('vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });

    var review_stat = '{{$review_stat}}';
    if (review_stat == 1) {
        setTimeout(function() {
            $("#summary_btn").trigger('click');
        }, 700);
    }

</script>
@endpush


