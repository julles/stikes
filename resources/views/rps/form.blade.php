{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
<h1>Entry Rencana Pembelajaran Semester (RPS)</h1>
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
                        @if($review_stat)
                            <li style="display: none;"><a href="#text_book">Text Book</a></li>
                            <li style="display: none;"><a href="#attribute">Attribute</a></li>
                            <li style="display: none;"><a href="#cp">CP</a></li>
                            <li style="display: none;"><a href="#topik" onclick = "return getCp();">Topic</a></li>
                        @else
                            <li><a href="#text_book">Text Book</a></li>
                            <li><a href="#attribute">Attribute</a></li>
                            <li><a href="#cp">CP</a></li>
                            <li><a href="#topik" onclick = "return getCp();">Topic</a></li>
                        @endif()

                        <li><a id="summary_btn" onclick = "return summary();" href="#summary">Summary</a></li>
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
                            
                            @if($rps->reviewer_commen || $rps->approv_commen)
                                <div class="box box-info">
                                    <div class="box-body">
                                        @if($rps->reviewer_commen)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    {!! Form::label("Reviewer Notes") !!}
                                                    <p>{{ $rps->reviewer_commen }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($rps->approv_commen)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    {!! Form::label("Kajur Notes") !!}
                                                    <p>{{ $rps->approv_commen }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($userStatus == 'reviewer')
                                @if($rps->status < 2)
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
                                @endif

                            @elseif($userStatus == 'approv')
                                @if($rps->status < 2)
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


