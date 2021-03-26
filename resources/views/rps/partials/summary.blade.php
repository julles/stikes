@include("rps.partials.text_book")

<div class="row">
    <h3>Attribute</h3>
    <hr>
    <div class="col-md-12">
        <table class= "table">
            <tbody>
                <tr>
                    <td class = "active">Peta Kompetensi</td>
                    <td id = "td_peta_kompetensi"></td>
                    
                    
                    <td class = "active">Strategi Pembelajaran</td>
                    <td id = "td_strategi_pembelajaran"></td>
                </tr>
                <tr>
                    <td class = "active">Rubik Penilaian</td>
                    <td id = "td_rubrik_penilaian"></td>
                    
                    
                    <td class = "active">Deskripsi Mata Kuliahh</td>
                    <td id = "td_deskripsi_mata_kuliah"></td>
                </tr>
                <tr>
                    <td class = "active">Metode Penilaian</td>
                    <td id = "td_metode_penilaian"></td>
                    
                    
                    <td class = "active">Media Pembelajaran</td>
                    <td id = "td_media_pembelajaran"></td>
                </tr>
            </tbody>
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
        <table class = "table" id = "table_summary_topic">
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

        
        $.ajax({
            url: "/parse-str",
            data: $("#topik_tbody :input").serialize(),
            success: function(res){

                var topics = "";

                for(a=0;a<res.sesi.length;a++){
                    paramSubTopik = "sub_topik_" + a;
                    topics += "<tr>";
                        topics+= "<td>"+ res.sesi[a] +"</td>";
                        topics+= "<td>"+ res.topik[a] +"</td>";
                        topics+= "<td>"+ res.cp_select[a] +"</td>";
                        console.log(res);
                        topics+= "<td>";
                            // $.each( res.sub_topik[a], function( key, value ) {
                            //   topics+= value+"<br>";
                            // });
                        topics+= "</td>";
                        // topics+= "<td>"+ res.sub_topik_a+"</td>";



                    topics += "</tr>";

                }

                $("#tbody_summary_topic").html(topics);
            },
        });
        

    }
</script>
@endpush