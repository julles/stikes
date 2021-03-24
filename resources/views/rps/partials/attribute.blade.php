<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-group">
                    {!! Form::label("Peta Kompetensi") !!} <small>(PDF)</small>
                    {{-- @include("components.file",["name" => "peta_kompetensi"]) --}}
                    {!! Form::file('peta_kompetensi', ["class" => "form-control","id" => "peta_kompetensi"]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label("Rubrik Penilaian") !!} <small>(PDF)</small>
                    {{-- @include("components.file",["name" => "rubik_penilaian"]) --}}
                    {!! Form::file('rubrik_penilaian', ["class" => "form-control","id" => "rubrik_penilaian"]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label("Strategi Pembelajaran") !!}
                    {!! Form::text("strategi_pembelajaran",@$rps->strategi_pembelajaran,["class"=>"form-control"]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label("Deskripsi Mata Kuliah") !!}
                {!! Form::text("deskripsi_mata_kuliah",@$rps->deskripsi_mata_kuliah,["class"=>"form-control"]) !!}
            </div>
            <div class="form-group">
                {!! Form::label("Metode Penilaian") !!}
                {!! Form::text("metode_penilaian",@$rps->metode_penilaian,["class"=>"form-control"]) !!}
            </div>
            <div class="form-group">
                {!! Form::label("Media Pembelajaran") !!}
                {!! Form::text("media_pembelajaran",@$rps->media_pembelajaran,["class"=>"form-control"]) !!}
            </div>
        </div>
    </div>
</div>
