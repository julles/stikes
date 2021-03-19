<div class="row">
    <div class="col-md-12" id = "cp_12">
        <table class="table">
            <thead>
                <tr>
                    <th>CP</th>
                    <th>-</th>
                </tr>
            </thead>
            <tbody id = "cp_tbody">
                @if(count(@$rps->cp) >0)
                    @foreach($rps->cp as $cp)
                        <tr id="{{ $loop->index }}"> 
                            <td class="row-index text-center"> 
                                <input type = "text" value = "{{ $cp }}" class = "form-control" name = "cp[]" class = "cp_text" />
                            </td> 
                            <td class="text-center"> 
                                <button type = "button" class = "btn btn-danger btn-sm remove_cp">X</button>
                            </td> 
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <button type = "button" class = "btn btn-success btn-sm" id = "button_cp">
        +
        </button>
    </div>
</div>

@push("js")
<script>
    $(document).ready(function(){
        var rowIdx = 0; 
        $("#button_cp").on("click",function(){
           $('#cp_tbody').append(`<tr id="R${++rowIdx}"> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "cp[]" class = "cp_text" />
                    </td> 
                    <td class="text-center"> 
                        <button type = "button" class = "btn btn-danger btn-sm remove_cp">X</button>
                    </td> 
                </tr>`
            ); 
        });

        $('#cp_tbody').on('click', '.remove_cp', function () { 

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
