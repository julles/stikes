<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-group">
                    {!! Form::label("Peta Komeptensi") !!}
                    @include("components.file",["name" => "peta_kompetensi"])
                </div>
                <div class="form-group">
                    {!! Form::label("Rubik Penilaian") !!}
                    @include("components.file",["name" => "rubik_penilaian"])
                </div>
                <div class="form-group">
                    {!! Form::label("Strategi Pembelajaran") !!}
                    {!! Form::text("deskripsi_mata_kuliah",null,["class"=>"form-control"]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label("Deskripsi Mata Kuliah") !!}
                {!! Form::text("deskripsi_mata_kuliah",null,["class"=>"form-control"]) !!}
            </div>
            <div class="form-group">
                {!! Form::label("Metode Peniliaian") !!}
                {!! Form::text("metode_penilaian",null,["class"=>"form-control"]) !!}
            </div>
            <div class="form-group">
                {!! Form::label("Media Pembelajaran") !!}
                {!! Form::text("media_pembelajaran",null,["class"=>"form-control"]) !!}
            </div>
        </div>
    </div>
</div>
