<div class="row">
    <div class="col-md-12" id = "cp_12">
        <table class="table">
            <thead>
                <tr>
                    <th>Sesi</th>
                    <th>Topik</th>
                    <th>CP</th>
                    <th>Sub Topik</th>
                    <th>-</th>
                </tr>
            </thead>
            <tbody id = "topik_tbody">
                @if(!empty($rps->sesi))
                @foreach($rps->sesi as $sesi)
                    <tr id="topic{{ $loop->index }}"> 
                        <td class="row-index text-center"> 
                            <input type = "text" value = "{{ $sesi }}" class = "form-control" name = "sesi[]" />
                        </td> 
                        <td class="row-index text-center"> 
                            <input type = "text" value = "{{ @$rps->topik[$loop->index] }}" class = "form-control" name = "topik[]" />
                        </td> 
                        <td class="row-index text-center"> 
{{--                             
                            @if(count(@$rps->cp_select) > 0)

                            @endif --}}
                        </td> 
                        <td class="row-index text-center"> 
                            <input type = "text" value = "{{ @$rps->sub_topik[$loop->index] }}" class = "form-control" name = "sub_topik[]" />
                        </td> 
                        <td class="text-center"> 
                            <button type = "button" class = "btn btn-danger btn-sm remove_topik">X</button>
                        </td> 
                    </tr>
                
                @endforeach
                @endif
            </tbody>
        </table>
        <button type = "button" class = "btn btn-success btn-sm" id = "button_topik">
        +
        </button>
    </div>
</div>

@push("js")



<script>
    $(document).ready(function(){
        var rowIdx = 0; 
        $("#button_topik").on("click",function(){
            
            var selects = "<select class = 'form-control' name = 'cp_select'>";
            selects += "<option value = ''></option>";
            $("#cp_tbody :text").each(function(){
                selects += "<option value = '"+$(this).val()+"'>"+$(this).val()+"</option>";
            });

            selects += "</select>";

           $('#topik_tbody').append(`<tr id="T${++rowIdx}"> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "sesi[]" />
                    </td> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "topik[]" />
                    </td> 
                    <td class="row-index text-center"> 
                        ${selects}
                    </td> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "sub_topik[]" />
                    </td> 
                    <td class="text-center"> 
                        <button type = "button" class = "btn btn-danger btn-sm remove_topik">X</button>
                    </td> 
                </tr>`
            ); 
        });

        $('#topik_tbody').on('click', '.remove_topik', function () { 

            // Getting all the rows next to the 
            // row containing the clicked button 
            var child = $(this).closest('tr').nextAll(); 

            // Iterating across all the rows 
            // obtained to change the index 
            child.each(function () { 
                
                // Getting <tr> id. 
                var id = $(this).attr('id'); 
                console.log(id);

                // Getting the <p> inside the .row-index class. 
                var idx = $(this).children('.row-index').children('input'); 

                // Gets the row number from <tr> id. 
                var dig = parseInt(id.substring(1)); 

                // Modifying row index. 
                idx.html(`Row ${dig - 1}`); 

                // Modifying row id. 
                $(this).attr('id', `T${dig - 1}`); 
            }); 

            // Removing the current row. 
            $(this).closest('tr').remove(); 

            // Decreasing the total number of rows by 1. 
            rowIdx--; 
        }); 

    });
</script>
@endpush
