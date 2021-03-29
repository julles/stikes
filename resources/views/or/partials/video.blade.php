<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>File</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id = "vid_tbody">
            </tbody>
        </table>
        <div class="row">
            <div class="col text-center">
                <button type = "button" class = "btn btn-success btn-sm" id = "vid">
                + Add video
                </button>  
            </div>
        </div>
    </div>
</div>

@push("js")
<script>
    $(document).ready(function(){
        var rowIdx = 0;
        var topicArr = @json($topic);

        $("#vid").on("click",function(){
            var selects = `<select 
            class = 'form-control select2vid-${rowIdx}' name = 'video[${rowIdx}][topic_id]'>`;
            
            $.each( topicArr, function( key, value ) {
                selects += "<option value = '"+value.id_topic+"'>"+value.topic+"</option>";
            });

            selects += "</select>";

           $('#vid_tbody').append(`<tr id="Rvid-${rowIdx}"> 
                    <td class="row-index " > 
                        ${selects}
                    </td> 
                    <td class="text-center" width="30%"> 
                        <input type="file" class="form-control" name="video[${rowIdx}][file]" required>
                    </td> 
                    <td class="text-center" width="10%"> 
                        <button type = "button" class = "btn btn-danger btn-sm vid">X</button>
                    </td> 
                </tr>`
            ); 

            $(`.select2vid-${rowIdx}`).select2();
            rowIdx++;
        });

        $('#vid_tbody').on('click', '.vid', function () { 

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
