@include("rps.partials.text_book")

<div class="row">
    <h3>Attribute</h3>
    <hr>
    <div class="col-md-8">
        <table class= "table">
            <tbody>
                <tr>
                    <td width="200px"><strong>Peta Kompetensi</strong></td>
                    <td>
                        <span id="td_peta_kompetensi"></span>
                        @if(@$rps->peta_kompetensi)
                            <a href="{{ Storage::url(contents_path().'peta_kompetensi/'.$rps->peta_kompetensi) }}" target="_blank" class="btn btn-outline-danger mt-2 btn-sm">
                                View PDF
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Strategi Pembelajaran</strong></td>
                    <td>
                        <span id="td_strategi_pembelajaran"></span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Rubik Penilaian</strong></td>
                    <td>
                        <span id="td_rubrik_penilaian"></span>
                        @if(@$rps->peta_kompetensi)
                            <a href="{{ Storage::url(contents_path().'rubrik_penilaian/'.$rps->rubrik_penilaian) }}" target="_blank" class="btn btn-outline-danger mt-2 btn-sm">
                                View PDF
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Deskripsi Mata Kuliahh</strong></td>
                    <td id = "td_deskripsi_mata_kuliah"></td>
                </tr>
                <tr>
                    <td><strong>Media Pembelajaran</strong></td>
                    <td id = "td_media_pembelajaran"></td>
                </tr>
            </tbody>
        </table>   
    </div>
    <div class="col-md-4">
        <strong>Metode Penilaian</strong>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Weight</th>
                </tr>
            </thead>
            <tbody id = "tbody_summary_metode">
                
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right">
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

<div class="row">
    <h3>Capaian Pembelajaran</h3>
    <hr>
    <div class="col-md-12">
        <table class = "table" id = "table_summary_pembelajaran">

        </table>
    </div>
</div>

<div class="row">
    <h3>Topik</h3>
    <hr>
    <div class="col-md-12">
        <table class = "table table-bordered" id = "table_summary_topic">
            <thead>
                <tr>
                    <th>Sesi</th>
                    <th>Topic</th>
                    <th>CP</th>
                    <th>Sub Topik</th>
                </tr>
            </thead>
            <tbody id = "tbody_summary_topic"></tbody>
        </table>
    </div>
</div>

@push("js")
<script>
    function summary(){
        openTab('summary', false);
        $("#td_strategi_pembelajaran").html($('[name="strategi_pembelajaran"]').val());
        $("#td_deskripsi_mata_kuliah").html($('[name="deskripsi_mata_kuliah"]').val());
        $("#td_metode_penilaian").html($('[name="metode_penilaian"]').val());
        $("#td_media_pembelajaran").html($('[name="media_pembelajaran"]').val());
        $("#td_peta_kompetensi").html($('[name="peta_kompetensi"]').val().replace(/C:\\fakepath\\/i, ''));
        $("#td_rubrik_penilaian").html($('[name="rubrik_penilaian"]').val().replace(/C:\\fakepath\\/i, ''));

        var topics = "";
        topicLoop = 1;
        $("#cp_tbody :text").each(function(){
            topics += "<tr><td>"+$(this).val()+"</td></tr>";
            topicLoop++;
        });


        $("#table_summary_pembelajaran").html(topics);

        
        $("#table_summary_topic .remove_topik").remove();
        $("#table_summary_topic").find("input").each(function(){
            value = $(this).attr('name','not');
        });

        // Metode
        var metodePenilaian = @json($metodePenilaian);
        $.ajax({
            url: "/parse-str",
            data: $("#metode_tbody :input").serialize(),
            success: function(res){

                // console.log(res);
                var metod = "";
                $.each( metodePenilaian, function( key, value ) {
                    // console.log(value);
                    // if ((jQuery.inArray( value.id, res )) >= 0 ) {
                    //     console.log(value);
                    // }
                    // console.log(value.id.toString(),res.metode_penilaian,jQuery.inArray( value.id.toString(), res.metode_penilaian ));
                    // console.log();
                    if (jQuery.inArray( value.id.toString(), res.metode_penilaian ) >= 0) {
                        console.log(value);
                        metod += '<tr>';
                            metod += '<td>';
                                metod += value.component;
                            metod += '</td>';
                            metod += '<td>';
                                metod += value.weight+'%';
                            metod += '</td>';
                        metod += '</tr>';
                    }
                });
                $("#tbody_summary_metode").html(metod);


            },
        });

        // Topic
        
        $.ajax({
            url: "/parse-str",
            data: $("#topik_tbody :input").serialize(),
            success: function(res){

                // console.log(res);
                var topics = "";

                $.each( res.topic, function( key, value ) {
                    var rowspanVal = (value.sub_topik.length + 1);
                    topics += '<tr>';
                        topics += '<td rowspan="'+rowspanVal+'">';
                            if (value.sesi) {
                                topics += value.sesi;   
                            }
                        topics += '</td>';

                        topics += '<td rowspan="'+rowspanVal+'">';
                            if (value.topic) {
                                topics += value.topic;   
                            }
                        topics += '</td>';

                        topics += '<td rowspan="'+rowspanVal+'">';
                            if (value.capaian_pembelajaran) {
                                topics += value.capaian_pembelajaran;   
                            }
                        topics += '</td>';

                    topics += '</tr>';

                    $.each( value.sub_topik, function( k, v ) {
                        topics += '<tr>';
                            topics += '<td>';
                                topics += v;
                            topics += '</td>';
                        topics += '</tr>';
                    });
                });

                $("#tbody_summary_topic").html(topics);
            },
        });
        

    }
</script>
@endpush