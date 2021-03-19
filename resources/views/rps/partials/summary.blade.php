@include("rps.partials.text_book")

<div class="row">
    <h3>Attribute</h3>
    <hr>
    <div class="col-md-12">
        <table class= "table">
            <tbody>
                <tr>
                    <td class = "active">Peta Kompetensi</td>
                    <td></td>
                    
                    
                    <td class = "active">Strategi Pembelajaran</td>
                    <td id = "td_strategi_pembelajaran"></td>
                </tr>
                <tr>
                    <td class = "active">Rubik Penilaian</td>
                    <td></td>
                    
                    
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

        var topics = "";
        topicLoop = 1;
        $("#cp_tbody :text").each(function(){
            topics += "<tr><td>"+$(this).val()+"</td></tr>";
            topicLoop++;
        });


        $("#table_summary_pembelajaran").html(topics);

        topicClone = $("#topik_tbody").clone();
        $("#table_summary_topic").html(topicClone)

        $("#table_summary_topic .remove_topik").remove();
        $("#table_summary_topic").find("input").each(function(){
            value = $(this).attr('name','not');

        });

    }
</script>
@endpush