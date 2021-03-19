<div class="row">
    <div class="col-md-12" id = "cp_12">
        <div class="form-group form_group_cp">
            {!! Form::label("CP 1") !!}
            {!! Form::text("cp[]",null,["class"=>"form-control"]) !!}
        </div>
        <button type = "button" id = "button_cp">
        +
        </button>
    </div>
</div>

@push("js")
<script>
    $(document).ready(function(){
        lineNo = 1;
        $("#button_cp").on("click",function(){
            $(".form_group_cp").clone().insertBefore("#button_cp");
            
            lineNo++;
        });
    });
</script>
@endpush
