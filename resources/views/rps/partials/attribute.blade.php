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
                    {!! Form::label("Media Pembelajaran") !!}
                    {!! Form::text("media_pembelajaran",@$rps->media_pembelajaran,["class"=>"form-control"]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label("Strategi Pembelajaran") !!}
                    {!! Form::textarea("strategi_pembelajaran",@$rps->strategi_pembelajaran,["class"=>"form-control"]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label("Deskripsi Mata Kuliah") !!}
                    {!! Form::textarea("deskripsi_mata_kuliah",@$rps->deskripsi_mata_kuliah,["class"=>"form-control"]) !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col">
                    <div class="box box-success">
                        <div class="box-header">
                            <h4>Metode Penilaian</h4>
                        </div>
                        <div class="box-body">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Component</th>
                                        <th>Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($metodePenilaian as $key => $v)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="metode_penilaian[]" id="mpc-{{$key}}" class="form-check-input metode-penilaian" value="{{ $v['id'] }}" data-weight="{{ $v['weight'] }}"
                                                @if(in_array($v['id'],$metodePenilaianChecked))
                                                    checked 
                                                @endif
                                                >
                                            </td>
                                            <td>
                                                <label for="mpc-{{$key}}">
                                                    {{ $v['component'] }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="mpc-{{$key}}">
                                                    <span>{{ $v['weight'] }}</span>%
                                                </label>    
                                            </td>
                                        </tr>
                                    @endforeach()
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right">
                                            <strong>Weight Total :</strong>
                                        </td>
                                        <td>
                                            <strong><span id="weight-total">0</span>%</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push("js")
    <script>
        function getTotalMP() {
            var weightTotal = 0;
            $(".metode-penilaian:checked").map(function () {
                weightTotal = weightTotal + $(this).data('weight');
            });

            $("#weight-total").html(weightTotal);
        }
        getTotalMP();

        $(".metode-penilaian").on('change click',function(e) {
            getTotalMP();            
        })
    </script>
@endpush