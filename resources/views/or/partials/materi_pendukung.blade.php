<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Judul</th>
                    <th>Link</th>
                    <th>File</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id = "or_materi_pendukung_tbody">
                @if(isset($orFile['or_materi_pendukung']))
                    @foreach($orFile['or_materi_pendukung'] as $key => $v)
                        <tr>
                            <td>
                                <select class = 'form-control select2' style="width:100%" name ='materi_pendukung[{{$key}}][topic_id]'>
                                    @foreach($topic as $keyT => $t)
                                        <option value="{{ $t['id_topic'] }}"
                                        @if($v->topic_id == $t['id_topic'])
                                        selected
                                        @endif
                                        >{{ $t['topic'] }}</option>
                                    @endforeach()
                                </select>
                            </td>
                            <td class="row-index" > 
                                <input type="text" class="form-control" name="materi_pendukung[{{$key}}][title]" value="{{ $v['title'] }}">
                            </td> 
                            <td class="row-index" > 
                                <input type="text" class="form-control" name="materi_pendukung[{{$key}}][link]" value="{{ $v['link'] }}">
                            </td> 
                            <td class="text-center" width="15%"> 
                                <input type="file" class="form-control" name="materi_pendukung[{{$key}}][file]" required>
                            </td> 
                            <td class="text-center" width="10%"> 
                                <button type = "button" class = "btn btn-danger btn-sm remove_ppt">X</button>
                            </td> 
                        </tr>
                    @endforeach()
                @endif()
            </tbody>
        </table>
        <div class="row">
            <div class="col text-center">
                <button type = "button" class = "btn btn-success btn-sm" id = "button_or_materi_pendukung">
                + Add Materi Pendukung
                </button>  
            </div>
        </div>
    </div>
</div>

@push("js")
<script>
    $(document).ready(function(){
        var rowIdx = "{{count($orFile['or_materi_pendukung']) ?? 0}}";
        var topicArr = @json($topic);

        $("#button_or_materi_pendukung").on("click",function(){

            var selects = `<select style="width:100%"
            class = 'form-control select2materi_pendukung-${rowIdx}' name = 'materi_pendukung[${rowIdx}][topic_id]'>`;
            
            $.each( topicArr, function( key, value ) {
                selects += "<option value = '"+value.id_topic+"'>"+value.topic+"</option>";
            });

            selects += "</select>";

           $('#or_materi_pendukung_tbody').append(`<tr id="Ror_materi_pendukung-${rowIdx}"> 
                    <td class="row-index " > 
                        ${selects}
                    </td> 
                    <td class="row-index" > 
                        <input type="text" class="form-control" name="materi_pendukung[${rowIdx}][title]">
                    </td> 
                    <td class="row-index" > 
                        <input type="text" class="form-control" name="materi_pendukung[${rowIdx}][link]">
                    </td> 
                    <td class="text-center" width="15%"> 
                        <input type="file" class="form-control" name="materi_pendukung[${rowIdx}][file]" required>
                    </td> 
                    <td class="text-center" width="10%"> 
                        <button type = "button" class = "btn btn-danger btn-sm remove_or_materi_pendukung">X</button>
                    </td> 
                </tr>`
            ); 

            $(`.select2materi_pendukung-${rowIdx}`).select2();
            rowIdx++;
        });

        $('#or_materi_pendukung_tbody').on('click', '.remove_or_materi_pendukung', function () { 

            // Getting all the rows next to the 
            // row containing the clicked button 
            var child = $(this).closest('tr').nextAll(); 

            // Iterating across all the rows 
            // obtained to change the index 
            child.each(function () { 
                
                // Getting <tr> id. 
                var id = $(this).attr('id'); 

                // Getting the <p> inside the .row-index class. 
                var idx = $(this).children('.row-index').children('input'); 

                // Gets the row number from <tr> id. 
                var dig = parseInt(id.substring(1)); 

                // Modifying row index. 
                idx.html(`Row ${dig - 1}`); 

                // Modifying row id. 
                $(this).attr('id', `R${dig - 1}`); 
            }); 

            // Removing the current row. 
            $(this).closest('tr').remove(); 

            // Decreasing the total number of rows by 1. 
            rowIdx--; 
        }); 

    });
</script>
@endpush
