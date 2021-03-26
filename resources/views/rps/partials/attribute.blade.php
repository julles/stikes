<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-group">
                    {!! Form::label("Peta Kompetensi") !!} <small>(PDF)</small>
                    {{-- @include("components.file",["name" => "peta_kompetensi"]) --}}
                    {!! Form::file('peta_kompetensi', ["class" => "form-control","id" => "peta_kompetensi"]) !!}

                    @if(@$rps->peta_kompetensi)
                        <a href="{{ Storage::url(contents_path().'peta_kompetensi/'.$rps->peta_kompetensi) }}" target="_blank" class="btn btn-outline-danger mt-2 btn-sm">
                            View Older PDF
                        </a>
                       <!--  <button type="button" class="btn btn-outline-danger mt-2 btn-sm" data-toggle="modal" data-target="#exampleModal">
                          View Older PDF
                        </button>

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <iframe src="URL('rps/view-pdf/peta_kompetensi/'.$rps->peta_kompetensi)" width="100%" height="400px"></iframe>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div> -->
                    @endif
                </div>
                <div class="form-group">
                    {!! Form::label("Rubrik Penilaian") !!} <small>(PDF)</small>
                    {{-- @include("components.file",["name" => "rubik_penilaian"]) --}}
                    {!! Form::file('rubrik_penilaian', ["class" => "form-control","id" => "rubrik_penilaian"]) !!}
                    
                    @if(@$rps->peta_kompetensi)
                        <a href="{{ Storage::url(contents_path().'rubrik_penilaian/'.$rps->rubrik_penilaian) }}" target="_blank" class="btn btn-outline-danger mt-2 btn-sm">
                            View Older PDF
                        </a>
                    @endif
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
                                <tbody id="metode_tbody">
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
                                            <strong><span class="weight-total">0</span>%</strong>
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

            $(".weight-total").html(weightTotal);
        }
        getTotalMP();

        $(".metode-penilaian").on('change click',function(e) {
            getTotalMP();            
        })
    </script>
@endpush