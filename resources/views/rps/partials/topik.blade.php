<div class="row">
    <div class="col-md-12" id = "cp_12">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="10%">Sesi</th>
                    <th width="20%">Topic</th>
                    <th width="30%">CP</th>
                    <th width="40%">Sub Topik</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id = "topik_tbody">
                @if(!empty($topic))
                    @php
                        $i = 0;
                    @endphp
                    @foreach($topic as $key => $topicVal)
                        <tr id="T{$i}"> 
                            <td class="row-index text-center"> 
                                <input type = "text" class = "form-control" value="{{ $topicVal[0]['sesi'] }}" name = "topic[{{$i}}][sesi]" />
                            </td> 
                            <td class="row-index text-center"> 
                                <input type = "text" class = "form-control" value="{{ $key }}" name = "topic[{{$i}}][topic]" />
                            </td> 
                            <td class="row-index text-center">
                                <div class="row"> 
                                    <div class="col-md-10 pr-0">
                                        <div id="cpSelect-{{$i}}">
                                            <select id="cpArr-{{$i}}" class = 'form-control' name = 'topic[{{$i}}][capaian_pembelajaran]'>
                                                @foreach($capaianPembelajaran as $CPkey => $CPv)
                                                    <option>{{ $CPv }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-2 pl-0">
                                        <span data-toggle="tooltip" data-placement="top" title="Refresh CP" 
                                        class="btn btn-outline-info" onclick="refreshCpArr({{$i}})">
                                        <i class="fa fa-undo"></i></span>
                                    </div> 
                                </div> 
                            </td> 
                            <td class="row-index text-center"> 
                                <table width="100%" id="sub_topik_data-{{$i}}">
                                    @php
                                        $sI = 0;
                                    @endphp
                                    @foreach($topicVal as $keySub => $v)
                                        @if($keySub != 0)
                                            <tr  id="sub_topik_add-{{$sI}}">
                                        @else
                                            <tr>
                                        @endif
                                            <td>
                                                <input class="form-control mb-3" type="text" name="topic[{{$keySub}}][sub_topik][]" value="{{ $v['sub_topic'] }}" />
                                            </td>
                                            <td>
                                                @if($keySub != 0)
                                                    <span class="btn btn-sm btn-outline-danger mb-3" onclick="removeSubTopic({{$sI}})"><i class="fa fa-trash"></i></span>
                                                @else
                                                    <span class="btn btn-sm btn-success mb-3" onclick="addSubTopic({{$i}})"><i class="fa fa-plus"></i></span>
                                                @endif()
                                            </td>
                                        </tr>
                                        @php
                                            $sI++;
                                        @endphp
                                    @endforeach
                                </table>
                            </td> 
                            <td class="text-center"> 
                                <button type = "button" class = "btn btn-danger btn-sm remove_topik">X</button>
                            </td> 
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach()
                @endif
            </tbody>
        </table>
        <div class="row">
            <div class="col text-center">
                <button type = "button" class = "btn btn-success btn-sm" id = "button_topik">
                + Add Topic
                </button>
            </div>
        </div>
    </div>
</div>

@push("js")



<script>

    function getCp(){
        //
    }

    $(document).ready(function(){

        var rowIdx = '{{ count($topic) }}';
        $("#button_topik").on("click",function(){
            
            var selects = `<select id="cpArr-${rowIdx}" 
            class = 'form-control select2' name = 'topic[${rowIdx}][capaian_pembelajaran]'>`;
            selects += "<option value = ''></option>";
            $("#cp_tbody :text").each(function(){
                selects += "<option value = '"+$(this).val()+"'>"+$(this).val()+"</option>";
            });

            selects += "</select>";

           $('#topik_tbody').append(`<tr id="T${rowIdx}"> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "topic[${rowIdx}][sesi]" />
                    </td> 
                    <td class="row-index text-center"> 
                        <input type = "text" class = "form-control" name = "topic[${rowIdx}][topic]" />
                    </td> 
                    <td class="row-index text-center">
                        <div class="row"> 
                            <div class="col-md-10 pr-0">
                                <div id="cpSelect-${rowIdx}">
                                    ${selects}
                                </div>
                            </div> 
                            <div class="col-md-2 pl-0">
                                <span data-toggle="tooltip" data-placement="top" title="Refresh CP" 
                                class="btn btn-outline-info" onclick="refreshCpArr(${rowIdx})">
                                <i class="fa fa-undo"></i></span>
                            </div> 
                        </div> 
                    </td> 
                    <td class="row-index text-center"> 
                        <table width="100%" id="sub_topik_data-${rowIdx}">
                            <tr>
                                <td>
                                    <input class="form-control mb-3" type="text" name="topic[${rowIdx}][sub_topik][]" />
                                </td>
                                <td>
                                    <span class="btn btn-sm btn-success mb-3" onclick="addSubTopic(${rowIdx})"><i class="fa fa-plus"></i></span>
                                </td>
                            </tr>
                        </table>
                    </td> 
                    <td class="text-center"> 
                        <button type = "button" class = "btn btn-danger btn-sm remove_topik">X</button>
                    </td> 
                </tr>`
            ); 

            $(".select2_tag").select2({
                tags: true
            });

            $(".select2_cp").select2();

            rowIdx++;
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
        
    var subTopicIndex = "{{ ($totalSubTopic + 1) ?? 0 }}";
    function addSubTopic(row) {

        var html = `<tr id="sub_topik_add-${subTopicIndex}">
                        <td>
                            <input class="form-control mb-3" type="text" name="topic[${row}][sub_topik][]" />
                        </td>
                        <td>
                            <span class="btn btn-sm btn-outline-danger mb-3" onclick="removeSubTopic(${subTopicIndex})"><i class="fa fa-trash"></i></span>
                        </td>
                    </tr>`;
        $(`#sub_topik_data-${row}`).append(html);
        subTopicIndex++;
    }

    function removeSubTopic(index) {
        $(`#sub_topik_add-${index}`).remove();
    }

    // refresh array CP
    function refreshCpArr(index) {
        var selectedVal = $(`#cpArr-${index} :selected`).val();

        var selects = `<select id="cpArr-${index}"
        class = 'form-control select2' name = 'topic[${index}][capaian_pembelajaran]'>`;
        selects += "<option value = ''></option>";
        
        $("#cp_tbody :text").each(function(){
            selects += "<option value = '"+$(this).val()+"'>"+$(this).val()+"</option>";
        });

        selects += "</select>";

        $(`#cpSelect-${index}`).html(selects);

        $(`#cpArr-${index}`).val(selectedVal);
        console.log(123);
    }
</script>
@endpush
